// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import DispatcherAction from 'actions/dispatcher-action';
import { UserLoginAction, UserLogoutAction } from 'actions/user-login-actions';
import { BeatmapsetWithDiscussionsJson } from 'beatmapsets/beatmapset-json';
import { isEmpty } from 'lodash';
import { action, computed, observable } from 'mobx';

export class BeatmapsetDiscussionStore {
  // store json for now to make it easier to work with existing coffeescript.
  @observable discussions = observable.map<number, BeatmapsetDiscussionJson>();

  get(id: number) {
    return this.discussions.get(id);
  }

  @computed
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

    // just override the value for now, we can do something fancier in the future.
    this.discussions.set(discussion.id, discussion);
  }

  @action
  updateWithBeatmapset(beatmapset: BeatmapsetWithDiscussionsJson) {
    for (const discussion of beatmapset.discussions) {
      // when updating from beatmapset for history listing, skip the ones
      // that don't exist yet because the list shouldn't change.
      if (this.discussions.get(discussion.id) == null) continue;

      this.update(discussion);
      // TODO: starting_post?
    }

    for (const user of beatmapset.related_users) {
      // update users
    }
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
