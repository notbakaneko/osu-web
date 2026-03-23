// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { action, autorun, makeObservable, observable } from 'mobx';
import { disposeOnUnmount, observer } from 'mobx-react';
import * as React from 'react';
import { blackoutToggle } from 'utils/blackout';
import { classWithModifiers, Modifiers, ModifiersExtended } from 'utils/css';
import { getInt } from 'utils/math';

const bn = 'select-options';

export interface RenderableOption {
  children: string | React.ReactNode;
  href: string;
  id: string | number | null;
  modifiers?: Modifiers;
  style?: React.CSSProperties;
}

interface Props {
  blackout: boolean;
  modifiers?: Modifiers;
  // the callback should return the display state the selector should go into after the click, or undefined for the default.
  onClick?: (id: RenderableOption['id']) => boolean | undefined;
  options: RenderableOption[];
  selected: Set<RenderableOption['id']>;

}

@observer
export default class SelectOptionsNew extends React.PureComponent<React.PropsWithChildren<Props>> {
  static readonly defaultProps = { blackout: true };

  private readonly ref = React.createRef<HTMLDivElement>();
  @observable private showingSelector = false;

  constructor(props: Props) {
    super(props);
    makeObservable(this);
    disposeOnUnmount(this, autorun(() => {
      blackoutToggle(this, this.props.blackout && this.showingSelector);
    }));
  }

  componentDidMount() {
    document.addEventListener('click', this.hideSelector);
  }

  componentWillUnmount() {
    document.removeEventListener('click', this.hideSelector);
    blackoutToggle(this, false);
  }

  render() {
    const className = classWithModifiers(
      bn,
      { selecting: this.showingSelector },
      this.props.modifiers,
    );

    return (
      <div ref={this.ref} className={className}>
        <div className={`${bn}__select`}>
          <a className={`${bn}__option`} href='#' onClick={this.toggleSelector}>
            {this.renderText(this.props.children)}
            <div className={`${bn}__decoration`}>
              <span className='fas fa-chevron-down' />
            </div>
          </a>
        </div>

        <div className={`${bn}__selector`}>
          {this.renderOptions()}
        </div>
      </div>
    );
  }

  // dismiss the selector if clicking anywhere outside of it.
  @action
  private readonly hideSelector = (e: MouseEvent) => {
    if (e.button === 0 && this.ref.current != null && !e.composedPath().includes(this.ref.current)) {
      this.showingSelector = false;
    }
  };

  @action
  private readonly optionSelected = (event: React.MouseEvent<HTMLAnchorElement>) => {
    if (event.button !== 0) return;
    event.preventDefault();

    const id = getInt(event.currentTarget.dataset.id) ?? null;
    this.showingSelector = this.props.onClick?.(id) ?? false;
    if (this.showingSelector) {
      event.currentTarget.blur(); // deactivate element is selector is still open.
    }
  };

  private renderOption(option: RenderableOption, modifiers?: ModifiersExtended) {
    return (
      <a
        key={option.id}
        className={classWithModifiers(`${bn}__option`, modifiers, option.modifiers)}
        data-id={option.id ?? undefined}
        href={option.href}
        onClick={this.optionSelected}
        style={option.style}
      >
        {this.renderText(option.children)}
      </a>
    );
  }

  private renderOptions() {
    return this.props.options.map((option) => {
      const selected = this.props.selected.has(option.id);
      return this.renderOption(option, { selected });
    });
  }

  private renderText(children: RenderableOption['children']) {
    return typeof children === 'string' ? (
      <div className='u-ellipsis-overflow'>
        {children}
      </div>
    ) : children;
  }

  @action
  private readonly toggleSelector = (event: React.MouseEvent) => {
    if (event.button !== 0) return;

    event.preventDefault();
    this.showingSelector = !this.showingSelector;
  };
}
