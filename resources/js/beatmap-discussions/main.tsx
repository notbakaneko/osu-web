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
import * as BeatmapHelper from 'utils/beatmap-helper';
import { defaultFilter, defaultMode, makeUrl, parseUrl, stateFromDiscussion } from 'utils/beatmapset-discussion-helper';
import { nextVal } from 'utils/seq';
import { currentUrl } from 'utils/turbolinks';
import { Discussions } from './discussions';
import { Events } from './events';
import { Header } from './header';
import { ModeSwitcher } from './mode-switcher';
import { NewDiscussion } from './new-discussion';
import { action, computed, makeObservable, observable } from 'mobx';
import { observer } from 'mobx-react';
import BeatmapsetWithDiscussionsJson from 'interfaces/beatmapset-with-discussions-json';
import { DiscussionPage, discussionPages } from './discussion-mode';
import { Filter } from './current-discussions';
import { isEmpty, keyBy, maxBy } from 'lodash';
import moment from 'moment';
import { findDefault } from 'utils/beatmap-helper';
import { group } from 'utils/beatmap-helper';
import GameMode from 'interfaces/game-mode';

const checkNewTimeoutDefault = 10000;
const checkNewTimeoutMax = 60000;

interface InitialData {
  beatmapset: BeatmapsetWithDiscussionsJson;
  reviews_config: {
    max_blocks: number;
  };
}

interface Props {
  container: HTMLElement;
  initial: InitialData;
}

interface State {
  beatmapset: BeatmapsetWithDiscussionsJson;
  currentMode: DiscussionPage;
  currentFilter: Filter | null;
  currentBeatmapId: number | null;
  focusNewDiscussion: boolean;
  pinnedNewDiscussion: boolean;
  readPostIds: Set<number>;
  readPostIdsArray: number[];
  selectedUserId: number | null;
  showDeleted: boolean;
}

interface UpdateOptions {
  callback: () => void;
  mode: DiscussionPage;
  modeIf: DiscussionPage;
  beatmapId: number;
  playmode: GameMode;
  beatmapset: BeatmapsetWithDiscussionsJson;
  watching: boolean;
  filter: Filter;
  selectedUserId: number;
}

@observer
export default class Main extends React.Component<Props, State> {
  @observable private beatmapset = this.props.initial.beatmapset;

  @observable private currentMode: DiscussionPage = 'general';
  @observable private currentFilter: Filter | null = null;
  @observable private currentBeatmapId: number | null = null;
  @observable private selectedUserId: number | null = null;

  // FIXME: update url handler to recognize this instead
  private focusNewDiscussion = currentUrl().hash === '#new';

  private reviewsConfig = this.props.initial.reviews_config;

  private jumpToDiscussion = false;
  private nextTimeout;

  private readonly eventId = `beatmap-discussions-${nextVal()}`;
  private readonly modeSwitcherRef = React.createRef<HTMLDivElement>()
  private readonly newDiscussionRef = React.createRef<HTMLDivElement>()
  @observable private pinnedNewDiscussion = false;

  @observable private readPostIds = new Set<number>();
  @observable private showDeleted = true;

  private readonly disposers = new Set<((() => void) | undefined)>();

  private xhrCheckNew?: JQuery.jqXHR<InitialData>;
  private readonly timeouts: Record<string, number> = {};

  @computed
  private get beatmaps() {
    const hasDiscussion = new Set<number>();
    for (const discussion of this.state.beatmapset.discussions) {
      if (discussion?.beatmap_id != null) {
        hasDiscussion.add(discussion.beatmap_id);
      }
    }

    return keyBy(
      this.state.beatmapset.beatmaps.filter((beatmap) => !isEmpty(beatmap) && (beatmap.deleted_at == null || hasDiscussion.has(beatmap.id))),
      'id',
    );
  }

  @computed
  private get currentBeatmap() {
    return this.beatmaps[this.state.currentBeatmapId] ?? findDefault({ group: this.groupedBeatmaps() });
  }

  @computed
  private get discussions() {
    // skipped discussions
    // - not privileged (deleted discussion)
    // - deleted beatmap
    return keyBy(this.state.beatmapset.discussions.filter((discussion) => !isEmpty(discussion)), 'id');
  }

  @computed
  private get presentDiscussions() {
    return Object.values(this.discussions).filter((discussion) => discussion.deleted_at == null);
  }

  private get totalHype() {
    return this.presentDiscussions.filter((discussion) => discussion.message_type === 'hype');
  }

  private get unresolvedDiscussions() {
    return this.presentDiscussions.filter((discussion) => discussion.can_be_resolved && !discussion.resolved)
  }

  private get currentDiscussions() {

  }



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



  @computed
  private get discussionStarters() {
    const userIds = new Set(Object.values(this.discussions)
      .filter((discussion) => discussion.message_type != 'hype')
      .map((discussion) => discussion.user_id)
    );

    // TODO: sort user.username.toLocaleLowerCase()
    return [...userIds.values()].map((userId) => this.users[userId]).sort();
  }

  private get groupedBeatmaps() {
    return group(Object.values(this.beatmaps));
  }

  @computed
  private get lastUpdate() {
    const maxLastUpdate = Math.max(
      +this.state.beatmapset.last_updated,
      +(maxBy(this.state.beatmapset.discussions, 'updated_at')?.updated_at ?? 0),
      +(maxBy(this.state.beatmapset.events, 'created_at')?.created_at ?? 0),
    );

    return maxLastUpdate != null ? moment(maxLastUpdate).unix() : null;
  }

  private get urlFromState() {
    return makeUrl({
      beatmap: this.currentBeatmap(),
      filter: this.state.currentFilter,
      mode: this.state.currentMode,
      user: this.state.selectedUserId,
    });
  }

  @computed
  private get users() {
    const value = keyBy(this.state.beatmapset.related_users, 'id');
    value.null = value.undefined = deletedUser.toJson();

    return value;
  }


  ujsDiscussionUpdate = (_e: unknown, beatmapset: BeatmapsetWithDiscussionsJson) => {
    // to allow ajax:complete to be run
    window.setTimeout(() => this.update(null, { beatmapset }, 0));
  };

  constructor(props: Props) {
    super(props);

    this.state = JSON.parse(props.container.dataset.beatmapsetDiscussionState ?? null) as (BeatmapsetWithDiscussionsJson | null); // TODO: probably wrong
    if (this.state != null) {
      this.state.readPostIds = new Set(this.state.readPostIdsArray);
      this.pinnedNewDiscussion = this.state.pinnedNewDiscussion;
    } else {
      this.jumpToDiscussion = true;
      for (const discussion of props.initial.beatmapset.discussions) {
        if (discussion.posts != null) {
          for (const post of discussion.posts) {
            this.state.readPostIds.add(post.id);
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
      this.disposers.add(core.reactTurbolinks.runAfterPageLoad(this.jumpToDiscussionByHash));
    }

    this.timeouts.checkNew = window.setTimeout(this.checkNew, checkNewTimeoutDefault);
  }


  componentDidUpdate(_prevProps, prevState) {
    if (prevState.currentBeatmapId == this.state.currentBeatmapId
      && prevState.currentFilter == this.state.currentFilter
      && prevState.currentMode == this.state.currentMode
      && prevState.selectedUserId == this.state.selectedUserId
      && prevState.showDeleted == this.state.showDeleted) {
      return;
    }

    Turbolinks.controller.advanceHistory(this.urlFromState());
  }

  componentWillUnmount() {
    $.unsubscribe(`.${this.eventId}`);
    $(document).off(`.${this.eventId}`);

    Object.values(this.timeouts).forEach(window.clearTimeout);

    this.xhrCheckNew?.abort();
    this.disposers.forEach((disposer) => disposer?.());
  }

  render() {
    return (
      <>
        <Header
          beatmaps={this.groupedBeatmaps()}
          beatmapset={this.state.beatmapset}
          currentBeatmap={this.currentBeatmap}
          currentDiscussions={this.currentDiscussions}
          currentFilter={this.state.currentFilter}
          currentUser={this.state.currentUser}
          discussions={this.discussions}
          discussionStarters={this.discussionStarters}
          events={this.state.beatmapset.events}
          mode={this.state.currentMode}
          selectedUserId={this.state.selectedUserId}
          users={this.users}
        />
        <ModeSwitcher
          innerRef={this.modeSwitcherRef}
          mode={this.state.currentMode}
          beatmapset={this.state.beatmapset}
          currentBeatmap={this.currentBeatmap}
          currentDiscussions={this.currentDiscussions}
          currentFilter={this.state.currentFilter}
        />
        {this.state.currentMode === 'events' ? (
          <Events
            events={this.state.beatmapset.events}
            users={this.users}
            discussions={this.discussions}
          />
        ) : (
          <DiscussionsContext.Provider value={this.discussions}>
            <BeatmapsContext.Provider value={this.beatmaps}>
              <ReviewEditorConfigContext.Provider value={this.reviewsConfig}>
                {this.state.currentMode === 'reviews' ? (
                  <NewReview
                    beatmapset={this.state.beatmapset}
                    beatmaps={this.beatmaps}
                    currentBeatmap={this.currentBeatmap}
                    innerRef={this.newDiscussionRef}
                    pinned={this.state.pinnedNewDiscussion}
                    setPinned={this.setPinnedNewDiscussion}
                    stickTo={this.modeSwitcherRef}
                  />
                ) : (
                  <NewDiscussion
                    beatmapset={this.state.beatmapset}
                    currentBeatmap={this.currentBeatmap}
                    currentDiscussions={this.currentDiscussions}
                    innerRef={this.newDiscussionRef}
                    mode={this.state.currentMode}
                    pinned={this.state.pinnedNewDiscussion}
                    setPinned={this.setPinnedNewDiscussion}
                    stickTo={this.modeSwitcherRef}
                    autoFocus={this.focusNewDiscussion}
                  />
                )}
                <Discussions
                  beatmapset={this.state.beatmapset}
                  currentBeatmap={this.currentBeatmap}
                  currentDiscussions={this.currentDiscussions}
                  currentFilter={this.state.currentFilter}
                  mode={this.state.currentMode}
                  readPostIds={this.state.readPostIds}
                  showDeleted={this.state.showDeleted}
                  users={this.users}
                />
              </ReviewEditorConfigContext.Provider>
            </BeatmapsContext.Provider>
          </DiscussionsContext.Provider>
        )}
        <BackToTop />
      </>
    )
  }

  private readonly checkNew = () => {
    this.nextTimeout ??= checkNewTimeoutDefault;

    window.clearTimeout(this.timeouts.checkNew);
    this.xhrCheckNew?.abort();

    this.xhrCheckNew = $.get(route('beatmapsets.discussion', { beatmapset: this.state.beatmapset.id }), {
      format: 'json',
      last_updated: this.lastUpdate,
    });

    this.xhrCheckNew.done((data, _textStatus, xhr) => {
      if (xhr.status === 304) {
        this.nextTimeout *= 2;
        return;
      }

        this.nextTimeout = checkNewTimeoutDefault;
        this.update(null, { beatmapset: data.beatmapset });
    })
    .always(() => {
      this.nextTimeout = Math.min(this.nextTimeout, checkNewTimeoutMax);

      this.timeouts.checkNew = window.setTimeout(this.checkNew, this.nextTimeout);
    });
  }

  private readonly jumpTo = (_e: unknown, { id, postId }: { id: number, postId?: number }) => {
    const discussion = this.discussions[id];

    if (discussion == null) return;

    const newState = stateFromDiscussion(discussion)

    newState.filter = this.currentDiscussions().byFilter[this.state.currentFilter][newState.mode][id] != null
      ? this.state.currentFilter
      : defaultFilter

    if (this.state.selectedUserId != null && this.state.selectedUserId !== discussion.user_id) {
      newState.selectedUserId = null; // unsets userid
    }

    newState.callback = () => {
      $.publish('beatmapset-discussions:highlight', { discussionId: discussion.id });

      const attribute = postId != null ? `data-post-id='${postId}'` : `data-id='${id}'`;
      const target = $(`.js-beatmap-discussion-jump[${attribute}]`);

      if (target.length === 0) return;

      let offsetTop = target.offset().top - this.modeSwitcherRef.current.getBoundingClientRect().height;
      if (this.state.pinnedNewDiscussion) {
        offsetTop -= this.newDiscussionRef.current.getBoundingClientRect().height
      }

      $(window).stop().scrollTo(core.stickyHeader.scrollOffset(offsetTop), 500);
    }

    this.update(null, newState)
  }

  private readonly jumpToClick = (e: React.SyntheticEvent<HTMLLinkElement>) => {
    const url = e.currentTarget.getAttribute('href');
    const parsedUrl = parseUrl(url, this.state.beatmapset.discussions);

    if (parsedUrl == null) return;

    const { discussionId, postId } = parsedUrl;

    if (discussionId == null) return

    e.preventDefault();
    this.jumpTo(null, { id: discussionId, postId });
  }

  private readonly jumpToDiscussionByHash = () => {
    const target = parseUrl(null, this.state.beatmapset.discussions)

    if (target.discussionId != null) {
      this.jumpTo(null, { id: target.discussionId, postId: target.postId });
    }
  };

  @action
  private readonly markPostRead = (_event: unknown, { id }: { id: number | number[] }) => {
    if (Array.isArray(id)) {
      id.forEach(this.state.readPostIds.add);
    } else {
      this.state.readPostIds.add(id);
    }

    // setState
  }

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

  @action
  private readonly update = (_e: unknown, options: Partial<UpdateOptions>) => {
    const {
      beatmapId,
      beatmapset,
      callback,
      filter,
      mode,
      modeIf,
      playmode,
      selectedUserId,
      watching,
    } = options;

    const newState: Partial<State> = {}

    if (beatmapset != null) {
      newState.beatmapset = beatmapset;
    }

    if (watching != null) {
      newState.beatmapset ??= Object.assign({}, this.state.beatmapset);
      newState.beatmapset.current_user_attributes.is_watching = watching;
    }

    if (playmode != null) {
      const beatmap = BeatmapHelper.findDefault({ items: this.groupedBeatmaps.get(playmode) });
      beatmapId = beatmap?.id;
    }

    if (beatmapId != null && beatmapId != this.currentBeatmap.id) {
      newState.currentBeatmapId = beatmapId;
    }

    if (filter != null) {
      if (this.state.currentMode === 'events') {
        newState.currentMode = this.lastMode ?? defaultMode(newState.currentBeatmapId);
      }

      if (filter !== this.state.currentFilter) {
        newState.currentFilter = filter;
      }
    }

    if (mode != null && mode !== this.state.currentMode) {
      if (modeIf == null || modeIf === this.state.currentMode) {
        newState.currentMode = mode;
      }

      // switching to events:
      // - record last filter, to be restored when setMode is called
      // - record last mode, to be restored when setFilter is called
      // - set filter to total
      if (mode === 'events') {
        this.lastMode = this.state.currentMode;
        this.lastFilter = this.state.currentFilter;
        newState.currentFilter = 'total';
      } else if (this.state.currentMode === 'events') {
        // switching from events:
        // - restore whatever last filter set or default to total
        newState.currentFilter = this.lastFilter ?? 'total'
      }
    }

    // need to setState if null
    if (selectedUserId !== undefined) {
      newState.selectedUserId = selectedUserId
    }

    this.setState(newState, callback);
  };
}
