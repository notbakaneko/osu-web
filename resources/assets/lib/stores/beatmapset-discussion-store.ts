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
