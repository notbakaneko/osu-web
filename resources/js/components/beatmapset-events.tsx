// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapsetEvent, { EventViewMode } from 'components/beatmapset-event';
import BeatmapsetEventJson from 'interfaces/beatmapset-event-json';
import * as React from 'react';

export interface Props {
  events: BeatmapsetEventJson[];
  mode: EventViewMode;
}

export default class BeatmapsetEvents extends React.PureComponent<Props> {
  render() {
    return this.props.events.map((event) => <BeatmapsetEvent key={event.id} event={event} mode={this.props.mode} />);
  }
}
