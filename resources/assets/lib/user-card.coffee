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

export class UserCard
  triggerDelay: 200
  fadeLength: 220

  constructor: ->
    $(document).on 'mouseover', '.js-usercard', @onMouseOver

  onMouseOver: (event) =>
    el = event.currentTarget
    userId = el.dataset.userId
    return unless userId
    return if _.find(currentUser.blocks, target_id: parseInt(userId)) # don't show cards for blocked users

    if el._tippy?
      api = el._tippy

      if el._tooltip == userId
        # disable existing cards when entering 'mobile' mode
        if osu.isMobile()
          event.preventDefault()
          api.disable()
          el._disable_card = true
        else
          if el._disable_card
            el._disable_card = false
            api.enable()

        return
      else
        # wrong userId, destroy current tooltip
        api.destroy()

    # disable usercards on mobile
    if osu.isMobile()
      return

    el._tooltip = userId

    at = el.getAttribute('data-tooltip-position') ? 'right-center'

    template = $('#js-usercard__loading-template').children().clone()
    tippy el,
      animation: 'fade'
      animateFill: false
      delay: @triggerDelay
      duration: 110
      html: template.get(0)
      placement: at
      onShow: () ->
        return if el._loaded
        $.ajax
          url: laroute.route 'users.card', user: userId
        .done (data) ->
          if data?
            content$ = $(el._tippy.popper.querySelector('.tippy-content'))
            content$.html(data)
            content$.find('.usercard')
              .imagesLoaded()
              .progress (_instance, image) ->
                $(image.img).fadeTo(@fadeLength, 1) if image.isLoaded
              .always (instance) ->
                $(instance.elements[0]).find('.js-usercard--avatar-loader').fadeTo(@fadeLength, 0)

            window.reactTurbolinks.boot()
            el._loaded = true
          else
            el._tippy.hide()
        .fail (xhr, status, error) ->
          content$ = $(el._tippy.popper.querySelector('.tippy-content'))
          content$.text("#{status}: #{error}")
