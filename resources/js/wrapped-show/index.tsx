// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapsetCover from 'components/beatmapset-cover';
import DifficultyBadge from 'components/difficulty-badge';
import FlagCountry from 'components/flag-country';
import StringWithComponent from 'components/string-with-component';
import UserAvatar from 'components/user-avatar';
import { toPng } from 'html-to-image';
import UserJson from 'interfaces/user-json';
import { intersection } from 'lodash';
import { action, makeObservable, observable, runInAction } from 'mobx';
import { observer } from 'mobx-react';
import React from 'react';
import { hasGuestOwners } from 'utils/beatmap-helper';
import { getArtist, getTitle } from 'utils/beatmapset-helper';
import { classWithModifiers, Modifiers, urlPresence } from 'utils/css';
import { formatNumber, htmlElementOrNull } from 'utils/html';
import { trans, transArray } from 'utils/lang';
import { mapBy } from 'utils/map';
import { getInt } from 'utils/math';
import { switchNever } from 'utils/switch-never';
import WrappedData, { BeatmapForWrappedJson, FavouriteMapper, TopPlay } from './data';

type Props = WrappedData & {
  user_id: number;
};

/* eslint-disable sort-keys */
const pageTypeMapping = {
  summary: 'summary',
  top_plays: 'beatmaps',
  daily_challenge: 'plain',
  statistics: 'plain',
  favourite_mappers: 'mappers',
  favourite_artists: 'beatmaps',
  mapping: 'plain',
} as const;
/* eslint-enable sort-keys */

type DisplayType = 'beatmaps' | 'mappers' | 'plain' | 'summary';
type PageType = keyof typeof pageTypeMapping;
const listTypes = new Set<DisplayType>(['beatmaps', 'mappers']) as Set<unknown>;

const pageTitles: Record<PageType, string> = {
  daily_challenge: 'Daily Challenge',
  favourite_artists: 'Favourite Artists',
  favourite_mappers: 'Favourite Mappers',
  mapping: 'Beatmapping',
  statistics: 'Statistics',
  summary: 'Summary',
  top_plays: 'Top Plays',
};

const rankColours = ['#ffe599', '#bab9b8', '#fd9a68'];

function FavouriteMapper(props: { mapper: FavouriteMapper; user?: UserJson }) {
  return (
    <div className='wrapped__favourite-mapper'>
      <div className='wrapped__favourite-mapper-avatar'><UserAvatar modifiers='full-circle' user={props.user} /></div>
      <div className='wrapped__summary-list-item-text'>
        <div className='wrapped__summary-list-item-title'>{props.user?.username ?? trans('users.deleted')}</div>
        <div className='wrapped__summary-list-item-value'>{formatNumber(props.mapper.scores.score_count)} plays</div>
      </div>
    </div>
  );
}

function Mappers(props: { beatmap: BeatmapForWrappedJson }) {
  if (props.beatmap.beatmapset == null) return null;

  const translationKey = hasGuestOwners(props.beatmap, props.beatmap.beatmapset)
    ? 'mapped_by_guest'
    : 'mapped_by';

  return (
    <StringWithComponent
      mappings={{
        mapper: (
          <span className={classWithModifiers('wrapped__text', 'difficulty')}>
            {transArray(props.beatmap.owners.map((owner) => owner.username))}
          </span>
        ),
      }}
      pattern={trans(`beatmapsets.show.details.${translationKey}`)}
    />
  );
}

function TopPlay(props: { beatmap?: BeatmapForWrappedJson; play: TopPlay }) {
  const beatmapset = props.beatmap?.beatmapset;
  return (
    <div className={classWithModifiers('wrapped__top-plays', 'summary-beatmap')}>
      <div className={classWithModifiers('wrapped__list-item', 'summary-beatmap')}
      >
        <BeatmapsetCover
          beatmapset={beatmapset}
          modifiers='full'
          size='card'
        />
      </div>
      <div className='wrapped__summary-list-item-text'>
        <div className='wrapped__summary-list-item-title'>
          {beatmapset != null ? getTitle(beatmapset) : trans('beatmapsets.cover.deleted')}
        </div>
        <div className='wrapped__summary-list-item-value'>
          {formatNumber(Math.round(props.play.pp))} pp
        </div>
      </div>
    </div>
  );
}

interface WrappedStatProps {
  modifiers?: Modifiers;
  percent?: boolean;
  round?: boolean;
  skippable?: boolean;
  title: string;
  value: number | string;
}

function WrappedStat(props: WrappedStatProps) {
  if ((props.skippable ?? false) && props.value === 0) {
    return null;
  }

  const value = typeof props.value === 'number' && (props.round ?? false)
    ? Math.round(props.value)
    : props.value;

  const text = typeof value !== 'number'
    ? value
    : props.percent ?? false
      ? formatNumber(value, 2, { style: 'percent' })
      : formatNumber(value);

  return (
    <div className={classWithModifiers('wrapped__stat', props.modifiers)}>
      <div className={classWithModifiers('wrapped__stat-title', props.modifiers)}>{props.title}</div>
      <div className={classWithModifiers('wrapped__stat-value', props.modifiers)}>{text}</div>
    </div>
  );
}

function WrappedStatItems(props: { children?: React.ReactNode; modifiers?: Modifiers; title: string }) {
  return (
    <div className='wrapped__stat'>
      <div className={classWithModifiers('wrapped__stat-title', props.modifiers)}>{props.title}</div>
      <div className={classWithModifiers('wrapped__stat-items', props.modifiers)}>
        {props.children}
      </div>
    </div>
  );
}

@observer
export default class WrappedShow extends React.Component<Props> {
  private readonly availablePages: PageType[];
  private readonly beatmaps = mapBy(this.props.related_beatmaps, 'id');
  // private readonly pages = pageData;
  private readonly ref = React.createRef<HTMLDivElement>();
  @observable private selectedIndex = 0;
  @observable private selectedListIndex = 0;
  private readonly user;
  private readonly users = mapBy(this.props.related_users, 'id');

  get currentList() {
    switch (this.selectedPageType) {
      case 'favourite_artists':
      case 'favourite_mappers':
      case 'top_plays':
        return this.props.summary[this.selectedPageType];
    }

    return [];
  }

  get hasList() {
    return listTypes.has(pageTypeMapping[this.selectedPageType]);
  }

  get isSummaryPage() {
    return this.selectedPageType === 'summary';
  }

  get pageTitle() {
    return pageTitles[this.selectedPageType];
  }

  get selectedFavouriteMapper() {
    return this.props.summary.favourite_mappers[this.selectedListIndex];
  }

  get selectedTopPlay() {
    return this.props.summary.top_plays[this.selectedListIndex];
  }

  get selectedPageType() {
    return this.availablePages[this.selectedIndex];
  }

  constructor(props: Props) {
    super(props);

    const user = this.users.get(props.user_id);
    if (user == null) {
      throw new Error('missing user');
    }

    this.user = user;

    this.availablePages = [
      'summary',
      'statistics',
      ...intersection(Object.keys(pageTypeMapping), Object.keys(props.summary)) as PageType[],
    ];

    // console.log(this.availablePages);

    document.addEventListener('keydown', this.handleKeyDown);

    makeObservable(this);
  }

  componentWillUnmount() {
    document.removeEventListener('keydown', this.handleKeyDown);
  }

  render() {
    const style = {
      '--bg-filter': this.selectedPageType === 'statistics' ? 'blur(2px)' : undefined,
      '--bg-url': `url("${this.backgroundForPage(this.selectedPageType)}")`,
    } as React.CSSProperties;

    return (
      <div
        className={classWithModifiers('wrapped', { summary: this.isSummaryPage })}
        style={style}
      >
        <div
          ref={this.ref}
          className='wrapped__container'
        >
          {/* pseudo elements won't show up in saved image */}
          {/* gradient separated from content background so it's not effected by the padding, etc */}
          <div className='wrapped__background' />
          <div className='wrapped__background wrapped__background--gradient' />
          {this.renderHeader()}

          <div className={classWithModifiers('wrapped__content', pageTypeMapping[this.selectedPageType])}>
            {this.renderPage()}
          </div>
          <div className='wrapped__page-mark'>
            <span className='wrapped__page-number'>{this.selectedIndex}</span>
            <span className='wrapped__page-title'>{this.pageTitle}</span>
          </div>
        </div>
        <div className='wrapped__switcher'>
          {this.availablePages.map((page, index) => this.renderSwitcher(page, index))}
        </div>
        <button className='wrapped__save' onClick={this.handleSaveAsImage}>Save</button>
      </div>
    );
  }

  private backgroundForPage(page: PageType, index?: number) {
    // TODO: actual from data
    switch (page) {
      case 'daily_challenge':
      case 'statistics':
        return this.user.cover?.url;
      case 'top_plays': {
        const beatmap = index == null
          ? this.beatmaps.get(this.selectedTopPlay.beatmap_id)
          : this.beatmaps.get(this.props.summary.top_plays[index].beatmap_id);
        return beatmap?.beatmapset?.covers.cover;
      }
    }

    return `/images/wrapped/${(index ?? 0) % 3 + 1}.png`;
  }

  // @action doesn't work for some reason?
  private readonly handleKeyDown = (e: KeyboardEvent) => runInAction(() => {
    switch (e.key) {
      case 'ArrowDown':
      case 'ArrowRight':
        if (!e.shiftKey && this.hasList && this.currentList.length > 0) {
          if (this.selectedListIndex < this.currentList.length - 1) {
            e.preventDefault();
            this.selectedListIndex++;
            this.scrollSelectedListElementIntoView();
            return;
          }
        }

        if (this.selectedIndex < this.availablePages.length - 1) {
          e.preventDefault();
          this.selectedIndex++;
          this.selectedListIndex = 0;
        }
        return;
      case 'ArrowLeft':
      case 'ArrowUp':
        if (!e.shiftKey && this.hasList && this.currentList.length > 0) {
          if (this.selectedListIndex > 0) {
            e.preventDefault();
            this.selectedListIndex--;
            this.scrollSelectedListElementIntoView();
            return;
          }
        }

        if (this.selectedIndex > 0) {
          e.preventDefault();
          this.selectedIndex--;
          this.selectedListIndex = 0;
        }
        return;
    }
  });

  private readonly handleSaveAsImage = () => {
    if (this.ref.current == null) return;
    toPng(this.ref.current).then((data) => {
      const img = new Image();
      img.src = data;

      const a = document.createElement('a');
      a.href = data;
      a.download = `wrapped_page_${this.selectedIndex}.png`;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    });
  };

  @action
  private readonly handleSelectMapper = (e: React.MouseEvent<HTMLElement>) => {
    const element = htmlElementOrNull(e.currentTarget);
    if (element == null) return;

    const index = getInt(element.dataset.index);
    if (index == null) return;

    if (index >= 0 && index < this.currentList.length) {
      e.preventDefault();
      this.selectedListIndex = index;
      this.scrollSelectedListElementIntoView(element);
    }
  };

  @action
  private readonly handleSwitcherOnClick = (e: React.MouseEvent<HTMLElement>) => {
    const element = htmlElementOrNull(e.currentTarget);
    if (element == null) return;

    const index = getInt(element.dataset.index);
    if (index == null) return;

    if (index >= 0 && index < this.availablePages.length) {
      this.selectedIndex = index;
      this.selectedListIndex = 0;
    }
  };

  private renderDailyChallenge() {
    return (
      <div className='wrapped__stats'>
        <WrappedStat modifiers='fancy' title='Cleared' value={this.props.summary.daily_challenge.cleared} />
        <WrappedStat modifiers='fancy' title='Top 10% Placements' value={this.props.summary.daily_challenge.top_10p} />
        <WrappedStat modifiers='fancy' title='Top 50% Placements' value={this.props.summary.daily_challenge.top_50p} />
        <WrappedStat modifiers='fancy' title='Longest Streak' value={this.props.summary.daily_challenge.highest_streak} />
      </div>
    );
  }

  private renderFavouriteArtists() {
    // TODO:
    return;
  }


  private renderHeader() {
    const summary = this.selectedPageType === 'summary';
    return (
      <div className={classWithModifiers('wrapped__header', { summary })}>
        <div className='wrapped__user'>
          <span
            className='wrapped__user-avatar'
            style={{ backgroundImage: urlPresence(this.user.avatar_url) }}
          />
          {this.isSummaryPage && <FlagCountry country={this.user.country} modifiers={['flat', 'large']} />}
          <span className={classWithModifiers('wrapped__username', { summary })}>{this.user.username}</span>
        </div>
        <img className='wrapped__logo' src='/images/wrapped/logo.svg' />
      </div>
    );
  }

  private renderListDetailsTitle(content: React.ReactNode) {
    return (
      <div className={classWithModifiers('wrapped__list-details-title')}>
        <div
          className='wrapped__rank'
          style={{ '--rank-colour': rankColours[this.selectedListIndex] ?? '' } as React.CSSProperties}
        >
          {`#${this.selectedListIndex + 1}`}
        </div>
        {content}
      </div>
    );
  }

  private renderMappers() {
    const selectedItem = this.selectedFavouriteMapper;
    const mapper = this.users.get(selectedItem.mapper_id);

    return (
      <>
        <div className='wrapped__list'>
          {this.props.summary.favourite_mappers.map((item, index) => (
            <div
              key={item.mapper_id}
              className={classWithModifiers('wrapped__list-item', { selected: this.selectedListIndex === index })}
              data-index={index}
              onClick={this.handleSelectMapper}
            >
              <UserAvatar modifiers='wrapped' user={this.users.get(item.mapper_id)} />
            </div>
          ))}
        </div>
        <div className='wrapped__list-details'>
          {this.renderListDetailsTitle(
            <div className={classWithModifiers('wrapped__text')}>{mapper?.username}</div>,
          )}
          <div className='wrapped__stats'>
            <WrappedStat title='Plays' value={selectedItem.scores.score_count} />
            <WrappedStat title='Best pp' value={selectedItem.scores.pp_best} />
            <WrappedStat title='Average pp' value={selectedItem.scores.pp_avg} />
            <WrappedStat title='Average score' value={selectedItem.scores.score_avg} />

            {/* TODO: duplicated to take up space */}
            <WrappedStat title='Plays' value={selectedItem.scores.score_count} />
            <WrappedStat title='Best pp' value={selectedItem.scores.pp_best} />
            <WrappedStat title='Average pp' value={selectedItem.scores.pp_avg} />
            <WrappedStat title='Average score' value={selectedItem.scores.score_avg} />
          </div>
        </div>
      </>
    );
  }

  private renderMapping() {
    // TODO:
    return;
  }

  private renderPage() {
    switch (this.selectedPageType) {
      case 'daily_challenge':
        return this.renderDailyChallenge();
      case 'favourite_artists':
        return this.renderFavouriteArtists();
      case 'favourite_mappers':
        return this.renderMappers();
      case 'mapping':
        return this.renderMapping();
      case 'summary':
        return this.renderSummary();
      case 'statistics':
        return this.renderStatistics();
      case 'top_plays':
        return this.renderTopPlays();
      default:
        switchNever(this.selectedPageType);
        return <></>;
    }
  }

  private renderStatistics() {
    const summary = this.props.summary;

    return (
      <div className='wrapped__stats'>
        <WrappedStat
          modifiers='fancy'
          percent
          title='Plays Percentile'
          value={summary.scores.playcount.top_percent}
        />
        <WrappedStat modifiers='fancy' title='Beatmaps Played' value={summary.scores.playcount.playcount} />
        <WrappedStat modifiers='fancy' title='Position' value={summary.scores.playcount.pos} />

        <WrappedStat modifiers='fancy' round title='pp' value={summary.scores.pp} />
        <WrappedStat modifiers='fancy' percent title='Accuracy' value={summary.scores.acc} />
        <WrappedStat modifiers='fancy' title='Combo' value={summary.scores.combo} />
        <WrappedStat modifiers='fancy' title='Highest Score' value={summary.scores.score} />

        <WrappedStat modifiers='fancy' title='Medals' value={summary.medals} />
        <WrappedStat modifiers='fancy' title='Replays Watched' value={summary.replays} />

        <WrappedStat modifiers='fancy' title='Daily Challenges Cleared' value={summary.daily_challenge.cleared} />
        <WrappedStat modifiers='fancy' title='Top 10% Placements' value={summary.daily_challenge.top_10p} />
        <WrappedStat modifiers='fancy' title='Top 50% Placements' value={summary.daily_challenge.top_50p} />
        <WrappedStat modifiers='fancy' title='Daily Challenge Streak' value={summary.daily_challenge.highest_streak} />
      </div>
    );
  }

  private renderSummary() {
    const summary = this.props.summary;

    return (
      <>
        <div className='wrapped__top-stats'>
          <WrappedStat modifiers='fancy' title='Beatmaps Played' value={summary.scores.playcount.playcount} />
          <WrappedStat modifiers='fancy' title='pp' value={Math.round(summary.scores.pp)} />
          <WrappedStat modifiers='fancy' title='Highest Score' value={summary.scores.score} />
          <WrappedStat modifiers='fancy' title='Medals' value={summary.medals} />
          <WrappedStat modifiers='fancy' skippable title='Daily Challenge Streak' value={summary.daily_challenge.highest_streak} />
          <WrappedStat modifiers='fancy' skippable title='Replays Watched' value={summary.replays} />
        </div>
        <div className='wrapped__bottom-stats'>
          <WrappedStatItems modifiers={['fancy', 'summary']} title='Your Favourite Mappers'>
            {summary.favourite_mappers.map((value) =>
              <FavouriteMapper key={value.mapper_id} mapper={value} user={this.users.get(value.mapper_id)} />,
            )}
          </WrappedStatItems>
          <WrappedStatItems modifiers={['fancy', 'summary']} title='Your Top Plays'>
            {summary.top_plays.map((value) => <TopPlay key={value.id} beatmap={this.beatmaps.get(value.beatmap_id)} play={value} />)}
          </WrappedStatItems>
        </div>
      </>
    );
  }

  private renderSwitcher(page: PageType, index: number) {
    return (
      <div
        key={index}
        className={classWithModifiers('wrapped__switcher-item', { active: index === this.selectedIndex })}
        data-index={index}
        onClick={this.handleSwitcherOnClick}
      >
        <img src={this.backgroundForPage(page, 0)} />
      </div>
    );
  }

  private renderTopPlays() {
    const selectedItem = this.selectedTopPlay;
    const selectedBeatmap = this.beatmaps.get(selectedItem.beatmap_id);
    const artist = selectedBeatmap?.beatmapset != null ? getArtist(selectedBeatmap.beatmapset) : '';
    const title = selectedBeatmap?.beatmapset != null ? getTitle(selectedBeatmap.beatmapset) : trans('beatmapsets.cover.deleted');

    return (
      <>
        <div className={classWithModifiers('wrapped__list', 'beatmap')}>
          {this.props.summary.top_plays.map((play, index) => (
            <div
              key={play.id}
              className={classWithModifiers('wrapped__list-item', 'beatmap', { selected: this.selectedListIndex === index })}
              data-index={index}
              onClick={this.handleSelectMapper}
            >
              <BeatmapsetCover
                beatmapset={this.beatmaps.get(play.beatmap_id)?.beatmapset}
                modifiers='full'
                size='card'
              />
            </div>
          ))}
        </div>
        {selectedBeatmap != null && (
          <div className='wrapped__list-details'>
            {this.renderListDetailsTitle(
              <div className={classWithModifiers('wrapped__text', 'container')}>
                <div className={classWithModifiers('wrapped__text', 'top')}>
                  {title}
                  <span className={classWithModifiers('wrapped__text', 'artist')}>
                    {` ${trans('beatmapsets.show.details.by_artist', { artist })}`}
                  </span>
                </div>
                <div className={classWithModifiers('wrapped__text', 'bottom')}>
                  <DifficultyBadge rating={selectedBeatmap.difficulty_rating} />
                  <span className={classWithModifiers('wrapped__text', 'difficulty')}>{selectedBeatmap.version}</span>
                  {' '}
                  <Mappers beatmap={selectedBeatmap} />
                </div>
              </div>,
            )}
            <div className='wrapped__stats'>
              <WrappedStat title='Rank' value={selectedItem.rank} />
              <WrappedStat round title='pp' value={selectedItem.pp} />
              <WrappedStat title='Score' value={selectedItem.total_score} />
              <WrappedStat percent title='Accuracy' value={selectedItem.accuracy} />
              <WrappedStat title='Max Combo' value={selectedItem.max_combo} />
              <WrappedStat title='Great' value={selectedItem.statistics.great} />
              <WrappedStat title='Ok' value={selectedItem.statistics.ok} />
              <WrappedStat title='Meh' value={selectedItem.statistics.meh} />
              <WrappedStat title='Miss' value={selectedItem.statistics.miss} />
            </div>
          </div>
        )}
      </>
    );
  }

  // boxing the primitive for observe is annoying so just use querySelector.
  private scrollSelectedListElementIntoView(element?: HTMLElement) {
    (element ?? document.querySelector('.wrapped__list-item--selected'))?.scrollIntoView({ behavior: 'smooth', inline: 'center' });
  }
}
