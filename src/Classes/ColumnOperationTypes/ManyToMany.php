<?php

namespace twa\uikit\Classes\ColumnOperationTypes;

use Illuminate\Support\Facades\DB;

class ManyToMany extends DefaultOperationType
{

    
    public function get(){

        $arg = func_get_arg(0);
        $relation = collect($this->relations)->first();


  
        $table = $relation['table'];
        $field = $relation['field'];

        if($this->attributes['group_by']){
            return null;
        }

        return DB::raw('(SELECT JSON_ARRAYAGG('.$table.'.'.$field.') FROM JSON_TABLE('.$arg.', "$[*]" COLUMNS (json TEXT PATH "$")) AS jt JOIN '.$table.' ON CAST(jt.json AS UNSIGNED) = '.$table.'.id) AS '.$this->alias);
    }


}
