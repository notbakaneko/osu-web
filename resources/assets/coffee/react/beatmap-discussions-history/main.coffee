# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { Discussion } from '../beatmap-discussions/discussion'
import { DiscussionsStoreContext } from 'beatmap-discussions/discussions-store-context'
import { Observer } from 'mobx-react'
import * as React from 'react'
import { a, div, img } from 'react-dom-factories'
el = React.createElement

export class Main extends React.Component
  constructor: (props) ->
    super props

    # @state = JSON.parse(props.container.dataset.discussionsState ? null)
    # @restoredState = @state?

    # # FIXME do something about this for turbolinks
    # if !@restoredState
    #   @state =
    #     beatmaps: props.beatmaps
    #     discussions: props.discussions
    #     users: props.users


  componentDidMount: =>
    $.subscribe 'beatmapsetDiscussions:update.discussionHistory', @discussionUpdate
    $(document).on 'ajax:success.discussionHistory', '.js-beatmapset-discussion-update', @ujsDiscussionUpdate


  componentWillUnmount: =>
    $.unsubscribe '.discussionHistory'
    $(window).off '.discussionHistory'

    $(window).stop()


  discussionUpdate: (_e, options) =>
    {beatmapset} = options
    return unless beatmapset?

    @props.stores.updateWithBeatmapset(beatmapset)


  saveStateToContainer: =>
    # @props.container.dataset.discussionsState = JSON.stringify(@state)


  render: =>
    el Observer, null, () =>
      discussions = @props.stores.discussionStore.orderedDiscussions

      el DiscussionsStoreContext.Provider, value: @props.stores,
        div className: 'modding-profile-list modding-profile-list--index',
          if discussions.length == 0
            div className: 'modding-profile-list__empty', osu.trans('beatmap_discussions.index.none_found')
          else
            for discussion in discussions
              beatmapset = @props.stores.beatmapsetStore.get(discussion.beatmapset_id)

              div
                className: 'modding-profile-list__row'
                key: discussion.id,

                a
                  className: 'modding-profile-list__thumbnail'
                  href: BeatmapDiscussionHelper.url(discussion: discussion),

                  img className: 'beatmapset-cover', src: beatmapset.covers.list

                el Discussion,
                  discussion: discussion
                  currentUser: currentUser
                  beatmapset: beatmapset
                  isTimelineVisible: false
                  visible: false
                  showDeleted: true
                  preview: true


  ujsDiscussionUpdate: (_e, data) =>
    # to allow ajax:complete to be run
    Timeout.set 0, => @discussionUpdate(null, beatmapset: data)
