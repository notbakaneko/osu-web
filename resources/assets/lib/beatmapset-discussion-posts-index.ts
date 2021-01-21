// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import Main from 'beatmapset-discussion-posts-index/main';
import BeatmapsetDiscussionRootStore from 'stores/beatmapset-discussion-root-store';

reactTurbolinks.registerPersistent('beatmapset-discussion-posts-index', Main, true, () => {
  const {
    beatmapset_discussions: beatmapsetDiscussions,
    beatmapsets,
    posts,
    users,
  } = osu.parseJson('json-beatmapset-discussion-posts-index');

  const stores = new BeatmapsetDiscussionRootStore();
  stores.reviewsConfig = { max_blocks: 10 }; // FIXME
  stores.beatmapsetStore.updateWithJson(beatmapsets);
  stores.discussionStore.updateWithJson(beatmapsetDiscussions);
  stores.userStore.updateWithJson(users);

  return {
    posts,
    stores,
  };
});
