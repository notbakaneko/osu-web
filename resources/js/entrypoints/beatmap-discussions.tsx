// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import Main, { InitialData } from 'beatmap-discussions/main';
import core from 'osu-core-singleton';
import React from 'react';
import { parseJson } from 'utils/json';


core.reactTurbolinks.register('beatmap-discussions', (container: HTMLElement) => {
  const initial = parseJson<InitialData>('json-beatmapset-discussion');

  return (
    <Main
      container={container}
      initial={initial}
    />
  );
});
