// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import React from 'react';
import ReactMarkdown from 'react-markdown';
import autolink from './plugins/autolink';
import disableConstructs from './plugins/disable-constructs';
import { linkRenderer, paragraphRenderer, transformLinkUri } from './renderers';

interface Props {
  markdown: string;
}

const DiscussionMessage = (props: Props) => (
  <ReactMarkdown
    className='beatmapset-discussion-message'
    components={{
      a: linkRenderer,
      p: paragraphRenderer,
    }}
    remarkPlugins={[autolink, disableConstructs]}
    transformLinkUri={transformLinkUri}
    unwrapDisallowed
  >
    {props.markdown}
  </ReactMarkdown>
);

export default DiscussionMessage;
