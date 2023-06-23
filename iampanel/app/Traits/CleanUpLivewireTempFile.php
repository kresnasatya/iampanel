<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait CleanUpLivewireTempFile
{
    /**
     * Clean up files from livewire-tmp hour ago.
     */
    public function cleanUp()
    {
        $oldFiles = Storage::files(config('livewire.temporary_file_upload.directory'));

        foreach ($oldFiles as $file) {
            $minutesAgoStamp = now()->subMinutes(5)->timestamp;
            if ($minutesAgoStamp > Storage::lastModified($file)) {
                Storage::delete($file);
            }
        }
    }
}