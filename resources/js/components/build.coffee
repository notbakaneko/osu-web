# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import * as React from 'react'
import { a, div, i, iframe, span } from 'react-dom-factories'
import { classWithModifiers } from 'utils/css'
import { changelogBuild } from 'utils/url'
import { ChangelogEntry } from './changelog-entry'

el = React.createElement

export class Build extends React.PureComponent
  render: =>
    blockClass = classWithModifiers 'build', @props.modifiers
    entries = _.groupBy(@props.build.changelog_entries, 'category')
    categories = _(entries).keys().sort().value()

    div className: "#{blockClass} t-changelog-stream--#{@props.build.update_stream.name}",
      div className: 'build__version',
        @renderNav version: 'previous', icon: 'fas fa-chevron-left'

        a
          className: 'build__version-link'
          href: changelogBuild @props.build
          span className: 'build__stream', @props.build.update_stream.display_name
          ' '
          span className: 'u-changelog-stream--text', @props.build.display_version

        @renderNav version: 'next', icon: 'fas fa-chevron-right'

      if @props.showDate ? false
        div className: 'build__date', moment(@props.build.created_at).format('LL')

      if @props.build.youtube_id? && (@props.showVideo ? false)
        div className: 'build__video',
          iframe
            className: 'u-embed-wide'
            allowFullScreen: true
            src: "https://www.youtube.com/embed/#{@props.build.youtube_id}?rel=0"
      for category in categories
        div
          key: category
          className: 'build__changelog-entries-container'
          div className: 'build__changelog-entries-category', category
          div className: 'build__changelog-entries',
            for entry in entries[category]
              div
                key: entry.id ? "#{entry.created_at}-#{entry.title}"
                className: 'build__changelog-entry'
                el ChangelogEntry, entry: entry


  renderNav: ({version, icon}) =>
    return unless @props.build.versions?

    build = @props.build.versions[version]

    if build?
      a
        className: 'build__version-link'
        href: changelogBuild build
        title: build.display_version
        i className: icon
    else
      span
        className: 'build__version-link build__version-link--disabled'
        i className: icon
