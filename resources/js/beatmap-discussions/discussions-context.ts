// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { BeatmapsetDiscussionJsonForBundle, BeatmapsetDiscussionJsonForShow } from 'interfaces/beatmapset-discussion-json';
import { makeObservable, observable } from 'mobx';
import { createContext } from 'react';

export class DiscussionStore {
  @observable discussions: Partial<Record<number, BeatmapsetDiscussionJsonForBundle | BeatmapsetDiscussionJsonForShow>> = {};


  constructor() {
    makeObservable(this);
  }
}

// TODO: needs discussions need flattening / normalization
export const DiscussionsContext = createContext(new DiscussionStore());
