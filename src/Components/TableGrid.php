<?php

namespace twa\uikit\Components;


use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Database\Query\Expression;
use twa\uikit\Traits\ToastTrait;

class TableGrid extends Component
{
    use WithPagination, ToastTrait;

    public $table_operations = [];
    public $grid_rules = [];
    public $columns = null;
    public $row_operations = [];

    public $sorting_column;
    public $sorting_direction;

    public $table = null;
    public $slug = null;
    public $title = "";
    public $filters = [];

    public $conditions = [];
    public $emptyFilter = [];
    public $filter = [];
    public $enabledFilterCount = 0;

    protected $queryString = [
        'filter' => ['except' => ''],
        'sorting_column' => ['except' => ''],
        'sorting_direction' => ['except' => '']
    ];

    public function mount(){
        
        
       $this->filters =  $this->table["filters"];
       $this->columns =  $this->table["columns"];
       $this->table_operations = $this->table['table_operations'];
      
  
       $updated_filters = [];

        foreach ($this->filters as $filter) {

            $class = new ($filter['type'])($filter);

            $options = $class->options();

            $default_option = collect($options)->where('active', true)->first()['value'] ?? null;

            $operand_options_field = count($options) > 0 ? [
                'id' => uniqid(),
                'livewire' => [
                    'wire:model' => 'filter.{name}.option',
                ],
                'type' => \twa\uikit\FieldTypes\Select::class,
                'label' =>  $filter['label'],
                'placeholder' => 'Select option',
                'name' => $filter['name'],
                'multiple' => false,
                'visible_selections' => 3,
                'query_limit' => 50,
                'options' => [
                    'type' => 'static',
                    'list' => $options,
                ],
                'container' => 'col-span-12',
                'events' => [
                    '@change' => 'optionChanged',
                ]
            ] : null;


            $field1 = [
                'id' => uniqid(),
                'livewire' => [
                    'wire:model' => 'filter.{name}.value1',
                ],
                'type' => $class->field_type,
                'label' => $filter['label'],
                'placeholder' => 'Select option',
                'name' => $filter['name'],
                'multiple' => false,
                'visible_selections' => 3,
                'query_limit' => 50,

            ];

            $field2 = [
                'id' => uniqid(),
                'livewire' => [
                    'wire:model' => 'filter.{name}.value2',
                ],
                'type' => $class->field_type,
                'label' => '',
                'placeholder' => 'Select option',
                'name' => $filter['name'],
                'multiple' => false,
                'visible_selections' => 3,
                'query_limit' => 50,
                'container' => 'col-span-12'
            ];

            if (is_a($class->field_type, \twa\uikit\FieldTypes\Select::class, true)) {
                $field1['options'] = [
                    'type' => 'query',
                    'table' => $filter['table'],
                    'field' => $filter['column']
                ];
            }


            if (($filter['db_type'] ?? "") == "BOOLEAN") {
                $this->filter[$filter['name']]['value1'] = (string) $this->filter[$filter['name']]['value1'];
                $this->filter[$filter['name']]['value1'] = $this->filter[$filter['name']]['value1'] == "true" || $this->filter[$filter['name']]['value1'] == "1" ? true : false;
            }



            $enabled = (string) ($this->filter[$filter['name']]['enabled'] ?? false);
            $enabled = $enabled == "true" || $enabled == "1" ? true : false;


            $this->filter[$filter['name']] = [
                'enabled' => $enabled,
                'option' => $this->filter[$filter['name']]['option'] ?? $default_option,
                'value1' => $this->filter[$filter['name']]['value1'] ?? null,
                'value2' => $this->filter[$filter['name']]['value2'] ?? null
            ];


            $this->emptyFilter[$filter['name']] = [
                'enabled' => false,
                'option' => $default_option,
                'value1' =>  null,
                'value2' =>  null
            ];

            $updated_filters[] = [
                'name' => $filter['name'],
                'column' =>  $filter['column'],
                'type' => $filter['type'],
                'relationship' => $filter['relationship'] ?? null,
                'table' => $filter['table'] ?? null,
                'foreign_key' => $filter['foreign_key'] ?? null,
                'label' => $filter['label'] ?? null,
                'db_type' => $filter['db_type'] ?? null,
                'operand' => $operand_options_field,
                'field1' => $field1,
                'field2' => $field2,
            ];
        }


        $this->filters = $updated_filters;
        $this->enabledFilterCount = collect($this->filter)
            ->filter(fn($filter) => $filter['enabled'])
            ->count();



   
    

    }

    public function setFilters($rows, &$joins, $selects)
    {
        foreach ($this->filter as $column => $filter) {

            if (!$filter['enabled']) {
                continue;
            }


       
            $current_filter = collect($this->filters)->where('name', $column)->first();

            if (!$current_filter) {
                continue;
            }
 
            $current_filter['relationship'] = $current_filter['relationship'] ?? null;
   

            if (!isset($filter['value1'])) {
                $filter['value1'] = null;
            }        

            $filter['value1'] = (string) $filter['value1'];
     
            // dd($current_filter['type']);
            try {
                (new ($current_filter['type']))->handle($rows, $joins, $this->columns, $this->table, $current_filter, $filter);
                dd("hereeee"); 
            } catch (\Throwable $e) {
                dd($e->getMessage(), $e->getTraceAsString());
            }
        }

        return $rows;
    }
 
    public function clearFilters()
    {
        $this->reset(['filter']);
        $this->filter = [...$this->emptyFilter];

        $this->dispatch('clear-filters');
        $this->enabledFilterCount = 0;
        // $this->resetPage();
    }

    public function applyFilters()
    {

     
        $this->enabledFilterCount = collect($this->filter)
            ->filter(fn($filter) => $filter['enabled'])
            ->count();
        $this->render();
        $this->dispatch('apply-filters');
    }

    public function clearSorting()
    {
        $this->sorting_column = '';
        $this->sorting_direction = '';
        $this->render();
    }

    public function setSorting($column)
    {
        if ($this->sorting_column === $column) {

            $this->sorting_direction = $this->sorting_direction === 'asc' ? 'desc' : 'asc';
        } else {

            $this->sorting_column = $column;
            $this->sorting_direction = 'asc';
        }
    }

    public function buildConcat($names, $table, $alias, $seperator)
    {
        $select = "CONCAT(";
        foreach ($names as $i => $name) {
            if ($i == 0) {
                $select .= "$table.$name";
            } else {
                $select .= ", '$seperator' , $table.$name";
            }
        }
        $select .= ") AS " . $alias;

        return $select;
    }


    public function render()
    {


        $selects = [];
        $joins = [];
        $selects = [];

        foreach ($this->table["columns"] as $column) {
            $column["parameters"] = collect($column["parameters"])->map(fn($item) => str($item)->contains(".") ? $item : $this->table['name'].".".$item);
            $relations =  collect($this->table['relationships'])->where('alias' , $column['alias']);
            $column["attributes"] = ['group_by' => $this->table["group"]];
            $selects [] = (new $column['operator']($column['alias'] , $column["attributes"] , $relations))->get(...$column["parameters"]); 
        }

        $this->table['selects'] = collect($this->table['selects'])->map(fn($item) => $item .' AS '.str($item)->explode(".")[1])->toArray();

  

        $selects = [...$this->table['selects'] , ...$selects];

        $selects = collect($selects)->unique()->filter()->values()->toArray();

    
       
        // dd($this->table['selects']);
       
        $rows = DB::table($this->table['name'])->select($selects)->whereNull($this->table['name'].".deleted_at");

        foreach ($this->table["conditions"] ?? [] as $condition) {
            apply_condition($rows , $condition);
        }



        $rows = $this->setFilters($rows, $joins, $selects);
   
    
        foreach ($this->table["relationships"] as $relation_table => $relationship) {

            if($relationship['type'] == 'polyBelongsTo'){

                $rows->leftJoin($relationship["table"], function ($join) use ($relationship) {
                    $join->on($this->table['name'].'.'.$relationship['foreign_key'], '=', $relationship["table"].'.id')
                         ->where($this->table['name'].'.'.$relationship['foreign_type'], '=',  $relationship["value"])
                         ->whereNull($relationship["table"].'.deleted_at');


                       

                         foreach ($relationship['conditions'] ?? [] as $condition) {
                            apply_condition($join , $condition);
                        }

                });

                continue;
            }
   




            if(!in_array($relationship['type'] , ['hasMany' , 'belongsTo'])){
                continue;
            }





           
            if($relationship['left_join']){

           
            $rows->leftJoin($relation_table, function ($q) use ($relation_table , $relationship) {

                $current_table = $this->table['name'];

                switch($relationship['type']){
                    case "hasMany" :

                        $param1 =  "$current_table.id";
                        $param2 = $relation_table . "." . $relationship['foreign_key'];

                        break;
                    
                    case "belongsTo":

                        $param1 = "$current_table." . $relationship['foreign_key'];
                        $param2 = $relation_table . ".id";
                        
                        break;
                }

                $q->on($param1, '=', $param2)->whereNull($relation_table. ".deleted_at");


               
         
                
                foreach ($relationship['conditions'] ?? [] as $condition) {
                    apply_condition($q , $condition);
                }

            });
            }else{
                $rows->join($relation_table, function ($q) use ($relation_table , $relationship) {

                    $current_table = $this->table['name'];
    
                    switch($relationship['type']){
                        case "hasMany" :
    
                            $param1 =  "$current_table.id";
                            $param2 = $relation_table . "." . $relationship['foreign_key'];
    
                            break;
                        
                        case "belongsTo":
    
                            $param1 = "$current_table." . $relationship['foreign_key'];
                            $param2 = $relation_table . ".id";
                            
                            break;
                    }
    
                    $q->on($param1, '=', $param2)->whereNull($relation_table. ".deleted_at");
    
                    
                    
                    foreach ($relationship['conditions'] ?? [] as $condition) {
                        apply_condition($q , $condition);
                    }
    
                });
            }
        }

        if($this->table["group"]){

            if(!is_array($this->table["group"])){
                $this->table["group"] = [$this->table["group"]];
            }

            $rows->groupBy(...$this->table["group"]);
        }

     

        $available_sorting_columns = collect($selects)->map(function ($item) {

            if (!is_string($item)) {
                return null;
            }

            $array = preg_split("/\sas\s/i",  $item);

            if (isset($array[1])) {
                return trim($array[1]);
            }

            return trim($array[0]);
        })->toArray();

      
        if (
            !empty($this->sorting_column) &&
            !empty($this->sorting_direction) &&
            in_array(strtolower(trim($this->sorting_direction)), ['asc', 'desc']) &&
            in_array(strtolower($this->sorting_column), $available_sorting_columns)

        ) {

            $rows = $rows->orderBy($this->sorting_column, $this->sorting_direction);
        }


        
        // dd($rows);

        $rows = $rows->paginate(20)->through(function ($row) {

            
            $row = (array) $row;

            // foreach ($this->table["callbacks"] as $callback) {
            //     if (!is_array($callback['name'])) {
            //         $cols = [$callback['name']];
            //     } else {
            //         $cols = $callback['name'];
            //     }

            //     $row[$callback['alias']] = ((new $callback['callback'])($row, ...$cols));
            // }

            return (object) $row;
        });



 


        return view('UIKitView::components.table-grid', ['rows' => $rows]);
    }
    // public function handleDelete($selected)
    // {


    //     if ($this->entity['gridRules'] ?? null) {

    //         $deleteRule = collect($this->entity['gridRules'])->where('operation', 'delete')->first();

    //         if ($deleteRule) {

    //             $ids =   DB::table($this->entity['table'])
    //                 ->where($deleteRule['condition']['field'], $deleteRule['condition']['operand'], $deleteRule['condition']['value'])
    //                 ->pluck('id');

    //             $intersectionFound = collect($selected)->intersect($ids)->count() > 0;

    //             if ($intersectionFound) {
    //                 $this->render();
    //                 $this->sendError("Not Deleted", "You don't have permission to delete this record");
    //                 return response()->json(["result" => true], 200);
    //             }
    //         }
    //     }


    //     // dd($selected);

    //     try {

    //         DB::beginTransaction();

    //         if (!is_array($selected)) {
    //             $selected = json_decode($selected, 1);
    //         }

    //         if (!is_array($selected)) {
    //             return;
    //         }

    //         DB::table($this->entity['table'])->whereIn('id', $selected)->update([
    //             'deleted_at' =>  now()
    //         ]);


    //         DB::commit();

    //         $this->render();

    //         $this->sendSuccess("Deleted", "Record sucessfully deleted");

    //         return response()->json(["result" => true], 200);
    //     } catch (\Throwable $th) {

    //         DB::rollBack();

    //         $this->render();

    //         $this->sendError("Not Deleted", "Record was not deleted");

    //         return response()->json(["result" => false], 200);
    //     }
    // }



    public function handleDelete($selected)
    {

        if (!is_array($selected) || empty($selected)) {
            $this->sendError("Error", "Invalid selection.");
           
        }    
        try {
            DB::table($this->table['name'])->whereIn('id', $selected)->update([
                'deleted_at' =>  now()
            ]);

        
            $this->sendSuccess("Deleted", "Record(s) successfully deleted.");
            $this->render(); 
    
        } catch (\Throwable $e) {
           
            $this->sendError("Error", "Could not delete records.");
        }
    }
    
}
