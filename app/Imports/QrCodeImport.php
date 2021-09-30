<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class QrCodeImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        
    }
}