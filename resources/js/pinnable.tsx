// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { action, computed, makeObservable, observable } from 'mobx';
import { observer } from 'mobx-react';
import core from 'osu-core-singleton';
import React from 'react';
import { classWithModifiers } from 'utils/css';

interface Props {
  children: React.ReactNode;
  pinned: boolean;
  stickTo: React.RefObject<HTMLElement>;
}

@observer
export default class Pinnable extends React.PureComponent<Props> {
  private readonly disposers = new Set<((() => void) | undefined)>();
  @observable private mounted = false;
  @observable private stickToHeight?: number;

  constructor(props: Props) {
    super(props);
    makeObservable(this);
  }

  @computed
  private get cssTop() {
    if (this.mounted && this.props.pinned && this.stickToHeight != null) {
      return Math.floor(core.stickyHeader.headerHeight + this.stickToHeight);
    }
  }

  componentDidMount() {
    // watching for height changes on the stickTo element to handle horizontal scrollbars when they appear.
    $(window).on('resize', this.updateStickToHeight);
    this.disposers.add(core.reactTurbolinks.runAfterPageLoad(action(() => {
      this.mounted = true;
      this.updateStickToHeight();
    })));
  }

  componentWillUnmount() {
    $(window).off('resize', this.updateStickToHeight);
    this.disposers.forEach((disposer) => disposer?.());
  }

  render() {
    return (
      <div
        className={classWithModifiers('pinnable', { pinned: this.props.pinned })}
        style={{ top: this.cssTop }}
      >
        {this.props.children}
      </div>
    );
  }

  @action
  private readonly updateStickToHeight = () => this.stickToHeight = this.props.stickTo?.current?.getBoundingClientRect().height;
}
