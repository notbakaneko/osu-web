// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { DiscussionsContext } from 'beatmap-discussions/discussions-context';
import { BeatmapsContext } from 'beatmap-discussions/beatmaps-context';
import NewReview from 'beatmap-discussions/new-review';
import { ReviewEditorConfigContext } from 'beatmap-discussions/review-editor-config-context';
import BackToTop from 'components/back-to-top';
import { route } from 'laroute';
import { deletedUser } from 'models/user';
import core from 'osu-core-singleton';
import * as React from 'react';
import { div } from 'react-dom-factories';
import * as BeatmapHelper from 'utils/beatmap-helper';
import { defaultFilter, defaultMode, makeUrl, parseUrl, stateFromDiscussion } from 'utils/beatmapset-discussion-helper';
import { nextVal } from 'utils/seq';
import { currentUrl } from 'utils/turbolinks';
import { Discussions } from './discussions';
import { Events } from './events';
import { Header } from './header';
import { ModeSwitcher } from './mode-switcher';
import { NewDiscussion } from './new-discussion';
import { action, makeObservable, observable } from 'mobx';
import { observer } from 'mobx-react';
import BeatmapsetWithDiscussionsJson from 'interfaces/beatmapset-with-discussions-json';
import { DiscussionPage } from './discussion-mode';
import { Filter } from './current-discussions';

const checkNewTimeoutDefault = 10000;
const checkNewTimeoutMax = 60000;

interface Props {
  container: HTMLElement;
  initial: {
    beatmapset: BeatmapsetWithDiscussionsJson;
    reviews_config: {
      max_blocks: number;
    },
  },
}

@observer
export default class Main extends React.Component<Props> {
  @observable private beatmapset = this.props.initial.beatmapset;

  @observable private currentMode: DiscussionPage = 'general';
  @observable private currentFilter: Filter | null = null;
  @observable private currentBeatmapId: number | null = null;
  @observable private selectedUserId: number | null = null;

  // FIXME: update url handler to recognize this instead
  private focusNewDiscussion = currentUrl().hash === '#new';

  private reviewsConfig = this.props.initial.reviews_config;

  private jumpToDiscussion = false;

  private readonly eventId = `beatmap-discussions-${nextVal()}`;
  private readonly modeSwitcherRef = React.createRef<HTMLDivElement>()
  private readonly newDiscussionRef = React.createRef<HTMLDivElement>()
  @observable private pinnedNewDiscussion = false;

  @observable private readPostIds = new Set<number>();
  @observable private showDeleted = true;

  private readonly disposers = new Set<((() => void) | undefined)>();

  private readonly xhrs = {};
  private readonly timeouts = {};

  constructor(props: Props) {
    super(props);

    this.state = JSON.parse(props.container.dataset.beatmapsetDiscussionState ?? null) as (BeatmapsetWithDiscussionsJson | null); // TODO: probably wrong
    if (this.state != null) {
      this.readPostIds = new Set(this.state.readPostIdsArray);
      this.pinnedNewDiscussion = this.state.pinnedNewDiscussion;
    } else {
      this.jumpToDiscussion = true;
      for (const discussion of props.initial.beatmapset.discussions) {
        if (discussion.posts != null) {
          for (const post of discussion.posts) {
            this.readPostIds.add(post.id);
          }
        }
      }
    }

    // Current url takes priority over saved state.
    const query = parseUrl(null, props.initial.beatmapset.discussions);
    if (query != null) {
      // TODO: maybe die instead?
      this.currentMode = query.mode;
      this.currentFilter = query.filter;
      this.currentBeatmapId = query.beatmapId ?? null; // TODO check if it's supposed to assign on null or skip and use existing value
      this.selectedUserId = query.user ?? null
    }

    makeObservable(this);
  }

  componentDidMount() {
    $.subscribe(`playmode:set.${this.eventId}`, this.setCurrentPlaymode);

    $.subscribe(`beatmapsetDiscussions:update.${this.eventId}`, this.update);
    $.subscribe(`beatmapDiscussion:jump.${this.eventId}`, this.jumpTo);
    $.subscribe(`beatmapDiscussionPost:markRead.${this.eventId}`, this.markPostRead);
    $.subscribe(`beatmapDiscussionPost:toggleShowDeleted.${this.eventId}`, this.toggleShowDeleted);

    $(document).on(`ajax:success.${this.eventId}`, '.js-beatmapset-discussion-update', this.ujsDiscussionUpdate);
    $(document).on(`click.${this.eventId}`, '.js-beatmap-discussion--jump', this.jumpToClick);
    $(document).on(`turbolinks:before-cache.${this.eventId}`, this.saveStateToContainer);

    if (this.jumpToDiscussion) {
      this.disposers.add(core.reactTurbolinks.runAfterPageLoad(this.jumpToDiscussionByHash);
    }

    this.timeouts.checkNew = window.setTimeout(this.checkNew, checkNewTimeoutDefault);
  }

  private readonly jumpToDiscussionByHash = () => {
    const target = parseUrl(null, this.state.beatmapset.discussions)

    if (target.discussionId != null) {
      this.jumpTo(null, { id: target.discussionId, postId: target.postId });
    }
  };

  private readonly saveStateToContainer = () => {
    // This is only so it can be stored with JSON.stringify.
    this.state.readPostIdsArray = Array.from(this.state.readPostIds)
    this.props.container.dataset.beatmapsetDiscussionState = JSON.stringify(this.state)
  }

  private readonly setCurrentPlaymode = (e, { mode }) => {
    this.update(e, { playmode: mode });
  };

  @action
  private readonly setPinnedNewDiscussion = (pinned: boolean) => {
    this.pinnedNewDiscussion = pinned
  };

  @action
  private readonly toggleShowDeleted = () => {
    this.showDeleted = !this.showDeleted;
  };
}


  componentDidUpdate: (_prevProps, prevState) =>
    return if prevState.currentBeatmapId == this.state.currentBeatmapId &&
      prevState.currentFilter == this.state.currentFilter &&
      prevState.currentMode == this.state.currentMode &&
      prevState.selectedUserId == this.state.selectedUserId &&
      prevState.showDeleted == this.state.showDeleted

    Turbolinks.controller.advanceHistory @urlFromState()


  componentWillUnmount: =>
    $.unsubscribe ".${@eventId}"
    $(document).off ".${@eventId}"

    Timeout.clear(timeout) for _name, timeout of @timeouts
    xhr?.abort() for _name, xhr of @xhr
    @disposers.forEach (disposer) => disposer?()


  render: =>
    @cache = {}

    el React.Fragment, null,
      el Header,
        beatmaps: @groupedBeatmaps()
        beatmapset: this.state.beatmapset
        currentBeatmap: @currentBeatmap()
        currentDiscussions: @currentDiscussions()
        currentFilter: this.state.currentFilter
        currentUser: this.state.currentUser
        discussions: @discussions()
        discussionStarters: @discussionStarters()
        events: this.state.beatmapset.events
        mode: this.state.currentMode
        selectedUserId: this.state.selectedUserId
        users: @users()

      el ModeSwitcher,
        innerRef: @modeSwitcherRef
        mode: this.state.currentMode
        beatmapset: this.state.beatmapset
        currentBeatmap: @currentBeatmap()
        currentDiscussions: @currentDiscussions()
        currentFilter: this.state.currentFilter

      if this.state.currentMode == 'events'
        el Events,
          events: this.state.beatmapset.events
          users: @users()
          discussions: @discussions()

      else
        el DiscussionsContext.Provider,
          value: @discussions()
          el BeatmapsContext.Provider,
            value: @beatmaps()
            el ReviewEditorConfigContext.Provider,
              value: this.state.reviewsConfig

              if this.state.currentMode == 'reviews'
                el NewReview,
                  beatmapset: this.state.beatmapset
                  beatmaps: @beatmaps()
                  currentBeatmap: @currentBeatmap()
                  currentUser: this.state.currentUser
                  innerRef: @newDiscussionRef
                  pinned: this.state.pinnedNewDiscussion
                  setPinned: @setPinnedNewDiscussion
                  stickTo: @modeSwitcherRef
              else
                el NewDiscussion,
                  beatmapset: this.state.beatmapset
                  currentUser: this.state.currentUser
                  currentBeatmap: @currentBeatmap()
                  currentDiscussions: @currentDiscussions()
                  innerRef: @newDiscussionRef
                  mode: this.state.currentMode
                  pinned: this.state.pinnedNewDiscussion
                  setPinned: @setPinnedNewDiscussion
                  stickTo: @modeSwitcherRef
                  autoFocus: @focusNewDiscussion

              el Discussions,
                beatmapset: this.state.beatmapset
                currentBeatmap: @currentBeatmap()
                currentDiscussions: @currentDiscussions()
                currentFilter: this.state.currentFilter
                currentUser: this.state.currentUser
                mode: this.state.currentMode
                readPostIds: this.state.readPostIds
                showDeleted: this.state.showDeleted
                users: @users()

      el BackToTop


  beatmaps: =>
    return @cache.beatmaps if @cache.beatmaps?

    hasDiscussion = {}
    for discussion in this.state.beatmapset.discussions
      hasDiscussion[discussion.beatmap_id] = true if discussion?

    @cache.beatmaps ?=
      _(this.state.beatmapset.beatmaps)
      .filter (beatmap) ->
        !_.isEmpty(beatmap) && (!beatmap.deleted_at? || hasDiscussion[beatmap.id]?)
      .keyBy 'id'
      .value()


  checkNew: =>
    @nextTimeout ?= @checkNewTimeoutDefault

    Timeout.clear @timeouts.checkNew
    @xhr.checkNew?.abort()

    @xhr.checkNew = $.get route('beatmapsets.discussion', beatmapset: this.state.beatmapset.id),
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
    @beatmaps()[this.state.currentBeatmapId] ? BeatmapHelper.findDefault(group: @groupedBeatmaps())


  currentDiscussions: =>
    return @cache.currentDiscussions if @cache.currentDiscussions?

    countsByBeatmap = {}
    countsByPlaymode = {}
    totalHype = 0
    unresolvedIssues = 0
    byMode =
      timeline: []
      general: []
      generalAll: []
      reviews: []
    byFilter =
      deleted: {}
      hype: {}
      mapperNotes: {}
      mine: {}
      pending: {}
      praises: {}
      resolved: {}
      total: {}
    timelineAllUsers = []

    for own mode, _items of byMode
      for own _filter, modes of byFilter
        modes[mode] = {}

    for own _id, d of @discussions()
      if !d.deleted_at?
        totalHype++ if d.message_type == 'hype'

        if d.can_be_resolved && !d.resolved
          beatmap = @beatmaps()[d.beatmap_id]

          if !d.beatmap_id? || (beatmap? && !beatmap.deleted_at?)
            unresolvedIssues++

          if beatmap?
            countsByBeatmap[beatmap.id] ?= 0
            countsByBeatmap[beatmap.id]++

            if !beatmap.deleted_at?
              countsByPlaymode[beatmap.mode] ?= 0
              countsByPlaymode[beatmap.mode]++

      if d.message_type == 'review'
        mode = 'reviews'
      else
        if d.beatmap_id?
          if d.beatmap_id == @currentBeatmap().id
            if d.timestamp?
              mode = 'timeline'
              timelineAllUsers.push d
            else
              mode = 'general'
          else
            mode = null
        else
          mode = 'generalAll'

      # belongs to different beatmap, excluded
      continue unless mode?

      # skip if filtering users
      continue if this.state.selectedUserId? && d.user_id != this.state.selectedUserId

      filters = total: true

      if d.deleted_at?
        filters.deleted = true
      else if d.message_type == 'hype'
        filters.hype = true
        filters.praises = true
      else if d.message_type == 'praise'
        filters.praises = true
      else if d.can_be_resolved
        if d.resolved
          filters.resolved = true
        else
          filters.pending = true

      if d.user_id == this.state.currentUser.id
        filters.mine = true

      if d.message_type == 'mapper_note'
        filters.mapperNotes = true

      # the value should always be true
      for own filter, _isSet of filters
        byFilter[filter][mode][d.id] = d

      if filters.pending && d.parent_id?
        parentDiscussion = @discussions()[d.parent_id]

        if parentDiscussion? && parentDiscussion.message_type == 'review'
          byFilter.pending.reviews[parentDiscussion.id] = parentDiscussion

      byMode[mode].push d

    timeline = byMode.timeline
    general = byMode.general
    generalAll = byMode.generalAll
    reviews = byMode.reviews

    @cache.currentDiscussions = {general, generalAll, timeline, reviews, timelineAllUsers, byFilter, countsByBeatmap, countsByPlaymode, totalHype, unresolvedIssues}


  discussions: =>
    # skipped discussions
    # - not privileged (deleted discussion)
    # - deleted beatmap
    @cache.discussions ?= _ this.state.beatmapset.discussions
                            .filter (d) -> !_.isEmpty(d)
                            .keyBy 'id'
                            .value()


  discussionStarters: =>
    _ @discussions()
      .filter (discussion) -> discussion.message_type != 'hype'
      .map 'user_id'
      .uniq()
      .map (user_id) => @users()[user_id]
      .orderBy (user) -> user.username.toLocaleLowerCase()
      .value()


  groupedBeatmaps: (discussionSet) =>
    @cache.groupedBeatmaps ?= BeatmapHelper.group _.values(@beatmaps())




  jumpTo: (_e, {id, postId}) =>
    discussion = @discussions()[id]

    return if !discussion?

    newState = stateFromDiscussion(discussion)

    newState.filter =
      if @currentDiscussions().byFilter[this.state.currentFilter][newState.mode][id]?
        this.state.currentFilter
      else
        defaultFilter

    if this.state.selectedUserId? && this.state.selectedUserId != discussion.user_id
      newState.selectedUserId = null

    newState.callback = =>
      $.publish 'beatmapset-discussions:highlight', discussionId: discussion.id

      attribute = if postId? then "data-post-id='${postId}'" else "data-id='${id}'"
      target = $(".js-beatmap-discussion-jump[${attribute}]")

      return if target.length == 0

      offsetTop = target.offset().top - @modeSwitcherRef.current.getBoundingClientRect().height
      offsetTop -= @newDiscussionRef.current.getBoundingClientRect().height if this.state.pinnedNewDiscussion

      $(window).stop().scrollTo core.stickyHeader.scrollOffset(offsetTop), 500

    @update null, newState


  jumpToClick: (e) =>
    url = e.currentTarget.getAttribute('href')
    { discussionId, postId } = parseUrl(url, this.state.beatmapset.discussions)

    return if !discussionId?

    e.preventDefault()
    @jumpTo null, { id: discussionId, postId }


  lastUpdate: =>
    lastUpdate = _.max [
      this.state.beatmapset.last_updated
      _.maxBy(this.state.beatmapset.discussions, 'updated_at')?.updated_at
      _.maxBy(this.state.beatmapset.events, 'created_at')?.created_at
    ]

    moment(lastUpdate) if lastUpdate?


  markPostRead: (_e, {id}) =>
    return if this.state.readPostIds.has(id)

    newSet = new Set(this.state.readPostIds)
    if Array.isArray(id)
      newSet.add(i) for i in id
    else
      newSet.add(id)

    @setState readPostIds: newSet


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
      newState.beatmapset = beatmapset

    if watching?
      newState.beatmapset ?= _.assign {}, this.state.beatmapset
      newState.beatmapset.current_user_attributes.is_watching = watching

    if playmode?
      beatmap = BeatmapHelper.findDefault items: @groupedBeatmaps().get(playmode)
      beatmapId = beatmap?.id

    if beatmapId? && beatmapId != @currentBeatmap().id
      newState.currentBeatmapId = beatmapId

    if filter?
      if this.state.currentMode == 'events'
        newState.currentMode = @lastMode ? defaultMode(newState.currentBeatmapId)

      if filter != this.state.currentFilter
        newState.currentFilter = filter

    if mode? && mode != this.state.currentMode
      if !modeIf? || modeIf == this.state.currentMode
        newState.currentMode = mode

      # switching to events:
      # - record last filter, to be restored when setMode is called
      # - record last mode, to be restored when setFilter is called
      # - set filter to total
      if mode == 'events'
        @lastMode = this.state.currentMode
        @lastFilter = this.state.currentFilter
        newState.currentFilter = 'total'
      # switching from events:
      # - restore whatever last filter set or default to total
      else if this.state.currentMode == 'events'
        newState.currentFilter = @lastFilter ? 'total'

    newState.selectedUserId = selectedUserId if selectedUserId != undefined # need to setState if null

    @setState newState, callback


  urlFromState: =>
    makeUrl
      beatmap: @currentBeatmap()
      mode: this.state.currentMode
      filter: this.state.currentFilter
      user: this.state.selectedUserId


  users: =>
    if !@cache.users?
      @cache.users = _.keyBy this.state.beatmapset.related_users, 'id'
      @cache.users[null] = @cache.users[undefined] = deletedUser.toJson()

    @cache.users


  ujsDiscussionUpdate: (_e, data) =>
    # to allow ajax:complete to be run
    Timeout.set 0, => @update(null, beatmapset: data)
