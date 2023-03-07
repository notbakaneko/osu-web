// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { BeatmapsContext } from 'beatmap-discussions/beatmaps-context';
import { BeatmapsetsContext } from 'beatmap-discussions/beatmapsets-context';
import { Discussion } from 'beatmap-discussions/discussion';
import { DiscussionsContext } from 'beatmap-discussions/discussions-context';
import DiscussionsState from 'beatmap-discussions/discussions-state';
import DiscussionsStateContext from 'beatmap-discussions/discussions-state-context';
import BeatmapsetCover from 'components/beatmapset-cover';
import { BeatmapsetDiscussionJsonForBundle } from 'interfaces/beatmapset-discussion-json';
import BeatmapsetExtendedJson from 'interfaces/beatmapset-extended-json';
import UserJson from 'interfaces/user-json';
import { route } from 'laroute';
import { computed, observable } from 'mobx';
import React from 'react';
import { makeUrl } from 'utils/beatmapset-discussion-helper';
import { trans } from 'utils/lang';

interface Props {
  discussions: BeatmapsetDiscussionJsonForBundle[];
  user: UserJson;
  users: Partial<Record<number, UserJson>>;
}

export default class Discussions extends React.Component<Props> {
  static readonly contextType = DiscussionsContext;
  declare context: React.ContextType<typeof DiscussionsContext>;

  @observable private discussionsState = new DiscussionsState();

  // FIXME: workaround to passing context is being able to use it as context instance in other classes.
  @computed
  private get discussionsStateProxy() {
    // this.context not available before or during init.
    if (this.discussionsState.discussionsContext == null) {
      this.discussionsState.discussionsContext = this.context;
    }

    return this.discussionsState;
  }

  render() {
    return (
      <div className='page-extra'>
        <h2 className='title title--page-extra'>{trans('users.show.extra.discussions.title_longer')}</h2>
        <div className='modding-profile-list'>
          {this.props.discussions.length === 0 ? (
            <div className='modding-profile-list__empty'>{trans('users.show.extra.none')}</div>
          ) : (
            <BeatmapsetsContext.Consumer>
              {(beatmapsets) => (
                <DiscussionsStateContext.Provider value={this.discussionsStateProxy}>
                  <>
                    {this.props.discussions.map((discussion) => this.renderDiscussion(discussion, beatmapsets))}
                    <a className='modding-profile-list__show-more' href={route('beatmapsets.discussions.index', { user: this.props.user.id })}>
                      {trans('users.show.extra.discussions.show_more')}
                    </a>
                  </>
                </DiscussionsStateContext.Provider>
              )}
            </BeatmapsetsContext.Consumer>
          )}
        </div>
      </div>
    );
  }

  private renderDiscussion(discussion: BeatmapsetDiscussionJsonForBundle, beatmapsets: Partial<Record<number, BeatmapsetExtendedJson>>) {
    const beatmapset = beatmapsets[discussion.beatmapset_id];
    const currentBeatmap = discussion.beatmap_id != null ? this.context.beatmaps[discussion.beatmap_id] : null;

    if (beatmapset == null) return null;

    return (
      <div key={discussion.id} className='modding-profile-list__row'>
        <a className='modding-profile-list__thumbnail' href={makeUrl({ discussion })}>
          <BeatmapsetCover beatmapset={beatmapsets[discussion.beatmapset_id]} size='list' />
        </a>
        <Discussion
          beatmapset={beatmapset}
          currentBeatmap={currentBeatmap ?? null}
          discussion={discussion}
          isTimelineVisible={false}
          preview
          showDeleted
        />
      </div>
    );
  }
}
