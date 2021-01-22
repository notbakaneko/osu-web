# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import DiscussionEvents from 'beatmap-discussions/events'
import * as React from 'react'
import { div, h2, a, img } from 'react-dom-factories'
el = React.createElement

export class Events extends React.Component
  render: =>
    if @props.events.length == 0
      div className: 'modding-profile-list__empty', osu.trans('users.show.extra.none')
    else
      div className: 'beatmapset-events beatmapset-events--profile',
        el DiscussionEvents,
          events: @props.events
          stores: @props.stores

        a
          className: 'modding-profile-list__show-more'
          href: laroute.route('beatmapsets.events.index', user: @props.user.id),
          osu.trans('users.show.extra.events.show_more')
