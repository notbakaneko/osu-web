// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import Posts from 'beatmap-discussions/posts';
import UserJson from 'interfaces/user-json';
import { keyBy } from 'lodash';
import * as React from 'react';
import { BeatmapsetJson } from 'react/beatmap-discussions/post';

interface Props {
  beatmapsetDiscussions: BeatmapsetDiscussionJson[];
  beatmapsets: BeatmapsetJson[];
  posts: BeatmapsetDiscussionPostJson[];
  users: UserJson[];
}

export default class Main extends React.Component<Props> {
  get beatmapsetDiscussions() {
    return keyBy(this.props.beatmapsetDiscussions, 'id');
  }

  get beatmapsets() {
    return keyBy(this.props.beatmapsets, 'id');
  }

  get users() {
    // FIXME: dodgy
    const users = keyBy(this.props.users, 'id');
    users.null = users.undefined = {
      username: osu.trans('users.deleted'),
    } as any;

    return users;
  }

  render() {
    return <Posts beatmapsetDiscussions={this.beatmapsetDiscussions} beatmapsets={this.beatmapsets} posts={this.props.posts} users={this.users} />;
  }
}
