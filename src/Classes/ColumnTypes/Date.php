<?php

namespace twa\uikit\Classes\ColumnTypes;

use Carbon\Carbon;

class Date extends DefaultType
{
    public function html($parameters = [])
    {
        if (!empty($this->input)) {

          
            $this->input = Carbon::parse($this->input)->diffForHumans();

            // dd($this->input);
            return "<div>$this->input</div>";

              
        }
    }
}
