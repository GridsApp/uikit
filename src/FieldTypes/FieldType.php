<?php

namespace twa\uikit\FieldTypes;



class FieldType
{

    public $field;

    public function __construct($field)
    {
        $this->field = $field;
    }

    public function columnType(){

        return \twa\uikit\Classes\ColumnTypes\DefaultType::class;
    }

    public function operationType(){
        return \twa\uikit\Classes\ColumnOperationTypes\DefaultOperationType::class;
    }

    public function db(&$table){
        $table->string($this->field['name'], 255)->nullable();
    }

    public function component()
    {
        return null;
    }

    public function initalValue($data)
    {
        $default = null;

        if($this->field['default'] ?? null){
            $default =  $this->field['default'] ;
        }

        return $data->{$this->field['name']} ?? $default;
    }

    public function value($form)
    {
        return $form[$this->field['name']] ?? null;
    }

    public function display($data)
    {
        return $data[$this->field['name']] ?? null;
    }


}
