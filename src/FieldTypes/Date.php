<?php

namespace twa\uikit\FieldTypes;





class Date extends FieldType
{

    public function component()
    {
        return "elements.date";
    }


    public function filterType(){

        return \twa\uikit\Classes\FilterTypes\Date::class;
    }



    public function db(&$table){
        $table->date($this->field['name'])->nullable();
    }
    public function initalValue($data)
    {

        $default = null;

        if($this->field['default'] ?? null){
            $default = $this->field['default'] ;
        }



        if(isset($data->{$this->field['name']})){
            return  now()->parse($data->{$this->field['name']})->format('Y-m-d');
        }

        return $default;

    }

    public function display($data){


        if(!(isset($data[$this->field['name']]) && $data[$this->field['name']])){
            return null;
        }


        if(isset($this->field['format'])){
            return now()->parse($data[$this->field['name']])->format($this->field['format']);
        }

        return now()->parse($data[$this->field['name']])->diffForHumans();



    }

}
