<?php

namespace twa\uikit\Classes\ColumnOperationTypes;

use Illuminate\Support\Facades\DB;

class DefaultOperationType
{

    public $alias;
    public $attributes;
    public $relations;

    public function __construct($alias , $attributes , $relations = [])
    {              

       $this->alias = $alias;
       $this->attributes = $attributes;
       $this->relations = $relations;

       

    }

    public function get(){
 
        $args = func_get_args();

        if(count($args) == 1){
            return $this->alias == $args[0] ? $this->alias : $args[0] ." AS ".$this->alias;
        }

        $separator = $this->attributes['separator'] ?? ' ';

        return DB::raw("CONCAT(".collect($args)->implode(",'".$separator."',").") as $this->alias");
    }
  
 
}
