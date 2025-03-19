<?php

namespace twa\uikit\Classes\ColumnTypes;


class Images extends DefaultType
{

    public $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function html($parameters = [])
    {

        if(json_validate($this->input)){
            $this->input = json_decode($this->input , 1);
        }


        if (!is_array($this->input)) {
            $this->input = [$this->input];
        }

        if(!(isset($this->input) && $this->input)){
            return "<div class='twa-table-td-image placeholder'><i class='fa-duotone fa-solid fa-image'></i></div>";
        }

        $html =  "<div class='twa-table-td-images'>";


        if (count($this->input) > 0) {
        // return "<div class='twa-table-td-image'><img class='td-image' src='".get_image($this->input)."'></div>";

            $html .= "<div class='twa-table-td-image'><img class='td-image' src='" .get_image($this->input[0])."' /></div>";
        }

        if (count($this->input) > 1) {


            $html .= "<div class='twa-table-td-image'><div class='overlay-more'> +" . (count($this->input) - 1) . " </div></div>";
        }


        $html .= "</div>";

        return $html;
    }
}
