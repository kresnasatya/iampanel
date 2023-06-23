<?php

namespace App\Traits;

use Illuminate\Support\Facades\Bus;

trait ImportBatch
{
    public $batchId;
    
    public function getImportBatchProperty()
    {
        if (!$this->batchId) {
            return null;
        }

        return Bus::findBatch($this->batchId);
    }

    public function updateBatchProgress()
    {
        $this->batchProgress = $this->getImportBatchProperty()->progress();
        $this->batchFinished = $this->getImportBatchProperty()->finished();
        $this->batchCancelled = $this->getImportBatchProperty()->cancelled();
    }
}