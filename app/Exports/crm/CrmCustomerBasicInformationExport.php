<?php

namespace App\Exports\crm;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithTitle;    // 設定工作名稱
use Maatwebsite\Excel\Concerns\WithHeadings;    // 設定欄位
use Maatwebsite\Excel\Concerns\WithEvents; // 使用event，就必須套用WithEvents，如沒有使用，AfterSheet就無法使用
use Maatwebsite\Excel\Events\AfterSheet;

use DB;

class CrmCustomerBasicInformationExport implements FromCollection, WithTitle, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data, array $DB_arrry, $worktable)
    {
        $this->data     = $data;
        $this->DB_arrry = $DB_arrry;
        $this->worktable  = $worktable;
    }
    public function headings():array{
        return $this->DB_arrry;
    }
    public function collection(){
        $this->count = count($this->data)+1;
        return collect($this->data);
        // $DB_arrry = $this->DB_arrry;
        // foreach($this->data as $v){
        //     $DB_arrry[] = array(
        //         '編號' => $v->id,
        //         '姓名/公司' => $v->c_name_company,
        //         '身分證/統編' => $v->identification_gui_number,
        //         '性別' => $v->c_sex,
        //         '生日/開業日' => $v->c_birth_opening_date,
        //         '客戶種類' => $v->c_type,
        //         '電話' => $v->c_telephone,
        //         '手機' => $v->c_cellphone,
        //         '宗教' => $v->religion,
        //         '戶號' => $v->c_number,
        //         '開戶親屬' => $v->c_family,
        //         '區碼' => $v->postcode,
        //         '縣市' => $v->city,
        //         '鄉鎮區' => $v->city_area,
        //         '地址' => $v->c_address,
        //         '本會開戶' => $v->open_account,
        //         '農會會員' => $v->farmer_association_member,
        //         '農保' => $v->farmer_insurance,
        //         '健康狀況' => $v->health_state,
        //         '溝通狀況' => $v->communicate_state,
        //         '回應態度' => $v->response_attitude,
        //         '存款等級' => $v->deposit_level,
        //         '貸款等級' => $v->loan_level,
        //         '主要往來銀行' => $v->c_bank,
        //         'VIP年度' => $v->cyears,
        //         '勸募員工' => $v->encourage_raise_staff,
        //         'AO人員' => $v->ao_staff,
        //         '備註' => $v->remark,
        //         '可拜訪時段' => $v->visitable_times,
        //         '性格' => $v->dispositions,
        //         '興趣' => $v->interests,
        //         '偏好投資' => $v->prefer_invests,
        //         '開放性較高業務' => $v->openness_high_business,
        //         '開放性較低業務'  => $v->openness_low_business,
        //         '資料來源'  => $v->c_source,
        //     );
        // }
        // $this->count = count($DB_arrry);
        // return collect($DB_arrry);
        
        // $this->count = count($DB_arrry);
        // return collect($DB_arrry);
    }
    public function registerEvents(): array
    {
        // if($this->type == "all"){ //下載全部資料
            return [
                AfterSheet::class  => function(AfterSheet $event) {
                    //設定列寬
                    $widths = [
                        'A' => 10, 
                        'B' => 16, 
                        'C' => 12, 
                        'D' => 8,  //性別
                        'E' => 14, //生日/開業日
                        'F' => 12,  //客戶種類
                        'G' => 12, 
                        'H' => 12, 
                        'I' => 12,
                        'J' => 12,
                        'K' => 12,
                        'L' => 8,
                        'M' => 10,
                        'N' => 10,
                        'O' => 50,
                        'P' => 12,
                        'Q' => 16,
                        'R' => 12,
                        'S' => 12,
                        'T' => 12,
                        'U' => 12,
                        'V' => 12,
                        'W' => 12,
                        'X' => 16,
                        'Y' => 16,
                        'Z' => 16,
                        'AA' => 16,
                        'AB' => 40,
                        'AC' => 40,
                        'AD' => 40,
                        'AE' => 40,
                        'AF' => 40,
                        'AG' => 40,
                        'AH' => 40,
                        'AI' => 16,];
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
                    $event->sheet->getDelegate()->getStyle('A1:AI'.$count)->getAlignment()->setVertical('center'); //垂直置中
                    $event->sheet->getDelegate()->getStyle('A1:AI'.$count)->getAlignment()->setHorizontal('center'); //平行置中
                    $event->sheet->getDelegate()->getStyle('A1:AI'.$count)->getAlignment()->setWrapText(true); //設定範圍換行
                    //表格加框
                    $styles = [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ];
                    $event->sheet->getStyle('A2:AI'.$count)->applyFromArray($styles);
                    //設定狀態
                    $event->sheet->getDelegate()->getStyle('A1:AI1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ]
                    ]);

                    //設定區域單元格字型、顏色、背景等，其他設定請檢視 applyFromArray 方法，提供了註釋
                    // $event->sheet->getDelegate()->getStyle('A1:K6')->applyFromArray([
                    //     'font' => [
                    //         'name' => 'Arial',
                    //         'bold' => true,
                    //         'italic' => false,
                    //         'strikethrough' => false,
                    //         'color' => [
                    //             'rgb' => '808080'
                    //         ]
                    //     ],
                    //     'fill' => [
                    //         'fillType' => 'linear', //線性填充，類似漸變
                    //         'rotation' => 45, //漸變角度
                    //         'startColor' => [
                    //             'rgb' => '000000' //初始顏色
                    //         ],
                    //         //結束顏色，如果需要單一背景色，請和初始顏色保持一致
                    //         'endColor' => [
                    //             'argb' => 'FFFFFF'
                    //         ]
                    //     ]
                    // ]);
                }
            ];
        // }
    }
    public function title(): string { //excel工作名稱
        return $this->worktable;
    }
}
