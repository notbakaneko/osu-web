// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

// TODO: incomplete
export default interface BeatmapsetDiscussionPostJson {
  beatmap_discussion_id: number;
  created_at: string;
  deleted_at: string | null;
  id: number;
  last_editor_id: number | null;
  message: string;
  user_id: number | null;
}
