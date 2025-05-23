// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { Spinner } from 'components/spinner';
import StringWithComponent from 'components/string-with-component';
import { route } from 'laroute';
import { observer } from 'mobx-react';
import * as React from 'react';
import { classWithModifiers } from 'utils/css';
import { formatNumber, htmlElementOrNull } from 'utils/html';
import { trans } from 'utils/lang';
import { navigate } from 'utils/turbolinks';
import Beatmapset from './beatmapset';
import Team from './team';
import User from './user';
import { otherModes, ResultMode, Section } from './worker';
import Worker from './worker';


interface Props {
  modifiers?: string[];
  onClose?: () => void;
  worker: Worker;
}

@observer export default class QuickSearch extends React.Component<Props> {
  private readonly inputRef = React.createRef<HTMLInputElement>();

  focus = () => {
    if (this.inputRef.current != null) {
      this.inputRef.current.selectionStart = 0;
      this.inputRef.current.selectionEnd = this.inputRef.current.value.length;

      this.props.worker.selectNone();

      this.inputRef.current?.focus();
    }
  };

  render() {
    let blockClass = classWithModifiers('quick-search', this.props.modifiers);
    blockClass += ' u-fancy-scrollbar';

    return (
      <div className={blockClass}>
        <div className='quick-search-input'>
          <div className='quick-search-input__field'>
            <span className='quick-search-input__icon'>
              {this.props.worker.searching ? <Spinner /> : <span className='fas fa-search' />}
            </span>

            <input
              ref={this.inputRef}
              className='quick-search-input__input js-click-menu--autofocus'
              onChange={this.updateQuery}
              onKeyDown={this.onInputKeyDown}
              placeholder={trans('home.search.placeholder')}
              type='search'
              value={this.props.worker.query}
            />
          </div>

          {this.props.onClose != null && (
            <button
              className='btn-osu-big btn-osu-big--quick-search-close'
              onClick={this.props.onClose}
              type='button'
            >
              {trans('common.buttons.close')}
            </button>
          )}
        </div>

        {this.renderResult()}
      </div>
    );
  }

  private boxIsActive(section: Section, idx: number): boolean {
    const worker = this.props.worker;
    return worker.currentSection === section && worker.selected?.index === idx;
  }

  private count(mode: ResultMode) {
    if (this.props.worker.searchResult === null) {
      return 0;
    }

    return this.props.worker.searchResult[mode].total;
  }

  private navigateToSelected() {
    const url = this.props.worker.selectedURL;
    if (url != null) {
      navigate(url, false);
    }
  }

  private readonly onInputKeyDown = (event: React.KeyboardEvent<HTMLInputElement>) => {
    const key = event.key;
    if (key === 'Enter') {
      // this will prevent keyboard arrow navigation
      htmlElementOrNull(event.target)?.blur();
      this.props.worker.debouncedSearch.flush();
      this.navigateToSelected();
    }
    if (key === 'ArrowUp' || key === 'ArrowDown') {
      this.props.worker.cycleSelectedItem(key === 'ArrowDown' ? 1 : -1);
    }
  };

  private readonly onMouseEnter = (event: React.MouseEvent<HTMLDivElement>) => {
    const section = event.currentTarget.dataset.section as Section;
    const index = parseInt(event.currentTarget.dataset.index ?? '0', 10);

    this.selectBox(section, index);
  };

  private readonly onMouseLeave = () => {
    this.props.worker.selectNone();
  };

  private renderBeatmapsets() {
    if (this.props.worker.searchResult === null) {
      return null;
    }

    return (
      <div className='quick-search-items'>
        {this.props.worker.searchResult.beatmapset.items.map((beatmapset, idx) => (
          <div
            key={beatmapset.id}
            className='quick-search-items__item'
            data-index={idx}
            data-section='beatmapset'
            onMouseEnter={this.onMouseEnter}
            onMouseLeave={this.onMouseLeave}
          >
            <Beatmapset
              beatmapset={beatmapset}
              modifiers={this.boxIsActive('beatmapset', idx) ? ['active'] : []}
            />
          </div>
        ))}

        <div
          className='quick-search-items__item'
          data-section='beatmapset_others'
          onMouseEnter={this.onMouseEnter}
          onMouseLeave={this.onMouseLeave}
        >
          {this.renderResultLink('beatmapset', this.boxIsActive('beatmapset_others', 0))}
        </div>
      </div>
    );
  }

  private renderOthers() {
    return (
      <div className='quick-search-items'>
        {otherModes.map((mode, idx) => (
          <div
            key={mode}
            className='quick-search-items__item'
            data-index={idx}
            data-section='others'
            onMouseEnter={this.onMouseEnter}
            onMouseLeave={this.onMouseLeave}
          >
            {this.renderResultLink(mode, this.boxIsActive('others', idx))}
          </div>
        ))}
      </div>
    );
  }

  private renderResult() {
    if (this.props.worker.searchResult == null) {
      return null;
    }

    return (
      <div className='quick-search-result'>
        <div className='quick-search-result__item'>
          {this.renderTitle('beatmapset')}
          {this.renderBeatmapsets()}
        </div>

        <div className='quick-search-result__item'>
          {this.renderTitle('user')}
          {this.renderUsers()}
        </div>

        <div className='quick-search-result__item'>
          {this.renderTitle('team')}
          {this.renderTeams()}
        </div>

        <div className='quick-search-result__item'>
          {this.renderTitle('other')}
          {this.renderOthers()}
        </div>
      </div>
    );
  }

  private renderResultLink(mode: ResultMode, active = false) {
    const count = this.count(mode);

    let key = 'quick_search.result.';
    key += count === 0
      ? 'empty_for'
      : otherModes.includes(mode)
        ? 'title'
        : 'more';

    return (
      <a
        className={classWithModifiers('search-result-more', active ? ['active'] : [])}
        href={route('search', { mode, query: this.props.worker.query })}
      >
        <div className='search-result-more__content'>
          {trans(key, { mode: trans(`quick_search.mode.${mode}`) })}
          <span className='search-result-more__count'>
            {formatNumber(count)}
          </span>
        </div>
        <div className='search-result-more__arrow'>
          <span className='fas fa-angle-right' />
        </div>
      </a>
    );
  }

  private renderTeams() {
    if (this.props.worker.searchResult === null) {
      return null;
    }

    return (
      <div className='quick-search-items'>
        {this.props.worker.searchResult.team.items.map((team, idx) => (
          <div
            key={team.id}
            className='quick-search-items__item'
            data-index={idx}
            data-section='team'
            onMouseEnter={this.onMouseEnter}
            onMouseLeave={this.onMouseLeave}
          >
            <Team
              modifiers={{ active: this.boxIsActive('team', idx) }}
              team={team}
            />
          </div>
        ))}

        <div
          className='quick-search-items__item'
          data-section='team_others'
          onMouseEnter={this.onMouseEnter}
          onMouseLeave={this.onMouseLeave}
        >
          {this.renderResultLink('team', this.boxIsActive('team_others', 0))}
        </div>
      </div>
    );
  }

  private renderTitle(mode: string) {
    return (
      <h2 className='title'>
        <StringWithComponent
          mappings={{ mode: <strong>{trans(`quick_search.mode.${mode}`)}</strong> }}
          pattern={trans('quick_search.result.title')}
        />
      </h2>
    );
  }

  private renderUsers() {
    if (this.props.worker.searchResult == null) {
      return null;
    }

    return (
      <div className='quick-search-items'>
        {this.props.worker.searchResult.user.items.map((user, idx) => (
          <div
            key={user.id}
            className='quick-search-items__item'
            data-index={idx}
            data-section='user'
            onMouseEnter={this.onMouseEnter}
            onMouseLeave={this.onMouseLeave}
          >
            <User
              modifiers={this.boxIsActive('user', idx) ? ['active'] : []}
              user={user}
            />
          </div>
        ))}

        <div
          className='quick-search-items__item'
          data-section='user_others'
          onMouseEnter={this.onMouseEnter}
          onMouseLeave={this.onMouseLeave}
        >
          {this.renderResultLink('user', this.boxIsActive('user_others', 0))}
        </div>
      </div>
    );
  }

  private selectBox(section: Section, index = 0) {
    this.props.worker.setSelected(section, index);
  }

  private readonly updateQuery = (event: React.SyntheticEvent<HTMLInputElement>) => {
    this.props.worker.updateQuery(event.currentTarget.value);
  };
}
