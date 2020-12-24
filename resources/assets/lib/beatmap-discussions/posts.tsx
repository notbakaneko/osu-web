// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import * as React from 'react';

interface Props {
  posts: BeatmapsetDiscussionPostJson[];
}

export default class Posts extends React.Component<Props> {
  render() {
    return this.props.posts.map((post) => {
      return <div key={post.id}>{post.message}</div>;
    });
  }
}
