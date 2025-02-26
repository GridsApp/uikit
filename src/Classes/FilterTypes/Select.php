<?php

namespace twa\uikit\Classes\FilterTypes;


class Select extends FilterType
{
   
    public $field_type = \twa\uikit\FieldTypes\Select::class;

   
    public function handle(&$rows , &$joins , $columns ,$table ,  $filter , $filter_value){


        $column = "$table.".$filter['foreign_key'];
        $value1 = $filter_value['value1'];

        if($filter['db_type'] == 'ARRAY'){
            $rows->where(function($q) use ($column , $value1){
                $q->where($column, 'LIKE',  "%'".$value1."'%");
                $q->orWhere($column, 'LIKE',  '%"'.$value1.'"%');           
            });
        }else{
           
            $rows->where($column, $value1);
        }


    }

}
