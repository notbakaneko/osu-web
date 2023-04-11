// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import { BeatmapsContext } from 'beatmap-discussions/beatmaps-context';
import { DiscussionsContext } from 'beatmap-discussions/discussions-context';
import Editor from 'beatmap-discussions/editor';
import { ReviewPost } from 'beatmap-discussions/review-post';
import BigButton from 'components/big-button';
import ClickToCopy from 'components/click-to-copy';
import { ReportReportable } from 'components/report-reportable';
import StringWithComponent from 'components/string-with-component';
import TimeWithTooltip from 'components/time-with-tooltip';
import UserLink from 'components/user-link';
import BeatmapExtendedJson from 'interfaces/beatmap-extended-json';
import BeatmapsetDiscussionJson from 'interfaces/beatmapset-discussion-json';
import { BeatmapsetDiscussionMessagePostJson } from 'interfaces/beatmapset-discussion-post-json';
import BeatmapsetExtendedJson from 'interfaces/beatmapset-extended-json';
import { BeatmapsetWithDiscussionsJson } from 'interfaces/beatmapset-json';
import UserJson from 'interfaces/user-json';
import { route } from 'laroute';
import { isEqual } from 'lodash';
import { action, autorun, computed, makeObservable, observable, runInAction } from 'mobx';
import { disposeOnUnmount, observer } from 'mobx-react';
import { deletedUser } from 'models/user';
import core from 'osu-core-singleton';
import * as React from 'react';
import { onError } from 'utils/ajax';
import { badgeGroup, canModeratePosts, makeUrl, validMessageLength } from 'utils/beatmapset-discussion-helper';
import { downloadLimited } from 'utils/beatmapset-helper';
import { classWithModifiers, groupColour } from 'utils/css';
import { InputEventType, makeTextAreaHandler } from 'utils/input-handler';
import { trans } from 'utils/lang';
import DiscussionMessage from './discussion-message';
import DiscussionsStateContext from './discussions-state-context';
import MarkdownEditor, { Mode } from './markdown-editor';
import MarkdownEditorSwitcher from './markdown-editor-switcher';
import { UserCard } from './user-card';

const bn = 'beatmap-discussion-post';

interface Props {
  beatmap: BeatmapExtendedJson | null;
  beatmapset: BeatmapsetExtendedJson;
  discussion: BeatmapsetDiscussionJson;
  post: BeatmapsetDiscussionMessagePostJson;
  read: boolean;
  readonly: boolean;
  resolvedSystemPostId: number;
  type: 'discussion' | 'reply';
  user: UserJson;
  users: Partial<Record<number, UserJson>>;
}

@observer
export default class Post extends React.Component<React.PropsWithChildren<Props>> {
  static contextType = DiscussionsStateContext;
  declare context: React.ContextType<typeof DiscussionsStateContext>;

  @observable private canSave = true; // this isn't computed because Editor's onChange doesn't provide anything to react to.
  private readonly handleTextareaKeyDown;
  @observable private message = '';
  private readonly messageBodyRef = React.createRef<HTMLDivElement>();
  private readonly reviewEditorRef = React.createRef<Editor>();
  private readonly textareaRef = React.createRef<HTMLTextAreaElement>();
  @observable private xhr: JQuery.jqXHR<BeatmapsetWithDiscussionsJson> | null = null;

  @computed
  private get canEdit() {
    return this.isAdmin
      || (!downloadLimited(this.props.beatmapset)
        && this.isOwn
        && this.props.post.id > this.props.resolvedSystemPostId
        && !this.props.beatmapset.discussion_locked
      );
  }

  private get canDelete() {
    return this.props.type === 'discussion'
      ? this.props.discussion.current_user_attributes?.can_destroy
      : this.canModerate || this.canEdit;
  }

  private get canModerate() {
    return canModeratePosts();
  }

  @computed
  private get canReport() {
    return core.currentUser != null && this.props.post.user_id !== core.currentUser.id;
  }

  @computed
  private get deleteModel() {
    return this.props.type === 'reply' ? this.props.post : this.props.discussion;
  }

  @computed
  private get isAdmin() {
    return core.currentUser?.is_admin ?? false;
  }

  private get editing() {
    return this.context.postEditing.has(this.props.post.id);
  }

  private set editing(value: boolean) {
    if (value) {
      this.context.postEditing.add(this.props.post.id);
    } else {
      this.context.postEditing.delete(this.props.post.id);
    }
  }

  @computed
  private get isOwn() {
    return core.currentUser != null && core.currentUser.id === this.props.post.user_id;
  }

  @computed
  private get isPosting() {
    return this.xhr != null;
  }

  @computed
  private get isReview() {
    return this.props.discussion.message_type === 'review' && this.props.type === 'discussion';
  }

  @computed
  private get isTimeline() {
    return this.props.discussion.timestamp != null;
  }

  constructor(props: Props) {
    super(props);
    makeObservable(this);

    this.handleTextareaKeyDown = makeTextAreaHandler(this.handleTextareaKeyDownCallback);

    disposeOnUnmount(this, autorun(() => {
      // TODO: something (context?) triggers this autorun before unmount when navigating away
      if (this.context != null && this.editing) {
        setTimeout(() => this.textareaRef.current?.focus(), 0);
      }
    }));
  }

  componentWillUnmount() {
    this.editing = false;
    this.xhr?.abort();
  }

  render() {
    const topClasses = classWithModifiers(bn, {
      deleted: this.props.post.deleted_at != null,
      editing: this.editing,
      unread: !this.props.read && this.props.type !== 'discussion',
    });

    const group = badgeGroup({
      beatmapset: this.props.beatmapset,
      currentBeatmap: this.props.beatmap,
      discussion: this.props.discussion,
      user: this.props.user,
    });

    return (
      <div
        className={`${topClasses} js-beatmap-discussion-jump`}
        data-post-id={this.props.post.id}
        onClick={this.handleMarkRead}
        style={groupColour(group)}
      >
        <div className={`${bn}__user`}>
          <UserCard
            group={badgeGroup({
              beatmapset: this.props.beatmapset,
              currentBeatmap: this.props.beatmap,
              discussion: this.props.discussion,
              user: this.props.user,
            })}
            hideStripe
            user={this.props.user}
          />
        </div>
        <div className={`${bn}__container`}>
          {this.props.children}
          <div className={classWithModifiers(`${bn}__message-container`, { review: this.isReview })}>
            {this.editing ? this.renderMessageEditor() : this.renderMessageViewer()}
          </div>
        </div>
      </div>
    );
  }

  private deleteHref(op: 'destroy' | 'restore') {
    const [controller, key] = this.props.type === 'reply'
      ? ['beatmapsets.discussions.posts', 'post']
      : ['beatmapsets.discussions', 'discussion'];

    return route(`${controller}.${op}`, { [key]: this.deleteModel.id });
  }

  @action
  private readonly editCancel = () => {
    this.editing = false;
  };

  @action
  private readonly editStart = () => {
    this.editing = true;
    this.message = this.props.post.message;
  };

  @action
  private readonly handleEditorChange = () => {
    this.canSave = this.reviewEditorRef.current?.canSave ?? false;
  };

  private readonly handleMarkRead = () => {
    $.publish('beatmapDiscussionPost:markRead', { id: this.props.post.id });
  };

  @action
  private readonly handleModeChange = (id: number, mode: Mode) => {
    this.context.editorMode.set(id, mode);
  };

  @action
  private readonly handleTextareaChange = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
    this.message = e.target.value;
    this.canSave = validMessageLength(this.message, this.isTimeline);
  };

  private readonly handleTextareaKeyDownCallback = (type: InputEventType | null) => {
    if (type === InputEventType.Submit) {
      this.updatePost();
    }
  };

  private renderDeletedBy() {
    if (this.deleteModel.deleted_at == null) return null;
    const user = (
      this.deleteModel.deleted_by_id != null
        ? this.props.users[this.deleteModel.deleted_by_id]
        : null
    ) ?? deletedUser;

    return (
      <span className={`${bn}__info`}>
        <StringWithComponent
          mappings={{
            delete_time: <TimeWithTooltip dateTime={this.deleteModel.deleted_at} relative />,
            editor: (
              <UserLink
                className={`${bn}__info-user`}
                user={user}
              />
            ),
          }}
          pattern={trans('beatmaps.discussions.deleted')}
        />
      </span>
    );
  }

  private renderEdited() {
    if (this.props.post.last_editor_id == null
      || this.props.post.updated_at === this.props.post.created_at) {
      return null;
    }

    const lastEditor = this.props.users[this.props.post.last_editor_id] ?? deletedUser.toJson();

    return (
      <span className={`${bn}__info`}>
        <StringWithComponent
          mappings={{
            editor: <UserLink className={`${bn}__info-user`} user={lastEditor} />,
            update_time: <TimeWithTooltip dateTime={this.props.post.updated_at} relative />,
          }}
          pattern={trans('beatmaps.discussions.edited')}
        />
      </span>
    );
  }

  private renderKudosuAction(op: 'allow' | 'deny') {
    return (
      <a
        className={`js-beatmapset-discussion-update ${bn}__action ${bn}__action--plain`}
        data-confirm={trans('common.confirmation')}
        data-method='POST'
        data-remote
        href={route(`beatmapsets.discussions.${op}-kudosu`, { discussion: this.props.discussion.id })}
      >
        {trans(`beatmaps.discussions.${op}_kudosu`)}
      </a>
    );
  }

  private renderMessageEditor() {
    if (!this.canEdit) return;
    const canPost = !this.isPosting && this.canSave;

    const document = this.props.post.message;
    return (
      <>
        {this.isReview ? (
          <DiscussionsContext.Consumer>
            {(discussions) => (
              <BeatmapsContext.Consumer>
                {(beatmaps) => (
                  <Editor
                    ref={this.reviewEditorRef}
                    beatmaps={beatmaps}
                    beatmapset={this.props.beatmapset}
                    currentBeatmap={this.props.beatmap}
                    discussion={this.props.discussion}
                    discussions={discussions}
                    document={document}
                    editing={this.editing}
                    onChange={this.handleEditorChange}
                  />
                )}
              </BeatmapsContext.Consumer>
            )}
          </DiscussionsContext.Consumer>
        ) : (
          <>
            <MarkdownEditorSwitcher
              id={this.props.post.id}
              mode={this.context.editorMode.get(this.props.post.id)}
              onModeChange={this.handleModeChange}
            />
            <MarkdownEditor
              disabled={this.isPosting}
              isTimeline={this.isTimeline}
              mode={this.context.editorMode.get(this.props.post.id)}
              onChange={this.handleTextareaChange}
              onKeyDown={this.handleTextareaKeyDown}
              value={this.message}
            />
          </>
        )}
        <div className={`${bn}__actions`}>
          <div className={`${bn}__action`}>
            <BigButton
              disabled={this.isPosting}
              props={{ onClick: this.editCancel }}
              text={trans('common.buttons.cancel')}
            />
          </div>
          <div className={`${bn}__action`}>
            <BigButton
              disabled={!canPost}
              props={{ onClick: this.updatePost }}
              text={trans('common.buttons.save')}
            />
          </div>
        </div>
      </>
    );
  }

  private renderMessageViewer() {
    return (
      <>
        {this.isReview ? (
          <div className={`${bn}__message`}>
            <ReviewPost post={this.props.post} />
          </div>
        ) : (
          <div ref={this.messageBodyRef} className={`${bn}__message`}>
            <DiscussionMessage markdown={this.props.post.message} />
          </div>
        )}
        <div className={`${bn}__info-container`}>
          <span className={`${bn}__info`}>
            <TimeWithTooltip dateTime={this.props.post.created_at} relative />
          </span>
          {this.renderDeletedBy()}
          {this.renderEdited()}

          {this.props.type === 'discussion' && this.props.discussion.kudosu_denied && (
            <span className={`${bn}__info`}>
              {trans('beatmaps.discussions.kudosu_denied')}
            </span>
          )}
        </div>

        {this.renderMessageViewerActions()}
      </>
    );
  }


  private renderMessageViewerActions() {
    return (
      <div className={`${bn}__actions`}>
        <span className={`${bn}__action ${bn}__action--plain`}>
          <ClickToCopy
            label={trans('common.buttons.permalink')}
            value={makeUrl({ discussion: this.props.discussion, post: this.props.type === 'reply' ? this.props.post : undefined })}
            valueAsUrl
          />
        </span>

        {!this.props.readonly && (
          <>
            {this.canEdit && (
              <button
                className={`${bn}__action ${bn}__action--plain`}
                onClick={this.editStart}
              >
                {trans('beatmaps.discussions.edit')}
              </button>
            )}

            {this.deleteModel.deleted_at == null && this.canDelete && (
              <a
                className={`js-beatmapset-discussion-update ${bn}__action ${bn}__action--plain`}
                data-confirm={trans('common.confirmation')}
                data-method='DELETE'
                data-remote
                href={this.deleteHref('destroy')}
              >
                {trans('beatmaps.discussions.delete')}
              </a>
            )}

            {this.deleteModel.deleted_at != null && this.canModerate && (
              <a
                className={`js-beatmapset-discussion-update ${bn}__action ${bn}__action--plain`}
                data-confirm={trans('common.confirmation')}
                data-method='POST'
                data-remote
                href={this.deleteHref('restore')}
              >
                {trans('beatmaps.discussions.restore')}
              </a>
            )}

            {this.props.type === 'discussion' && this.props.discussion.current_user_attributes?.can_moderate_kudosu && (
              this.props.discussion.can_grant_kudosu
                ? this.renderKudosuAction('deny')
                : this.props.discussion.kudosu_denied && this.renderKudosuAction('allow')
            )}
          </>
        )}

        {this.canReport && (
          <ReportReportable
            className={`${bn}__action ${bn}__action--plain`}
            reportableId={this.props.post.id.toString()}
            reportableType='beatmapset_discussion_post'
            user={this.props.user}
          />
        )}
      </div>
    );
  }


  @action
  private readonly updatePost = () => {
    if (this.isPosting) return;

    if (this.isReview) {
      if (this.reviewEditorRef.current == null) {
        console.error('reviewEditor is missing!');
        return;
      }

      const messageContent = this.reviewEditorRef.current.serialize();

      if (isEqual(JSON.parse(this.props.post.message), JSON.parse(messageContent))) {
        this.editing = false;
        return;
      }

      if (!this.reviewEditorRef.current.showConfirmationIfRequired()) return;

      this.message = messageContent;
    }

    if (this.message === this.props.post.message) {
      this.editing = false;
      return;
    }

    this.xhr = $.ajax(route('beatmapsets.discussions.posts.update', { post: this.props.post.id }), {
      data: {
        beatmap_discussion_post: {
          message: this.message,
        },
      },
      method: 'PUT',
    });

    this.xhr.done((beatmapset) => runInAction(() => {
      this.editing = false;
      $.publish('beatmapsetDiscussions:update', { beatmapset });
    }))
      .fail(onError)
      .always(action(() => this.xhr = null));
  };
}
