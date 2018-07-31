###
#    Copyright 2015-2018 ppy Pty. Ltd.
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

export class TooltipDefault
  constructor: ->
    $(document).on 'mouseover', '[title]:not(iframe)', @onMouseOver
    $(document).on 'mouseenter touchstart', '.u-ellipsis-overflow, .u-ellipsis-overflow-desktop', @autoAddTooltip
    $(document).on 'turbolinks:before-cache', @rollback


  onMouseOver: (event) =>
    el = event.currentTarget

    title = el.title

    return if _.size(title) == 0

    isTime = el.classList.contains('timeago') || el.classList.contains('js-tooltip-time')

    content = (
      if isTime
        @timeagoTip el, title
      else
        $('<span>').text(title)
    ).get(0)

    if el._tippy
      el._tippy.popper.querySelector('.tippy-content').innerHTML = content
      return

    at = el.dataset.tooltipPosition ? 'top-center'

    options =
      html: content
      arrow: true
      placement: at

    el.dataset.origTitle = title

    tippy el, options


  autoAddTooltip: (e) =>
    # Automagically add tooltips when text becomes truncated (and auto-removes
    # them when text becomes... un-truncated)
    target = e.currentTarget

    if (target.offsetWidth < target.scrollWidth)
      if target._tippy
        target._tippy?.enable()
      else
        target.title = target.textContent
        tippy(target)
    else
      target._tippy?.disable()


  rollback: =>
    $('.qtip').remove()

    for el in document.querySelectorAll('[data-orig-title]')
      el.setAttribute 'title', el.dataset.origTitle


  timeagoTip: (el, title) =>
    timeString = el.getAttribute('datetime') ? title ? el.textContent

    time = moment(timeString)

    $dateEl = $('<strong>')
      .text time.format('LL')
    $timeEl = $('<span>')
      .addClass 'tooltip-default__time'
      .text "#{time.format('LT')} #{@tzString(time)}"

    $('<span>')
      .append $dateEl
      .append ' '
      .append $timeEl


  tzString: (time) ->
    offset = time.utcOffset()

    offsetString =
      if offset % 60 == 0
        "#{if offset >= 0 then '+' else ''}#{offset / 60}"
      else
        time.format('Z')

    "UTC#{offsetString}"


window.tooltipDefault ?= new TooltipDefault()
