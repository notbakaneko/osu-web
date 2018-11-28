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
 * @property int|null $artist_id
 * @property int|null $album_id
 * @property int|null $display_order
 * @property string $title
 * @property string|null $title_romanized
 * @property string|null $version
 * @property string $genre
 * @property float $bpm
 * @property int $length
 * @property int $exclusive
 * @property string|null $cover_url
 * @property string $preview
 * @property string $osz
 * @property Carbon\Carbon|null $created_at
 * @property Carbon\Carbon|null $updated_at
 */
class ArtistTrack extends Model
{
    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function album()
    {
        return $this->belongsTo(ArtistAlbum::class);
    }
}
