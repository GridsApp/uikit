<?php

namespace twa\uikit\Classes\ColumnOperationTypes;

use Illuminate\Support\Facades\DB;

class Count extends DefaultOperationType
{

    public function get(){
        
        $arg = func_get_arg(0);

        return DB::raw("COUNT($arg) as $this->alias");
    }

    

}
