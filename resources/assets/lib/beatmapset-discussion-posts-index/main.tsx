// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import Posts from 'beatmap-discussions/posts';
import * as React from 'react';

interface Props {
  posts: BeatmapsetDiscussionPostJson[];
}

export default class Main extends React.Component<Props> {
  render() {
    return <Posts posts={this.props.posts} />;
  }
}
