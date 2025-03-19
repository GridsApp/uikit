<?php

namespace twa\uikit\FieldTypes;



class Textarea extends FieldType
{

    public function component()
    {
        return "elements.textarea";
    }

    

    public function db(&$table){
        $table->text($this->field['name'])->nullable();
    }
    public function display($data)
    {
        if (isset($data[$this->field['name']]) && $data[$this->field['name']]) {
            $content = $data[$this->field['name']];


            $limit = 15;


            if (strlen($content) > $limit) {
                return e(substr($content, 0, $limit)) . '...';
            }

            return e($content);
        }

        return null;
    }
}
