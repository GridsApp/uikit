<?php

namespace twa\uikit\Classes\ColumnOperationTypes;


class ColumnOperationType
{

    public $table;
    public $field;
    public $alias;

    public function __construct($table , $field , $alias)
    {
        $this->table = $table;
        $this->field = $field;
        $this->alias = $alias;
    }

    public function get(){
       return $this->field; 
    }
  
 
}
