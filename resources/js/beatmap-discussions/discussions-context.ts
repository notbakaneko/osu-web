// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapExtendedJson from 'interfaces/beatmap-extended-json';
import { BeatmapsetDiscussionJsonForBundle, BeatmapsetDiscussionJsonForShow } from 'interfaces/beatmapset-discussion-json';
import { BeatmapsetWithDiscussionsJson } from 'interfaces/beatmapset-json';
import { isEmpty } from 'lodash';
import { makeObservable, observable } from 'mobx';
import { createContext } from 'react';

export class DiscussionStore {
  @observable beatmaps: Partial<Record<number, BeatmapExtendedJson>> = {};
  @observable discussions: Partial<Record<number, BeatmapsetDiscussionJsonForBundle | BeatmapsetDiscussionJsonForShow>> = {};

  constructor() {
    makeObservable(this);
  }

  updateWithBeatmapset(beatmapset: BeatmapsetWithDiscussionsJson) {
    const discussions = beatmapset.discussions.filter((discussion) => !isEmpty(discussion));
    for (const discussion of discussions) {
      this.discussions[discussion.id] = discussion;
    }

    // TODO: just store all and handle states later?
    for (const beatmap of beatmapset.beatmaps) {
      this.beatmaps[beatmap.id] = beatmap;
    }
  }
}

// TODO: needs discussions need flattening / normalization
export const DiscussionsContext = createContext(new DiscussionStore());
