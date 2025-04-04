// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import BeatmapJson from 'interfaces/beatmap-json';
import ChangelogBuild from 'interfaces/changelog-build';
import Ruleset from 'interfaces/ruleset';
import { route } from 'laroute';
import { unescape } from 'lodash';
import { generate } from './beatmapset-page-hash';
import { currentUrl } from './turbolinks';

interface OsuLinkOptions {
  classNames?: string[];
  isRemote?: boolean;
  props?: Partial<Record<string, string | undefined>>;
  unescape?: boolean;
}

export function beatmapDownloadDirect(id: string | number): string {
  return `osu://b/${id}`;
}

export function beatmapsetDownloadDirect(id: string | number): string {
  return `osu://s/${id}`;
}

export function beatmapUrl(beatmap: BeatmapJson, ruleset?: Ruleset) {
  return route('beatmapsets.show', { beatmapset: beatmap.beatmapset_id })
    + generate({ beatmap, ruleset });
}

export function changelogBuild(build: ChangelogBuild): string {
  return route('changelog.build', { build: build.version, stream: build.update_stream.name });
}

export function giftSupporterTagUrl(user: { username: string }) {
  return route('store.products.show', { product: 'supporter-tag', target: user.username });
}

// external link
export function openBeatmapEditor(timestampWithRange: string): string {
  return `osu://edit/${timestampWithRange}`;
}

/**
 * Creates a html link string.
 *
 * @param url href for the link
 * @param text text for the link
 * @param options props.className will override classNames if set.
 * @returns html string of the link
 */
export function linkHtml(url: string, text: string, options?: OsuLinkOptions): string {
  if (options?.unescape) {
    url = unescape(url);
    text = unescape(text);
  }

  const el = document.createElement('a');
  el.textContent = text;
  el.setAttribute('href', url);

  if (options != null) {
    if (options.isRemote) {
      el.setAttribute('data-remote', '1');
    }

    if (options.classNames != null) {
      el.className = options.classNames.join(' ');
    }

    if (options.props != null) {
      const { className, ...props } = options.props;
      if (className != null) {
        el.className = className;
      }

      for (const [prop, val] of Object.entries(props)) {
        if (val != null) el.setAttribute(prop, val);
      }
    }
  }

  return el.outerHTML;
}

// Default url transformer changes non-conforming url to javascript:void(0)
// which causes warning from React.
export function safeReactMarkdownUrl(url: string | undefined) {
  if (url !== 'javascript:void(0)') {
    return url;
  }
}

export function updateQueryString(url: string | null, params: Record<string, string | null | undefined>, hash?: string) {
  const docUrl = currentUrl();
  const urlObj = new URL(url ?? docUrl.href, docUrl.origin);

  for (const [key, value] of Object.entries(params)) {
    if (value != null) {
      urlObj.searchParams.set(key, value);
    } else {
      urlObj.searchParams.delete(key);
    }
  }

  if (hash != null) {
    urlObj.hash = hash;
  }

  return urlObj.href;
}

export function wikiUrl(path: string, locale?: string | null) {
  return route('wiki.show', { locale: locale ?? currentLocale, path: 'WIKI_PATH' })
    .replace('WIKI_PATH', encodeURI(path));
}

export function wikiUrlWithoutLocale(path: string) {
  return route('wiki.show', { path: 'WIKI_PATH' })
    .replace('/WIKI_PATH', encodeURI(path));
}
