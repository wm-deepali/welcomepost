<!DOCTYPE html>
<html>
<head>
    <title>Izharson Perfumers - Order Invoice</title>
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
    <div style="border: 1px solid #ddd; padding-left: 15px;">
        <div class="inovice-view">
          
            <table style="width:100%">
                
                <tr style="border-bottom: 1px solid #ddd; ">
                    <td colspan="4">
                        <div style="max-width:290px;">
                            <div style="max-width:290px;">
                                 <img src="{{url('assets/website/images/logo.png')}}" style="height: 50px;">
                            </div>
                        </div>
                    </td>
                    @php $gstsetting = \App\Models\Adminsettings::first(); @endphp
                    <td colspan="3" style="text-align: right;">
                        <h6 style="margin-bottom: 5px; font-size: 15px; font-weight: bold;text-transform:uppercase">Invoice</h6>
                        <p style="margin-bottom: 0px; font-size: 12px; font-weight: bold; ">Company Name - <span style="font-weight: normal;">{{ $gstsetting->company_name }} </span></p>
                        <p style="margin-bottom: 0; font-size: 12px; font-weight: bold; ">Address - <span style="font-weight: normal;"> {{ $gstsetting->full_address }}, {{ $gstsetting->citys->name }}, {{ $gstsetting->states->name }}, {{ $gstsetting->countries->name }}, {{ $gstsetting->pincode }}</span></p>
                        <p style="margin-bottom: 0px; font-size: 12px; font-weight: bold; ">Tax Number - <span style="font-weight: normal;"> {{ $gstsetting->gstno }}</span></p>
                    </td>
                </tr>
                
                <tr style="border-bottom: 1px solid #ddd; ">
                    <td colspan="4">
                        <div style="max-width:290px;margin-bottom:15px">
                            <h6 style="margin-bottom: 10px; font-size: 12px; font-weight: bold;">Invoice To </h6>
                                <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Name </strong> : <span>{{ $order->name ?? 'None' }} </span></p>
                                <p style="font-size: 12px;"><strong>Address </strong> : {{ $order->address }}, {{ $order->citys->name }}, {{ $order->states->name }}, {{ $order->countries->name }}, {{ $order->pincode }}</p>
                                <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Phone Number </strong> : <span>{{ $order->mobile_number }} </span></p>
                                <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Email Id </strong> : <span>{{ $order->email }}</span></p>
                                
                            </div>
                    </td>
                    <td colspan="3" style="text-align: right;">
                        <h6 style="margin-bottom: 10px; font-size: 12px; font-weight: bold;">Payment Details </h6>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Invoice Number </strong> : <span>{{ $gstsetting->prefix_number."-".$gstsetting->financial_year."/".$gstsetting->serial_number }}</span></p>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Order Date </strong> : <span>{{ $order->created_at }}</span></p>
                        <p style="font-size: 12px;margin-bottom: 0px;"> <strong>Payment Status </strong> : <span>{{ $order->payment_status }}</span></p>
                        <!--<h6 style="margin-bottom: 5px; margin-right: 20px; font-size: 12px; font-weight: bold; ">Company Name - <span style="font-weight: normal;">{{ $gstsetting->company_name }} </span></h6>-->
                        <!--<h6 style="margin-bottom: 5px; margin-right: 20px; font-size: 12px; font-weight: bold; ">Address - <span style="font-weight: normal;"> {{ $gstsetting->invoice_address }}, {{ $gstsetting->citys->name }}, {{ $gstsetting->states->name }}, {{ $gstsetting->countries->name }}, {{ $gstsetting->pincode }}</span></h6>-->
                        <!--<h6 style="margin-bottom: 5px; margin-right: 20px; font-size: 12px; font-weight: bold; ">Tax Number - <span style="font-weight: normal;"> {{ $gstsetting->gst_number }}</span></h6>-->
                    </td>
                </tr>
                
                <tr style="background-color: #ddd;font-weight: normal;">
                    <th style="padding:6px; font-size: 12px;"> # </th>
                    <th style="padding:6px; font-size: 12px;"> Product Image </th>
                    <th style="padding:6px; font-size: 12px;"> Product Name </th>
                    <th style="padding:6px; font-size: 12px;"> MRP </th>
                    <th style="padding:6px; font-size: 12px;"> Pre-Discount </th>
                    <th style="padding:6px; font-size: 12px;"> Quantity </th>
                    <th style="padding:6px; font-size: 12px;"> Product Cost </th>
                </tr>
                @php $prediscount=0; @endphp
                @if (isset($order->order_detailss) && count($order->order_detailss) > 0)
                    @foreach ($order->order_detailss as $key=> $order_detail)
                        <tr style=" font-size: 14px; font-weight: normal;">
                            <td style="padding:10px; border:1px solid #ddd; ">{{++$key}}</td>
                            <td style="padding:10px; border:1px solid #ddd; "> 
                            @if(isset($order_detail->product))
                                <a href="javascript:void(0)">
                                    <img src="{{ URL::asset('storage/' . $order_detail->product->image) }}" class="img-fluid"style="height:50px;" />
                                </a>
                            @else
                                <a href="javascript:void(0)">
                                    <img src="{{ URL::asset('front/images/no_image.jpg') }}" class="img-fluid">
                                </a>
                            @endif
                           
                           </td>
                            <td style="padding:10px; border:1px solid #ddd; ">{{$order_detail->product_name}} <span style="font-size:11px;font-weight:400">({{$order_detail->product->categories->name}} / @if(isset($order_detail->product->subcategories))  {{ $order_detail->product->subcategories->name}} @else NA @endif )</span></td>
                            
                            <td style="padding:10px; border:1px solid #ddd; ">{{ $order_detail->mrp }}</td>
                            <td style="padding:10px; border:1px solid #ddd; ">{{ $order_detail->discount_amount }}
                            </td>
                            @php $prediscount+=$order_detail->discount_amount; @endphp
                            <td style="padding:10px; border:1px solid #ddd; ">{{ $order_detail->quantity }}</td>
                            <td style="padding:10px; border:1px solid #ddd; ">{{ $order_detail->price }}</td>
                            
                        </tr>
                    @endforeach
                @endif
                <tr style=" font-size: 14px; font-weight: normal;">
                    <td colspan="6" style="text-align: right"> <strong style="padding: 0px 10px"> Sub Total </strong> </td>
                    <td style="padding:10px; border:1px solid #ddd; "> Rs {{ $order->order_amount }} </td>
                </tr>
                <tr style=" font-size: 14px; font-weight: normal;">
                    <td colspan="6" style="text-align: right"> <strong style="padding: 0px 10px"> Discount </strong> </td>
                    <td style="padding:10px; border:1px solid #ddd; "> Rs {{ $order->discount_amount }} </td>
                </tr>
                <!--@if($order->gst_type=="IGST")-->
                <!--<tr style=" font-size: 14px; font-weight: normal;">-->
                <!--    <td colspan="6" style="text-align: right"> <strong style="padding: 0px 10px"> IGST </strong> </td>-->
                <!--    <td style="padding:10px; border:1px solid #ddd; "> Rs {{ $order->total_gst_amount }} </td>-->
                <!--</tr>-->
                <!--@elseif($order->gst_type=="VAT")-->
                <!--<tr style=" font-size: 14px; font-weight: normal;">-->
                <!--    <td colspan="6" style="text-align: right"> <strong style="padding: 0px 10px"> VAT </strong> </td>-->
                <!--    <td style="padding:10px; border:1px solid #ddd; "> Rs {{ $order->total_gst_amount }} </td>-->
                <!--</tr>-->
                
                <!--@else-->
                <!--<tr style=" font-size: 14px; font-weight: normal;">-->
                <!--    <td colspan="6" style="text-align: right"> <strong style="padding: 0px 10px"> CGST </strong> </td>-->
                <!--    <td style="padding:10px; border:1px solid #ddd; "> Rs {{ $order->cgst_amount }} </td>-->
                <!--</tr>-->
                <!--<tr style=" font-size: 14px; font-weight: normal;">-->
                <!--    <td colspan="6" style="text-align: right"> <strong style="padding: 0px 10px"> SGST </strong> </td>-->
                <!--    <td style="padding:10px; border:1px solid #ddd; "> Rs {{ $order->sgst_amount }} </td>-->
                <!--</tr>-->
                <!--@endif-->
                @if($order->shipping_type_price)
                <tr style=" font-size: 14px; font-weight: normal;">
                    <td colspan="6" style="text-align: right"> <strong style="padding: 0px 10px"> Shipping Fee </strong> </td>
                    <td style="padding:10px; border:1px solid #ddd; "> Rs {{ $order->shipping_type_price }} </td>
                </tr>
                @endif
                <tr style=" font-size: 14px; font-weight: normal;">
                    <td colspan="6" style="text-align: right;"> <strong style="padding: 0px 10px"> Grand Total </strong> </td>
                    <td style="padding:10px; border:1px solid #ddd; "> <strong> Rs {{ $order->order_amount_with_shipping }} </strong> </td>
                </tr>
                <tr style=" font-size: 14px; font-weight: normal;color:red">
                    <td colspan="8" style="text-align: left; border-top: 1px solid #ddd; padding: 0px 10px;">
                        <h4 style="margin-bottom: 2px; font-size: 18px; margin-top: 200px; margin-bottom: 15px;"> Note:- All prices are including applicable taxes </h4>
                        
                    </td>
                </tr>

                <tr style=" font-size: 14px; font-weight: normal;">
                    <td colspan="8" style="text-align: left; border-top: 1px solid #ddd; padding: 0px 10px;">
                        <h4 style="margin-bottom: 2px; font-size: 18px; margin-top: 10px; margin-bottom: 15px;"> Terms Conditions </h4>
                        <p style="font-size: 14px; margin-top: 2px;">
                            {!! $terms_and_condition->content !!}
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>