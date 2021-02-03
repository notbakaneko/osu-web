# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { Main } from './beatmap-discussions-history/main'
import BeatmapsetDiscussionRootStore from 'stores/beatmapset-discussion-root-store'


reactTurbolinks.registerPersistent 'beatmap-discussions-history', Main, true, (target) ->
  # FIXME: problem is the dispatcher means these don't get cleaned up.
  stores = new BeatmapsetDiscussionRootStore
  stores.reviewsConfig = osu.parseJson 'json-reviewsConfig'
  stores.beatmapsetStore.updateWithJson(osu.parseJson('json-beatmapsets'))
  stores.beatmapStore.updateWithJson(osu.parseJson('json-beatmaps'))
  stores.discussionStore.updateWithJson(osu.parseJson('json-discussions'))
  stores.userStore.updateWithJson(osu.parseJson('json-users'))

  stores: stores
