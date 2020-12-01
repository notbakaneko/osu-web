// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import DispatcherAction from 'actions/dispatcher-action';
import { dispatch } from 'app-dispatcher';
import { ChannelJson, MessageJson } from 'chat/chat-api-responses';
import Channel from 'models/chat/channel';
import Message from 'models/chat/message';

// tslint:disable: max-classes-per-file

export interface ChatEventJson {
  data: ChannelJson | MessageJson;
  event: string;
}

interface MaybeEvent {
  event?: string;
}

export class ChatChannelJoinEvent extends DispatcherAction {
  constructor(readonly channel: Channel) {
    super();
  }
}

export class ChatChannelNewMessagesEvent extends DispatcherAction {
  constructor(readonly channelId: number, readonly json: MessageJson[]) {
    super();
  }
}

export class ChatChannelPartEvent extends DispatcherAction {
  constructor(readonly channelId: number) {
    super();
  }
}

export class ChatMessageNewEvent extends DispatcherAction {
  constructor(readonly message: Message) {
    super();
  }
}

export function isChatEvent(arg: MaybeEvent): arg is ChatEventJson {
  return arg.event?.startsWith('chat.') ?? false;
}

export function fromJson(json: ChatEventJson) {
  // TODO: how to typed dynamic factory?
  switch (json.event) {
    case 'chat.channel.join':
      const channel = new Channel(json.data.channel_id);
      channel.updateWithJson(json.data);
      return new ChatChannelJoinEvent(channel);
    case 'chat.channel.part':
      return new ChatChannelPartEvent(json.data.channel_id);
    case 'chat.message.new':
      return new ChatMessageNewEvent(Message.fromJson(json.data));
  }

  // tslint:disable-next-line: no-console
  console.error(`failed to parse event ${json.event}.`);
}

export function dispatchNewEventFromJson(json: ChatEventJson) {
  const event = fromJson(json);
  console.log(event);
  if (event != null) {
    dispatch(event);
  }
}
