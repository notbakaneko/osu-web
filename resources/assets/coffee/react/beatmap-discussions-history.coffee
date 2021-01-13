# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { Main } from './beatmap-discussions-history/main'

reactTurbolinks.registerPersistent 'beatmap-discussions-history', Main, true, (target) ->
  beatmaps: osu.parseJson('json-beatmaps')
  beatmapsets: osu.parseJson('json-beatmapsets')
  container: target
  discussions: osu.parseJson('json-discussions')
  events: osu.parseJson('json-events')
  posts: osu.parseJson('json-posts')
  reviewsConfig: osu.parseJson 'json-reviewsConfig'
  users: osu.parseJson('json-users')
  votes: osu.parseJson('json-votes')
