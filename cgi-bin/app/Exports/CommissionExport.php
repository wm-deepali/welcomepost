<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\CustomerCommission;
use Carbon\Carbon;
class CommissionExport implements FromView
{
    private $monthYear;
    public function __construct(string $monthYear)
    {
        $this->monthYear = $monthYear;
    }

    public function view(): View
    {
        // Parse month and year from input (e.g., "2023-04")
        $date = Carbon::createFromFormat('Y-m', $this->monthYear)->startOfMonth();
        
        // Fetch data based on the selected month and year
        $data = CustomerCommission::with('customer.customerparent')->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->get();

        // Pass the data to the view
        return view('admin.exports.commission-export', [
            'commissions' => $data
        ]);
    }
}
