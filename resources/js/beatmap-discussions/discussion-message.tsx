// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import React from 'react';
import ReactMarkdown from 'react-markdown';
import remarkBreaks from 'remark-breaks';
import autolink from 'remark-plugins/autolink';
import disableConstructs, { DisabledType } from 'remark-plugins/disable-constructs';
import ImageLink from './image-link';
import { emphasisRenderer, linkRenderer, listItemRenderer, paragraphRenderer, strongRenderer, transformLinkUri } from './renderers';

interface Props {
  markdown: string;
  type?: DisabledType;
}

export default class DiscussionMessage extends React.Component<Props> {
  render() {
    return (
      <ReactMarkdown
        className='osu-md osu-md--discussions'
        components={{
          a: linkRenderer,
          em: emphasisRenderer,
          img: ImageLink,
          li: listItemRenderer,
          p: paragraphRenderer,
          strong: strongRenderer,
        }}
        remarkPlugins={[autolink, [disableConstructs, { type: this.props.type }], remarkBreaks]}
        transformLinkUri={transformLinkUri}
        unwrapDisallowed
      >
        {this.props.markdown}
      </ReactMarkdown>
    );
  }
}
