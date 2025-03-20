<?php

namespace twa\uikit\FieldTypes;




class Toggle extends FieldType
{

    public function component()
    {
        return "elements.toggle";
    }

    public function db(&$table){

        $table->tinyInteger($this->field['name'])->default(0)->nullable();

    }

    public function initalValue($data)
    {

        $default = null;

        if($this->field['default'] ?? null){
            $default = $this->field['default'] ? true : false;
        }

        if(isset($data->{$this->field['name']})){
            return  $data->{$this->field['name']}? true : $default;
        }

        return $default;

    }

    public function filterType(){

        return \twa\uikit\Classes\FilterTypes\Toggle::class;
    }


    public function display($data){
        if(!(isset($data[$this->field['name']]) && $data[$this->field['name']])){
            return '<i class="fa-regular fa-x"></i>';
        }

        return '<i class="fa-regular fa-check"></i>';

    }

}
