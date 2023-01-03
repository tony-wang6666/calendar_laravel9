<?php

namespace App\Exports\crm;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithTitle;    // 設定工作名稱
use Maatwebsite\Excel\Concerns\WithEvents; // 使用event，就必須套用WithEvents，如沒有使用，AfterSheet就無法使用
use Maatwebsite\Excel\Events\AfterSheet;
class CrmChangeCustomerExport implements FromCollection, WithTitle, WithEvents
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
                '日期' => $v->cc_date,
                '分會id' => $v->cc_branch_id,
                '科目' => $v->cc_class,
                '帳號' => $v->cc_acount,
                '本日餘額' => $v->cc_day_balance,
                '前日餘額' => $v->cc_last_day_balance,
                '正負成長' => $v->cc_grow,
                '增減金額' =>  $v->cc_settlement_money,
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
                    'D' => 16,
                    'E' => 16,
                    'F' => 16,
                    'G' => 16,
                    'H' => 16,
                    'I' => 16,
                    'J' => 16,];
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
                $event->sheet->getDelegate()->getStyle('A1:J'.$count)->getAlignment()->setVertical('center'); //垂直置中
                $event->sheet->getDelegate()->getStyle('A1:J'.$count)->getAlignment()->setHorizontal('center'); //平行置中
                $event->sheet->getDelegate()->getStyle('A1:J'.$count)->getAlignment()->setWrapText(true); //設定範圍換行
                //表格加框
                $styles = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $event->sheet->getStyle('A2:J'.$count)->applyFromArray($styles);
                //設定狀態
                $event->sheet->getDelegate()->getStyle('A1:J1')->applyFromArray([
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
