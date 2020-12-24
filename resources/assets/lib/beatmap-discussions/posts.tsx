// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import UserJson from 'interfaces/user-json';
import * as React from 'react';
import { BeatmapsetJson, Post } from 'react/beatmap-discussions/post';

interface Props {
  beatmapsetDiscussions: Record<number, BeatmapsetDiscussionJson>;
  beatmapsets: Record<number, BeatmapsetJson>;
  posts: BeatmapsetDiscussionPostJson[];
  users: Record<number | string, UserJson>;
}

export default class Posts extends React.Component<Props> {
  render() {
    const canModeratePosts = BeatmapDiscussionHelper.canModeratePosts(currentUser);

    return this.props.posts.map((post) => {
      const canBeDeleted = canModeratePosts || currentUser.id != null && currentUser.id === post.user_id;
      const canBeEdited = currentUser.is_admin || currentUser.id != null && currentUser.id === post.user_id;

      const discussionModifiers = ['preview'];

      if (post.deleted_at != null) {
        discussionModifiers.push('deleted');
      }

      const discussion = this.props.beatmapsetDiscussions[post.beatmap_discussion_id];
      const beatmapset = this.props.beatmapsets[discussion.beatmapset_id];
      const user = post.user_id != null ? this.props.users[post.user_id] : this.props.users.null;
      const lastEditor = post.last_editor_id != null ? this.props.users[post.last_editor_id] : this.props.users.null;

      return (
        <div className='modding-profile-list__row' key={post.id}>
          <a
            className='modding-profile-list__thumbnail'
            href={BeatmapDiscussionHelper.url({ discussion })}
          >
            {/* post.beatmap_discussion.beatmapset.covers.list */}
            <img className='beatmapset-cover' src={beatmapset.covers.list} />
          </a>
          <div className='modding-profile-list__timestamp hidden-xs'>
            <div className='beatmap-discussion-timestamp'>
              <div className='beatmap-discussion-timestamp__icons-container'>
                <span className='fas fa-reply' />
              </div>
            </div>
          </div>
          <div className={osu.classWithModifiers('beatmap-discussion', discussionModifiers)}>
            <div className='beatmap-discussion__discussion'>
              <Post
                key={post.id}
                beatmapset={beatmapset}
                discussion={discussion}
                post={post}
                type='reply'
                users={this.props.users}
                user={user}
                read={true}
                lastEditor={lastEditor}
                canBeEdited={canBeEdited}
                canBeDeleted={canBeDeleted}
                canBeRestored={canModeratePosts}
                currentUser={currentUser}
              />
            </div>
          </div>
        </div>
      );
    });
  }
}
