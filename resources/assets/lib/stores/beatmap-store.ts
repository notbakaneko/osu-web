// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import DispatcherAction from 'actions/dispatcher-action';
import { UserLoginAction, UserLogoutAction } from 'actions/user-login-actions';
import BeatmapJsonExtended from 'interfaces/beatmap-json-extended';
import { action, observable } from 'mobx';

export default class BeatmapStore {
  // store json for now to make it easier to work with existing coffeescript.
  @observable beatmaps = observable.map<number, BeatmapJsonExtended>();

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
  update(beatmap: BeatmapJsonExtended) {
    // just override the value for now, we can do something fancier in the future.
    this.beatmaps.set(beatmap.id, beatmap);
  }

  @action
  updateWithJson(json: BeatmapJsonExtended[]) {
    // FIXME
    const update = this.update.bind(this);
    json.forEach(update);
  }

  @action
  private flushStore() {
    this.beatmaps.clear();
  }
}
