<?php

namespace twa\uikit\Classes\ColumnOperationTypes;
use Illuminate\Support\Facades\DB;

class Polymorphic extends DefaultOperationType
{

    public function get(){

        $args = func_get_args();
        

        // dd($this->relations);

        $id = $args[0];
        $type = $args[1];
        $cases = [];

        foreach ($this->relations as $key => $relation) {

            $table = $relation['table'];
            $field = $relation['field'];
            $value = addslashes($relation['value']);



            $cases[] = "WHEN $type = '$value' THEN $table.$field";
            // dd($table);
        }

        $case = implode(" ", $cases);

       

        return DB::raw("CASE $case ELSE NULL END as $this->alias");
    //    return DB::raw("CASE 
    //     WHEN $type = 'post' THEN p.title 
    //     WHEN $type = 'video' THEN v.title 
    //     ELSE NULL 
    //  END as $this->alias");

        
        // return DB::raw("CONCAT($id ,' ' ,$type)   AS $this->alias");
    }
   

  
 
}
