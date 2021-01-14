// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { createContext } from 'react';
import BeatmapStore from 'stores/beatmap-store';
import { BeatmapsetDiscussionStore } from 'stores/beatmapset-discussion-store';
import { BeatmapsetStore } from 'stores/beatmapset-store';
import UserStore from 'stores/user-store';

export interface Props {
  beatmapsetStore: BeatmapsetStore;
  beatmapStore: BeatmapStore;
  discussionStore: BeatmapsetDiscussionStore;
  userStore: UserStore;
}

// TODO: temporary for while consolidating discussion state?
export const DiscussionsStoreContext = createContext<Props>({
  beatmapsetStore: new BeatmapsetStore(),
  beatmapStore: new BeatmapStore(),
  discussionStore: new BeatmapsetDiscussionStore(),
  userStore: new UserStore(),
});
