<?php

namespace twa\uikit\Components;


use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use twa\uikit\Traits\ToastTrait;

class Table extends Component
{
    use WithPagination, ToastTrait;


    public $title = "";
    public $columns = [];
    public $rows = [];



    public function render()
    {
      



        return view('UIKitView::components.table');
    }


}
