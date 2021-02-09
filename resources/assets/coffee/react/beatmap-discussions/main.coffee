# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { Discussions } from './discussions'
import { Events } from './events'
import { Header } from './header'
import { ModeSwitcher } from './mode-switcher'
import { NewDiscussion } from './new-discussion'
import { BackToTop } from 'back-to-top'
import * as React from 'react'
import { DiscussionsStoreContext } from 'beatmap-discussions/discussions-store-context'
import { div } from 'react-dom-factories'
import NewReview from 'beatmap-discussions/new-review'
import * as BeatmapHelper from 'utils/beatmap-helper'

el = React.createElement

export class Main extends React.PureComponent
  constructor: (props) ->
    super props

    @modeSwitcherRef = React.createRef()
    @newDiscussionRef = React.createRef()

    @checkNewTimeoutDefault = 10000
    @checkNewTimeoutMax = 60000
    @cache = {}
    @timeouts = {}
    @xhr = {}
    @state = JSON.parse(props.container.dataset.beatmapsetDiscussionState ? null)
    @restoredState = @state?
    # FIXME: update url handler to recognize this instead
    @focusNewDiscussion = document.location.hash == '#new'

    if @restoredState
      @state.readPostIds = new Set(@state.readPostIdsArray)
    else
      beatmapset = props.initial.beatmapset
      showDeleted = true
      readPostIds = new Set

      for discussion in beatmapset.discussions
        for post in discussion?.posts ? []
          readPostIds.add(post.id) if post?

      @state = { readPostIds, showDeleted }

    # FIXME: comment seems wrong now?
    # Current url takes priority over saved state.
    query = BeatmapDiscussionHelper.urlParse(null, @discussions())
    @state.currentMode = query.mode
    @state.currentFilter = query.filter
    @state.currentBeatmapId = query.beatmapId if query.beatmapId?
    @state.selectedUserId = query.user


  componentDidMount: =>
    $.subscribe 'playmode:set.beatmapDiscussions', @setCurrentPlaymode

    $.subscribe 'beatmapsetDiscussions:update.beatmapDiscussions', @update
    $.subscribe 'beatmapDiscussion:jump.beatmapDiscussions', @jumpTo
    $.subscribe 'beatmapDiscussionPost:markRead.beatmapDiscussions', @markPostRead
    $.subscribe 'beatmapDiscussionPost:toggleShowDeleted.beatmapDiscussions', @toggleShowDeleted

    $(document).on 'ajax:success.beatmapDiscussions', '.js-beatmapset-discussion-update', @ujsDiscussionUpdate
    $(document).on 'click.beatmapDiscussions', '.js-beatmap-discussion--jump', @jumpToClick
    $(document).on 'turbolinks:before-cache.beatmapDiscussions', @saveStateToContainer

    @jumpToDiscussionByHash() if !@restoredState
    @timeouts.checkNew = Timeout.set @checkNewTimeoutDefault, @checkNew


  componentWillUpdate: =>
    @cache = {}
    @focusNewDiscussion = false


  componentDidUpdate: (_prevProps, prevState) =>
    return if prevState.currentBeatmapId == @state.currentBeatmapId &&
      prevState.currentFilter == @state.currentFilter &&
      prevState.currentMode == @state.currentMode &&
      prevState.selectedUserId == @state.selectedUserId &&
      prevState.showDeleted == @state.showDeleted

    Turbolinks.controller.advanceHistory @urlFromState()


  componentWillUnmount: =>
    $.unsubscribe '.beatmapDiscussions'
    $(document).off '.beatmapDiscussions'

    Timeout.clear(timeout) for _name, timeout of @timeouts
    xhr?.abort() for _name, xhr of @xhr


  render: =>
    el DiscussionsStoreContext.Provider, value: @props.stores,
      div className: 'osu-layout osu-layout--full',
        el Header,
          beatmapset: @beatmapset()
          currentBeatmap: @currentBeatmap()
          currentFilter: @state.currentFilter
          currentUser: currentUser
          events: @beatmapset().events
          mode: @state.currentMode
          groupedBeatmaps: @groupedBeatmaps()
          selectedUserId: @state.selectedUserId

        el ModeSwitcher,
          innerRef: @modeSwitcherRef
          mode: @state.currentMode
          beatmapset: @beatmapset()
          currentBeatmap: @currentBeatmap()
          discussions: @discussions()
          currentFilter: @state.currentFilter

        if @state.currentMode == 'events'
          div
            className: 'osu-layout__section osu-layout__section--extra'
            el Events,
              events: @beatmapset().events

        else
          div
            className: 'osu-layout__section osu-layout__section--extra'
            if @state.currentMode == 'reviews'
              el NewReview,
                beatmapset: @beatmapset()
                currentBeatmap: @currentBeatmap()
                currentUser: currentUser
                pinned: @state.pinnedNewDiscussion
                setPinned: @setPinnedNewDiscussion
                stickTo: @modeSwitcherRef
            else
              el NewDiscussion,
                autoFocus: @focusNewDiscussion
                beatmapset: @beatmapset()
                currentUser: currentUser
                currentBeatmap: @currentBeatmap()
                innerRef: @newDiscussionRef
                mode: @state.currentMode
                pinned: @state.pinnedNewDiscussion
                setPinned: @setPinnedNewDiscussion
                stickTo: @modeSwitcherRef
                timelineDiscussions: @props.stores.discussionStore.timelineDiscussions() # TODO: by beatmap?

            el Discussions,
              beatmapset: @beatmapset()
              currentBeatmap: @currentBeatmap()
              currentFilter: @state.currentFilter
              currentUser: currentUser
              discussions: @props.stores.discussionStore.getDiscussions(@state.currentMode, @state.currentFilter, @currentBeatmap().id)
              mode: @state.currentMode
              readPostIds: @state.readPostIds
              showDeleted: @state.showDeleted

        el BackToTop


  beatmapset: =>
    @props.stores.beatmapsetStore.get(@props.initial.beatmapset.id)


  discussions: =>
    @props.stores.discussionStore.getByBeatmapset(@props.initial.beatmapset.id)


  checkNew: =>
    @nextTimeout ?= @checkNewTimeoutDefault

    Timeout.clear @timeouts.checkNew
    @xhr.checkNew?.abort()

    @xhr.checkNew = $.get laroute.route('beatmapsets.discussion', beatmapset: @props.initial.beatmapset.id),
      format: 'json'
      last_updated: @lastUpdate()?.unix()
    .done (data, _textStatus, xhr) =>
      if xhr.status == 304
        @nextTimeout *= 2
        return

      @nextTimeout = @checkNewTimeoutDefault

      @update null, beatmapset: data.beatmapset

    .always =>
      @nextTimeout = Math.min @nextTimeout, @checkNewTimeoutMax

      @timeouts.checkNew = Timeout.set @nextTimeout, @checkNew


  currentBeatmap: =>
    @props.stores.beatmapStore.get(@state.currentBeatmapId) ? BeatmapHelper.findDefault(group: @groupedBeatmaps())


  groupedBeatmaps: =>
    @cache.groupedBeatmaps ?= BeatmapHelper.group(Array.from(@props.stores.beatmapStore.beatmaps.values()))


  jumpToDiscussionByHash: =>
    target = BeatmapDiscussionHelper.urlParse(null, @discussions())

    @jumpTo(null, id: target.discussionId) if target.discussionId?


  jumpTo: (_e, {id}) =>
    discussion = @props.stores.discussionStore.get(id)

    return if !discussion?

    newState = BeatmapDiscussionHelper.stateFromDiscussion(discussion)

    # FIXME: fix changing to a type filter that doesn't contain the discussion.
    # newState.filter =
    #   if @currentDiscussions().byFilter[@state.currentFilter][newState.mode][id]?
    #     @state.currentFilter
    #   else
    #     BeatmapDiscussionHelper.DEFAULT_FILTER

    if @state.selectedUserId? && @state.selectedUserId != discussion.user_id
      newState.selectedUserId = null

    newState.callback = =>
      $.publish 'beatmapset-discussions:highlight', discussionId: discussion.id

      target = $(".js-beatmap-discussion-jump[data-id='#{id}']")

      return if target.length == 0

      offsetTop = target.offset().top - @modeSwitcherRef.current.getBoundingClientRect().height
      offsetTop -= @newDiscussionRef.current.getBoundingClientRect().height if @state.pinnedNewDiscussion

      $(window).stop().scrollTo window.stickyHeader.scrollOffset(offsetTop), 500

    @update null, newState


  jumpToClick: (e) =>
    url = e.currentTarget.getAttribute('href')
    id = BeatmapDiscussionHelper.urlParse(url, @discussions()).discussionId

    return if !id?

    e.preventDefault()
    @jumpTo null, {id}


  lastUpdate: =>
    beatmapset = @beatmapset()
    lastUpdate = _.max [
      beatmapset.last_updated
      _.maxBy(@discussions(), 'updated_at')?.updated_at
      _.maxBy(beatmapset.events, 'created_at')?.created_at
    ]

    moment(lastUpdate) if lastUpdate?


  markPostRead: (_e, {id}) =>
    return if @state.readPostIds.has(id)

    newSet = new Set(@state.readPostIds)
    if Array.isArray(id)
      newSet.add(i) for i in id
    else
      newSet.add(id)

    @setState readPostIds: newSet


  saveStateToContainer: =>
    # This is only so it can be stored with JSON.stringify.
    @state.readPostIdsArray = Array.from(@state.readPostIds)
    @props.container.dataset.beatmapsetDiscussionState = JSON.stringify(@state)


  setCurrentPlaymode: (e, {mode}) =>
    @update e, playmode: mode


  setPinnedNewDiscussion: (pinned) =>
    @setState pinnedNewDiscussion: pinned


  toggleShowDeleted: =>
    @setState showDeleted: !@state.showDeleted


  update: (_e, options) =>
    {
      callback
      mode
      modeIf
      beatmapId
      playmode
      beatmapset
      watching
      filter
      selectedUserId
    } = options
    newState = {}

    if beatmapset?
      @props.stores.updateWithBeatmapset(beatmapset)

    if watching?
      @props.stores.beatmapsetStore.get(@props.initial.beatmapset.id)?.current_user_attributes.is_watching = watching

    if playmode?
      beatmap = BeatmapHelper.findDefault items: @groupedBeatmaps()[playmode]
      beatmapId = beatmap?.id

    if beatmapId? && beatmapId != @currentBeatmap().id
      newState.currentBeatmapId = beatmapId

    if filter?
      if @state.currentMode == 'events'
        newState.currentMode = @lastMode ? BeatmapDiscussionHelper.DEFAULT_MODE

      if filter != @state.currentFilter
        newState.currentFilter = filter

    if mode? && mode != @state.currentMode
      if !modeIf? || modeIf == @state.currentMode
        newState.currentMode = mode

      # switching to events:
      # - record last filter, to be restored when setMode is called
      # - record last mode, to be restored when setFilter is called
      # - set filter to total
      if mode == 'events'
        @lastMode = @state.currentMode
        @lastFilter = @state.currentFilter
        newState.currentFilter = 'total'
      # switching from events:
      # - restore whatever last filter set or default to total
      else if @state.currentMode == 'events'
        newState.currentFilter = @lastFilter ? 'total'

    newState.selectedUserId = selectedUserId if selectedUserId != undefined # need to setState if null

    @setState newState, callback


  urlFromState: =>
    BeatmapDiscussionHelper.url
      beatmap: @currentBeatmap()
      mode: @state.currentMode
      filter: @state.currentFilter
      user: @state.selectedUserId


  ujsDiscussionUpdate: (_e, data) =>
    # to allow ajax:complete to be run
    Timeout.set 0, => @update(null, beatmapset: data)
