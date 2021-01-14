// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import DispatcherAction from 'actions/dispatcher-action';
import { UserLoginAction, UserLogoutAction } from 'actions/user-login-actions';
import BeatmapJson from 'interfaces/beatmap-json';
import { action, observable } from 'mobx';

export default class BeatmapStore {
  // store json for now to make it easier to work with existing coffeescript.
  @observable beatmaps = observable.map<number, BeatmapJson>();

  get(id: number) {
    return this.beatmaps.get(id);
  }

  handleDispatchAction(dispatcherAction: DispatcherAction) {
    if (dispatcherAction instanceof UserLoginAction
      || dispatcherAction instanceof UserLogoutAction) {
      this.flushStore();
    }
  }

  @action
  update(beatmap: BeatmapJson) {
    // just override the value for now, we can do something fancier in the future.
    this.beatmaps.set(beatmap.id, beatmap);
  }

  @action
  updateWithJson(json: BeatmapJson[]) {
    // FIXME
    const update = this.update.bind(this);
    json.forEach(update);
  }

  @action
  private flushStore() {
    this.beatmaps.clear();
  }
}
