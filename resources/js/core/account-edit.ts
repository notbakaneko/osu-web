// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import OsuCore from 'osu-core';
import AccountEditState from './account-edit-state';

type ContainerEvent = JQuery.TriggeredEvent<unknown, unknown, AccountEditHTMLElement, unknown>;

const autoSubmitClassSelector = '.js-account-edit-auto-submit';
const classSelector = '.js-account-edit';

interface AccountEditHTMLElement extends HTMLElement {
  state?: AccountEditState;
}

export default class AccountEdit {
  constructor(private readonly core: OsuCore) {
    $(document).on('input change', autoSubmitClassSelector, this.handleInputChange);
    $(document).on('ajax:error', classSelector, this.handleAjaxError);
    $(document).on('ajax:send', classSelector, this.handleAjaxSend);
    $(document).on('ajax:success', classSelector, this.handleAjaxSuccess);
  }

  private getState(e: ContainerEvent) {
    return e.currentTarget.state ??= new AccountEditState(e.currentTarget, this.core);
  }

  private readonly handleAjaxError = (e: ContainerEvent) => {
    this.getState(e).clear();
  };

  private readonly handleAjaxSend = (e: ContainerEvent) => {
    this.getState(e).saving();
  };

  private readonly handleAjaxSuccess = (e: ContainerEvent) => {
    this.getState(e).saved();
  };

  private readonly handleInputChange = (e: ContainerEvent) => {
    this.getState(e).onInput();
  };
}
