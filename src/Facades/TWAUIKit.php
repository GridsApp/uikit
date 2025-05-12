<?php

namespace twa\uikit\Facades;

use Illuminate\Support\Facades\Facade;

class TWAUIKit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \twa\uikit\TWAUIKit::class;
    }
}
