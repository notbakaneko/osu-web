// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import ChatIcon from 'chat-icon';
import { action } from 'mobx';
import { observer } from 'mobx-react';
import NotificationIcon from 'notification-icon';
import NotificationWidget from 'notification-widget/main';
import Worker from 'notifications/worker';
import core from 'osu-core-singleton';
import * as React from 'react';

interface Props {
  chat: {
    type?: string;
  };
  notifications: {
    type?: string;
  };
  worker: Worker;
}

interface State {
  isShowingWidget: boolean;
}

@observer
export default class NotificationChatIcon extends React.Component<Props, State> {
  readonly notificationsUIState = core.dataStore.uiState.notifications;

  render() {
    return (
      <>
        <button className='nav-button nav-button--stadium'>
          <ChatIcon onClick={this.handleChatIconClick} type={this.props.chat.type} worker={this.props.worker} />
          <NotificationIcon onClick={this.handleNotificationIconClick} type={this.props.notifications.type} worker={this.props.worker} />

        </button>
        {this.renderNotificationWidget()}
      </>
    );
  }

  renderNotificationWidget() {
    if (!this.notificationsUIState.isVisible) return null;

    return (
      <div className='nav-click-popup'>
        <NotificationWidget extraClasses='js-nav2--centered-popup' />
      </div>
    );
  }

  @action
  private handleChatIconClick = () => {
    this.notificationsUIState.isVisible = !this.notificationsUIState.isVisible;
    this.notificationsUIState.currentFilter = 'channel';
  }

  @action
  private handleNotificationIconClick = () => {
    this.notificationsUIState.isVisible = !this.notificationsUIState.isVisible;
  }
}
