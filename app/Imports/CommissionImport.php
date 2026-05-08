<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\CustomerCommission;

class CommissionImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $index => $row) {
            if ($index === 0) {
                continue; // Skip header row
            }
        
            // Assuming the transaction ID is in the first column
            $id = $row[0];
            $transaction_id = $row[8]; // Assuming the transaction ID is in the ninth column
            $payment_date = $row[9]; // Assuming the payment date is in the tenth column
            $payment_method = $row[10]; // Assuming the payment method is in the eleventh column
            $remark = $row[11]; // Assuming the remarks are in the twelfth column
        
            // Find the corresponding record in the database and update the columns
            $commission = CustomerCommission::find($id);
            
            if ($commission) {
                $commission->payment_date = $payment_date;
                $commission->payment_method = $payment_method;
                $commission->transaction_id = $transaction_id;
                $commission->reason = $remark ?? "";
                $commission->status = 'approved';
                $commission->save();
            }
        }

    }
}
