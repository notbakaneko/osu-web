import BeatmapsetJson from 'interfaces/beatmapset-json';
import UserJson from 'interfaces/user-json';

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
  mapper: {
    avatar_url: string;
    cover_url: string;
    id: number;
    username: string;
  };
  scores: {
    pp_avg: number;
    pp_best: number;
    score_avg: number;
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


export default interface Data {
  daily_challenge: DailyChallenge;
  favorite_artists: FavouriteArtist[];
  favorite_mappers: FavouriteMapper[];
  mapping: Mapping;
  medals: number;
  replays: number;
  scores: Scores;
  top_plays: TopPlay[];
  user: UserJson;
}

/* eslint-disable @stylistic/quotes */
/* eslint-disable sort-keys */
/* eslint-disable @stylistic/quote-props */
const mockData = {
  "daily_challenge": {
    "cleared": 0,
    "top_10p": 0,
    "top_50p": 0,
    "highest_streak": 0,
  },
  "favorite_artists": [
    {
      "artist": {
        "name": "Jeff Williams feat. Casey Lee Williams",
        "id": null,
      },
      "scores": {
        "pp_avg": 571.6944,
        "pp_best": 809.595,
        "score_avg": 640817.025,
        "score_count": 40,
      },
    },
    {
      "artist": {
        "name": "xi",
        "id": 727,
      },
      "scores": {
        "pp_avg": 470.712060714286,
        "pp_best": 1130.63,
        "score_avg": 420211.678571429,
        "score_count": 28,
      },
    },
    {
      "artist": {
        "name": "Demetori",
        "id": null,
      },
      "scores": {
        "pp_avg": 356.93072,
        "pp_best": 624.906,
        "score_avg": 395573.28,
        "score_count": 25,
      },
    },
    {
      "artist": {
        "name": "Mutsuhiko Izumi",
        "id": null,
      },
      "scores": {
        "pp_avg": 699.756681818182,
        "pp_best": 1123.5,
        "score_avg": 629107.136363636,
        "score_count": 22,
      },
    },
    {
      "artist": {
        "name": "Victorius",
        "id": null,
      },
      "scores": {
        "pp_avg": 898.5084,
        "pp_best": 1338.24,
        "score_avg": 558493.05,
        "score_count": 20,
      },
    },
    {
      "artist": {
        "name": "PassCode",
        "id": null,
      },
      "scores": {
        "pp_avg": 619.665194736842,
        "pp_best": 908.447,
        "score_avg": 617894,
        "score_count": 19,
      },
    },
    {
      "artist": {
        "name": "goreshit",
        "id": 57,
      },
      "scores": {
        "pp_avg": 501.562133333333,
        "pp_best": 1002.64,
        "score_avg": 554451.722222222,
        "score_count": 18,
      },
    },
    {
      "artist": {
        "name": "Junichi Masuda",
        "id": null,
      },
      "scores": {
        "pp_avg": 724.309,
        "pp_best": 919.412,
        "score_avg": 778206.705882353,
        "score_count": 17,
      },
    },
    {
      "artist": {
        "name": "kawaii vocaloid",
        "id": null,
      },
      "scores": {
        "pp_avg": 558.582223529412,
        "pp_best": 855.705,
        "score_avg": 678018.764705882,
        "score_count": 17,
      },
    },
    {
      "artist": {
        "name": "Various Artists",
        "id": null,
      },
      "scores": {
        "pp_avg": 598.850185714286,
        "pp_best": 820.991,
        "score_avg": 598901.6875,
        "score_count": 16,
      },
    },
  ],
  "favorite_mappers": [
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/11854343?1710601058.png",
        "cover_url": "https://osuweb.macaron/uploads/default/user-cover-presets/9/27963b632594b78ac79c6169756fecd95018ae370ad25f96eae28a919b4b1d58.png",
        "id": 11854343,
        "username": "shoyeu",
      },
      "scores": {
        "pp_avg": 826.104487804878,
        "pp_best": 1338.24,
        "score_avg": 732662.585365854,
        "score_count": 41,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/4175698?1758739915.jpeg",
        "cover_url": "https://osuweb.macaron/uploads/default/user-cover-presets/19/2d85fcc09cd17097e0043c88646aa73371bbef2499970c276b409bc9b260717c.jpeg",
        "id": 4175698,
        "username": "sytho",
      },
      "scores": {
        "pp_avg": 695.322055555556,
        "pp_best": 1130.63,
        "score_avg": 575914.972972973,
        "score_count": 37,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/4452992?1735576536.png",
        "cover_url": "https://osuweb.macaron/uploads/default/user-profile-covers/4452992/6930dde80f0cbe68e142f403e551493b83c74f1f58e3e35f81cfc3944d24fd65.jpeg",
        "id": 4452992,
        "username": "Sotarks",
      },
      "scores": {
        "pp_avg": 590.6444,
        "pp_best": 919.412,
        "score_avg": 675524.514285714,
        "score_count": 35,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/3638962?1734590922.jpeg",
        "cover_url": "https://osuweb.macaron/uploads/default/user-profile-covers/3638962/de2eec9ac74b1c502239a15ba8dfda345aa008f429842d80c3fd172e3fa14661.gif",
        "id": 3638962,
        "username": "-Tynamo",
      },
      "scores": {
        "pp_avg": 632.083525,
        "pp_best": 929.372,
        "score_avg": 689592.53125,
        "score_count": 32,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/9320502?1709540514.jpeg",
        "cover_url": "https://osuweb.macaron/uploads/default/user-profile-covers/9320502/a909f81c441c4751f9826d037c16514e4ef3792448255ebcc5943ac9248a3af9.png",
        "id": 9320502,
        "username": "Astronic",
      },
      "scores": {
        "pp_avg": 630.0723,
        "pp_best": 922.469,
        "score_avg": 640805.25,
        "score_count": 32,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/4754771?1760955764.jpeg",
        "cover_url": "https://osuweb.macaron/uploads/default/user-cover-presets/4/2fd772ad175c5687370e0aab50799a84adef7d0fff3f97dccfa5c94384ebb8af.jpeg",
        "id": 4754771,
        "username": "Akitoshi",
      },
      "scores": {
        "pp_avg": 663.34895,
        "pp_best": 908.447,
        "score_avg": 640934.423076923,
        "score_count": 26,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/19111992?1721292766.jpeg",
        "cover_url": "https://osuweb.macaron/uploads/default/user-profile-covers/19111992/fac5e3277d6ff01afac8c85beadc2ad2df109f5cac630f4cd5f53869e18fac8c.jpeg",
        "id": 19111992,
        "username": "Urition",
      },
      "scores": {
        "pp_avg": 849.248875,
        "pp_best": 1160.53,
        "score_avg": 760193.961538462,
        "score_count": 26,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/4378277?1578522284.jpeg",
        "cover_url": "https://osuweb.macaron/uploads/default/user-profile-covers/4378277/045ac0063146dbe73849d57bf717cd23f47b44173b6f837602227f01482b2b21.jpeg",
        "id": 4378277,
        "username": "Log Off Now",
      },
      "scores": {
        "pp_avg": 745.82552,
        "pp_best": 1026.47,
        "score_avg": 790186.28,
        "score_count": 25,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/2571893?1760226378.jpeg",
        "cover_url": "https://osuweb.macaron/uploads/default/user-profile-covers/2571893/209105d82dbaf6a6a944fd662259f1ab3946d3beefe6422b16775e81efbcaacc.png",
        "id": 2571893,
        "username": "m1ts",
      },
      "scores": {
        "pp_avg": 648.6991,
        "pp_best": 877.438,
        "score_avg": 554372.4,
        "score_count": 20,
      },
    },
    {
      "mapper": {
        "avatar_url": "https://osuweb.macaron/uploads/avatar/6174349?1672435525.jpeg",
        "cover_url": "https://osuweb.macaron/uploads/default/user-cover-presets/4/2fd772ad175c5687370e0aab50799a84adef7d0fff3f97dccfa5c94384ebb8af.jpeg",
        "id": 6174349,
        "username": "Kuki1537",
      },
      "scores": {
        "pp_avg": 510.550157894737,
        "pp_best": 737.311,
        "score_avg": 603179,
        "score_count": 19,
      },
    },
  ],
  "mapping": {
    "created": 0,
    "discussions": 3,
    "guest": 0,
    "kudosu": 0,
    "loved": 0,
    "nominations": 0,
    "ranked": 0,
  },
  "medals": 4,
  "replays": 477,
  "scores": {
    "acc": 0.913015199648196,
    "combo": 2401,
    "pp": 253240.94129,
    "score": 1043157,
  },
  "top_plays": [
    {
      "classic_total_score": 98659426,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 1836,
        "legacy_combo_increase": 571,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 148,
        "meh": 2,
        "great": 1686,
      },
      "total_score_without_mods": 850190,
      "beatmap_id": 4704022,
      "best_id": null,
      "id": 4809258579,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.945352,
      "build_id": null,
      "ended_at": "2025-05-08T08:56:23Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4832070273,
      "legacy_total_score": 113799156,
      "max_combo": 2401,
      "passed": true,
      "pp": 1338.24,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 897801,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 45707556,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 1254,
        "legacy_combo_increase": 421,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 60,
        "miss": 3,
        "great": 1191,
      },
      "total_score_without_mods": 843459,
      "beatmap_id": 5119288,
      "best_id": null,
      "id": 5571121310,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.96571,
      "build_id": null,
      "ended_at": "2025-10-08T21:45:17Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4918867789,
      "legacy_total_score": 40229840,
      "max_combo": 1373,
      "passed": true,
      "pp": 1160.53,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 890693,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 64123923,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 1824,
        "legacy_combo_increase": 768,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 196,
        "meh": 3,
        "miss": 3,
        "great": 1622,
      },
      "total_score_without_mods": 559871,
      "beatmap_id": 4511021,
      "best_id": null,
      "id": 5242252308,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.925347,
      "build_id": null,
      "ended_at": "2025-08-02T10:19:28Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4881823194,
      "legacy_total_score": 39382313,
      "max_combo": 1028,
      "passed": true,
      "pp": 1130.63,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 591224,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 16040827,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 731,
        "legacy_combo_increase": 243,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 51,
        "meh": 1,
        "great": 679,
      },
      "total_score_without_mods": 867805,
      "beatmap_id": 4641263,
      "best_id": null,
      "id": 4952172967,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.952348,
      "build_id": null,
      "ended_at": "2025-06-05T02:28:39Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4848745287,
      "legacy_total_score": 18801943,
      "max_combo": 972,
      "passed": true,
      "pp": 1123.5,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 916402,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 41602174,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 1135,
        "legacy_combo_increase": 535,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 37,
        "great": 1098,
      },
      "total_score_without_mods": 936718,
      "beatmap_id": 5109853,
      "best_id": null,
      "id": 5571140017,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.978267,
      "build_id": null,
      "ended_at": "2025-10-08T21:50:57Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4918869951,
      "legacy_total_score": 47595693,
      "max_combo": 1664,
      "passed": true,
      "pp": 1114.01,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 989174,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 13767293,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 656,
        "legacy_combo_increase": 291,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 26,
        "great": 630,
      },
      "total_score_without_mods": 923574,
      "beatmap_id": 5145068,
      "best_id": null,
      "id": 5285243055,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.973577,
      "build_id": null,
      "ended_at": "2025-08-11T00:56:00Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4886705789,
      "legacy_total_score": 16574088,
      "max_combo": 946,
      "passed": true,
      "pp": 1091.32,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 975294,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 13161411,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 665,
        "legacy_combo_increase": 298,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 50,
        "great": 615,
      },
      "total_score_without_mods": 859355,
      "beatmap_id": 4528587,
      "best_id": null,
      "id": 5359827144,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.949875,
      "build_id": null,
      "ended_at": "2025-08-26T04:41:30Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4895164018,
      "legacy_total_score": 20561572,
      "max_combo": 960,
      "passed": true,
      "pp": 1078.26,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 907479,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 3708185,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 342,
        "legacy_combo_increase": 121,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 15,
        "miss": 1,
        "great": 326,
      },
      "total_score_without_mods": 898203,
      "beatmap_id": 3963421,
      "best_id": null,
      "id": 5225163932,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.967836,
      "build_id": null,
      "ended_at": "2025-07-29T22:31:34Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4879884086,
      "legacy_total_score": 5214004,
      "max_combo": 449,
      "passed": true,
      "pp": 1026.47,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 948502,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 107782224,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 2121,
        "legacy_combo_increase": 623,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 139,
        "miss": 1,
        "great": 1981,
      },
      "total_score_without_mods": 696126,
      "beatmap_id": 5080973,
      "best_id": null,
      "id": 4926612282,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.955838,
      "build_id": null,
      "ended_at": "2025-05-31T02:56:27Z",
      "has_replay": false,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4845745907,
      "legacy_total_score": 76531618,
      "max_combo": 1369,
      "passed": true,
      "pp": 1002.64,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 735109,
      "replay": false,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 21825304,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 829,
        "legacy_combo_increase": 435,
      },
      "mods": [
        {
          "acronym": "NC",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 35,
        "great": 794,
      },
      "total_score_without_mods": 919250,
      "beatmap_id": 4343206,
      "best_id": null,
      "id": 4442428313,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.971854,
      "build_id": null,
      "ended_at": "2025-03-01T21:13:59Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4789328714,
      "legacy_total_score": 27928038,
      "max_combo": 1261,
      "passed": true,
      "pp": 990.843,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 970728,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 230420224,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 3260,
        "legacy_combo_increase": 1052,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 189,
        "meh": 2,
        "miss": 7,
        "great": 3062,
      },
      "total_score_without_mods": 630200,
      "beatmap_id": 1811527,
      "best_id": null,
      "id": 5364959314,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.958691,
      "build_id": null,
      "ended_at": "2025-08-27T06:20:07Z",
      "has_replay": false,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4895755503,
      "legacy_total_score": 91820422,
      "max_combo": 1772,
      "passed": true,
      "pp": 976.052,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 665491,
      "replay": false,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 12806980,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 632,
        "legacy_combo_increase": 218,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 15,
        "great": 617,
      },
      "total_score_without_mods": 925135,
      "beatmap_id": 4019123,
      "best_id": null,
      "id": 5364722078,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.984177,
      "build_id": null,
      "ended_at": "2025-08-27T04:35:06Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4895728811,
      "legacy_total_score": 16760837,
      "max_combo": 800,
      "passed": true,
      "pp": 974.755,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 976943,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 42462040,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 1519,
        "legacy_combo_increase": 587,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 192,
        "meh": 8,
        "miss": 6,
        "great": 1313,
      },
      "total_score_without_mods": 534350,
      "beatmap_id": 4746232,
      "best_id": null,
      "id": 5242115784,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.907395,
      "build_id": null,
      "ended_at": "2025-08-02T09:36:17Z",
      "has_replay": false,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4881807860,
      "legacy_total_score": 26788943,
      "max_combo": 754,
      "passed": true,
      "pp": 972.171,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 564274,
      "replay": false,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 114636074,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 2594,
        "legacy_combo_increase": 1031,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 265,
        "meh": 16,
        "miss": 9,
        "great": 2304,
      },
      "total_score_without_mods": 495110,
      "beatmap_id": 5065131,
      "best_id": null,
      "id": 5334656188,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.923285,
      "build_id": null,
      "ended_at": "2025-08-21T04:24:05Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4892308489,
      "legacy_total_score": 40682363,
      "max_combo": 862,
      "passed": true,
      "pp": 972.014,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 522836,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 13019458,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 726,
        "legacy_combo_increase": 243,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 43,
        "meh": 3,
        "miss": 4,
        "great": 676,
      },
      "total_score_without_mods": 714028,
      "beatmap_id": 4628575,
      "best_id": null,
      "id": 5255049153,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.951561,
      "build_id": null,
      "ended_at": "2025-08-04T21:44:41Z",
      "has_replay": false,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4883284700,
      "legacy_total_score": 8822621,
      "max_combo": 504,
      "passed": true,
      "pp": 958.454,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 754014,
      "replay": false,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 11667849,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 592,
        "legacy_combo_increase": 346,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 12,
        "great": 580,
      },
      "total_score_without_mods": 959572,
      "beatmap_id": 5136936,
      "best_id": null,
      "id": 5285183628,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.986486,
      "build_id": null,
      "ended_at": "2025-08-11T00:35:24Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4886699147,
      "legacy_total_score": 15223649,
      "max_combo": 937,
      "passed": true,
      "pp": 946.367,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 1013308,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 8040426,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 502,
        "legacy_combo_increase": 187,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 22,
        "great": 480,
      },
      "total_score_without_mods": 916496,
      "beatmap_id": 1706834,
      "best_id": null,
      "id": 4863798296,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.970784,
      "build_id": null,
      "ended_at": "2025-05-18T14:16:05Z",
      "has_replay": true,
      "is_perfect_combo": true,
      "legacy_perfect": true,
      "legacy_score_id": 4838424834,
      "legacy_total_score": 11304439,
      "max_combo": 689,
      "passed": true,
      "pp": 943.436,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 967820,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 2839543,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 297,
        "legacy_combo_increase": 65,
      },
      "mods": [
        {
          "acronym": "NC",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 15,
        "great": 282,
      },
      "total_score_without_mods": 904471,
      "beatmap_id": 4381212,
      "best_id": null,
      "id": 5254635784,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.96633,
      "build_id": null,
      "ended_at": "2025-08-04T20:03:25Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4883236508,
      "legacy_total_score": 3463735,
      "max_combo": 361,
      "passed": true,
      "pp": 929.372,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 955121,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 17144240,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 743,
        "legacy_combo_increase": 223,
      },
      "mods": [
        {
          "acronym": "DT",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 39,
        "meh": 1,
        "great": 703,
      },
      "total_score_without_mods": 897946,
      "beatmap_id": 3928577,
      "best_id": null,
      "id": 4413842048,
      "rank": "S",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.963885,
      "build_id": null,
      "ended_at": "2025-02-24T19:12:07Z",
      "has_replay": true,
      "is_perfect_combo": true,
      "legacy_perfect": true,
      "legacy_score_id": 4785984131,
      "legacy_total_score": 24440505,
      "max_combo": 966,
      "passed": true,
      "pp": 925.144,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 948231,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
    {
      "classic_total_score": 12090758,
      "preserve": true,
      "processed": true,
      "ranked": true,
      "maximum_statistics": {
        "great": 640,
        "legacy_combo_increase": 182,
      },
      "mods": [
        {
          "acronym": "NC",
        },
        {
          "acronym": "CL",
        },
      ],
      "statistics": {
        "ok": 49,
        "miss": 1,
        "great": 590,
      },
      "total_score_without_mods": 851861,
      "beatmap_id": 5061813,
      "best_id": null,
      "id": 4948334111,
      "rank": "A",
      "type": "solo_score",
      "user_id": 15119977,
      "accuracy": 0.947396,
      "build_id": null,
      "ended_at": "2025-06-04T10:08:29Z",
      "has_replay": true,
      "is_perfect_combo": false,
      "legacy_perfect": false,
      "legacy_score_id": 4848291554,
      "legacy_total_score": 16913338,
      "max_combo": 818,
      "passed": true,
      "pp": 922.469,
      "ruleset_id": 0,
      "started_at": null,
      "total_score": 899565,
      "replay": true,
      "current_user_attributes": {
        "pin": null,
      },
    },
  ],
  "user": "MouseR1ez",
};
/* eslint-enable @stylistic/quotes */
/* eslint-enable sort-keys */
/* eslint-enable @stylistic/quote-props */

export const sampleData: Data = {
  ...mockData,
  user: {
    avatar_url: 'https://osuweb.macaron/uploads/avatar/475001?1762494521.png',
    country: {
      code: 'JP',
      name: 'Japan',
    },
    country_code: 'JP',
    default_group: 'bng',
    id: 475001,
    is_active: true,
    is_bot: false,
    is_deleted: false,
    is_online: false,
    is_supporter: true,
    last_visit: null,
    pm_friends_only: false,
    profile_colour: '#abcdef',
    username: 'wat5',
  },
};


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
