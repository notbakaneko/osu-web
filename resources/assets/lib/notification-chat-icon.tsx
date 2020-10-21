// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import ChatIcon from 'chat-icon';
import NotificationIcon from 'notification-icon';
import NotificationWidget from 'notification-widget/main';
import Worker from 'notifications/worker';
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

export default class NotificationChatIcon extends React.Component<Props, State> {
  readonly state = {
    isShowingWidget: false,
  };

  render() {
    return (
      <>
        <button className='nav-button nav-button--stadium'>
          <ChatIcon onClick={this.handleClick} type={this.props.chat.type} worker={this.props.worker} />
          <NotificationIcon onClick={this.handleClick} type={this.props.notifications.type} worker={this.props.worker} />

        </button>
        {this.renderNotificationWidget()}
      </>
    );
  }

  renderNotificationWidget() {
    if (!this.state.isShowingWidget) return null;

    return (
      <div className='nav-click-popup'>
        <NotificationWidget extraClasses='js-nav2--centered-popup' />
      </div>
    );
  }

  private handleClick = () => {
    this.setState({ isShowingWidget: !this.state.isShowingWidget });
  }
}
