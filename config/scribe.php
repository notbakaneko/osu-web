<?php

use Knuckles\Scribe\Config;
use Knuckles\Scribe\Extracting\Strategies;

return [

    /*
     * The HTML <title> for the generated documentation. If this is empty, Scribe will infer it from $GLOBALS['cfg']['app']['name'].
     */
    'title' => null,

    /*
     * A short description of your API. Will be included in the docs webpage, Postman collection and OpenAPI spec.
     */
    'description' => '',


    /*
     * Tell Scribe what routes to generate documentation for.
     * Each group contains rules defining which routes should be included ('match', 'include' and 'exclude' sections)
     * and settings which should be applied to them ('apply' section).
     */
    'routes' => [
        [
            /*
             * Specify conditions to determine what routes will be a part of this group.
             * A route must fulfill ALL conditions to be included.
             */
            'match' => [
                /*
                 * Match only routes whose domains match this pattern (use * as a wildcard to match any characters). Example: 'api.*'.
                 */
                'domains' => ['*'],

                /*
                 * Match only routes whose paths match this pattern (use * as a wildcard to match any characters). Example: 'users/*'.
                 */
                'prefixes' => ['api/v2/*'],
            ],

            /*
             * Include these routes even if they did not match the rules above.
             * The route can be referenced by name or path here. Wildcards are supported.
             */
            'include' => [
                // 'users.index', 'healthcheck*'
            ],

            /*
             * Exclude these routes even if they matched the rules above.
             * The route can be referenced by name or path here. Wildcards are supported.
             */
            'exclude' => [
                // '/health', 'admin.*'
            ],
        ],
    ],

    /*
     * The type of documentation output to generate.
     * - "static" will generate a static HTMl page in the /public/docs folder,
     * - "laravel" will generate the documentation as a Blade view, so you can add routing and authentication.
     */
    'type' => 'static',

    /*
     * Settings for `static` type output.
     */
    'static' => [
        /*
         * HTML documentation, assets and Postman collection will be generated to this folder.
         * Source Markdown will still be in resources/docs.
         */
        'output_path' => 'public/docs',
    ],

    /*
     * Settings for `laravel` type output.
     */
    'laravel' => [
        /*
         * Whether to automatically create a docs endpoint for you to view your generated docs.
         * If this is false, you can still set up routing manually.
         */
        'add_routes' => true,

        /*
         * URL path to use for the docs endpoint (if `add_routes` is true).
         * By default, `/docs` opens the HTML page, `/docs.postman` opens the Postman collection, and `/docs.openapi` the OpenAPI spec.
         */
        'docs_url' => '/docs',

        /*
         * Middleware to attach to the docs endpoint (if `add_routes` is true).
         */
        'middleware' => [],
        /*
         * Directory within `public` in which to store CSS and JS assets.
         * By default, assets are stored in `public/vendor/scribe`.
         * If set, assets will be stored in `public/{{assets_directory}}`
         */
        'assets_directory' => null,
    ],

    /*
     * How is your API authenticated? This information will be used in the displayed docs, generated examples and response calls.
     */
    'auth' => [
        /*
         * Set this to true if any endpoints in your API use authentication.
         */
        'enabled' => true,

        /*
         * Set this to true if your API should be authenticated by default. If so, you must also set `enabled` (above) to true.
         * You can then use @unauthenticated or @authenticated on individual endpoints to change their status from the default.
         */
        'default' => false,

        /*
         * Where is the auth value meant to be sent in a request?
         * Options: query, body, basic, bearer, header (for custom header)
         */
        'in' => 'bearer',

        /*
         * The name of the auth parameter (eg token, key, apiKey) or header (eg Authorization, Api-Key).
         */
        'name' => 'key',

        /*
         * The value of the parameter to be used by Scribe to authenticate response calls.
         * This will NOT be included in the generated documentation.
         * If this value is empty, Scribe will use a random value.
         */
        'use_value' => env('SCRIBE_AUTH_KEY'),

        /*
         * Placeholder your users will see for the auth parameter in the example requests.
         * Set this to null if you want Scribe to use a random value as placeholder instead.
         */
        'placeholder' => '{YOUR_AUTH_KEY}',

        /*
         * Any extra authentication-related info for your users. For instance, you can describe how to find or generate their auth credentials.
         * Markdown and HTML are supported.
         */
        'extra_info' => null,
    ],

    /*
     * Text to place in the "Introduction" section, right after the `description`. Markdown and HTML are supported.
     */
    'intro_text' => <<<INTRO
This documentation aims to provide all the information you need to work with our API.

<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
INTRO
    ,

    /*
     * Example requests for each endpoint will be shown in each of these languages.
     * Supported options are: bash, javascript, php, python
     * To add a language of your own, see https://scribe.readthedocs.io/en/latest/customization.html
     *
     */
    'example_languages' => [
        'bash',
        'javascript',
    ],

    /*
     * The base URL to be used in examples. If this is empty, Scribe will use the value of $GLOBALS['cfg']['app']['url'].
     */
    'base_url' => null,

    /*
     * Generate a Postman collection (v2.1.0) in addition to HTML docs.
     * For 'static' docs, the collection will be generated to public/docs/collection.json.
     * For 'laravel' docs, it will be generated to storage/app/scribe/collection.json.
     * Setting `laravel.add_routes` to true (above) will also add a route for the collection.
     */
    'postman' => [
        'enabled' => true,

        /*
         * Manually override some generated content in the spec. Dot notation is supported.
         */
        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    /*
     * Generate an OpenAPI spec (v3.0.1) in addition to docs webpage.
     * For 'static' docs, the collection will be generated to public/docs/openapi.yaml.
     * For 'laravel' docs, it will be generated to storage/app/scribe/openapi.yaml.
     * Setting `laravel.add_routes` to true (above) will also add a route for the spec.
     */
    'openapi' => [
        'enabled' => true,

        /*
         * Manually override some generated content in the spec. Dot notation is supported.
         */
        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    /*
     * Custom logo path. This will be used as the value of the src attribute for the <img> tag,
     * so make sure it points to a public URL or path accessible from your web server. For best results, the image width should be 230px.
     * Set this to false to not use a logo.
     *
     * For example, if your logo is in public/img:
     * - 'logo' => '../img/logo.png' // for `static` type (output folder is public/docs)
     * - 'logo' => 'img/logo.png' // for `laravel` type
     *
     */
    // for osu theme it's specified directly in the css instead
    'logo' => null,

    /**
     * The strategies Scribe will use to extract information about your routes at each stage.
     * If you write or install a custom strategy, add it here.
     */
    'strategies' => [
        'metadata' => [
            ...Config\Defaults::METADATA_STRATEGIES,
        ],
        'urlParameters' => [
            ...Config\Defaults::URL_PARAMETERS_STRATEGIES,
        ],
        'queryParameters' => [
            ...Config\Defaults::QUERY_PARAMETERS_STRATEGIES,
            App\Docs\Strategies\UsesCursor::class,
        ],
        'headers' => [
            ...Config\Defaults::HEADERS_STRATEGIES,
            Strategies\StaticData::withSettings(data: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]),
        ],
        'bodyParameters' => [
            ...Config\Defaults::BODY_PARAMETERS_STRATEGIES,
        ],
        'responses' => [
            // Limit to explicitly documented responses
            Strategies\Responses\UseResponseAttributes::class,
            Strategies\Responses\UseResponseTag::class,
            Strategies\Responses\UseResponseFileTag::class,
        ],
        'responseFields' => [
            ...Config\Defaults::RESPONSE_FIELDS_STRATEGIES,
        ],
    ],

    /*
     * Configure how responses are transformed using @transformer and @transformerCollection (requires league/fractal package)
     */
    'fractal' => [
        /* If you are using a custom serializer with league/fractal, you can specify it here.
         * Leave as null to use no serializer or return simple JSON.
         */
        'serializer' => null,
    ],

    /*
     * [Advanced] If you would like to customize how routes are matched beyond the route configuration you may
     * declare your own implementation of RouteMatcherInterface
     *
     */
    'routeMatcher' => \Knuckles\Scribe\Matching\RouteMatcher::class,

    /**
     * For response calls, api resource responses and transformer responses, Scribe will try to start database transactions, so no changes are persisted to your database.
     * Tell Scribe which connections should be transacted here. If you only use the default db connection, you can leave this as is.
     */
    'database_connections_to_transact' => [],
    'theme' => 'osu',
    'try_it_out' => [
        /**
         * Add a Try It Out button to your endpoints so consumers can test endpoints right from their browser.
         * Don't forget to enable CORS headers for your endpoints.
         */
        'enabled' => false,
        /**
         * The base URL for the API tester to use (for example, you can set this to your staging URL).
         * Leave as null to use the current app URL (config(app.url)).
         */
        'base_url' => null,
        /**
         * Fetch a CSRF token before each request, and add it as an X-XSRF-TOKEN header. Needed if you're using Laravel Sanctum.
         */
        'use_csrf' => false,
        /**
         * The URL to fetch the CSRF token from (if `use_csrf` is true).
         */
        'csrf_url' => '/sanctum/csrf-cookie',
    ],
    'groups' => [
        /*
         * Endpoints which don't have a @group will be placed in this default group.
         */
        'default' => 'Undocumented',
        /*
         * By default, Scribe will sort groups alphabetically, and endpoints in the order their routes are defined.
         * You can override this by listing the groups, subgroups and endpoints here in the order you want them.
         *
         * Any groups, subgroups or endpoints you don't list here will be added as usual after the ones here.
         * If an endpoint/subgroup is listed under a group it doesn't belong in, it will be ignored.
         * Note: you must include the initial '/' when writing an endpoint.
         */
        'order' => [],
    ],
    /**
     * Customize the "Last updated" value displayed in the docs by specifying tokens and formats.
     * Examples:
     * - {date:F j Y} => March 28, 2022
     * - {git:short} => Short hash of the last Git commit
     *
     * Available tokens are `{date:<format>}` and `{git:<format>}`.
     * The format you pass to `date` will be passed to PhP's `date()` function.
     * The format you pass to `git` can be either "short" or "long".
     */
    'last_updated' => 'Last updated: {date:F j, Y}',
    'examples' => [
        /*
         * If you would like the package to generate the same example values for parameters on each run,
         * set this to any number (eg. 1234)
         */
        'faker_seed' => null,
        /*
         * With API resources and transformers, Scribe tries to generate example models to use in your API responses.
         * By default, Scribe will try the model's factory, and if that fails, try fetching the first from the database.
         * You can reorder or remove strategies here.
         */
        'models_source' => ['factoryCreate', 'factoryMake', 'databaseFirst'],
    ],
];
