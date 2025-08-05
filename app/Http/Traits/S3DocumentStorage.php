<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait S3DocumentStorage
{
    public function getS3Path($file_path)
    {
        return Storage::disk('s3')->temporaryUrl(
            $file_path,
            now()->addDays(2)
        );

        //return Storage::disk('s3')->url($file_path);
    }


    public function fileUpload($file_name, $file, $path)
    {
        $generatedName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = rtrim($path, '/') . '/' . $generatedName;
        $original_file_name = $file->getClientOriginalName();
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        return [
            'file_name'          => $generatedName,
            'file_path'          => $filePath,
            'original_file_name' => $original_file_name,
        ];
    }
    public function checkFolderExists($main_folder, $id)
    {
        return true;
    }
}
