{{--
    Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
    See the LICENCE file in the repository root for full licence text.
--}}
@extends('master')

@section('content')
@component('layout._page_header_v4')
@endcomponent

<div class="osu-page osu-page--generic-compact">
    <div class="download-page">
        <div class="download-page__header">
            <div class="download-page__banner">
                <div class="download-page__banner-content">
                    <h2 class="download-page__tagline">{{ strtr(osu_trans('home.download.tagline'), ['<br>' => ' ']) }}</h2>
                    <div class="download-page__button download-page__button--main">
                        <a
                            class="btn-osu-big btn-osu-big--download btn-osu-big--full btn-osu-big--download-lazer"
                            href="{{ $lazerUrl }}"
                        >
                            <div class="btn-osu-big__text-bottom">
                                {{ osu_trans('home.download.download') }}
                            </div>
                            <div>
                                <div class="btn-osu-big__text-top">
                                    osu!
                                </div>
                                <div class="btn-osu-big__text-bottom">
                                    {{ osu_trans('home.download.for_os', ['os' => $lazerPlatformName]) }}
                                </div>
                            </div>
                            <div class="btn-osu-big__text-bottom">
                                version
                            </div>
                        </a>
                    </div>

                    <div class="download-page__other-platforms">
                        <div class="select-options">
                            <div class="select-options__select">
                                <a class="select-options__option" href="{{ osu_url('lazer_dl_other') }}">
                                    {{ osu_trans('home.download.other_os') }}
                                    <div class="select-options__decoration"><span class="fas fa-chevron-down"></span></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="download-page__banner-tail">
                <div>
                    if you're looking for the older one
                </div>
                <div class="download-page__button">
                    <a class="btn-osu-big btn-osu-big--download btn-osu-big--full" href="{{ osu_url('installer') }}">
                        <span class="btn-osu-big__text-top">osu!Classic</span>
                        <span class="btn-osu-big__text-bottom">{{ osu_trans('home.download.os.windows') }}</span>
                    </a>

                    <div class="download-page-header__extra-links">
                        <a class="download-page-header__extra-link" href="{{ osu_url('installer-mirror') }}">
                            {{ osu_trans('home.download.mirror') }}
                        </a>
                        <span class="download-page-header__extra-link download-page-header__extra-link--separator"></span>
                        <a class="download-page-header__extra-link" href="{{ osu_url('osx') }}">
                            {{ osu_trans('home.download.macos-fallback') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="download-page__guide">
            <iframe
                class="download-page__video u-embed-wide"
                src="https://youtube.com/embed/videoseries?list={{ $GLOBALS['cfg']['osu']['urls']['youtube-tutorial-playlist'] }}"
            ></iframe>
            <div class="download-page__guide-content">
                <div class="download-page__steps">
                    <div class="download-page__step">
                        <span class="download-page__step-number">1</span>
                        <div class="download-page__text download-page__text--title">
                            {{ osu_trans("home.download.steps.download.title") }}
                        </div>
                        <div class="download-page__text download-page__text--description">
                            {{ osu_trans("home.download.steps.download.description") }}
                        </div>
                    </div>
                    <div class="download-page__step">
                        <span class="download-page__step-number">2</span>
                        <div class="download-page__text download-page__text--title">
                            {{ osu_trans('home.download.steps.register.title') }}
                        </div>
                        <div class="download-page__text download-page__text--description">
                            {{ osu_trans('home.download.steps.register.description') }}
                        </div>
                    </div>
                    <div class="download-page__step">
                        <span class="download-page__step-number">3</span>
                        <div class="download-page__text download-page__text--title">
                            {{ osu_trans("home.download.steps.beatmaps.title") }}
                        </div>
                        <div class="download-page__text download-page__text--description">
                            {!! osu_trans('home.download.steps.beatmaps.description._', [
                                'browse' => link_to(
                                    route('beatmapsets.index'),
                                    osu_trans('home.download.steps.beatmaps.description.browse')
                                )
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
