###
#    Copyright 2015-2017 ppy Pty. Ltd.
#
#    This file is part of osu!web. osu!web is distributed with the hope of
#    attracting more community contributions to the core ecosystem of osu!.
#
#    osu!web is free software: you can redistribute it and/or modify
#    it under the terms of the Affero GNU General Public License version 3
#    as published by the Free Software Foundation.
#
#    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
#    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
#    See the GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
###

import { BeatmapDiscussions } from './_index'

{a, div, h1, p} = ReactDOMFactories
el = React.createElement

modeSwitcher = document.getElementsByClassName('js-mode-switcher')

export class Main extends React.PureComponent
  constructor: (props) ->
    super props

    @checkNewTimeoutDefault = 10000
    @checkNewTimeoutMax = 60000
    @cache = {}

    beatmaps = BeatmapHelper.group props.initial.beatmapset.beatmaps

    @state =
      beatmapset: @props.initial.beatmapset
      beatmaps: beatmaps
      beatmapsetDiscussion: @props.initial.beatmapsetDiscussion
      currentBeatmap: BeatmapHelper.default(group: beatmaps)
      currentUser: currentUser
      userPermissions: @props.initial.userPermissions
      mode: 'timeline'
      readPostIds: _.chain(props.initial.beatmapsetDiscussion.beatmap_discussions)
        .map (d) =>
          d.beatmap_discussion_posts?.map (r) =>
            r.id
        .flatten()
        .value()
      currentFilter: 'total'


  componentDidMount: =>
    $.subscribe 'beatmap:select.beatmapDiscussions', @setCurrentBeatmapId
    $.subscribe 'playmode:set.beatmapDiscussions', @setCurrentPlaymode
    $.subscribe 'beatmapsetDiscussion:update.beatmapDiscussions', @setBeatmapsetDiscussion
    $.subscribe 'beatmapset:update.beatmapDiscussions', @setBeatmapset
    $.subscribe 'beatmapDiscussion:jump.beatmapDiscussions', @jumpTo
    $.subscribe 'beatmapDiscussion:setMode.beatmapDiscussions', @setMode
    $.subscribe 'beatmapDiscussionPost:markRead.beatmapDiscussions', @markPostRead
    $.subscribe 'beatmapDiscussion:filter.beatmapDiscussions', @setFilter
    $(document).on 'ajax:success.beatmapDiscussions', '.js-beatmapset-discussion-update', @ujsDiscussionUpdate
    $(document).on 'click.beatmapDiscussions', '.js-beatmap-discussion--jump', @jumpToClick

    @jumpByHash()
    @checkNewTimeout = Timeout.set @checkNewTimeoutDefault, @checkNew


  componentWillUpdate: =>
    @cache = {}


  componentWillUnmount: =>
    $.unsubscribe '.beatmapDiscussions'
    $(document).off '.beatmapDiscussions'

    Timeout.clear @checkNewTimeout
    @checkNewAjax?.abort()


  render: =>
    div null,
      el BeatmapDiscussions.Header,
        beatmapset: @state.beatmapset
        beatmaps: @state.beatmaps
        currentBeatmap: @state.currentBeatmap
        currentDiscussions: @currentDiscussions()
        currentUser: @state.currentUser
        currentFilter: @state.currentFilter
        beatmapsetDiscussion: @state.beatmapsetDiscussion
        users: @users()
        mode: @state.mode

      el BeatmapDiscussions.ModeSwitcher,
        mode: @state.mode

      div
        className: 'osu-layout__section osu-layout__section--extra'
        el BeatmapDiscussions.NewDiscussion,
          currentUser: @state.currentUser
          currentBeatmap: @state.currentBeatmap
          currentDiscussions: @currentDiscussions()
          mode: @state.mode

        el BeatmapDiscussions.Discussions,
          beatmapset: @state.beatmapset
          beatmapsetDiscussion: @state.beatmapsetDiscussion
          currentBeatmap: @state.currentBeatmap
          currentDiscussions: @currentDiscussions()
          currentFilter: @state.currentFilter
          currentUser: @state.currentUser
          mode: @state.mode
          readPostIds: @state.readPostIds
          userPermissions: @state.userPermissions
          users: @users()


  checkNew: =>
    @nextTimeout ?= @checkNewTimeoutDefault

    Timeout.clear @checkNewTimeout

    @checkNewAjax = $.ajax document.location.pathname,
      data:
        format: 'json'
        last_updated: moment(@state.beatmapsetDiscussion.updated_at).unix()

    .done (data, _textStatus, xhr) =>
      if xhr.status == 304
        @nextTimeout *= 2
        return

      @nextTimeout = @checkNewTimeoutDefault

      @setBeatmapsetDiscussion null, beatmapsetDiscussion: data.beatmapsetDiscussion

    .always =>
      @nextTimeout = Math.min @nextTimeout, @checkNewTimeoutMax

      @checkNewTimeout = Timeout.set @nextTimeout, @checkNew


  currentDiscussions: =>
    if !@cache.currentDiscussions?
      general = []
      timeline = []
      generalAll = []

      timelineByFilter =
        deleted: {}
        mine: {}
        pending: {}
        praises: {}
        resolved: {}
        total: {}

      for d in @state.beatmapsetDiscussion.beatmap_discussions
        if d.timestamp?
          continue if d.beatmap_id != @state.currentBeatmap.id

          timeline.push d
          timelineByFilter.total[d.id] = d

          if d.deleted_at?
            timelineByFilter.deleted[d.id] = d
          else if d.message_type == 'praise'
            timelineByFilter.praises[d.id] = d
          else
            if d.resolved
              timelineByFilter.resolved[d.id] = d
            else
              timelineByFilter.pending[d.id] = d

          if d.user_id == @state.currentUser.id
            timelineByFilter.mine[d.id] = d

        else
          if d.beatmap_id?
            if d.beatmap_id == @state.currentBeatmap.id
              general.push d
          else
            generalAll.push d

      timeline = _.orderBy timeline, ['timestamp', 'id']
      general = _.orderBy general, 'id'
      generalAll = _.orderBy generalAll, 'id'

      @cache.currentDiscussions =
        general: general
        generalAll: generalAll
        timeline: timeline
        timelineByFilter: timelineByFilter

    @cache.currentDiscussions


  jumpByHash: =>
    target = BeatmapDiscussionHelper.hashParse()

    if target.discussionId?
      return $.publish 'beatmapDiscussion:jump', id: target.discussionId

    target.beatmapId ?= @state.currentBeatmap.id
    $.publish 'beatmap:select', id: target.beatmapId


  jumpTo: (_e, {id}) =>
    discussion = _.find @state.beatmapsetDiscussion.beatmap_discussions, id: id

    return if !discussion?

    mode = if discussion.timestamp? then 'timeline' else 'general'

    @setMode null, mode, =>
      @setCurrentBeatmapId null,
        id: discussion.beatmap_id
        callback: =>
          $.publish 'beatmapDiscussionEntry:highlight', id: discussion.id

          target = $(".js-beatmap-discussion-jump[data-id='#{id}']")
          $(window).stop().scrollTo target, 500,
            offset: modeSwitcher[0].getBoundingClientRect().height * -1


  jumpToClick: (e) =>
    e.preventDefault()
    url = e.currentTarget.getAttribute('href')

    id = BeatmapDiscussionHelper.hashParse(url).discussionId

    @jumpTo null, {id}


  markPostRead: (_e, {id}) =>
    return if _.includes @state.readPostIds, id

    @setState readPostIds: @state.readPostIds.concat(id)


  setBeatmapset: (_e, {beatmapset, callback}) =>
    @setState
      beatmapset: beatmapset
      beatmaps: BeatmapHelper.group beatmapset.beatmaps
      callback


  setBeatmapsetDiscussion: (_e, {beatmapsetDiscussion, callback}) =>
    @setState
      beatmapsetDiscussion: beatmapsetDiscussion
      callback

  setCurrentBeatmapId: (_e, {id, callback}) =>
    return callback?() if !id?
    return callback?() if id == @state.currentBeatmap.id

    beatmap = _.find @state.beatmapset.beatmaps, id: id

    return callback?() if !beatmap?

    @setState currentBeatmap: beatmap, callback


  setCurrentPlaymode: (_e, {mode}) =>
    beatmap = BeatmapHelper.default items: @state.beatmaps[mode]
    @setCurrentBeatmapId null, id: beatmap?.id


  setFilter: (_e, {filter}) =>
    return if @state.mode == 'timeline' && filter == @state.currentFilter

    @setState
      mode: 'timeline'
      currentFilter: filter


  setMode: (_e, mode, callback) =>
    newState = mode: mode

    if mode == 'timeline'
      currentFilter = @state.currentFilter
      filter = 'total'
      newState.currentFilter = filter
    else
      currentFilter = filter = null

    return callback?() if mode == @state.mode && filter == currentFilter

    @setState newState, callback


  users: =>
    if !@cache.users?
      @cache.users = _.keyBy @state.beatmapsetDiscussion.users, 'id'
      @cache.users[null] = @cache.users[undefined] =
        username: osu.trans 'users.deleted'

    @cache.users

  ujsDiscussionUpdate: (_e, data) =>
    # to allow ajax:complete to be run
    Timeout.set 0, =>
      @setBeatmapsetDiscussion null, beatmapsetDiscussion: data
