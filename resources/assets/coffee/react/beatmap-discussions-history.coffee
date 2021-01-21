# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { Main } from './beatmap-discussions-history/main'
import BeatmapsetDiscussionRootStore from 'stores/beatmapset-discussion-root-store'


reactTurbolinks.registerPersistent 'beatmap-discussions-history', Main, true, (target) ->
  # FIXME: problem is the dispatcher means these don't get cleaned up.
  stores = new BeatmapsetDiscussionRootStore
  stores.reviewsConfig = osu.parseJson 'json-reviewsConfig'

  props =
    beatmaps: osu.parseJson('json-beatmaps')
    beatmapsets: osu.parseJson('json-beatmapsets')
    container: target
    discussions: osu.parseJson('json-discussions')
    events: osu.parseJson('json-events')
    posts: osu.parseJson('json-posts')
    reviewsConfig: osu.parseJson 'json-reviewsConfig'
    users: osu.parseJson('json-users')
    votes: osu.parseJson('json-votes')
    stores: stores

  stores.beatmapsetStore.updateWithJson(props.beatmapsets)
  stores.beatmapStore.updateWithJson(props.beatmaps)
  stores.discussionStore.updateWithJson(props.discussions)
  stores.userStore.updateWithJson(props.users)

  props
