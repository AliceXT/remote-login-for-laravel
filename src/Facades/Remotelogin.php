<?php
namespace AliceXT\Remotelogin\Facades;
use Illuminate\Support\Facades\Facade;
class Remotelogin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'remotelogin';
    }
}