<?php

/**
 * This file is part of the GeocoderLaravel library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Ivory\HttpAdapter\CurlHttpAdapter;
use Ivory\HttpAdapter\Guzzle6HttpAdapter;
use Geocoder\Provider\GoogleMaps;

return [
    'cache-duraction' => 999999999,
    'providers' => [
        GoogleMaps::class => [
            'en',
            'us',
            true,
            env('GOOGLE_MAPS_API_KEY'),
        ],
    ],
    'adapter'  => CurlHttpAdapter::class,
];
