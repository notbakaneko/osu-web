// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapsetCover from 'components/beatmapset-cover';
import FlagCountry from 'components/flag-country';
import UserAvatar from 'components/user-avatar';
import { toPng } from 'html-to-image';
import { intersection } from 'lodash';
import { action, makeObservable, observable, runInAction } from 'mobx';
import { observer } from 'mobx-react';
import React from 'react';
import { classWithModifiers, Modifiers, urlPresence } from 'utils/css';
import { formatNumber, htmlElementOrNull } from 'utils/html';
import { getInt } from 'utils/math';
import { switchNever } from 'utils/switch-never';
import Data, { FavouriteMapper, sampleBeatmapset } from './data';

// interface Page {
//   background: string;
// }

// const pageData = [
//   { background: '/images/wrapped/1.png', type: 'favourite_mappers' },
//   { background: '/images/wrapped/1.png', type: 'summary' },
//   { background: '/images/wrapped/2.png', type: 'stats' },
//   { background: '/images/wrapped/3.png', type: 'beatmaps' },

//   { background: '/images/wrapped/2.png', type: 'mapping' },
//   { background: '/images/wrapped/2.png', title: 'Daily Challenges', type: 'daily_challenge' },
// ];

// // type Keys = keyof Data;
// // const pageKeys: (Keys | 'summary' | 'stats')[] = ['summary', 'favorite_artists', 'favorite_mappers', 'mapping'];

type Props = Data;

/* eslint-disable sort-keys */
const pageTypeMapping = {
  summary: 'summary',
  daily_challenge: 'grid',
  statistics: 'grid',
  top_plays: 'beatmaps',
  favorite_mappers: 'mappers',
  favorite_artists: 'beatmaps',
  mapping: 'grid',
} as const;
/* eslint-enable sort-keys */

type DisplayType = 'beatmaps' | 'mappers' | 'grid' | 'summary';
type PageType = keyof typeof pageTypeMapping;
const listTypes = new Set<DisplayType>(['beatmaps', 'mappers']) as Set<unknown>;

function favouriteMapper(props: FavouriteMapper) {
  return (
    <div className='wrapped__favourite-mapper'>
      <div className='wrapped__favourite-mapper-avatar'><UserAvatar modifiers='full-circle' user={props.mapper} /></div>
      <div className='wrapped__favourite-mapper-text'>
        <div className='wrapped__favourite-mapper-username'>{props.mapper.username}</div>
        <div className='wrapped__favourite-mapper-counts'>{props.scores.score_count} plays</div>
      </div>
    </div>
  );
}

function renderSummaryTopStats(title: string, value: number, modifiers?: Modifiers) {
  return (
    <div className={classWithModifiers('wrapped__stat', modifiers)}>
      <div className={classWithModifiers('wrapped__stat-title', modifiers)}>{title}</div>
      <div className={classWithModifiers('wrapped__stat-value', modifiers)}>{formatNumber(value)}</div>
    </div>
  );
}

@observer
export default class WrappedShow extends React.Component<Props> {
  private readonly availablePages: PageType[];
  // private readonly pages = pageData;
  private readonly ref = React.createRef<HTMLDivElement>();
  @observable private selectedIndex = 0;
  @observable private selectedListIndex = 0;

  get currentList() {
    switch (this.selectedPageType) {
      case 'favorite_artists':
      case 'favorite_mappers':
      case 'top_plays':
        return this.props[this.selectedPageType].slice(0, 10);
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
    // TODO: actual titles
    // return this.selectedPage.title ?? this.selectedPageType;
    return this.selectedPageType;
  }

  get selectedFavouriteMapper() {
    return this.props.favorite_mappers[this.selectedListIndex];
  }

  get selectedTopPlay() {
    return this.props.top_plays[this.selectedListIndex];
  }

  // get selectedPage() {
  //   return this.pages[this.selectedIndex];
  // }

  get selectedPageType() {
    return this.availablePages[this.selectedIndex];
  }

  constructor(props: Props) {
    super(props);

    this.availablePages = [
      'summary',
      'statistics',
      ...intersection(Object.keys(pageTypeMapping), Object.keys(props)) as PageType[],
    ];

    console.log(this.availablePages);

    document.addEventListener('keydown', this.handleKeyDown);

    makeObservable(this);
  }

  componentWillUnmount(): void {
    document.removeEventListener('keydown', this.handleKeyDown);
  }

  render() {
    return (
      <div
        className={classWithModifiers('wrapped', { summary: this.isSummaryPage })}
        style={{ '--url': `url("${this.backgroundForPage(this.selectedPageType, this.selectedIndex)}")` } as React.CSSProperties}
      >
        <div
          ref={this.ref}
          className='wrapped__container'
        >
          {/* pseudo elements won't show up in saved image */}
          <div className='wrapped__background' />
          {this.renderHeader()}
          {this.renderPage()}
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

  private backgroundForPage(page: PageType, index: number) {
    // TODO: actual from data
    return `/images/wrapped/${index % 3 + 1}.png`;
  }

  // @action doesn't work for some reason?
  private readonly handleKeyDown = (e: KeyboardEvent) => runInAction(() => {
    switch (e.key) {
      case 'ArrowDown':
      case 'ArrowRight':
        if (!e.shiftKey && this.hasList && this.currentList.length > 0) {
          if (this.selectedListIndex < this.currentList.length - 1) {
            this.selectedListIndex++;
            return;
          }
        }

        if (this.selectedIndex < this.availablePages.length - 1) {
          this.selectedIndex++;
          this.selectedListIndex = 0;
        }
        return;
      case 'ArrowLeft':
      case 'ArrowUp':
        if (!e.shiftKey && this.hasList && this.currentList.length > 0) {
          if (this.selectedListIndex > 0) {
            this.selectedListIndex--;
            return;
          }
        }

        if (this.selectedIndex > 0) {
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
      this.selectedListIndex = index;
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
    }
  };

  private renderBeatmaps() {
    const selectedItem = this.selectedTopPlay;

    return (
      <>
        <div className={classWithModifiers('wrapped__mappers', 'beatmap')}>
          {this.props.top_plays.map((play, index) => (
            <div
              key={play.id}
              className={classWithModifiers('wrapped__mapper', 'beatmap', { selected: this.selectedListIndex === index })}
              data-index={index}
              onClick={this.handleSelectMapper}
            >
              <BeatmapsetCover
                beatmapset={sampleBeatmapset}
                modifiers='full'
                size='card'
              />
            </div>
          ))}
        </div>
        <div className='wrapped__mapper-details'>
          {/* <div className={classWithModifiers('wrapped__text')}>{selectedMapper.mapper.username}</div>
          <div className='wrapped__stats'>
            <div className='wrapped__stat'>
              <div className={classWithModifiers('wrapped__stat-title')}>Plays</div>
              <div className={classWithModifiers('wrapped__stat-value')}>{formatNumber(selectedMapper.scores.score_count)}</div>
            </div>
            <div className='wrapped__stat'>
              <div className={classWithModifiers('wrapped__stat-title')}>Best pp</div>
              <div className={classWithModifiers('wrapped__stat-value')}>{formatNumber(selectedMapper.scores.pp_best)}</div>
            </div>
            <div className='wrapped__stat'>
              <div className={classWithModifiers('wrapped__stat-title')}>Average pp</div>
              <div className={classWithModifiers('wrapped__stat-value')}>{formatNumber(selectedMapper.scores.pp_avg)}</div>
            </div>
            <div className='wrapped__stat'>
              <div className={classWithModifiers('wrapped__stat-title')}>Average score</div>
              <div className={classWithModifiers('wrapped__stat-value')}>{formatNumber(selectedMapper.scores.score_avg)}</div>
            </div>
          </div> */}
        </div>
      </>
    );
  }

  private renderDailyChallenge() {
    const keyMapping = {
      cleared: 'Cleared',
      top_10p: 'Top 10 Percent Placements',
      top_50p: 'Top 50 Percent Placements',
      // eslint-disable-next-line sort-keys
      highest_streak: 'Longest Streak',
    };

    type KeyMappingType = keyof typeof keyMapping;

    return Object.keys(keyMapping).map((key: KeyMappingType) => (
      <React.Fragment key={key}>
        {renderSummaryTopStats(keyMapping[key], this.props.daily_challenge[key])}
      </React.Fragment>
    ));
  }

  private renderFavouriteArtists() {
    return;
  }


  private renderHeader() {
    return (
      <div className={classWithModifiers('wrapped__header', { summary: this.selectedPageType === 'summary' })}>
        <div className='wrapped__user'>
          <span
            className='wrapped__user-avatar'
            style={{ backgroundImage: urlPresence(this.props.user.avatar_url) }}
          />
          {this.isSummaryPage && <FlagCountry country={this.props.user.country} modifiers={['flat', 'large']} />}
          {this.props.user.username}
        </div>
        <img className='wrapped__logo' src='/images/wrapped/logo.svg' />
      </div>
    );
  }

  private renderMappers() {
    const selectedMapper = this.selectedFavouriteMapper;

    return (
      <>
        <div className='wrapped__mappers'>
          {this.props.favorite_mappers.map((mapper, index) => (
            <div
              key={mapper.mapper.id}
              className={classWithModifiers('wrapped__mapper', { selected: this.selectedListIndex === index })}
              data-index={index}
              onClick={this.handleSelectMapper}
            >
              <UserAvatar modifiers='wrapped' user={mapper.mapper} />
            </div>
          ))}
        </div>
        <div className='wrapped__mapper-details'>
          <div className={classWithModifiers('wrapped__text')}>{selectedMapper.mapper.username}</div>
          <div className='wrapped__stats'>
            <div className='wrapped__stat'>
              <div className={classWithModifiers('wrapped__stat-title')}>Plays</div>
              <div className={classWithModifiers('wrapped__stat-value')}>{formatNumber(selectedMapper.scores.score_count)}</div>
            </div>
            <div className='wrapped__stat'>
              <div className={classWithModifiers('wrapped__stat-title')}>Best pp</div>
              <div className={classWithModifiers('wrapped__stat-value')}>{formatNumber(selectedMapper.scores.pp_best)}</div>
            </div>
            <div className='wrapped__stat'>
              <div className={classWithModifiers('wrapped__stat-title')}>Average pp</div>
              <div className={classWithModifiers('wrapped__stat-value')}>{formatNumber(selectedMapper.scores.pp_avg)}</div>
            </div>
            <div className='wrapped__stat'>
              <div className={classWithModifiers('wrapped__stat-title')}>Average score</div>
              <div className={classWithModifiers('wrapped__stat-value')}>{formatNumber(selectedMapper.scores.score_avg)}</div>
            </div>
          </div>
        </div>
      </>
    );
  }

  private renderPage() {
    const layout = pageTypeMapping[this.selectedPageType] ?? 'grid';

    return (
      <div className={classWithModifiers('wrapped__content', layout)}>
        {this.renderPageActual()}
      </div>
    );
  }

  private renderPageActual() {
    switch (this.selectedPageType) {
      case 'daily_challenge':
        return this.renderDailyChallenge();
      case 'favorite_artists':
        return this.renderFavouriteArtists();
      case 'favorite_mappers':
        return this.renderMappers();
      case 'mapping':
        return this.renderBeatmaps();
      case 'summary':
        return this.renderSummary();
      case 'statistics':
        return this.renderStats();
      case 'top_plays':
        return this.renderBeatmaps();
      default:
        switchNever(this.selectedPageType);
        return <></>;
    }

    return this.renderSummary();
  }

  private renderStats() {
    return;
  }

  private renderSummary() {
    return (
      <>
        <div className='wrapped__top-stats'>
          {renderSummaryTopStats('medals', this.props.medals)}
          {renderSummaryTopStats('replays', this.props.replays)}
          {renderSummaryTopStats('Beatmaps Played', 378)}
          {renderSummaryTopStats('Daily Challenge Streak', 372)}
          {renderSummaryTopStats('Higest Score', 382739393)}
          {renderSummaryTopStats('Made up stat', 454)}
        </div>
        <div className='wrapped__bottom-stats'>
          <div className='wrapped__stat'>
            <div className={classWithModifiers('wrapped__stat-title')}>Your Top Mappers</div>
            <div className={classWithModifiers('wrapped__stat-items')}>
              {this.props.favorite_mappers.map(favouriteMapper)}
            </div>
          </div>
          <div className='wrapped__stat'>
            <div className={classWithModifiers('wrapped__stat-title')}>Your Top Maps</div>
            <div className={classWithModifiers('wrapped__stat-items')}>
            </div>
          </div>
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
        <img src={this.backgroundForPage(page, index)} />
      </div>
    );
  }
}
