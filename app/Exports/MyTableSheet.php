<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;    // 設定工作名稱
use Maatwebsite\Excel\Concerns\WithEvents; // 使用event，就必須套用WithEvents，如沒有使用，AfterSheet就無法使用
use Maatwebsite\Excel\Events\AfterSheet;

//多表
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MyTableSheet implements FromCollection, WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(string $type,array $selects,array $names)  //controller傳入資料
    {
        $this->type = $type;
        $this->selects = $selects;
        $this->names = $names;
    }
    public function collection()
    {
    }
    // public function registerEvents(): array
    // {
    //     if($this->type == "all"){ //下載全部資料
    //         return [];
    //     }
    // }
    // public function title(): string { //excel工作名稱
    //     return '報表';
    // }
    //20210104目前測試結果，是把一個資料進行分割成好幾個，例如建立日期分成1~12個月的工作表
    public function sheets(): array
    {
        $sheets = [];
        // $table_array = ['CfaMember','LeaveRecord','OvertimeRecord','RosterVolunteer','DateRecord',
        // 'CfaMemberRecord','LeaveSet','LeaveType','LeaveSetDate','OvertimeSet','LeavePosition'];
        // $name_array = ['員工資料','請假紀錄','加班補休紀錄','志工排班紀錄','行事曆資料',
        // '員工年度資料','請假設定','請假類型','節日設定','補休設定','審核主管'];
        // for ($month = 1; $month <= 2; $month++) {
        //     //不同的資料可以呼叫不同的方法
        //     $sheets[] = new TableSheet($this->year, $month);
        // }
        // foreach($table_array as $k=>$v){
        //     // $sheets[] = new TableSheet($this->year, $k);
        //     if($k==0){
        //         $sheets[] = new LeaveRecordsExport('all', 'all');
        //     }elseif($k==1){
        //         $sheets[] = new OvertimeRecordsExport('all', 'all');
        //     }
        // }
        // $type = $this->type;
        // $selects = $this->selects;
        // $names = $this->names;
        if($this->type == "rangeTOrandom"){
            foreach($this->selects as $k=>$v){
                $sheets[] = new MyExport($v, $this->names[$k]); 
            }
        }
        // foreach($table_array as $k=>$v){
        //     $sheets[] = new TableSheet($v,$name_array[$k]);
        // }
        return $sheets;
    }
}
