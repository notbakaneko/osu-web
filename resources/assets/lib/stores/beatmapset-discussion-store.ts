// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import DispatcherAction from 'actions/dispatcher-action';
import { UserLoginAction, UserLogoutAction } from 'actions/user-login-actions';
import { isEmpty } from 'lodash';
import { action, observable } from 'mobx';

export class BeatmapsetDiscussionStore {
  // store json for now to make it easier to work with existing coffeescript.
  @observable discussions = observable.map<number, BeatmapsetDiscussionJson>();

  get(id: number) {
    return this.discussions.get(id);
  }

  get orderedDiscussions() {
    return [...this.discussions.values()].sort((a, b) => {
      return Date.parse(a.starting_post.created_at) > Date.parse(b.starting_post.created_at) ? 1 : -1;
    });
  }

  getUserDiscussions(userId: number) {
    return [...this.discussions.values()].filter((d) => d.user_id === userId);
  }

  handleDispatchAction(dispatcherAction: DispatcherAction) {
    if (dispatcherAction instanceof UserLoginAction
      || dispatcherAction instanceof UserLogoutAction) {
      this.flushStore();
    }
  }

  @action
  update(discussion: BeatmapsetDiscussionJson) {
    // skip if empty
    if (isEmpty(discussion)) return;

    // fill in starting_post if missing
    if (discussion.starting_post == null) {
      // The discussion list shows discussions started by the current user, so it can be assumed that the first post is theirs
      console.warn(`discussion ${discussion.id} missing starting_post`);
      discussion.starting_post = discussion.posts[0];
    }

    // just override the value for now, we can do something fancier in the future.
    this.discussions.set(discussion.id, discussion);
  }

  @action
  updateWithJson(json: BeatmapsetDiscussionJson[]) {
    // FIXME
    const update = this.update.bind(this);
    json.forEach(update);
  }

  @action
  private flushStore() {
    this.discussions.clear();
  }
}
