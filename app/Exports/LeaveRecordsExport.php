<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\CalendarThingRecord;
use DB;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;    // 設定工作名稱
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; //自動調整框度大小

use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithMultipleSheets; //多張

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
//一般的方式
class LeaveRecordsExport implements FromCollection, WithTitle, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data, $dateTime, $payways)  //controller傳入資料
    {
        $this->data     = $data;
        $this->dateTime = $dateTime;
        $this->payways  = $payways;
    }
   

    public function collection(){ //excel 表格內容資料
        return CalendarThingRecord::all();
    }

    public function title(): string { //excel工作名稱
           return '報表';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {
                //設定列寬
                $widths = [
                    'A' => 10, 
                    'B' => 10, 
                    'C' => 13, 
                    'D' => 20, 
                    'E' => 20,];
                foreach($widths as $a=>$c){
                    $event->sheet->getDelegate()->getColumnDimension($a)->setWidth($c);
                }
            }
        ];
        
    }
}

