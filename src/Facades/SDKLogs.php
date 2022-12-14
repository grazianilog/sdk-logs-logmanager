<?php

namespace LogManager\SdkLogs\Facades;

use Illuminate\Support\Facades\Facade;

class SDKLogs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SDKLogs';
    }
}