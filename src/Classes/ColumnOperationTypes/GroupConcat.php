<?php

namespace twa\uikit\Classes\ColumnOperationTypes;

use Illuminate\Support\Facades\DB;

class GroupConcat extends ColumnOperationType
{

    public $separator;

    public function __construct($table , $field , $alias , $separator)
    {
        $this->table = $table;
        $this->field = $field;
        $this->alias = $alias;
        $this->separator = $separator;
    }
   
    public function get(){
        // return DB::raw("SUM($this->table.$this->field) as $this->field");

        $this->separator = ', ';

        return DB::raw("GROUP_CONCAT($this->table.$this->field SEPARATOR ', ') as $this->alias");
    }


}
