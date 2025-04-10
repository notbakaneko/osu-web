// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import DispatcherAction from 'actions/dispatcher-action';
import SocketMessageSendAction from 'actions/socket-message-send-action';
import SocketStateChangedAction from 'actions/socket-state-changed-action';
import { dispatch, dispatchListener } from 'app-dispatcher';
import { route } from 'laroute';
import { forEach } from 'lodash';
import { action, computed, makeObservable, observable, reaction } from 'mobx';
import { NotificationEventLogoutJson, NotificationEventVerifiedJson } from 'notifications/notification-events';
import core from 'osu-core-singleton';
import SocketMessageEvent, { isSocketEventData, SocketEventData } from 'socket-message-event';
import RetryDelay from 'utils/retry-delay';

const isNotificationEventLogoutJson = (arg: SocketEventData): arg is NotificationEventLogoutJson => arg.event === 'logout';

const isNotificationEventVerifiedJson = (arg: SocketEventData): arg is NotificationEventVerifiedJson => arg.event === 'verified';

interface NotificationFeedMetaJson {
  url: string;
}

type ConnectionStatus = 'disconnected' | 'disconnecting' | 'connecting' | 'connected';

@dispatchListener
export default class SocketWorker {
  @observable connectionStatus: ConnectionStatus = 'disconnected';
  @observable hasConnectedOnce = false;
  userId: number | null = null;
  @observable private active = false;
  private endpoint?: string;
  private readonly retryDelay = new RetryDelay();
  private readonly timeout: Partial<Record<string, number>> = {};
  private ws: WebSocket | null | undefined;
  private xhr: JQuery.jqXHR<NotificationFeedMetaJson> | null = null;

  @computed
  get isConnected() {
    return this.connectionStatus === 'connected';
  }

  constructor() {
    makeObservable(this);

    reaction(
      () => this.isConnected,
      (value) => dispatch(new SocketStateChangedAction(value)),
      { fireImmediately: true },
    );
  }

  boot() {
    this.active = this.userId != null;

    if (this.active) {
      this.startWebSocket();
    }
  }

  handleDispatchAction(event: DispatcherAction) {
    // ignore everything if not connected.
    if (this.ws?.readyState !== WebSocket.OPEN) return;

    if (event instanceof SocketMessageSendAction) {
      this.ws?.send(JSON.stringify(event.message));
    }
  }

  setUserId(id: number | null) {
    if (id === this.userId) {
      return;
    }

    if (this.active) {
      this.destroy();
    }

    this.userId = id;
    this.boot();
  }

  @action
  private connected() {
    this.retryDelay.reset();
    this.connectionStatus = 'connected';
    this.hasConnectedOnce = true;
  }

  @action
  private connectWebSocket() {
    if (!this.active || this.endpoint == null || this.ws != null) {
      return;
    }

    this.connectionStatus = 'connecting';
    window.clearTimeout(this.timeout.connectWebSocket);

    const tokenEl = document.querySelector('meta[name=csrf-token]');

    if (tokenEl == null) {
      return;
    }

    const token = tokenEl.getAttribute('content');
    this.ws = new WebSocket(`${this.endpoint}?csrf=${token}`);
    this.ws.addEventListener('close', this.reconnectWebSocket);
    this.ws.addEventListener('message', this.handleNewEvent);
  }

  @action
  private destroy() {
    this.connectionStatus = 'disconnecting';

    this.userId = null;
    this.active = false;
    this.xhr?.abort();
    forEach(this.timeout, (timeout) => window.clearTimeout(timeout));

    if (this.ws != null) {
      this.ws.close();
      this.ws = null;
    }

    this.connectionStatus = 'disconnected';
  }

  private readonly handleNewEvent = (event: MessageEvent<string>) => {
    const eventData = this.parseMessageEvent(event);
    if (eventData == null) return;

    if (eventData.event === 'connection.ready') {
      this.connected();
    } else if (isNotificationEventLogoutJson(eventData)) {
      this.destroy();
      core.userLoginObserver.logout();
    } else if (isNotificationEventVerifiedJson(eventData)) {
      $.publish('user-verification:success');
    } else {
      dispatch(new SocketMessageEvent(eventData));
    }
  };

  private parseMessageEvent(event: MessageEvent<string>) {
    try {
      const json = JSON.parse(event.data) as unknown;
      if (isSocketEventData(json)) {
        return json;
      }

      console.error('message missing event type.');
    } catch {
      console.error('Failed parsing data:', event.data);
    }
  }

  @action
  private readonly reconnectWebSocket = () => {
    this.connectionStatus = 'disconnected';
    if (!this.active) {
      return;
    }

    this.timeout.connectWebSocket = window.setTimeout(action(() => {
      this.ws = null;
      this.connectWebSocket();
    }), this.retryDelay.get());
  };

  private readonly startWebSocket = () => {
    if (this.endpoint != null) {
      return this.connectWebSocket();
    }

    if (this.xhr != null) {
      return;
    }

    window.clearTimeout(this.timeout.startWebSocket);

    this.xhr = $.get(route('notifications.endpoint'));
    this.xhr
      .always(() => {
        this.xhr = null;
      })
      .done((data) => {
        this.retryDelay.reset();
        this.endpoint = data.url;
        this.connectWebSocket();
      })
      .fail((xhr) => {
        // Check if the user is logged out.
        // TODO: Add message to the popup.
        if (xhr.status === 401) {
          this.destroy();
          return;
        }
        this.timeout.startWebSocket = window.setTimeout(this.startWebSocket, this.retryDelay.get());
      });
  };
}
