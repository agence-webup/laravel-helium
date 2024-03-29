<?php

namespace Webup\LaravelHelium\Core\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CrudHelper
{
    public static function replaceInStub($stubPath, $replacers)
    {
        $stubContent = file_get_contents(base_path("vendor/webup/laravel-helium/src/Core/Console/stubs/crud/") . ltrim($stubPath, '/'));
        $stubReplacers = [];
        foreach ($replacers as $key => $replacer) {
            $stubReplacers["{{ $key }}"] = $replacer;
        }

        foreach ($stubReplacers as $searchString => $replaceBy) {
            $stubContent = str_replace($searchString, $replaceBy, $stubContent);
        }

        return $stubContent;
    }


    public static function replaceInStubAndSave($stubPath, $replacers, $outputPath)
    {
        $fileContent = CrudHelper::replaceInStub($stubPath, $replacers);
        file_put_contents($outputPath, $fileContent);
    }
}
