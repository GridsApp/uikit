<?php

namespace twa\uikit\Classes\ColumnCallbacks;


class ImageCallback 
{

   
    public function __invoke()
    {
        $args = func_get_args();

        $row = $args[0];
        $image = $args[1];



        return get_image($row->{$image});
    }
 
}
