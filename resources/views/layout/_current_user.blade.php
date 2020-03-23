{{--
    Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
    See the LICENCE file in the repository root for full licence text.
--}}
<script id="js-current-user" type="application/json">
    {!! Auth::check() ? json_encode(Auth::user()->defaultJson()) : '{}' !!}
</script>
