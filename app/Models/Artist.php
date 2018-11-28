<?php

/**
 *    Copyright 2015-2017 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Models;

/**
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int|null $label_id
 * @property int $visible
 * @property string|null $twitter
 * @property string|null $facebook
 * @property string|null $soundcloud
 * @property string|null $bandcamp
 * @property string|null $patreon
 * @property string|null $spotify
 * @property string|null $youtube
 * @property string|null $website
 * @property int|null $user_id
 * @property string|null $cover_url
 * @property string|null $header_url
 * @property Carbon\Carbon|null $created_at
 * @property Carbon\Carbon|null $updated_at
 */
class Artist extends Model
{
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    public function albums()
    {
        return $this->hasMany(ArtistAlbum::class, 'artist_id', 'id');
    }

    public function tracks()
    {
        return $this->hasMany(ArtistTrack::class);
    }
}
