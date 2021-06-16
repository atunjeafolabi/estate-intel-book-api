<?php

namespace App\Helpers;

interface Status
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;

    const message = [
        self::HTTP_OK => 'OK',
        self::HTTP_CREATED => 'Created',
        self::HTTP_NO_CONTENT => 'No Content',
    ];

    const SUCCESS = 'success';
}
