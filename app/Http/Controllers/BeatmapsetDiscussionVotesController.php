<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Http\Controllers;

use App\Models\BeatmapDiscussionVote;
use App\Transformers\BeatmapsetDiscussionVoteTransformer;
use Illuminate\Pagination\LengthAwarePaginator;

class BeatmapsetDiscussionVotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('require-scopes:public');

        return parent::__construct();
    }

    public function index()
    {
        $params = request()->all();
        $params['is_moderator'] = priv_check('BeatmapDiscussionModerate')->can();

        $search = BeatmapDiscussionVote::search($params);

        $data = $search['query']->with([
            'user',
            'beatmapDiscussion',
            'beatmapDiscussion.user',
            'beatmapDiscussion.beatmapset',
            'beatmapDiscussion.startingPost',
        ])->get();

        if (is_json_request()) {
            return ['votes' => json_collection($data, new BeatmapsetDiscussionVoteTransformer())];
        }

        $votes = new LengthAwarePaginator(
            $data,
            $search['query']->realCount(),
            $search['params']['limit'],
            $search['params']['page'],
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $search['params'],
            ]
        );

        return ext_view('beatmapset_discussion_votes.index', compact('votes'));
    }
}
