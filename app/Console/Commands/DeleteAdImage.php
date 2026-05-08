<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Adposting;
use App\Models\AdPostingImage;
use File;
use Carbon\Carbon;

class DeleteAdImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deleteadimage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        info("Cron Job running at ". now());
        
        
        $ids =array();
        $ads = Adposting::select('subscription_id', 'ad_id')->where('delete_status','0')->where('active_status','1')->groupBy('subscription_id')->get();
        
        
        if(isset($ads) && count($ads)>0)
        {
            foreach($ads as $ad)
            {
                if($ad->subscriptionhistory->subscription_expiry < date('Y-m-d'))
                {
                    $ids[]= $ad->ad_id;
                }
            }
        }
        
        
        if(!empty($ids))
        {
            $images = AdPostingImage::whereIn('ads_id', $ids)->where('image_no', '!=', 1)->get();
            
            if(isset($images) && count($images)>0)
            {
                foreach($images as $image)
                {
                    $deleteAsset = parse_url($image->image, PHP_URL_PATH);
                     if (File::exists(base_path().$deleteAsset)) {
                            File::delete(base_path().$deleteAsset);
                    }
                    $image->delete();
                }
            }
        }
        
    }
}
