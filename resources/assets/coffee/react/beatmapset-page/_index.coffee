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

import { BeatmapPicker } from './beatmap-picker.coffee'
import { BeatmapSelection } from './beatmap-selection.coffee'
import { Header } from './header.coffee'
import { Info } from './info.coffee'
import { Main } from './main.coffee'
import { ScoreBig } from './score-big.coffee'
import { ScoreTop } from './score-top.coffee'
import { Score } from './score.coffee'
import { ScoreboardMod } from './scoreboard-mod.coffee'
import { ScoreboardTab } from './scoreboard-tab.coffee'
import { Scoreboard } from './scoreboard.coffee'
import { Stats } from './stats.coffee'

export BeatmapsetPage = {
  BeatmapPicker
  BeatmapSelection
  Header
  Info
  Main
  ScoreBig
  ScoreTop
  Score
  ScoreboardMod
  ScoreboardTab
  Scoreboard
  Stats
}
