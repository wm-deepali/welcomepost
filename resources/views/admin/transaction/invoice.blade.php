@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('transaction-history')}}">Transaction History</a></li>
                        <li class="breadcrumb-item active">View Detail</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="invoice p-3 mb-3">
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                From
                <address>
                    <strong>{{ ucfirst($admin_detail->name)}}</strong><br>
                    
                    Phone: {{ $admin_detail->mobile}}<br>
                    Email: {{ $admin_detail->email}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 invoice-col">
                To
                <address>
                    <strong>{{ ucfirst(@$customer_detail->name)}}</strong><br>
                    Address: {{ @$customer_detail->address}}<br>
                    {{ @$customer_detail->cities->name }}, {{ @$customer_detail->states->name}}, {{ @$customer_detail->countries->name}} {{ @$customer_detail->pin}}<br>
                    Phone: {{ @$customer_detail->mobile }}<br>
                    Email: {{@$customer_detail->email}}
                </address>
            </div>
                <!-- /.col -->
           
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Package</th>
                            <th>Package Validity</th>
                            <th>No. of Ads</th>
                            <th>Ads Validity</th>
                            <th>Per Ads Costing</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Offered Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ucfirst(@$subscription->package)}}</td>
                            <td>{{ @$subscription->package_validity}}</td>
                            <td>{{ @$subscription->no_of_ads}}</td>
                            <td>{{ @$subscription->ads_validity}}</td>
                            <td>{{ @$subscription->ads_costing}}</td>
                            <td>{{ @$subscription->mrp}}</td>
                            <td>{{ @$subscription->discount}}</td>
                            <td>{{ @$subscription->offered_price}}</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">
                <p class="lead">Payment Methods:</p>
                <img src="{{ asset('assets/adminlte/dist/img/credit/visa.png')}}" alt="Visa">
                <img src="{{ asset('assets/adminlte/dist/img/credit/mastercard.png')}}" alt="Mastercard">
                <img src="{{ asset('assets/adminlte/dist/img/credit/american-express.png')}}" alt="American Express">
                <img src="{{ asset('assets/adminlte/dist/img/credit/paypal2.png')}}" alt="Paypal">

                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                    plugg
                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                </p>
            </div>
            <!-- /.col -->
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Used Ads:</th>
                            <td>{{ @$transaction_history->used_ads}}</td>
                        </tr>
                        <tr>
                            <th>Remaining Ads:</th>
                            <td>{{ @$transaction_history->remaining_ads}}</td>
                        </tr>
                        <tr>
                            <th style="width:50%">Transaction ID:</th>
                            <td>{{ @$transaction_history->transaction_id}}</td>
                        </tr>
                        <tr>
                            <th>Payment Status</th>
                            <td>{{ @$transaction_history->payment_status }}</td>
                        </tr>
                        
                        
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        
        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                 
            </div>
        </div>
    </div>
</div>
@stop