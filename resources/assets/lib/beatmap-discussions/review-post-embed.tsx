// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { BeatmapIcon } from 'beatmap-icon';
import * as React from 'react';
import { FunctionComponent } from 'react';
import { DiscussionsStoreContext } from './discussions-store-context';

interface Props {
  data: {
    discussion_id: number;
  };
}

export const ReviewPostEmbed: FunctionComponent<Props> = ({data}) => {
  const bn = 'beatmap-discussion-review-post-embed-preview';
  const stores = React.useContext(DiscussionsStoreContext);
  const discussions = stores.discussionStore;
  const beatmaps = stores.beatmapStore;
  const discussion = discussions.get(data.discussion_id);

  if (!discussion) {
    // if a discussion has been deleted or is otherwise missing
    return (
      <div className={osu.classWithModifiers(bn, ['deleted', 'lighter'])}>
        <div className={`${bn}__missing`}>{osu.trans('beatmaps.discussions.review.embed.missing')}</div>
      </div>
    );
  }

  const additionalClasses = ['lighter'];
  if (discussion.message_type === 'praise') {
    additionalClasses.push('praise');
  } else if (discussion.resolved) {
    additionalClasses.push('resolved');
  }

  const beatmap = beatmaps.get(discussion.beatmap_id ?? 0);
  if (!beatmap) {
    additionalClasses.push('general-all');
  }

  if (discussion.deleted_at) {
    additionalClasses.push('deleted');
  }

  const messageTypeIcon = () => {
    const type = discussion.message_type;
    return (
      <div className={`beatmap-discussion-message-type beatmap-discussion-message-type--${type}`}><i className={BeatmapDiscussionHelper.messageType.icon[type]} title={osu.trans(`beatmaps.discussions.message_type.${type}`)} /></div>
    );
  };

  const timestamp = () => {
    return (
      <div className={`${bn}__timestamp-text`}>
        {
          discussion.timestamp !== null
            ? BeatmapDiscussionHelper.formatTimestamp(discussion.timestamp)
            : osu.trans(`beatmap_discussions.timestamp_display.general`)
        }
      </div>
    );
  };

  const parentLink = () => {
    if (!discussion.parent_id) {
      return;
    }

    return (
      <div className={`${bn}__link`}>
        <a
            href={BeatmapDiscussionHelper.url({discussion})}
            className={`${bn}__link-text js-beatmap-discussion--jump`}
            title={osu.trans('beatmap_discussions.review.go_to_child')}
        >
            <i className='fas fa-external-link-alt'/>
        </a>
      </div>
    );
  };

  return (
    <div className={osu.classWithModifiers(bn, additionalClasses)}>
      <div className={`${bn}__content`}>
        <div className={`${bn}__selectors`}>
          <div className='icon-dropdown-menu icon-dropdown-menu--disabled'>
            {beatmap &&
              <BeatmapIcon beatmap={beatmap} />
            }
            {!beatmap &&
              <i className='fas fa-fw fa-star-of-life' title={osu.trans('beatmaps.discussions.mode.scopes.generalAll')} />
            }
          </div>
          <div className='icon-dropdown-menu icon-dropdown-menu--disabled'>
            {messageTypeIcon()}
          </div>
          <div className={`${bn}__timestamp`}>
            {timestamp()}
          </div>
          <div className={`${bn}__stripe`} />
          {parentLink()}
        </div>
        <div className={`${bn}__stripe`} />
        <div className={`${bn}__message-container`}>
          <div className={`${bn}__body`} dangerouslySetInnerHTML={{__html: BeatmapDiscussionHelper.format((discussion.starting_post || discussion.posts[0]).message)}} />
        </div>
        {parentLink()}
      </div>
    </div>
  );
};
