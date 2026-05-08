<!DOCTYPE html>
<html>
<head>
    <title>Welcomepost - Subscription Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Start here SEO Part -->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <!-- End here SEO Part -->
    <!--start here favicon-->
    <link rel="shortcut icon" href="" alt="Signo Elevators">
     
    <!--end here favicon-->
    <!--start here css file-->
    <style type="text/css">
        @page {
            size: 8in 10.25in;
            margin: 10mm 10mm 10mm 10mm;
            mso-header-margin: .5in;
            mso-footer-margin: .5in;
            mso-paper-source: 0;
        }
    </style>
</head>

<body style="font-family: 'Poppins', sans-serif;">
    <div style="border: 1px solid #ddd;">
        <div style="background-color: #f5f5f5; padding: 30px; text-align: center">
            <img src="{{url('assets/website/images/logo.png')}}" style="height: 50px; margin-bottom: 20px; margin:0px auto 10px;">
        </div>

        <div class="inovice-view">
          
            <table  style="width:100%">
                
                <tr style="border-bottom: 1px solid #ddd; ">
                    <!--<td colspan="4"></td>-->
                    <td colspan="12" style="text-align: right;">
                        <h6 style="margin-bottom: 5px; font-size: 15px; font-weight: bold;text-transform:uppercase">Company Info</h6>
                        <p style="margin-bottom: 0px; font-size: 12px; font-weight: bold; ">Company Name - <span style="font-weight: normal;">{{ $gstsetting->company_name }} </span></p>
                        <p style="margin-bottom: 0; font-size: 12px; font-weight: bold; ">Address - <span style="font-weight: normal;"> {{ $gstsetting->full_address }}, {{ $gstsetting->cities->name }}, {{ $gstsetting->states->name }}, {{ $gstsetting->countries->name }}</span></p>
                       <p style="margin-bottom: 0px; font-size: 12px; font-weight: bold; ">GST Number - <span style="font-weight: normal;"> {{ $gstsetting->gstno }}</span></p>
                    </td>
                </tr>
                
                <tr style="border-bottom: 1px solid #ddd; ">
                    <td colspan="6" style="margin-left:30px;">
                        <div style="max-width:290px;margin-bottom:15px">
                            <h6 style="margin-bottom: 5px; font-size: 15px; font-weight: bold;text-transform:uppercase">Invoice To </h6>
                            <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Name </strong> : <span>{{ $user_detail->name ?? 'None' }} </span></p>
                            <p style="font-size: 12px;"><strong>Address </strong> : {{ $user_detail->address }}, {{ $user_detail->countries->name ?? "-" }}, {{ $user_detail->states->name ?? "-" }}, {{ $user_detail->cities->name ?? "-" }}, {{ $user_detail->pin }}</p>
                            <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Contact Details </strong> : <span>{{ $user_detail->mobile }} | {{ $user_detail->email }}</span></p>
                            <!--<p style="font-size: 12px;margin-bottom: 0px;"> <strong>Email Id </strong> : <span>{{ $user_detail->email }}</span></p>-->
                                
                        </div>
                    </td>
                    <td colspan="6" style="text-align: right;">
                        <h6 style="margin-bottom: 5px; font-size: 15px; font-weight: bold;text-transform:uppercase">Payment Details </h6>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Invoice Number </strong> : <span>{{ $gstsetting->prefix_number."-".$gstsetting->financial_year."/".$gstsetting->serial_number }}</span></p>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Order Date </strong> : <span>{{ date('d-M-Y',strtotime($subscriptionOrder->created_at)) }}</span></p>
                        <!--<p style="font-size: 12px;margin-bottom: 0px;"> <strong>Order Id </strong> : <span>{{$subscriptionOrder->transaction_id}}</span></p>-->
                         <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Transaction Id </strong> : <span>{{$subscriptionOrder->transaction_id}}</span></p>
                          <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Payment Method </strong> : <span>{{$subscriptionOrder->payment_method}}</span></p>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Payment Status </strong> : <span>{{ $subscriptionOrder->payment_status }}</span></p>
                    </td>
                </tr>
                
              
                        
            </table>
            
            <table colspan="12" style="width:100%; margin-top:20px;">
                  <tr  style="background-color: #ddd;font-weight: normal;">
                   
                    <th style="padding:4px; font-size: 10px;"> Subscription Name </th>
                    <!--<th style="padding:4px; font-size: 10px;"> Category Name </th>-->
                    <!--<th style="padding:4px; font-size: 10px;"> Package Validity </th>-->
                    <th style="padding:4px; font-size: 10px;"> No. of Ads </th>
                    <!--<th style="padding:4px; font-size: 10px;"> Transaction ID	 </th>-->
                    <th style="padding:4px; font-size: 10px;"> MRP</th>
                    <th style="padding:4px; font-size: 10px;"> Discount</th>
                    <th style="padding:4px; font-size: 10px;"> Offered Amount </th>
                    <!--<th style="padding:4px; font-size: 10px;"> GST</th>-->
                    <!--<th style="padding:4px; font-size: 10px;"> Expiry</th>-->
                    <!--<th style="padding:4px; font-size: 10px;"> Payment Status</th>-->
                </tr>
               
                <tr style=" font-size: 8px; font-weight: normal;">
                   
                    <td style="padding:6px; border:1px solid #ddd; "> {{ ucfirst($subscription->package) }}</td>
                    <!--<td style="padding:4px; border:1px solid #ddd; ">@if(isset($category)) {{ ucfirst($category->implode(",")) }} @endif</td>-->
                    <!--<td style="padding:6px; border:1px solid #ddd; "> {{ $subscription->package_validity }} Days</td>-->
                    <td style="padding:6px; border:1px solid #ddd; text-align:center;"> {{ $subscription->no_of_ads }}</td>
                    <!--<td style="padding:6px; border:1px solid #ddd; ">{{$subscriptionOrder->transaction_id}} </td>-->
                    <td style="padding:6px; border:1px solid #ddd;  text-align:center;">{{ $subscription->mrp ?? 0 }}</td>
                    <td style="padding:6px; border:1px solid #ddd;  text-align:center;">{{ $subscription->discount_amount ?? 0 }}</td>
                    <td style="padding:6px; border:1px solid #ddd;  text-align:center;">{{ $subscription->offered_price }}</td>
                    <!--<td style="padding:6px; border:1px solid #ddd; ">{{ $subscription->gst_amount??0 }}</td>-->
                    <!--<td style="padding:6px; border:1px solid #ddd; ">{{ $subscriptionOrder->subscription_expiry }} </td>-->
                    <!--<td style="padding:6px; border:1px solid #ddd; ">{{ $subscriptionOrder->payment_status }} </td>-->
                </tr>
                
                
                </table>
                 <table colspan="12" style="width:100%;">
                      <tr style="border-bottom: 1px solid #ddd; ">
                    <td colspan="8" style="margin-left:30px;">
                        <div style="max-width:290px;margin-bottom:15px">
                            <h6 style="margin-bottom: 5px; font-size: 15px; font-weight: bold;text-transform:uppercase">Subscription Details </h6>
                            <p style="font-size: 12px;margin-bottom: 0px;">  <span>@if(isset($category)) {{ ucfirst($category->implode(",")) }} @endif </span></p>
                           <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Subscription Validity</strong> : <span>{{ $subscription->package_validity }} Days</span></p>
                           <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Subscription Expiry</strong> : <span>{{ $subscriptionOrder->subscription_expiry }}</span></p>
                                
                        </div>
                    </td>
                    <td colspan="4" style="text-align: right;">
                        
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Sub Total </strong> : <span>{{ sprintf("%.2f", $subscription->mrp);  }}</span></p>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>GST </strong> : <span>{{ sprintf("%.2f", $subscription->gst_amount);  }}</span></p>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Welcome Bonus Discount </strong> : <span>{{ sprintf("%.2f", $subscription->discount_amount ?? 0);  }}</span></p>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Total Amount </strong> : <span>
                             @php 
                             $discount = $subscription->discount_amount ?? 0;
                             $sum=0;
                             $sum = $subscription->mrp + $subscription->gst_amount - $discount; 
                             @endphp
                             {{ sprintf("%.2f", $sum);}}
                            </span></p>
                        
                    </td>
                </tr>
                     </table>
        </div>
    </div>
</body>
</html>