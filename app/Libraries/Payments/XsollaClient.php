<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace App\Libraries\Payments;

use GuzzleHttp\Client;

class XsollaClient
{
    private string $merchantId;
    private Client $guzzle;

    public function __construct()
    {
        $this->merchantId = $GLOBALS['cfg']['payments']['xsolla']['merchant_id'];

        $config = [
            'auth' => [$this->merchantId, $GLOBALS['cfg']['payments']['xsolla']['api_key'], 'Basic'],
            'base_uri' => 'https://api.xsolla.com',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'xsolla-sdk-php/v4.3.2 curl/'.curl_version()['version'].' PHP/'.PHP_VERSION,
            ],
        ];

        $this->guzzle = new Client($config);
    }

    public function getTokenResponse(array $data)
    {
        $uri = "/merchant/v2/merchants/{$this->merchantId}/token";
        $response = $this->guzzle
            ->request('POST', $uri, ['json' => $data])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}
