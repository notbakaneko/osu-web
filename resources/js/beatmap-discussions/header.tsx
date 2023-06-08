// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import headerLinks from 'beatmapsets-show/header-links';
import BeatmapBasicStats from 'components/beatmap-basic-stats';
import BeatmapsetBadge from 'components/beatmapset-badge';
import BeatmapsetCover from 'components/beatmapset-cover';
import BeatmapsetMapping from 'components/beatmapset-mapping';
import BigButton from 'components/big-button';
import HeaderV4 from 'components/header-v4';
import PlaymodeTabs from 'components/playmode-tabs';
import StringWithComponent from 'components/string-with-component';
import UserLink from 'components/user-link';
import BeatmapExtendedJson from 'interfaces/beatmap-extended-json';
import BeatmapJson from 'interfaces/beatmap-json';
import { BeatmapsetDiscussionJsonForShow } from 'interfaces/beatmapset-discussion-json';
import BeatmapsetEventJson from 'interfaces/beatmapset-event-json';
import BeatmapsetWithDiscussionsJson from 'interfaces/beatmapset-with-discussions-json';
import GameMode, { gameModes } from 'interfaces/game-mode';
import UserJson from 'interfaces/user-json';
import { route } from 'laroute';
import { kebabCase, size, snakeCase } from 'lodash';
import { observer } from 'mobx-react';
import { deletedUser } from 'models/user';
import core from 'osu-core-singleton';
import * as React from 'react';
import { getArtist, getTitle } from 'utils/beatmap-helper';
import { makeUrl } from 'utils/beatmapset-discussion-helper';
import { classWithModifiers } from 'utils/css';
import { trans } from 'utils/lang';
import BeatmapList from './beatmap-list';
import Chart from './chart';
import { Filter } from './current-discussions';
import { DiscussionPage } from './discussion-mode';
import DiscussionsState, { filterDiscussionsByFilter } from './discussions-state';
import { Nominations } from './nominations';
import { Subscribe } from './subscribe';
import { UserFilter } from './user-filter';

interface Props {
  // beatmaps: Map<GameMode, BeatmapExtendedJson[]>;
  // beatmapset: BeatmapsetWithDiscussionsJson;
  // currentBeatmap: BeatmapExtendedJson;
  // currentFilter: Filter;
  // discussions: Partial<Record<number, BeatmapsetDiscussionJsonForShow>>;
  discussionsState: DiscussionsState;
  // discussionStarters: UserJson[];
  events: BeatmapsetEventJson[];
  // mode: DiscussionPage;
  // selectedUserId: number | null;
  // users: Partial<Record<number, UserJson>>;
}

const statTypes: Filter[] = ['mine', 'mapperNotes', 'resolved', 'pending', 'praises', 'deleted', 'total'];

@observer
export class Header extends React.Component<Props> {
  private get beatmaps() {
    return this.discussionsState.groupedBeatmaps;
  }

  private get beatmapset() {
    return this.discussionsState.beatmapset;
  }

  private get currentBeatmap() {
    return this.discussionsState.currentBeatmap;
  }

  private get discussions() {
    return this.discussionsState.discussions;
  }

  private get discussionsState() {
    return this.props.discussionsState;
  }

  private get users() {
    return this.discussionsState.users;
  }

  render() {
    return (
      <>
        <HeaderV4
          links={headerLinks('discussions', this.beatmapset)}
          linksAppend={(
            <PlaymodeTabs
              currentMode={this.currentBeatmap.mode}
              entries={gameModes.map((mode) => ({
                count: this.discussionsState.discussionsCountByPlaymode[mode],
                disabled: (this.discussionsState.groupedBeatmaps.get(mode)?.length ?? 0) === 0,
                mode,
              }))}
              modifiers='beatmapset'
              onClick={this.onClickMode}
            />
          )}
          theme='beatmapset'
        />
        <div className='osu-page'>{this.renderHeaderTop()}</div>
        <div className='osu-page osu-page--small'>{this.renderHeaderBottom()}</div>
      </>
    );
  }

  private readonly createLink = (beatmap: BeatmapJson) => makeUrl({ beatmap });

  private readonly getCount = (beatmap: BeatmapExtendedJson) => beatmap.deleted_at == null ? this.discussionsState.discussionsByBeatmap(beatmap.id).length : undefined;

  private onClickMode = (event: React.MouseEvent<HTMLAnchorElement>, mode: GameMode) => {
    event.preventDefault();
    $.publish('playmode:set', [{ mode }]);
  };

  private onSelectBeatmap = (beatmapId: number) => {
    $.publish('beatmapsetDiscussions:update', {
      beatmapId,
      mode: 'timeline',
    });
  };

  private renderHeaderBottom() {
    const bn = 'beatmap-discussions-header-bottom';

    return (
      <div className={bn}>
        <div className={`${bn}__content ${bn}__content--details`}>
          <div className={`${bn}__details ${bn}__details--full`}>
            <BeatmapsetMapping
              beatmapset={this.beatmapset}
              user={this.discussionsState.users[this.beatmapset.user_id]}
            />
          </div>
          <div className={`${bn}__details`}>
            <Subscribe beatmapset={this.beatmapset} />
          </div>
          <div className={`${bn}__details`}>
            <BigButton
              href={route('beatmapsets.show', { beatmapset: this.beatmapset.id })}
              icon='fas fa-info'
              modifiers='full'
              text={trans('beatmaps.discussions.beatmap_information')}
            />
          </div>
        </div>
        <div className={`${bn}__content ${bn}__content--nomination`}>
          <Nominations
            beatmapset={this.beatmapset}
            discussions={this.discussions}
            discussionsState={this.discussionsState}
            events={this.props.events}
            users={this.users}
          />
        </div>
      </div>
    );
  }


  private renderHeaderTop() {
    const bn = 'beatmap-discussions-header-top';

    return (
      <div className={bn}>
        <div className={`${bn}__content`}>
          <div className={`${bn}__cover`}>
            <BeatmapsetCover
              beatmapset={this.beatmapset}
              modifiers='full'
              size='cover'
            />
          </div>
          <div className={`${bn}__title-container`}>
            <h1 className={`${bn}__title`}>
              <a
                className='link link--white link--no-underline'
                href={route('beatmapsets.show', { beatmapset: this.beatmapset.id })}
              >
                {getTitle(this.beatmapset)}
              </a>
              <BeatmapsetBadge beatmapset={this.beatmapset} type='nsfw' />
              <BeatmapsetBadge beatmapset={this.beatmapset} type='spotlight' />
            </h1>
            <h2 className={`${bn}__title ${bn}__title--artist`}>
              {getArtist(this.beatmapset)}
              <BeatmapsetBadge beatmapset={this.beatmapset} type='featured_artist' />
            </h2>
          </div>
          <div className={`${bn}__filters`}>
            <div className={`${bn}__filter-group`}>
              <BeatmapList
                beatmaps={this.beatmaps.get(this.currentBeatmap.mode) ?? []}
                beatmapset={this.beatmapset}
                createLink={this.createLink}
                currentBeatmap={this.currentBeatmap}
                getCount={this.getCount}
                onSelectBeatmap={this.onSelectBeatmap}
                users={this.users}
              />
            </div>
            <div className={`${bn}__filter-group ${bn}__filter-group--stats`}>
              <UserFilter
                discussionsState={this.discussionsState}
              />
              <div className={`${bn}__stats`}>{this.renderStats()}</div>
            </div>
          </div>
          <div className='u-relative'>
            <Chart
              discussions={this.discussionsState.discussionsByFilter(this.discussionsState.currentFilter, 'timeline', this.currentBeatmap.id)}
              duration={this.currentBeatmap.total_length * 1000}
            />
            <div className={`${bn}__beatmap-stats`}>
              <div className={`${bn}__guest`}>
                {this.currentBeatmap.user_id !== this.beatmapset.user_id ? (
                  <span>
                    <StringWithComponent
                      mappings={{
                        user: <UserLink user={this.users[this.currentBeatmap.user_id] ?? deletedUser} />,
                      }}
                      pattern={trans('beatmaps.discussions.guest')}
                    />
                  </span>
                ) : null}
              </div>
              <BeatmapBasicStats beatmap={this.currentBeatmap} beatmapset={this.beatmapset} />
            </div>
          </div>
        </div>
      </div>
    );
  }

  private renderStats() {
    return statTypes.map(this.renderType);
  }

  private readonly renderType = (type: Filter) => {
    if ((type === 'deleted') && !core.currentUser?.is_admin) {
      return null;
    }

    const bn = 'counter-box';

    let topClasses = classWithModifiers(bn, 'beatmap-discussions', kebabCase(type));
    if (this.discussionsState.currentMode !== 'events' && this.discussionsState.currentFilter === type) {
      topClasses += ' js-active';
    }

    // TODO: count all at once
    const discussionsByFilter = filterDiscussionsByFilter(this.discussionsState.currentBeatmapDiscussions, type);
    const total = discussionsByFilter.length;

    return (
      <a
        key={type}
        className={topClasses}
        data-type={type}
        href={makeUrl({
          beatmapId: this.currentBeatmap.id,
          beatmapsetId: this.beatmapset.id,
          filter: type,
          mode: this.discussionsState.currentMode,
        })}
        onClick={this.setFilter}
      >
        <div className={`${bn}__content`}>
          <div className={`${bn}__title`}>
            {trans(`beatmaps.discussions.stats.${snakeCase(type)}`)}
          </div>
          <div className={`${bn}__count`}>
            {total}
          </div>
        </div>
        <div className={`${bn}__line`} />
      </a>
    );
  };

  private readonly setFilter = (event: React.SyntheticEvent<HTMLElement>) => {
    event.preventDefault();
    $.publish('beatmapsetDiscussions:update', { filter: event.currentTarget.dataset.type });
  };
}
