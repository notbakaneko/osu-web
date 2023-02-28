// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapExtendedJson from 'interfaces/beatmap-extended-json';
import { BeatmapsetDiscussionJsonForBundle, BeatmapsetDiscussionJsonForShow } from 'interfaces/beatmapset-discussion-json';
import { createContext } from 'react';

class DiscussionsContextValue {
  @observable beatmaps: Partial<Record<number, BeatmapExtendedJson>> = {};
  @observable discussions: Partial<Record<number, BeatmapsetDiscussionJsonForBundle | BeatmapsetDiscussionJsonForShow>> = {};

  constructor() {
    makeObservable(this);
  }
}

// TODO: needs discussions need flattening / normalization
// TODO: combine with DiscussionsState?
export const DiscussionsContext = createContext(new DiscussionsContextValue());
