# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { Main } from './beatmap-discussions/main'
import BeatmapStore from 'stores/beatmap-store'
import { BeatmapsetDiscussionStore } from 'stores/beatmapset-discussion-store'
import { BeatmapsetStore } from 'stores/beatmapset-store'
import UserStore from 'stores/user-store'

reactTurbolinks.registerPersistent 'beatmap-discussions', Main, true, (target) ->
  initial = osu.parseJson 'json-beatmapset-discussion'

  # FIXME: problem is the dispatcher means these don't get cleaned up.
  stores =
    beatmapsetStore: new BeatmapsetStore
    beatmapStore: new BeatmapStore
    discussionStore: new BeatmapsetDiscussionStore
    reviewsConfig: {}
    userStore: new UserStore

  console.log(initial)
  stores.beatmapsetStore.updateWithJson([initial.beatmapset])
  stores.beatmapStore.updateWithJson([initial.beatmapset.beatmaps])
  stores.discussionStore.updateWithJson(initial.beatmapset.discussions)
  stores.userStore.updateWithJson(initial.beatmapset.related_users)

  container: target
  initial: initial
  stores: stores
