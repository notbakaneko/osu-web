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

import { AchievementBadge } from './achievement-badge.coffee'
import { Beatmaps } from './beatmaps.coffee'
import { CoverSelection } from './cover-selection.coffee'
import { CoverSelector } from './cover-selector.coffee'
import { CoverUploader } from './cover-uploader.coffee'
import { ExtraHeader } from './extra-header.coffee'
import { ExtraTab } from './extra-tab.coffee'
import { HeaderExtra } from './header-extra.coffee'
import { HeaderFlags } from './header-flags.coffee'
import { HeaderInfo } from './header-info.coffee'
import { HeaderMain } from './header-main.coffee'
import { Header } from './header.coffee'
import { Historical } from './historical.coffee'
import { Kudosu } from './kudosu.coffee'
import { Main } from './main.coffee'
import { Medals } from './medals.coffee'
import { Rank } from './rank.coffee'
import { RecentActivities } from './recent-activities.coffee'
import { Stats } from './stats.coffee'
import { TopRanks } from './top-ranks.coffee'
import { UserPageEditor } from './user-page-editor.coffee'
import { UserPage } from './user-page.coffee'

export ProfilePage = {
  AchievementBadge
  Beatmaps
  CoverSelection
  CoverSelector
  CoverUploader
  ExtraHeader
  ExtraTab
  HeaderExtra
  HeaderFlags
  HeaderInfo
  HeaderMain
  Header
  Historical
  Kudosu
  Main
  Medals
  Rank
  RecentActivities
  Stats
  TopRanks
  UserPageEditor
  UserPage
}
