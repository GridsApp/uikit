<?php

namespace twa\uikit\FieldTypes;




class Editor extends FieldType
{

    public function component()
    {
        return "elements.editor";
    }


    public function db(&$table){
        $table->longtext($this->field['name'])->nullable();
    }


    // public function display($data)
    // {
       
    //     return null;
    // }
}
