<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithTitle;    // 設定工作名稱
use Maatwebsite\Excel\Concerns\WithEvents; // 使用event，就必須套用WithEvents，如沒有使用，AfterSheet就無法使用
use Maatwebsite\Excel\Events\AfterSheet;
class MyExport implements FromCollection, WithTitle, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data, $worktable){
        $this->data = $data;
        $this->worktable  = $worktable;
    }
    public function collection(){
        $DB_arrry[] = ['亂數'];
        // $DB_arrry = $this->DB_arrry;
        foreach($this->data as $v){
            $DB_arrry[] = array(
                '亂數' => $v,
            );
        }
        // $this->count = count($DB_arrry);
        return collect($DB_arrry);
        // return $DB_arrry;
    }
    public function registerEvents(): array{
        return [
        ];
    }
    public function title(): string { //excel工作名稱
        return $this->worktable;
    }
}
