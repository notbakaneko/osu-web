import BeatmapJson from 'interfaces/beatmap-json';
import BeatmapsetJson from 'interfaces/beatmapset-json';
import UserJson from 'interfaces/user-json';

export type BeatmapForWrappedJson = BeatmapJson & Required<Pick<BeatmapJson, 'beatmapset'>>;

export default interface WrappedData {
  related_beatmaps: BeatmapForWrappedJson[];
  related_users: UserJson[];
  summary: Summary;
}

export interface DailyChallenge {
  cleared: number;
  highest_streak: number;
  top_10p: number;
  top_50p: number;
}

export interface FavouriteArtist {
  artist: {
    id: null | number;
    name: string;
  };
  scores: {
    pp_avg: number;
    pp_best: number;
    score_avg: number;
    score_count: number;
  };
}

export interface FavouriteMapper {
  mapper_id: number;
  scores: {
    pp_avg: number;
    pp_best: number;
    score_avg: number;
    score_best: number;
    score_best_beatmap_id: number;
    score_count: number;
  };
}

export interface Mapping {
  created: number;
  discussions: number;
  guest: number;
  kudosu: number;
  loved: number;
  nominations: number;
  ranked: number;
}

export interface Scores {
  acc: number;
  combo: number;
  pp: number;
  score: number;
}

export interface TopPlay {
  accuracy: number;
  beatmap_id: number;
  best_id: null;
  build_id: null;
  classic_total_score: number;
  ended_at: string;
  has_replay: boolean;
  id: number;
  is_perfect_combo: boolean;
  legacy_perfect: boolean;
  legacy_score_id: number;
  legacy_total_score: number;
  max_combo: number;
  maximum_statistics: {
    great: number;
    legacy_combo_increase: number;
  };
  mods: {
    acronym: string;
  }[];
  passed: boolean;
  pp: number;
  preserve: boolean;
  processed: boolean;
  rank: string;
  ranked: boolean;
  replay: boolean;
  ruleset_id: number;
  started_at: null;
  statistics: {
    great: number;
    meh: number;
    ok: number;
  };
  total_score: number;
  total_score_without_mods: number;
  type: string;
  user_id: number;
}

interface Summary {
  daily_challenge: DailyChallenge;
  favourite_artists: FavouriteArtist[];
  favourite_mappers: FavouriteMapper[];
  mapping: Mapping;
  medals: number;
  replays: number;
  scores: Scores;
  top_plays: TopPlay[];
  user: UserJson;
}

export const sampleBeatmapset: BeatmapsetJson = {
  anime_cover: false,
  artist: 'Taneda Risa',
  artist_unicode: 'Taneda Risa',
  covers: {
    card: 'https://osuweb.macaron/uploads/default/beatmaps/402995/covers/card.jpg?1637044879',
    // 'card@2x': 'https://osuweb.macaron/uploads/default/beatmaps/402995/covers/card@2x.jpg?1637044879',
    cover: 'https://osuweb.macaron/uploads/default/beatmaps/402995/covers/cover.jpg?1637044879',
    // 'cover@2x': 'https://osuweb.macaron/uploads/default/beatmaps/402995/covers/cover@2x.jpg?1637044879',
    list: 'https://osuweb.macaron/uploads/default/beatmaps/402995/covers/list.jpg?1637044879',
    // 'list@2x': 'https://osuweb.macaron/uploads/default/beatmaps/402995/covers/list@2x.jpg?1637044879',
    slimcover: 'https://osuweb.macaron/uploads/default/beatmaps/402995/covers/slimcover.jpg?1637044879',
    // 'slimcover@2x': 'https://osuweb.macaron/uploads/default/beatmaps/402995/covers/slimcover@2x.jpg?1637044879',
  },
  creator: 'deetz',
  favourite_count: 30467,
  // genre_id: 3,
  hype: null,
  id: 402995,
  // language_id: 2,
  nsfw: true,
  offset: -32681,
  play_count: 0,
  preview_url: '//b.ppy.sh/preview/402995.mp3',
  source: '新世界より',
  spotlight: false,
  status: 'ranked',
  title: 'Wareta Ringo (TV edit)',
  title_unicode: 'Wareta Ringo (TV edit)',
  track_id: null,
  user_id: 475002,
  video: false,
};
