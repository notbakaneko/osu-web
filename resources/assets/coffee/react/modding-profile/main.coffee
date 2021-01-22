# Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
# See the LICENCE file in the repository root for full licence text.

import { Events } from './events'
import { ExtraTab } from '../profile-page/extra-tab'
import { Discussions } from './discussions'
import { Header } from './header'
import { Kudosu } from '../profile-page/kudosu'
import { Votes } from './votes'
import { DiscussionsStoreContext } from 'beatmap-discussions/discussions-store-context'
import { BlockButton } from 'block-button'
import { NotificationBanner } from 'notification-banner'
import Posts from 'beatmap-discussions/posts'
import * as React from 'react'
import { a, button, div, h1, i, span } from 'react-dom-factories'
import UserProfileContainer from 'user-profile-container'
el = React.createElement

pages = document.getElementsByClassName("js-switchable-mode-page--scrollspy")
pagesOffset = document.getElementsByClassName("js-switchable-mode-page--scrollspy-offset")

currentLocation = ->
  "#{document.location.pathname}#{document.location.search}"


export class Main extends React.PureComponent
  constructor: (props) ->
    super props

    @cache = {}
    @tabs = React.createRef()
    @pages = React.createRef()

    page = location.hash.slice(1)
    @initialPage = page if page?

    @state =
      beatmaps: props.beatmaps
      beatmapsets: props.beatmapsets
      discussions: props.discussions
      events: props.events
      user: props.user
      users: props.users
      posts: props.posts
      votes: props.votes
      profileOrder: ['events', 'discussions', 'posts', 'votes', 'kudosu']
      rankedAndApprovedBeatmapsets: @props.extras.rankedAndApprovedBeatmapsets
      lovedBeatmapsets: @props.extras.lovedBeatmapsets
      unrankedBeatmapsets: @props.extras.unrankedBeatmapsets
      graveyardBeatmapsets: @props.extras.graveyardBeatmapsets
      recentlyReceivedKudosu: @props.extras.recentlyReceivedKudosu
      showMorePagination: {}

    for own elem, perPage of @props.perPage
      @state.showMorePagination[elem] ?= {}
      @state.showMorePagination[elem].hasMore = @state[elem].length > perPage

      if @state.showMorePagination[elem].hasMore
        @state[elem].pop()


  componentDidMount: =>
    $.subscribe 'user:update.moddingProfilePage', @userUpdate
    $.subscribe 'profile:showMore.moddingProfilePage', @showMore
    $.subscribe 'beatmapsetDiscussions:update.moddingProfilePage', @discussionUpdate
    $(document).on 'ajax:success.moddingProfilePage', '.js-beatmapset-discussion-update', @ujsDiscussionUpdate
    $(window).on 'scroll.moddingProfilePage', @pageScan

    osu.pageChange()

    @modeScrollUrl = currentLocation()

    Timeout.set 0, => @pageJump null, @initialPage


  componentWillUnmount: =>
    $.unsubscribe '.moddingProfilePage'
    $(window).off '.moddingProfilePage'

    $(window).stop()
    Timeout.clear @modeScrollTimeout


  discussionUpdate: (_e, options) =>
    {beatmapset} = options
    return unless beatmapset?

    @props.stores.updateWithBeatmapset(beatmapset)


  discussions: =>
    # skipped discussions
    # - not privileged (deleted discussion)
    # - deleted beatmap
    @cache.discussions ?= _ @state.discussions
                            .filter (d) -> !_.isEmpty(d)
                            .keyBy 'id'
                            .value()


  beatmaps: =>
    @cache.beatmaps ?= _.keyBy(this.state.beatmaps, 'id')


  beatmapsets: =>
    @cache.beatmapsets ?= _.keyBy(this.state.beatmapsets, 'id')


  render: =>
    profileOrder = @state.profileOrder

    el DiscussionsStoreContext.Provider, value: @props.stores,
      el UserProfileContainer,
        user: @state.user,
        el Header,
          user: @state.user
          stats: @state.user.statistics
          userAchievements: @props.userAchievements

        div
          className: 'hidden-xs page-extra-tabs page-extra-tabs--profile-page js-switchable-mode-page--scrollspy-offset'
          div className: 'osu-page',
            div
              className: 'page-mode page-mode--profile-page-extra'
              ref: @tabs
              for m in profileOrder
                a
                  className: 'page-mode__item'
                  key: m
                  'data-page-id': m
                  onClick: @tabClick
                  href: "##{m}"
                  el ExtraTab,
                    page: m
                    currentPage: @state.currentPage
                    currentMode: @state.currentMode

        div
          className: 'user-profile-pages'
          ref: @pages
          @extraPage name for name in profileOrder


  extraPage: (name) =>
    {extraClass, props, component, showMore} = @extraPageParams name
    classes = 'user-profile-pages__item js-switchable-mode-page--scrollspy js-switchable-mode-page--page'
    classes += " #{extraClass}" if extraClass?
    props.name = name

    @extraPages ?= {}

    div
      key: name
      'data-page-id': name
      className: classes
      ref: (el) => @extraPages[name] = el
      div className: 'page-extra',
        h1 className: 'title title--page-extra', osu.trans("users.show.extra.#{name}.title_longer")
        div className: 'modding-profile-list',
          el component, props
          if showMore?
            a
              key: 'show-more'
              className: 'modding-profile-list__show-more'
              href: laroute.route("users.modding.#{name}", { user: @props.user.id }),
              osu.trans("users.show.extra.#{name}.show_more")


  extraPageParams: (name) =>
    switch name
      when 'discussions'
        props:
          discussions: @userDiscussions()
          user: @state.user
        component: Discussions

      when 'events'
        props:
          events: @state.events
          user: @state.user
          users: @users()
        component: Events

      when 'kudosu'
        props:
          user: @state.user
          recentlyReceivedKudosu: @state.recentlyReceivedKudosu
          pagination: @state.showMorePagination
        component: Kudosu

      when 'posts'
        props:
          beatmapsetDiscussions: @props.stores.discussionStore
          beatmapsets: @props.stores.beatmapsetStore
          posts: @state.posts
          user: @state.user
          users: @props.stores.userStore
        component: Posts
        showMore: true

      when 'votes'
        props:
          votes: @state.votes
          user: @state.user
          users: @users()
        component: Votes


  showMore: (e, {name, url, perPage = 50}) =>
    offset = @state[name].length

    paginationState = _.cloneDeep @state.showMorePagination
    paginationState[name] ?= {}
    paginationState[name].loading = true

    @setState showMorePagination: paginationState, ->
      $.get osu.updateQueryString(url, offset: offset, limit: perPage + 1), (data) =>
        state = _.cloneDeep(@state[name]).concat(data)
        hasMore = data.length > perPage

        state.pop() if hasMore

        paginationState = _.cloneDeep @state.showMorePagination
        paginationState[name].loading = false
        paginationState[name].hasMore = hasMore

        @setState
          "#{name}": state
          showMorePagination: paginationState


  pageJump: (_e, page) =>
    if page == 'main'
      @setCurrentPage null, page
      return

    target = $(@extraPages[page])

    # if invalid page is specified, scan current position
    if target.length == 0
      @pageScan()
      return

    # Don't bother scanning the current position.
    # The result will be wrong when target page is too short anyway.
    @scrolling = true
    Timeout.clear @modeScrollTimeout

    # count for the tabs height; assume pageJump always causes the header to be pinned
    # otherwise the calculation needs another phase and gets a bit messy.
    offsetTop = target.offset().top - pagesOffset[0].getBoundingClientRect().height

    $(window).stop().scrollTo window.stickyHeader.scrollOffset(offsetTop), 500,
      onAfter: =>
        # Manually set the mode to avoid confusion (wrong highlight).
        # Scrolling will obviously break it but that's unfortunate result
        # from having the scrollspy marker at middle of page.
        @setCurrentPage null, page, =>
          # Doesn't work:
          # - part of state (callback, part of mode setting)
          # - simple variable in callback
          # Both still change the switch too soon.
          @modeScrollTimeout = Timeout.set 100, => @scrolling = false


  pageScan: =>
    return if @modeScrollUrl != currentLocation()

    return if @scrolling
    return if pages.length == 0

    anchorHeight = pagesOffset[0].getBoundingClientRect().height

    if osu.bottomPage()
      @setCurrentPage null, _.last(pages).dataset.pageId
      return

    for page in pages
      pageDims = page.getBoundingClientRect()
      pageBottom = pageDims.bottom - Math.min(pageDims.height * 0.75, 200)
      continue unless pageBottom > anchorHeight

      @setCurrentPage null, page.dataset.pageId
      return

    @setCurrentPage null, page.dataset.pageId


  setCurrentPage: (_e, page, extraCallback) =>
    callback = ->
      extraCallback?()

    if @state.currentPage == page
      return callback()

    @setState currentPage: page, callback


  tabClick: (e) =>
    e.preventDefault()

    @pageJump null, e.currentTarget.dataset.pageId


  userUpdate: (_e, user) =>
    return @forceUpdate() if user?.id != @state.user.id

    # this component needs full user object but sometimes this event only sends part of it
    @setState user: _.assign({}, @state.user, user)


  users: =>
    if !@cache.users?
      @cache.users = _.keyBy @state.users, 'id'
      @cache.users[null] = @cache.users[undefined] =
        username: osu.trans 'users.deleted'

    @cache.users

  userDiscussions: =>
    @props.stores.discussionStore.getUserDiscussions(@state.user.id)


  ujsDiscussionUpdate: (_e, data) =>
    # to allow ajax:complete to be run
    Timeout.set 0, => @discussionUpdate(null, beatmapset: data)
