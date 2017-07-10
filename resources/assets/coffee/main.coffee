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

import { CountdownTimer } from 'react/_components/countdown-timer'
import { BeatmapsetPanel } from 'react/_components/beatmapset-panel'

window.polyfills ?= new Polyfills
Lang.setLocale(currentLocale)
Lang.setFallback(fallbackLocale)
jQuery.timeago.settings.allowFuture = true


# loading animation overlay
# fired from turbolinks
$(document).on 'turbolinks:request-start', LoadingOverlay.show
$(document).on 'turbolinks:request-end', LoadingOverlay.hide
# form submission is not covered by turbolinks
$(document).on 'submit', 'form', (e) ->
  LoadingOverlay.show() if e.currentTarget.dataset.loadingOverlay != '0'

$(document).on 'turbolinks:load', ->
  BeatmapPack.initialize()
  StoreSupporterTag.initialize()

window.accountEdit ?= new AccountEdit
window.accountEditPlaystyle ?= new AccountEditPlaystyle
window.accountEditAvatar ?= new AccountEditAvatar
window.checkboxValidation ?= new CheckboxValidation
window.currentUserObserver ?= new CurrentUserObserver
window.editorZoom ?= new EditorZoom
window.fancyGraph ?= new FancyGraph
window.formClear ?= new FormClear
window.formError ?= new FormError
window.formPlaceholderHide ?= new FormPlaceholderHide
window.formToggle ?= new FormToggle
window.forum ?= new Forum
window.forumAutoClick ?= new ForumAutoClick
window.forumCover ?= new ForumCover
window.gallery ?= new Gallery
window.globalDrag ?= new GlobalDrag
window.landingGraph ?= new LandingGraph
window.landingHero ?= new LandingHero
window.menu ?= new Menu
window.nav ?= new Nav
window.navSearch ?= new NavSearch
window.osuAudio ?= new OsuAudio
window.osuLayzr ?= new OsuLayzr
window.parentFocus ?= new ParentFocus
window.postPreview ?= new PostPreview
window.reactTurbolinks ?= new ReactTurbolinks
window.replyPreview ?= new ReplyPreview
window.scale ?= new Scale
window.search ?= new Search
window.stickyFooter ?= new StickyFooter
window.stickyHeader ?= new StickyHeader
window.syncHeight ?= new SyncHeight
window.throttledWindowEvents ?= new ThrottledWindowEvents
window.timeago ?= new Timeago
window.tooltipDefault ?= new TooltipDefault
window.turbolinksDisable ?= new TurbolinksDisable
window.turbolinksDisqus ?= new TurbolinksDisqus
window.turbolinksReload ?= new TurbolinksReload
window.twitchPlayer ?= new TwitchPlayer
window.wiki ?= new Wiki
window.userCard ?= new UserCard

window.formConfirmation ?= new FormConfirmation(window.formError)
window.forumPostsSeek ?= new ForumPostsSeek(window.forum)
window.forumSearchModal ?= new ForumSearchModal(window.forum)
window.forumTopicPostJump ?= new ForumTopicPostJump(window.forum)
window.forumTopicReply ?= new ForumTopicReply(window.forum, window.stickyFooter)
window.userLogin ?= new UserLogin(window.nav)
window.userVerification ?= new UserVerification(window.nav)


$(document).on 'change', '.js-url-selector', (e) ->
  osu.navigate e.target.value, (e.target.dataset.keepScroll == '1')


$(document).on 'keydown', (e) ->
  $.publish 'key:esc' if e.keyCode == 27

# Globally init countdown timers
reactTurbolinks.register 'countdownTimer', CountdownTimer, (e) ->
  deadline: e.dataset.deadline

# Globally init friend buttons
reactTurbolinks.register 'friendButton', FriendButton, (target) ->
  container: target
  user_id: parseInt(target.dataset.target)

reactTurbolinks.register 'beatmapset-panel', BeatmapsetPanel, (el) ->
  JSON.parse(el.dataset.beatmapsetPanel)

rootUrl = "#{document.location.protocol}//#{document.location.host}"
rootUrl += ":#{document.location.port}" if document.location.port
rootUrl += '/'

# Internal Helper
$.expr[':'].internal = (obj, index, meta, stack) ->
  # Prepare
  $this = $(obj)
  url = $this.attr('href') or ''
  url.substring(0, rootUrl.length) == rootUrl or url.indexOf(':') == -1
