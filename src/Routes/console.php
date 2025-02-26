<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('twa:updateVite', function () {
   
   

    $assets = get_assets();

    $viteConfigPath = base_path('vite.config.js');

    if(!file_exists($viteConfigPath)){
        return;
    }

    $content = file_get_contents($viteConfigPath);

    $content = preg_replace('/\s*,?\s*"vendor\/twa\/uikit\/dist\/.*?"\s*/', '', $content);
    $content = preg_replace('/,\s*([\]\}])/', '$1', $content);
   
    $pattern = "/input:\s*\[([^\]]*)\]/";

    foreach($assets as $asset){

        $newInput1 = '"'.$asset.'"';

        if (preg_match($pattern, $content, $matches)) {
            $existingInputs = trim($matches[1]);
            $updatedInputs = $existingInputs ? "$existingInputs, $newInput1" : $newInput1;
            $content = preg_replace($pattern, "input: [$updatedInputs]", $content);
          
        } else {
      
        }
    }

    file_put_contents($viteConfigPath, $content); 

});
