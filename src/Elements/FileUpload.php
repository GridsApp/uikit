<?php

namespace twa\uikit\Elements;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\WithFileUploads;

class FileUpload extends Component
{

    use WithFileUploads;

    #[Modelable]
    public $value;

    public $file = [];
    public $info;


    


    public function render()
    {

        return view('UIKitView::components.form.file-upload');
    }



}
