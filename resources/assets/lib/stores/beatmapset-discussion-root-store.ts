// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { BeatmapsetWithDiscussionsJson } from 'beatmapsets/beatmapset-json';
import ReviewEditorConfigJson from 'interfaces/review-editor-config-json';
import { action } from 'mobx';
import BeatmapStore from 'stores/beatmap-store';
import { BeatmapsetDiscussionStore } from 'stores/beatmapset-discussion-store';
import { BeatmapsetStore } from 'stores/beatmapset-store';
import UserStore from 'stores/user-store';

export default class BeatmapsetDiscussionRootStore {
  beatmapsetStore = new BeatmapsetStore();
  beatmapStore = new BeatmapStore();
  discussionStore = new BeatmapsetDiscussionStore();
  reviewsConfig: ReviewEditorConfigJson = { max_blocks: 0 }; // dummy value to make it obvious something didn't work right.
  userStore = new UserStore();

  @action
  updateWithBeatmapset(beatmapset: BeatmapsetWithDiscussionsJson) {
    for (const discussion of beatmapset.discussions) {
      // when updating from beatmapset for history listing, skip the ones
      // that don't exist yet because the list shouldn't change.
      if (this.discussionStore.get(discussion.id) == null) continue;
      // FIXME: need to check discussion.beatmapset?
      if (discussion.beatmapset == null) {
        console.warn(`discussion ${discussion.id} has no beatmapset`);
      } else {
        console.warn(`discussion ${discussion.id} has beatmapset`);
      }

      this.discussionStore.update(discussion);
    }

    this.userStore.updateWithJson(beatmapset.related_users);
  }
}
