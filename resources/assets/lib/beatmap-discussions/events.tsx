// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapsetEventJson from 'interfaces/beatmapset-event-json';
import * as React from 'react';
import BeatmapsetDiscussionRootStore from 'stores/beatmapset-discussion-root-store';
import { DiscussionsStoreContext } from './discussions-store-context';
import Event from './event';

interface Props {
  events: BeatmapsetEventJson[];
  stores: BeatmapsetDiscussionRootStore;
}

export default class Events extends React.PureComponent<Props> {
  render() {
    return (
      // FIXME: handle cases where context already exists.
      <DiscussionsStoreContext.Provider value={this.props.stores}>
        {this.props.events.map((event) => <Event event={event} key={event.id} mode='profile' />)}
      </DiscussionsStoreContext.Provider>
    );
  }
}
