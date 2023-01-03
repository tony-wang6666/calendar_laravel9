<?php

namespace App\Imports;

use App\Models\CalendarDatetimeRecord;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CalendarDatetimeRecordImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CalendarDatetimeRecord([
            //以下是直接匯入資料的方式  20210303
            // 'relevant_member' => $row['relevant_member'],
            // 'case_level' => $row['case_level'],
            // 'informant' => $row['informant'],
            // 'informant_type' => $row['informant_type'],
            // 'case_title' => $row['case_title'],
            // 'case_content' => $row['case_content'],
            // 'case_location' => $row['case_location'],
            // 'case_remarks' => $row['case_remarks'],
            // 'case_begin' => $row['case_begin'],
            // 'case_end' => $row['case_end'],
            // 'case_all_day' => $row['case_all_day'],
        ]);
    }
}
