<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Adposting;
use App\Models\AdPostingImage;
use File;
use Carbon\Carbon;

class ExpireAd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expireads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty Bucket on expire ad';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info("Expre Ad Cron Job running at ". now());
        
        
        $ids =array();
        $ads = Adposting::where('delete_status','0')->get();
        
        if(isset($ads) && count($ads)>0)
        {
            foreach($ads as $ad)
            {
                if(strtotime($ad->subscriptionhistory->subscription_expiry) >= strtotime(now()))
                {
                    if($ad->ad_expiry < date('Y-m-d'))
                    {
                        DB::table('subscription_history')
                            ->where('id', $ad->subscription_id)
                            ->where('status', '0')
                            ->where('subscription_expiry', '>', Carbon::now())
                            ->decrement('used_ads', 1);
                    	
                    }
                }
	             
                    
            }
            info("Expre Ad Cron Job running at ". implode(",", $ids));
            
        }
    }
        
        
    
}