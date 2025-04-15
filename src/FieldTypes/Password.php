<?php

namespace twa\uikit\FieldTypes;

use Illuminate\Support\Facades\Hash;

class Password extends FieldType
{

    public function component()
    {
        return "elements.password";
    }

    public function initalValue($data)
    {
        return null;
    }

    public function value($form)
    {
        return ($form[$this->field['name']] ?? null) ? md5($form[$this->field['name']]) : null;
    }

    public function display($data)
    {
        return "Encrypted";
    }

}
