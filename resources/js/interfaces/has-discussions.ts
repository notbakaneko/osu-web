// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.


import DiscussionsState from 'beatmap-discussions/discussions-state';
import BeatmapExtendedJson from 'interfaces/beatmap-extended-json';
import { BeatmapsetDiscussionJsonForShow } from 'interfaces/beatmapset-discussion-json';
import BeatmapsetDiscussionsStore from 'interfaces/beatmapset-discussions-store';
import WithBeatmapOwners from 'interfaces/with-beatmap-owners';

export interface HasDiscussionsReadOnly {
  discussionsState: null;
  store: BeatmapsetDiscussionsStore;
}

export interface HasDiscussionsEditable {
  discussionsState: DiscussionsState;
  store: BeatmapsetDiscussionsStore<WithBeatmapOwners<BeatmapExtendedJson>, BeatmapsetDiscussionJsonForShow>;
}
