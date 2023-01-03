<?php

namespace App\Imports\crm;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
// use Maatwebsite\Excel\Concerns\WithStartRow; //開頭
// use App\Models\Crm_CustomerBasicInformation;

class CrmExcelImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function startRow(): int //開頭
    // {
    //     return 2;
    // }
    // public function getCsvSettings(): array
    // {
    //     return [
    //         'delimiter' => ';'
    //     ];
    // }
    public function model(array $row)
    {
        // return 123;
        // return new Crm_CustomerBasicInformation([
        //     //
        // ]);
    }
}
