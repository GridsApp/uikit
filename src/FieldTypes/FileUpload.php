<?php

namespace twa\uikit\FieldTypes;



use App\Http\Controllers\UploadController;

class FileUpload extends FieldType
{

    public function component()
    {
        return "elements.file-upload";
    }

    public function db(&$table){
        $table->text($this->field['name'])->nullable();
    }




    public function columnType()
    {
       
        if (isset($this->field['multiple']) &&  $this->field['multiple']) {
            return \twa\uikit\Classes\ColumnTypes\Images::class;
        }
    
        return \twa\uikit\Classes\ColumnTypes\Image::class;
    }





    public function value($form){


        $field = $this->field;

        $default = isset($field['multiple']) && $field['multiple'] ? [] : null;

        $value =  $form[$this->field['name']] ?? $default;

        $files = [];
        $already_uploaded = [];

        if (isset($field['multiple']) && $field['multiple']) {
            foreach ($value as $file) {

                if (!$file) {
                    continue;
                }

                if ($file['progress'] == 102) {
                    $already_uploaded[] = $file['uploaded'];
                    continue;
                }

                $files[] = [
                    'file' => $file['uploaded'],
                    'crop' => isset($file['cropping']) ? [
                        'x' => $file['cropping']['x'],
                        'y' => $file['cropping']['y'],
                        'width' => $file['cropping']['width'],
                        'height' => $file['cropping']['height']
                    ] : null
                ];
            }


            $upload_files = (new UploadController($field))->uploadFiles($files);

            return json_encode([...$already_uploaded, ...$upload_files]);
        } else {

            $file = $form[$field['name']];
            if (!$file) {
                return null;
            }

            if ($file['progress'] == 102) {
                return $file['uploaded'];
            }

            return (new UploadController($field))->uploadFile([
                'file' => $file['uploaded'],
                'crop' => isset($file['cropping']) ? [
                    'x' => $file['cropping']['x'],
                    'y' => $file['cropping']['y'],
                    'width' => $file['cropping']['width'],
                    'height' => $file['cropping']['height']
                ] : null
            ]);
        }
    }

    public function initalValue($data)
    {

    
        $field = $this->field;

  

        $default = $field['value'] ?? (isset($field['multiple']) && $field['multiple'] ? [] : null);


        if (isset($field['multiple']) && $field['multiple']) {

  
           

            if(isset($data->{$field['name']})){

                    if(!is_array($data->{$field['name']})){
                        $data->{$field['name']} = json_decode($data->{$field['name']});
                    }

                $array = collect($data->{$field['name']} )->map(function($item){
                    if(!str_contains($item , ".")){
                        return null;
                    }

                    [$folder , $extension] = explode("." , $item);

                    if(in_array(strtolower($extension) , ['jpeg' , 'jpg' , 'png'])){
                        $url = "/storage/data/".$folder."/thumb.webp";
                    }else{
                        $url = "/storage/data/".$folder."/original.".$extension;
                    }

                    return [
                        'name' => $item,
                        'url' => $url,
                        'progress' => 102,
                        'status' => 'uploaded',
                        'uploaded' => $item
                    ];
                })
                ->filter()
                ->values()
                ->toArray();

               return $array;

            }else{
                return $default;
            }

          

            return isset($data->{$field['name']}) ? json_decode($data->{$field['name']})  : [];

        } else {

            if(isset($data->{$field['name']})){
                $item = $data->{$field['name']};


                if(!str_contains($item , ".")){
                    return null;
                }

                [$folder , $extension] = explode("." , $item);

                if(in_array(strtolower($extension) , ['jpeg' , 'jpg' , 'png'])){
                    $url = "/storage/data/".$folder."/thumb.webp";
                }else{
                    $url = "/storage/data/".$folder."/original.".$extension;
                }


                    return [
                        'name' => $item,
                        'url' => $url,
                        'progress' => 102,
                        'status' => 'uploaded',
                        'uploaded' => $item
                    ];
            }else{
                return $default;
            }
        }




    }

    public function display($data){


        $field = $this->field;

        if(!(isset($data[$field['name']]) && $data[$field['name']])){
            return "<div class='twa-table-td-image placeholder'><i class='fa-duotone fa-solid fa-image'></i></div>";
        }


        if (isset($field['multiple']) && $field['multiple']) {


            $urls = get_images(json_decode($data[$field['name']]) ,'thumb');

            $html =  "<div class='twa-table-td-images'>";


            if(count($urls) > 0){
                $html.="<div class='twa-table-td-image'><img class='td-image' src='".$urls[0]."' /></div>";
            }

            if(count($urls) > 1){
                // dd(count($urls));

                $html.="<div class='twa-table-td-image'><div class='overlay-more'> +".(count($urls) - 1)." </div></div>";

                // $html.="<div class='twa-table-td-image'><div class='overlay-more'>+".count($urls) - 1."</div></div>";
            }

            // foreach($urls as $url){
            //     $html.="<div class='twa-table-td-image'><img class='td-image' src='".$url."' /></div>";
            // }
            $html .= "</div>";

            return $html;
        }


        $url = get_image($data[$field['name']] ,'thumb');

        return "<div class='twa-table-td-image'><img class='td-image' src='".$url."'></img></div>";



    }


}
