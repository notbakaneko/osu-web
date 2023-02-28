// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { makeObservable, observable } from 'mobx';
import { DiscussionsContextValue } from './discussions-context';

export default class DiscussionsState {
  @observable discussionCollapsed = new Map<number, boolean>();
  @observable discussionDefaultCollapsed = false;
  @observable discussionsContext?: DiscussionsContextValue;
  @observable highlightedDiscussionId: number | null = null;


  constructor() {
    makeObservable(this);
  }
}
