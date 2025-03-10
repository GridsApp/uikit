<?php

namespace twa\uikit\Classes\ColumnTypes;

class Button extends DefaultType
{
    public function html($parameters = []){


        // dd($parameters);
     
        $link = str_replace("{id}" , $this->input , $parameters['link']);
// dd($this->input);

        // dd($link);
        return "<a class='btn bg-primaryColor hover:opacity-85 btn-sm text-white !py-1' href='".$link."'>".$parameters['label']."</a>"; 
    }
}
