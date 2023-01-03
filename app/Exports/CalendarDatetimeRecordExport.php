<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithTitle;    // 設定工作名稱
use Maatwebsite\Excel\Concerns\WithHeadings;    // 設定欄位
use Maatwebsite\Excel\Concerns\WithEvents; // 使用event，就必須套用WithEvents，如沒有使用，AfterSheet就無法使用
use Maatwebsite\Excel\Events\AfterSheet;

use App\Models\CalendarThingRecord;
use DB;

class CalendarDatetimeRecordExport implements FromCollection, WithTitle, WithEvents, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data, array $DB_head, $worktablename){
        $this->data    = $data;
        $this->DB_head = $DB_head;
        $this->worktablename  = $worktablename;
    }
    public function title(): string { //excel工作名稱
        return $this->worktablename;
    }
    public function headings():array{ //欄位名稱
        return $this->DB_head;
    }
    public function collection(){ //資料
        $this->count = count($this->data)+1;
        // return collect($this->data);

        // $DB_arrry[] = array('測試1');
        // $DB_arrry[] = array('測試2');
        // $DB_arrry[] = [['測試1'],['測試2']];
        // $DB_arrry[] = ["aa","bb","cc","dd"];
        return collect($this->data);
    }
    public function registerEvents(): array{ //excel欄位調整 (合併、字體大小、顏色...等)
        return [
            AfterSheet::class  => function(AfterSheet $event) {
                $count = $this->count;
                //設定列寬
                $widths = [
                    'G' => 12, 
                    'I' => 12,];
                foreach($widths as $a=>$c){
                    $event->sheet->getDelegate()->getColumnDimension($a)->setWidth($c);
                }
            //     //設定行高，$i為資料行數
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);
            //     for ($i = 2; $i<=$count; $i++) {
            //         $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50);
            //     }
                
            //     // $event->sheet->getStyle('F1:I1')->getAlignment()->setIndent(1);  //縮排 (*先縮排，再置中)
                //設定區域單元格垂直平行置中換行
                // $event->sheet->getDelegate()->getStyle('A1:P'.$count)->getAlignment()->setVertical('center'); //垂直置中
                // $event->sheet->getDelegate()->getStyle('A1:P'.$count)->getAlignment()->setHorizontal('center'); //平行置中
                // $event->sheet->getDelegate()->getStyle('A1:P'.$count)->getAlignment()->setWrapText(true); //設定範圍換行
            //     // //表格加框
            //     $styles = [
            //         'borders' => [
            //             'allBorders' => [
            //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            //                 'color' => ['argb' => '000000'],
            //             ],
            //         ],
            //     ];
            //     $event->sheet->getStyle('A2:D'.$count)->applyFromArray($styles);
            //     // //設定狀態
                $event->sheet->getDelegate()->getStyle('A1:P1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);

            //     //設定區域單元格字型、顏色、背景等，其他設定請檢視 applyFromArray 方法，提供了註釋
            //     // $event->sheet->getDelegate()->getStyle('A1:K6')->applyFromArray([
            //     //     'font' => [
            //     //         'name' => 'Arial',
            //     //         'bold' => true,
            //     //         'italic' => false,
            //     //         'strikethrough' => false,
            //     //         'color' => [
            //     //             'rgb' => '808080'
            //     //         ]
            //     //     ],
            //     //     'fill' => [
            //     //         'fillType' => 'linear', //線性填充，類似漸變
            //     //         'rotation' => 45, //漸變角度
            //     //         'startColor' => [
            //     //             'rgb' => '000000' //初始顏色
            //     //         ],
            //     //         //結束顏色，如果需要單一背景色，請和初始顏色保持一致
            //     //         'endColor' => [
            //     //             'argb' => 'FFFFFF'
            //     //         ]
            //     //     ]
            //     // ]);
            }
        ];
    }
}
