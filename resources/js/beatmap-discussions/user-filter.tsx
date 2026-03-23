// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import mapperGroup from 'beatmap-discussions/mapper-group';
import SelectOptions, { RenderableOption } from 'components/select-options';
import BeatmapsetDiscussionsStore from 'interfaces/beatmapset-discussions-store';
import UserJson from 'interfaces/user-json';
import { action, computed, makeObservable } from 'mobx';
import { observer } from 'mobx-react';
import { usernameSortAscending } from 'models/user';
import * as React from 'react';
import { makeUrl, parseUrl } from 'utils/beatmapset-discussion-helper';
import { groupColour } from 'utils/css';
import { trans } from 'utils/lang';
import DiscussionsState from './discussions-state';

const allUsers = Object.freeze({
  id: null,
  username: trans('beatmap_discussions.user_filter.everyone'),
});

interface Option {
  groups?: UserJson['groups'];
  id: UserJson['id'] | null;
  username: UserJson['username'];
}

interface Props {
  discussionsState: DiscussionsState;
  store: BeatmapsetDiscussionsStore;
}

@observer
export class UserFilter extends React.Component<Props> {
  private get ownerId() {
    return this.props.discussionsState.beatmapset.user_id;
  }

  @computed
  private get options() {
    const usersWithDicussions = new Map<number, UserJson>();
    for (const [, discussion] of this.props.store.discussions) {
      if (discussion.message_type === 'hype') continue;

      const user = this.props.store.users.get(discussion.user_id);
      if (user != null && !usersWithDicussions.has(user.id)) {
        usersWithDicussions.set(user.id, user);
      }
    }

    return [
      this.mapUserProperties(allUsers),
      ...[...usersWithDicussions.values()]
        .sort(usernameSortAscending)
        .map(this.mapUserProperties),
    ];
  }

  @computed
  private get text() {
    return this.props.discussionsState.selectedUsers.length > 0
      ? this.props.discussionsState.selectedUsers.sort(usernameSortAscending).map((user) => user.username).join(', ')
      : trans('beatmap_discussions.user_filter.label');
  }

  constructor(props: Props) {
    super(props);
    makeObservable(this);
  }

  render() {
    return (
      <SelectOptions
        modifiers='beatmap-discussions-user-filter'
        onClick={this.handleClick}
        options={this.options}
        selected={this.props.discussionsState.selectedUserIds}
      >
        {this.text}
      </SelectOptions>
    );
  }

  private getGroup(user: Option) {
    if (this.isOwner(user)) return mapperGroup;

    return user.groups?.[0];
  }

  @action
  private readonly handleClick = (id: number | null) => {
    const selectedUserIds = this.props.discussionsState.selectedUserIds;

    if (id == null) {
      selectedUserIds.clear();
      return false;
    } else if (selectedUserIds.has(id)) {
      selectedUserIds.delete(id);
    } else {
      selectedUserIds.add(id);
    }

    return true;
  };

  private isOwner(user?: Option): boolean {
    return user != null && user.id === this.ownerId;
  }

  private readonly mapUserProperties = (user: Option): RenderableOption => {
    const group = this.getGroup(user);
    const style = groupColour(group);

    const urlOptions = parseUrl();
    // means it doesn't work on non-beatmapset discussion paths
    if (urlOptions != null) {

      // TODO: add existing users onto url.
      urlOptions.users = user.id != null ? [user.id] : undefined;
    }

    return {
      children: user.username,
      href: urlOptions != null ? makeUrl(urlOptions) : '#',
      id: user.id,
      style,
    };
  };
}
