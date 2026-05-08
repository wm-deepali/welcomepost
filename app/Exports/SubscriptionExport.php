<?php 
namespace App\Exports;
 
use App\Models\SubscriptionHistory;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
 
class SubscriptionExport implements FromArray,WithHeadings
{
    
    protected $subscription;
    
    public function __construct(array $subscription)
    {
        $this->subscription = $subscription;
    }
    
    public function array(): array
    {
        return $this->subscription;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        return[
            'Id',
            'Date',
            'Name',
            'Expiry',
            'Billed',
            'TransactionID' ,
            'PaymentStatus',
            'Status'
        ];
    } 
    // public function collection()
    // {
    //       return SubscriptionHistory::select('name','email')->get();
    // }
}