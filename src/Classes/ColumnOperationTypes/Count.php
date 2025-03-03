<?php

namespace twa\uikit\Classes\ColumnOperationTypes;

use Illuminate\Support\Facades\DB;

class Count extends ColumnOperationType
{

    public function get(){
  
        return DB::raw("COUNT($this->table.$this->field) as $this->alias");
    }


    

}
