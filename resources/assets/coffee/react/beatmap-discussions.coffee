# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { Main } from './beatmap-discussions/main'
import BeatmapsetDiscussionRootStore from 'stores/beatmapset-discussion-root-store'

reactTurbolinks.registerPersistent 'beatmap-discussions', Main, true, (target) ->
  initial = osu.parseJson 'json-beatmapset-discussion'

  # FIXME: problem is the dispatcher means these don't get cleaned up.
  stores = new BeatmapsetDiscussionRootStore
  stores.reviewsConfig = { max_blocks: 10 } # FIXME

  console.log(initial)
  stores.beatmapsetStore.updateWithJson([initial.beatmapset])
  stores.beatmapStore.updateWithJson([initial.beatmapset.beatmaps])
  stores.discussionStore.updateWithJson(initial.beatmapset.discussions)
  stores.userStore.updateWithJson(initial.beatmapset.related_users)

  container: target
  initial: initial
  stores: stores
