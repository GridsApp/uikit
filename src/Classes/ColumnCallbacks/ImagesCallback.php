<?php

namespace twa\uikit\Classes\ColumnCallbacks;


class ImagesCallback 
{

   
    public function __invoke()
    {
        $args = func_get_args();

        $row = $args[0];
        $image = $args[1];

        $value = trim($row->{$image});

        $array = [];

        if(strlen($value) > 0 && $value[0] == '[' && $value[0] == ']'){
            try {
                $array = json_decode($value);
             } catch (\Throwable $th) {
                $array = [];
             }
        }elseif(strlen($value) > 0){
            $array = [$value];
        }else{
            $array = [];
        }
    
        return get_images($array);
    }
 
}
