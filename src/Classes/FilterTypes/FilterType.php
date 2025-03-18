<?php

namespace twa\uikit\Classes\FilterTypes;

use Illuminate\Support\Facades\DB;

class FilterType
{

    // public $filter;
    public $field_type;

    // public function __construct($filter)
    // {
    //     $this->filter = $filter;
    // }

    public function options(){
        return [];
    }

    public function handle(&$rows , &$joins , $columns , $table ,  $filter , $filter_value){

        $value1 = $filter_value['value1'];

        $value2 = $filter_value['value2'];

        switch($filter['db_type']){
            case "BOOLEAN" : 
      
                $value1 = (string) $value1;
                $value1 = $value1 == "true" || $value1 == "1" ? 1 : 0; 
      

                break;

                default:

                if(!$value1 || $value1 == "null"){
                    return;
                }
            
        }
    
        switch ($filter['relationship']) {


            case 'manyToMany':
         
                $column = "$table.".$filter['foreign_key'];

                $temp_rows =  DB::table($filter['table']);

                $this->filter($temp_rows, $filter_value['option'], $filter['column'], $value1 , $value2);
    
                $temp_ids = $temp_rows->pluck('id');
    
                $rows->where(function ($q) use ($temp_ids, $column) {
              
                    if(count($temp_ids) == 0){
                            $q->where($column , uniqid());
                    }else{
                        foreach ($temp_ids as $temp_id) {
                            $q->orWhere(function ($q1) use ($column, $temp_id) {
                                $q1->orWhere($column, 'LIKE', '%"' . $temp_id . '"%');
                                $q1->orWhere($column, 'LIKE', "%'" . $temp_id . "'%");
                            });
                        }
                    }
                   
                });

                break;

                case "hasMany":



                    if(!collect($columns)->where('alias' ,$filter['name'])->first()){
                        break;
                    }
       
            
                    $column = $filter['name'];
   
                    $this->filter($rows, $filter_value['option'], $column, $value1 , $value2 , 'having');
                  

                    break;


            case "belongsTo":
            
                $column = $filter['table'].".".$filter['column'];

                $joins[] = [$filter['table'],  "$table." . $filter['foreign_key'],  $filter['table'] . ".id"];


                $this->filter($rows, $filter_value['option'], $column, $value1 , $value2);

                break;
            
            default:
            // dd($table,$filter['column']);

                $column = "$table.".$filter['column'];
                $this->filter($rows, $filter_value['option'], $column, $value1 , $value2);

                break;
        }
    }


    public function filter(&$rows , $type , $column , $value1 , $value2 = null , $condition = "where"){



       
        switch ($type) {
            case 'equals':

                if($condition == "having"){
                    $rows->having($column, $value1);
                }else{
                    $rows->where($column, $value1);
                }
               
                break;
            case 'contains':

                if($condition == "having"){

                }else{
                    $rows->where($column, 'like', '%' . $value1 . '%');
                }

                break;

            case 'starts_with':
                if($condition == "having"){

                }else{
                $rows->where($column, 'like', $value1 . '%');
                }
                break;

            case 'is_greater':
                if($condition == "having"){
                    $rows->having($column, '>', $value1);
                }else{
                $rows->where($column, '>', $value1);
                }
                break;

            case 'is_greater_or_equal':
                if($condition == "having"){
                    $rows->having($column, '>=', $value1);
                }else{
                $rows->where($column, '>=', $value1);
                }
                break;

            case 'is_less':
                if($condition == "having"){
                    $rows->having($column, '<', $value1);
                }else{
                $rows->where($column, '<', $value1);
                }
                break;

            case 'is_less_or_equal':
                if($condition == "having"){
                    $rows->having($column, '<=', $value1);
                }else{
                $rows->where($column, '<=', $value1);
                }
                break;


            case 'between':
               
                if($condition == "having"){
                    $rows->having($column, '>=', $value1)->having($column, '<=', $value1);
                }else{
                if($value2){
                    $rows->whereBetween($column, [$value1 , $value2]);
                }else{
                    $rows->where($column, '>=', $value1);
                }
            }
               
            break;

               default:

          
               if($condition == "having"){
                $rows->having($column, $value1);
               }else{
                    $rows->where($column, $value1);
                }
                    break;
        }

    }

}
