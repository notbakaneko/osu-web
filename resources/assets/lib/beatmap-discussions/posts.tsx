// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import UserJson from 'interfaces/user-json';
import { deletedUserJson } from 'models/user';
import * as React from 'react';
import { BeatmapsetJson, Post } from 'react/beatmap-discussions/post';

interface Props {
  beatmapsetDiscussions: Map<number, BeatmapsetDiscussionJson>;
  beatmapsets: Map<number, BeatmapsetJson>;
  posts: BeatmapsetDiscussionPostJson[];
  users: Map<number | string, UserJson>;
}

// TODO: handle empty case.
export default class Posts extends React.Component<Props> {
  render() {
    const canModeratePosts = BeatmapDiscussionHelper.canModeratePosts(currentUser);

    const posts = this.props.posts.map((post) => {
      const canBeDeleted = canModeratePosts || currentUser.id != null && currentUser.id === post.user_id;
      const canBeEdited = currentUser.is_admin || currentUser.id != null && currentUser.id === post.user_id;

      const discussionModifiers = ['preview'];

      if (post.deleted_at != null) {
        discussionModifiers.push('deleted');
      }

      const discussion = this.props.beatmapsetDiscussions.get(post.beatmap_discussion_id);
      if (discussion == null) return null; // TODO: handle deleted

      const beatmapset = this.props.beatmapsets.get(discussion?.beatmapset_id ?? 0);
      if (beatmapset == null) return null; // TODO: handle deleted

      const user = this.props.users.get(post.user_id ?? 0) ?? deletedUserJson;
      const lastEditor = post.last_editor_id != null ? this.props.users.get(post.last_editor_id) : deletedUserJson;

      return (
        <div className='beatmapset-discussion-posts__container' key={post.id}>
          {/* should give Post an optional render or something */}
          <a
            className='beatmapset-discussion-posts__container-thumbnail'
            href={BeatmapDiscussionHelper.url({ discussion })}
          >
            {/* post.beatmap_discussion.beatmapset.covers.list */}
            <img className='beatmapset-cover' src={beatmapset.covers.list} />
          </a>
          <div className='beatmap-discussion-timestamp hidden-xs'>
            <div className='beatmap-discussion-timestamp__icons-container'>
              <span className='fas fa-reply' />
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

    return (
      <div className='beatmapset-discussion-posts'>
        {posts}
      </div>
    );
  }
}
