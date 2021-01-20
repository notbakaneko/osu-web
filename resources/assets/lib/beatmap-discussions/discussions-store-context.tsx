// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import ReviewEditorConfigJson from 'interfaces/review-editor-config-json';
import { createContext } from 'react';
import BeatmapStore from 'stores/beatmap-store';
import { BeatmapsetDiscussionStore } from 'stores/beatmapset-discussion-store';
import { BeatmapsetStore } from 'stores/beatmapset-store';
import UserStore from 'stores/user-store';

export interface Props {
  beatmapsetStore: BeatmapsetStore;
  beatmapStore: BeatmapStore;
  discussionStore: BeatmapsetDiscussionStore;
  reviewsConfig: ReviewEditorConfigJson;
  userStore: UserStore;
}

// TODO: temporary for while consolidating discussion state?
export const DiscussionsStoreContext = createContext<Props>({
  beatmapsetStore: new BeatmapsetStore(),
  beatmapStore: new BeatmapStore(),
  discussionStore: new BeatmapsetDiscussionStore(),
  reviewsConfig: { max_blocks: 0 }, // dummy value to make it obvious something didn't work right.
  userStore: new UserStore(),
});
