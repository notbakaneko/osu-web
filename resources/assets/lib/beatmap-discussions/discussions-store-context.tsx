// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { createContext } from 'react';
import BeatmapsetDiscussionRootStore from 'stores/beatmapset-discussion-root-store';

// TODO: temporary for while consolidating discussion state?
export const DiscussionsStoreContext = createContext(new BeatmapsetDiscussionRootStore());
