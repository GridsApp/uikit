<?php

namespace twa\uikit\Components;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Database\Query\Expression;

class Table extends Component
{
    use WithPagination;
    public $table_operations = [];
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

    public function mount()
    {


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


            // if (($filter['relationship'] ?? '' == 'hasMany') && !collect($this->columns)->where('alias', $filter['column'])->first()) {
            //     continue;
            // }   
            // dd($filter);

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


            (new ($current_filter['type']))->handle($rows, $joins, $this->columns, $this->table, $current_filter, $filter);
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

    public function buildSelect($names, $table, $alias, $seperator)
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
        $related_tables = [];

        $selects = [];
        $joins = [];


        $callbacks = [];
        $group_by = false;
        $group_column = 'id';
        $group_table = '';

        foreach ($this->columns as $column) {


            if ($column['callback'] ?? null) {
                $callbacks[]  = $column;
            }

            $column['relationship'] = $column['relationship'] ?? null;
            // $group_by = false;

            switch ($column['relationship']) {
                case 'manyToMany':
                    $related_tables[] = $column;
                    $select = $this->table . '.' . $column['foreign_key'];

                    break;

                case 'belongsTo':

                    if (is_array($column['name'])) {
                        $select = DB::raw($this->buildSelect($column['name'], $column['table'],  $column['alias'], $column['separator'] ?? ' '));
                        $joins[] = [$column['table'],  "$this->table." . $column['foreign_key'],  $column['table'] . ".id"];
                    } else {
                        $select = $column['table'] . '.' . $column['name'];
                        //    dd("here");
                        if (isset($column['alias']) && $column['alias']) {
                            $select .= " AS " . $column['alias'];
                        }
                        $joins[] = [$column['table'], "$this->table." . $column['foreign_key'], $column['table'] . ".id"];
                    }

                    break;

                case 'hasMany':
                    $select = (new $column["operator"]($column['table'], $column['name'], $column['alias']))->get();
                    $joins[] = [$column['table'], "$this->table.id", $column['table'] . "." . $column['foreign_key'], $column['conditions'] ?? []];
                    $group_by = true;



                    $group_column = $column['group_column'] ?? 'id';
                    $group_table = $column['table'];
                    break;

                default:
                    if (isset($column['operator']) && isset($column['group_column'])) {

                        $select = DB::raw(strtoupper(class_basename($column['operator'])) . '(' . $this->table . '.' . $column['foreign_key'] . ') AS ' . $column['alias']);

                        // dd($select);

                        $group_by = true;
                        $group_column = $column['group_column'] ?? 'id';
                    } elseif (is_array($column['name']) && !isset($column['callback'])) {

                        $select = DB::raw($this->buildSelect($column['name'], $this->table,  $column['alias'], $column['separator'] ?? ' '));
                    } elseif (is_array($column['name']) && isset($column['callback'])) {

                        $ss = [];
                        foreach ($column['name'] as $n) {
                            $ss[]  =  $this->table . '.' . $n;
                        }
                        $selects = [...$selects, ...$ss];
                    } else {

                        $select = $this->table . '.' . $column['name'];

                        if (isset($column['alias']) && $column['alias'] && !isset($column['callback'])) {
                            $select .= " AS " . $column['alias'];
                        }
                    }

                    break;
            }

            $selects[] = $select;
        }



        $selects = collect($selects)->unique()->values()->toArray();



        $rows = DB::table($this->table)->whereNull("$this->table.deleted_at")
            ->select($selects);



        foreach ($this->conditions as $condition) {

            $condition['operand'] = $condition['operand'] ?? '=';

            switch ($condition['type']) {
                case 'having':
                    $rows->having($condition['column'], $condition['operand'], $condition['value']);
                    break;

                case 'whereIn':
                    $rows->whereIn($condition['column'], $condition['value']);
                    break;

                case 'whereNotIn':
                    $rows->whereNotIn($condition['column'], $condition['value']);
                    break;

                case 'like':
                    $rows->where($condition['column'], 'LIKE', '%' . $condition['value'] . '%');
                    break;


                default:
                    $rows->where($condition['column'], $condition['operand'], $condition['value']);
                    break;
            }
        }


        $rows = $this->setFilters($rows, $joins, $selects);

        $joins = collect($joins)->unique()->values()->toArray();




        foreach ($joins as $join) {
            $rows->leftJoin($join[0], function ($j) use ($join) {
                $j->on($join[1], '=', $join[2])
                    ->whereNull($join[0] . ".deleted_at");
                foreach ($join[3] ?? [] as $condition) {

                    $condition['operand'] =  $condition['operand'] ?? '=';

                    switch ($condition['type']) {
                        case 'where':
                            $j->where($join[0] . '.' . $condition['column'], $condition['operand'], $condition['value']);
                    }
                }
            });
        }


        // dd($rows);

        if ($group_by) {

            // dd("jere");
            // dd($group_table , $group_column);
            $rows->groupBy("$this->table.$group_column");
        }



        $tables = [];
        foreach ($related_tables as $related_table) {
            $tables[$related_table['table']] = DB::table($related_table['table'])->whereIn('id', (clone $rows)->get()->pluck($related_table['foreign_key'])->map(function ($item) {
                return json_decode($item);
            })->flatten(1)->toArray())->get()->keyBy('id')->toArray();
        }
        /*

        collect(json_decode($row->{$column['alias']}))->map(function($item) use ($casts) { 
                                        
                                                return $casts[$item]->name; 
                                            })->toArray()

        */
        // dd(request()->all());



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


        $rows = $rows->paginate(20)->through(function ($row) use ($related_tables, $tables, $callbacks) {
            $new_row = (array) $row;

            foreach ($callbacks as $callback) {
                if (!is_array($callback['name'])) {
                    $cols = [$callback['name']];
                } else {
                    $cols = $callback['name'];
                }

                $new_row[$callback['alias']] = ((new $callback['callback'])($row, ...$cols));
            }
            foreach ($related_tables as $related_table) {

                $current_table = $related_table['table'];
                $new_row[$related_table['alias']] = collect(json_decode($new_row[$related_table['foreign_key']]))->map(function ($item) use ($current_table, $tables, $related_table) {

                    $active_model = $tables[$current_table][$item];

                    $separator = $related_table['separator'] ?? ' ';
                    if (is_array($related_table['name'])) {
                        return collect($related_table['name'])->map(function ($item) use ($active_model) {
                            return $active_model->{$item};
                        })->implode($separator);
                    } else {
                        return $active_model->{$related_table['name']} ?? '';
                    }
                })->toArray();
            }

            return (object) $new_row;
        });


        return view('UIKitView::components.table', ['rows' => $rows]);
    }
}
