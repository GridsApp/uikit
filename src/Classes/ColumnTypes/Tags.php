<?php

namespace twa\uikit\Classes\ColumnTypes;


class Tags extends DefaultType
{


    public function html($parameters = [])
    {

        if(json_validate($this->input)){
            $this->input = json_decode($this->input , 1);
        }

        if (!is_array($this->input)) {
            $this->input = [$this->input];
        }

        if (empty($this->input)) {
            return ''; 
        }

        $count= count($this->input);
        $nb = 1;

        $html = "<div class='flex gap-1 items-center'>";

        foreach($this->input as $input){
            $html .= "<div class='twa-table-td-select'><span>$input</span></div>";
        }

        if ($count > $nb) {
            $html .= "<div class='twa-table-td-select'><span>+" . ($count - $nb) . " more</span></div>";
        }

        

        // if ($count > 0) {

        //     $html .= "<div class='twa-table-td-select'><span>{$this->input[0]}</span></div>";

          
        //     if ($count > $nb) {
        //         $html .= "<div class='twa-table-td-select'><span>+" . ($count - $nb) . " more</span></div>";
        //     }
        // }

        $html .= "</div>";

        return $html;
    }
}
