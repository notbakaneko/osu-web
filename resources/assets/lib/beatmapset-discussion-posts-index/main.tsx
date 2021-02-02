// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import Posts from 'beatmap-discussions/posts';
import BeatmapsetDiscussionPostJson from 'interfaces/beatmapset-discussion-post-json';
import * as React from 'react';
import BeatmapsetDiscussionRootStore from 'stores/beatmapset-discussion-root-store';

interface Props {
  posts: BeatmapsetDiscussionPostJson[];
  stores: BeatmapsetDiscussionRootStore;
}

export default class Main extends React.Component<Props> {
  render() {
    return (
      <Posts
        beatmapsetDiscussions={this.props.stores.discussionStore.discussions}
        beatmapsets={this.props.stores.beatmapsetStore.beatmapsets}
        posts={this.props.posts}
        users={this.props.stores.userStore.users}
      />
    );
  }
}
