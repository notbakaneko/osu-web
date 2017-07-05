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

import { BeatmapListItem } from './beatmap-list-item.coffee'
import { BeatmapList } from './beatmap-list.coffee'
import { Discussion } from './discussion.coffee'
import { Discussions } from './discussions.coffee'
import { Header } from './header.coffee'
import { Main } from './main.coffee'
import { ModeSwitcher } from './mode-switcher.coffee'
import { NewDiscussion } from './new-discussion.coffee'
import { NewReply } from './new-reply.coffee'
import { Nominations } from './nominations.coffee'
import { Post } from './post.coffee'
import { SystemPost } from './system-post.coffee'

export BeatmapDiscussions = {
  BeatmapListItem
  BeatmapList
  Discussion
  Discussions
  Header
  Main
  ModeSwitcher
  NewDiscussion
  NewReply
  Nominations
  Post
  SystemPost
}
