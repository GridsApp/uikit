<?php

namespace twa\uikit\Classes\Table;

class TableData
{


    public $table;
    public $title;

    public $relationships = [];
    public $conditions = [];
    public $columns = [];
    public $table_operations = [];
    public $row_operations = [];
    public $group = null;
    public $filters = [];
    public $selects = [];


    public function __construct($title, $table)
    {

        $this->table = $table;
        $this->title = $title;
    }


    public function groupBy()
    {

        $columns = func_get_args();
        $columns = collect($columns)->flatten(1);

        $this->group =  $columns->map(function ($item) {
            return str($item)->contains(".") ? $item : $this->table . '.' . $item;
        })->toArray();

        return $this;
    }


    public function addFilter($label, $name, $column, $type , $attributes = [])
    {

        $column =  str($column)->contains(".") ? $column : $this->table . '.' . $column;

        $this->filters[] = [
            'name' => $name,
            'column' => $column,
            'label' => $label,
            'type' => $type,
            ...$attributes
        ];

        return $this;
    }


    public function selects(array $array)
    {

        $this->selects =  collect($array)->map(function ($item) {
            return str($item)->contains(".") ? $item : $this->table . '.' . $item;
        })->toArray();

        return $this;
    }

    public function addColumn($label, $name, $type = "\twa\uikit\Classes\ColumnTypes\DefaultType::class",   $operator = null, $parameters = [], $attributes = [], $callback = null)
    {
        


        $this->columns[] = [
            // 'name' => $fields,
            'alias' => $name,
            'label' => $label,
            'type' => $type,
            'operator' => $operator,
            'parameters' => $parameters,
            'attributes' => $attributes,
            'callabck' => $callback
        ];

        return $this;
    }


    public function addTableOperation($label, $link, $icon)
    {
        $this->table_operations[] = [
            'label' => $label,
            'link' => $link,
            'icon' => $icon,

        ];
        return $this;
    }

    public function addRowOperation($label, $link, $icon)
    {
        $this->row_operations[] = [
            'label' => $label,
            'link' => $link,
            'icon' => $icon,

        ];
        return $this;
    }






    public function addCondition($type, $column, $value = null, $operand = '=')
    {

        $this->conditions[] = [
            'type' => $type,
            'column' => $column,
            'operand' => $operand,
            'value' => $value
        ];

        return $this;
    }


    public function polyBelongsTo($table, $foreign_key, $foreign_type, $value, $field, $alias, $attributes = [])
    {


        $this->relationships[$table] = [
            'alias' => $alias,
            'type' => 'polyBelongsTo',
            'table' => $table,
            'foreign_key' => $foreign_key,
            'foreign_type' => $foreign_type,
            'value' => $value,
            'field' => $field,
            'attributes' => $attributes
        ];
        return $this;
    }

    public function belongsTo($table, $foreign_key, $left_join = true,  $attributes = [])
    {

        // dd($table ,$foreign_key);


        $this->relationships[$table] = [
            'type' => 'belongsTo',
            'left_join' => $left_join,
            'table' => $table,
            'foreign_key' => $foreign_key,
            'attributes' => $attributes
        ];
        // dd($this->relationships);

        return $this;
    }

    public function manyToMany($table, $foreign_key, $field, $alias, $attributes = [])
    {

        $this->relationships[$table] = [
            'alias' => $alias,
            'field' => $field,
            'type' => 'manyToMany',
            'table' => $table,
            'foreign_key' => $foreign_key,
            'attributes' => $attributes
        ];

        return $this;
    }

    public function hasMany($table, $foreign_key, $left_join = true, $attributes = [])
    {


        // dd($left_join);

        $this->relationships[$table] = [
            'left_join' => $left_join,
            'type' => 'hasMany',
            'foreign_key' => $foreign_key,
            'attributes' => $attributes
        ];



        return $this;
    }


    public function get()
    {


        $relation_conditions = [];

        $conditions = [];



        foreach ($this->conditions as $condition) {

            if (!str($condition['column'])->contains(".")) {
                $condition['column'] = $this->table . "." . $condition['column'];
            }

            $array = str($condition['column'])->explode('.');

            if ($array[0] != $this->table) {

                if (!isset($relation_conditions[$array[0]])) {
                    $relation_conditions[$array[0]] = [];
                }

                $relation_conditions[$array[0]][] = $condition;
            } else {

                $conditions[] = $condition;
            }
        }



        // dd($conditions);
        foreach ($relation_conditions as $key => $condition) {

            if (!isset($this->relationships[$key])) {
                continue;
            }


            $this->relationships[$key]['conditions'] = $condition;
        }


        // dd([

        //     'name' => $this->table,
        //     'title' => $this->title,
        //     'selects' => $this->selects,
        //     'relationships' => $this->relationships,
        //     'conditions' => $conditions,
        //     'group' => $this->group,
        //     'columns' => $this->columns,
        //     'filters' => $this->filters,
        //     'table_operations' => $this->table_operations

        // ]);

        return [

            'name' => $this->table,
            'title' => $this->title,
            'selects' => $this->selects,
            'relationships' => $this->relationships,
            'conditions' => $conditions,
            'group' => $this->group,
            'columns' => $this->columns,
            'filters' => $this->filters,
            'table_operations' => $this->table_operations,
            'row_operations' => $this->row_operations
       

        ];
    }
}
