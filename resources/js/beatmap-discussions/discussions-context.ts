// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapExtendedJson from 'interfaces/beatmap-extended-json';
import { BeatmapsetDiscussionJsonForBundle, BeatmapsetDiscussionJsonForShow } from 'interfaces/beatmapset-discussion-json';
import { BeatmapsetWithDiscussionsJson } from 'interfaces/beatmapset-json';
import { isEmpty, keyBy } from 'lodash';
import { action, computed, makeObservable, observable } from 'mobx';
import { deletedUser } from 'models/user';
import { createContext } from 'react';

export class DiscussionStore {
  @observable beatmaps: Partial<Record<number, BeatmapExtendedJson>> = {};
  @observable discussions: Partial<Record<number, BeatmapsetDiscussionJsonForBundle | BeatmapsetDiscussionJsonForShow>> = {};

  @observable private beatmapset: BeatmapsetWithDiscussionsJson | null = null;

  @computed
  get users() {
    if (this.beatmapset == null) return {};

    const users = keyBy(this.beatmapset.related_users, 'id');
    // eslint-disable-next-line id-blacklist
    users.null = users.undefined = deletedUser.toJson();

    return users;
  }

  constructor() {
    makeObservable(this);
  }

  @action
  updateWithBeatmapset(beatmapset: BeatmapsetWithDiscussionsJson) {
    this.beatmapset = beatmapset;

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
