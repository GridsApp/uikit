<?php

namespace twa\uikit\Classes\ColumnOperationTypes;

use Illuminate\Support\Facades\DB;

class GroupConcat extends DefaultOperationType
{

    
    public function get(){

        $arg = func_get_arg(0);
        
        $separator = $this->attributes['separator'] ?? ', ';

        return DB::raw("GROUP_CONCAT($arg SEPARATOR $separator) as $this->alias");
    }


}
