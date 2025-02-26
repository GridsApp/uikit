<?php

namespace twa\uikit\FieldTypes;


class Colorpicker extends FieldType
{

    public function component()
    {
        return "elements.colorpicker";
    }

    public function initalValue($data)
    {
        $default = '#000000';

        if($this->field['default'] ?? null){
            $default =  $this->field['default'] ;
        }

        return $data->{$this->field['name']} ?? $default;
    }


    public function display($data){
        if(!(isset($data[$this->field['name']]) && $data[$this->field['name']])){
            return null;
        }
        return "<div class='twa-table-td-color'><div class='td-color' style='background-color:".$data[$this->field['name']]."' ></div><div class='text'>".$data[$this->field['name']]."</div></div>";
    }
}
