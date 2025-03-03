<?php

namespace twa\uikit\Classes\ColumnOperationTypes;

use Illuminate\Support\Facades\DB;

class Sum extends ColumnOperationType {


    public function get(){

        // dd("here");
        return DB::raw("SUM($this->table.$this->field) as $this->alias");
    }

}
