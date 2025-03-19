<?php

namespace twa\uikit\FieldTypes;




class Select extends FieldType
{
    public function component()
    { 
        return "elements.select";
    }


    public function columnType()
    {
       
        if (isset($this->field['multiple']) &&  $this->field['multiple']) {
            return \twa\uikit\Classes\ColumnTypes\Tags::class;
        }
    
        return \twa\uikit\Classes\ColumnTypes\Tag::class;
    }

    public function operationType()
    {

        if (isset($this->field['multiple']) &&  $this->field['multiple']) {
            return \twa\uikit\Classes\ColumnOperationTypes\ManyToMany::class;
        }
    
        return \twa\uikit\Classes\ColumnOperationTypes\BelongsTo::class;
    }


    public function db(&$table)
    {  


        if ($this->field['options']['type'] ?? null == "static") {
            $table->string($this->field['name'])->nullable();
            return;
        }

        if (isset($this->field['multiple']) && $this->field['multiple']) {
            $table->text($this->field['name'])->nullable();
        } else {
            $table->foreignId($this->field['name'])->nullable();
        }
    }

    public function initalValue($data)
    {
        if ($this->field['default'] ?? null) {
            return $this->field['default'];
        }

        if (isset($this->field['multiple']) && $this->field['multiple']) {
            $default = [];

            if (!isset($data->{$this->field['name']})) {
                return $default;
            }


            if (!is_array($data->{$this->field['name']})) {
                $data->{$this->field['name']} = json_decode($data->{$this->field['name']});
            }

            return  $data->{$this->field['name']};
        }

        return $data->{$this->field['name']} ?? null;
    }


    public function value($form)
    {
        if (isset($this->field['multiple']) && $this->field['multiple']) {
            if ($form[$this->field['name']] ?? null) {
                return json_encode($form[$this->field['name']]);
            } else {
                return "[]";
            }
        }

        return $form[$this->field['name']] ?? null;
    }


    // public function display($data)
    // {
    //     $field = $this->field;

    //     if (!(isset($data[$field['name']]) && $data[$field['name']])) {
    //         return null;
    //     }
    //     if (isset($this->field['multiple']) && $this->field['multiple']) {
    //         $html = "<div >";
    //         foreach ($data[$field['name']] ?? [] as $value) {
    //             if (!empty($value)) {
    //                 $html .= "<div class=' twa-table-td-select'>" . $value . "</div>";
    //             }
    //         }
    //         $html .= "<div>";
    //         return $html;
    //     }
    //     return $data[$field['name']];
    // }


    public function display($data)
    {

     
        $field = $this->field;
        if (!(isset($data[$field['name']]) && $data[$field['name']])) {
            return null;
        }

        if (isset($this->field['multiple']) && $this->field['multiple']) {
            $selectedValues = $data[$field['name']] ?? [];

            $count = count($selectedValues);
            $html = "<div class='flex gap-1 items-center'>";
            if ($count > 0) {
                $html .= "<div class='twa-table-td-select'>" . "<span>  $selectedValues[0]</span> " . "</div>";
            }
            if ($count > 1) {
                $html .= "<div class='twa-table-td-select'>  +" . (count($selectedValues) - 1) . " more</div>";
            }
            $html .= "</div>";
            return $html;
        }

        return $data[$field['name']];
    }
}
