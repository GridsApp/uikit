<?php

namespace twa\uikit\Traits;



trait ToastTrait {


    public function send($title , $description = "") {
        $this->dispatch('toast-show' , 
        type : "default",
        message : $title,
        description : $description,
        position : "top-right",
        html : ''
    );


    }

    public function sendError($title , $description =""  , $position = "top-right") {
        $this->dispatch('toast-show' , 
        type : "danger",
        message : $title,
        description : $description,
        position : $position,
        html : ''
        );
    }

    public function sendSuccess($title , $description = "" ) {
     
        $this->dispatch('toast-show' , 
        type : "success",
        message : $title,
        description : $description,
        position : "top-right",
        html : ''
        );
    }

    public function sendWarning($title , $description = "") {
        $this->dispatch('toast-show' , 
        type : "warning",
        message : $title,
        description : $description,
        position : "top-right",
        html : ''
        );

    }

    public function sendInfo($title , $description = "") {
        $this->dispatch('toast-show' , 
        type : "info",
        message : $title,
        description : $description,
        position : "top-right",
        html : ''
        );
    }

}