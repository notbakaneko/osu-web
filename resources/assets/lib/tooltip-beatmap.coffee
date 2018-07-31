###
#    Copyright 2015-2017 ppy Pty. Ltd.
#
#    This file is part of osu!web. osu!web is distributed with the hope of
#    attracting more community contributions to the core ecosystem of osu!.
#
#    osu!web is free software: you can redistribute it and/or modify
#    it under the terms of the Affero GNU General Public License version 3
#    as published by the Free Software Foundation.
#
#    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
#    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
#    See the GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
###

import tippy from 'tippy.js'

export class TooltipBeatmap
  tmpl: _.template '<div class="tooltip-beatmap__text tooltip-beatmap__text--title"><%- beatmapTitle %></div>' +
      '<div class="tooltip-beatmap__text tooltip-beatmap__text--<%- difficulty %>"><%- stars %> <i class="fas fa-star" aria-hidden="true"></i></div>'

  constructor: ->
    $(document).on 'mouseover', '.js-beatmap-tooltip', @onMouseOver

  onMouseOver: (event) =>
    el = event.currentTarget

    return if !el.dataset.beatmapTitle?

    content = document.createElement('div')
    content.innerHTML = @tmpl el.dataset

    at = el.dataset.tooltipPosition ? 'top-center'

    options =
      html: content
      arrow: true
      placement: at

    tippy el, options
