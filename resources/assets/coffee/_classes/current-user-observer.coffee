# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

class @CurrentUserObserver
  constructor: ->
    @covers = document.getElementsByClassName('js-current-user-cover')
    @avatars = document.getElementsByClassName('js-current-user-avatar')

    $.subscribe 'user:update', @setData
    $(document).on 'turbolinks:load', @reinit
    $.subscribe 'osu:page:change', @reinit


  reinit: =>
    json = osu.parseJson('js-current-user', true)
    @initUser(json) if json?

    @setAvatars()
    @setCovers()
    @setSentryUser()


  setAvatars: (elements) =>
    elements ?= @avatars

    bgImage = osu.urlPresence(currentUser.avatar_url) if currentUser.id?
    for el in elements
      el.style.backgroundImage = bgImage


  setCovers: (elements) =>
    elements ?= @covers

    bgImage = osu.urlPresence(currentUser.cover_url) if currentUser.id?
    for el in elements
      el.style.backgroundImage = bgImage


  setData: (_e, data) =>
    @initUser(data)

    @reinit()


  setSentryUser: ->
    return unless Sentry?

    Sentry.configureScope (scope) ->
      scope.setUser id: currentUser.id, username: currentUser.username


  initUser: (json) ->
    handler =
      get: (obj, prop) ->
        if prop of obj then obj[prop] else json[prop]

    window.currentUser = new Proxy(_exported.User.fromJSON(json), handler)
