<?php

namespace App\Exports\crm;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithTitle;    // 設定工作名稱
use Maatwebsite\Excel\Concerns\WithEvents; // 使用event，就必須套用WithEvents，如沒有使用，AfterSheet就無法使用
use Maatwebsite\Excel\Events\AfterSheet;
class CrmContributionExport implements FromCollection, WithTitle, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data, array $DB_arrry, $worktable){
        $this->data     = $data;
        $this->DB_arrry = $DB_arrry;
        $this->worktable  = $worktable;
    }
    public function collection(){
        $DB_arrry = $this->DB_arrry;
        foreach($this->data as $v){
            $DB_arrry[] = array(
                '流水號' => $v->id,
                '客戶編號' => $v->c_id,
                '日期' => $v->c_date,
                '活儲' => $v->c_current_deposits,
                '定儲' => $v->c_time_deposits,
                '放款' => $v->c_loan,
                '轉帳' => $v->c_transfer,
                '保險' => $v->c_insurance,
                '貢獻度' => $v->c_score,
            );
        }
        $this->count = count($DB_arrry);
        return collect($DB_arrry);
    }
    public function registerEvents(): array{
        return [
            AfterSheet::class  => function(AfterSheet $event) {
                //設定列寬
                $widths = [
                    'A' => 10, 
                    'B' => 16, 
                    'C' => 12, 
                    'D' => 12, 
                    'E' => 12, 
                    'F' => 12, 
                    'G' => 12, 
                    'H' => 12, 
                    'I' => 12,];
                foreach($widths as $a=>$c){
                    $event->sheet->getDelegate()->getColumnDimension($a)->setWidth($c);
                }
                //設定行高，$i為資料行數
                $count = $this->count;
                // $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(40);
                // for ($i = 2; $i<=$count; $i++) {
                //     $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(50);
                // }
                
                // $event->sheet->getStyle('F1:I1')->getAlignment()->setIndent(1);  //縮排 (*先縮排，再置中)
                //設定區域單元格垂直平行置中換行
                $event->sheet->getDelegate()->getStyle('A1:I'.$count)->getAlignment()->setVertical('center'); //垂直置中
                $event->sheet->getDelegate()->getStyle('A1:I'.$count)->getAlignment()->setHorizontal('center'); //平行置中
                $event->sheet->getDelegate()->getStyle('A1:I'.$count)->getAlignment()->setWrapText(true); //設定範圍換行
                //表格加框
                $styles = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $event->sheet->getStyle('A2:I'.$count)->applyFromArray($styles);
                //設定狀態
                $event->sheet->getDelegate()->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
            }
        ];
    }
    public function title(): string { //excel工作名稱
        return $this->worktable;
    }
}
