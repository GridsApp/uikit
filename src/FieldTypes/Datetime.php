<?php

namespace twa\uikit\FieldTypes;




class Datetime extends FieldType
{

    public function component()
    {
        return "elements.datetime";
    }



    public function db(&$table){
        $table->timestamp($this->field['name'])->nullable();
    }
    public function initalValue($data)
    {

        $default = null;

        if($this->field['default'] ?? null){
            $default = $this->field['default'] ;
        }

       

        if(isset($data->{$this->field['name']})){
           
            return  now()->parse($data->{$this->field['name']})->format('Y-m-d h:i:s');
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

        return now()->parse($data[$this->field['name']]);



    }

}
