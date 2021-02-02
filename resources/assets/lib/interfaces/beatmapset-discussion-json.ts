// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { BeatmapsetJson } from 'beatmapsets/beatmapset-json';
import BeatmapsetDiscussionPostJson from 'interfaces/beatmapset-discussion-post-json';

// TODO: incomplete
export default interface BeatmapsetDiscussionJson {
  beatmap_id: number | null;
  // discussion on beatmapset page may include beatmapset.
  beatmapset?: BeatmapsetJson;
  beatmapset_id: number;
  deleted_at: string | null;
  id: number;
  message_type: string;
  parent_id: number | null;
  posts: BeatmapsetDiscussionPostJson[];
  resolved: boolean;
  starting_post: BeatmapsetDiscussionPostJson;
  timestamp: number | null;
  user_id: number;
}
