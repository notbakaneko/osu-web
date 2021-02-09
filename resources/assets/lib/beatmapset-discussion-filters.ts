// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapsetDiscussionJson from 'interfaces/beatmapset-discussion-json';

export const typeNames = [null, 'user', 'beatmapset', 'forum_topic', 'news_post', 'build', 'channel'] as const;
export type Name = (typeof typeNames)[number];

const filterNames = ['deleted', 'hype', 'mapperNotes', 'mine', 'pending', 'praises', 'resolved', 'total'] as const;
export type Filter = (typeof filterNames)[number];
const modeNames = ['general', 'generalAll', 'reviews', 'timeline'] as const;
export type Mode = (typeof modeNames)[number];

export function groupByFilter(discussions: BeatmapsetDiscussionJson[], beatmapId?: number) {
  const deleted: BeatmapsetDiscussionJson[] = [];
  const hype: BeatmapsetDiscussionJson[] = [];
  const mapperNotes: BeatmapsetDiscussionJson[] = [];
  const mine: BeatmapsetDiscussionJson[] = [];
  const pending: BeatmapsetDiscussionJson[] = [];
  const praises: BeatmapsetDiscussionJson[] = [];
  const resolved: BeatmapsetDiscussionJson[] = [];
  const total: BeatmapsetDiscussionJson[] = [];

  for (const discussion of discussions) {
    // if (beatmapId != null && discussion.beatmap_id !== beatmapId) continue;

    total.push(discussion);

    if (discussion.user_id === currentUser.id && currentUser.id != null) {
      mine.push(discussion);
    }

    if (discussion.message_type === 'mapper_note') {
      mapperNotes.push(discussion);
    }

    if (discussion.deleted_at != null) {
      deleted.push(discussion);
    } else if (discussion.message_type === 'hype') {
      hype.push(discussion);
      praises.push(discussion);
    } else if (discussion.message_type === 'praise') {
      praises.push(discussion);
    } else if (discussion.can_be_resolved) {
      if (discussion.resolved) {
        resolved.push(discussion);
      } else {
        pending.push(discussion);
      }
    }
  }

  return { deleted, hype, mapperNotes, mine, pending, praises, resolved, total };
}

export function groupByMode(discussions: BeatmapsetDiscussionJson[], beatmapId?: number) {
  const general: BeatmapsetDiscussionJson[] = [];
  const generalAll: BeatmapsetDiscussionJson[] = [];
  const reviews: BeatmapsetDiscussionJson[] = [];
  const timeline: BeatmapsetDiscussionJson[] = [];

  for (const discussion of discussions) {
    if (discussion.message_type === 'reviews') {
      reviews.push(discussion);
    } else {
      if (discussion.beatmap_id != null) {
        if (beatmapId != null && discussion.beatmap_id === beatmapId) {
          if (discussion.timestamp != null) {
            timeline.push(discussion);
          } else {
            general.push(discussion);
          }
        }
      } else {
        generalAll.push(discussion);
      }
    }
  }

  return { general, generalAll, reviews, timeline };
}

export function totalHype(discussions: BeatmapsetDiscussionJson[]) {
  return discussions.reduce((acc, discussion) => {
    const hype = discussion.deleted_at != null && discussion.message_type === 'type' ? 1 : 0;
    return acc + hype;
  }, 0);
}
