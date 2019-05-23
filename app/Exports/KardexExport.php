<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class KardexExport implements  FromView, ShouldAutoSize
{
    use Exportable;
    
    public function balance($balance) {
        $this->balance = $balance;
        
        return $this;
    }

    public function item_inicial($item_inicial) {
        $this->item_inicial = $item_inicial;
        
        return $this;
    }

    public function item($item) {
        $this->item = $item;
        
        return $this;
    }
    
    public function records($records) {
        $this->records = $records;
        
        return $this;
    }
    
    public function company($company) {
        $this->company = $company;
        
        return $this;
    }
    
    public function establishment($establishment) {
        $this->establishment = $establishment;
        
        return $this;
    }
    
    public function view(): View {
        return view('tenant.reports.kardex.report_excel', [
            'balance'=> $this->balance,
            'item_inicial'=> $this->item_inicial,
            'item'=> $this->item,
            'records'=> $this->records,
            'company' => $this->company,
            'establishment'=>$this->establishment
        ]);
    }
}
