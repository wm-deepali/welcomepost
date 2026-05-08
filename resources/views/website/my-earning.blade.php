@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-12 col-md-9">
   <div class="profile-cont" style="overflow:auto;">
      <h3>
         My Earnings
      </h3>

      <table class="table">
         <thead>
            <tr>
               <th scope="col">#</th>
                     <th scope="col">Payment Date</th>
                     <th scope="col">Total Commission</th>
                     <th scope="col">TDS</th>
                     <th scope="col">Admin Charges</th>
                     <th scope="col">Other Charges</th>
                     <th scope="col">Total Earned</th>
                     <th scope="col">Transaction Id</th>
                     <th scope="col">Payment Method</th>
                     <th scope="col">Image</th>
                     <th scope="col">Reason</th>
                     <th scope="col">Status</th>
                 </tr>
             </thead>
             <tbody id="sort-ads-html">
                @if(isset($my_earnings) && count($my_earnings)>0)
                  @foreach($my_earnings as $index=>$earn)
                <tr>
                   <th scope="row">{{$index+1}}</th>
                     <td>{{$earn->payment_date ?? "--"}}</td>
                     <td>{{$earn->total_commission}}</td>
                     <td>{{$earn->tds}}</td>
                     <td>{{$earn->admin_charges}}</td>
                     <td>{{$earn->other_charges}}</td>
                     <td>{{$earn->total_earned}}</td>
                     <td>{{$earn->transaction_id ?? "--"}}</td>
                     <td>{{$earn->payment_method ?? "--"}}</td>
                     <td><img src="{{$earn->image}}" alt=""></td>
                     <td>{{$earn->reason ?? "--"}}</td>
                     <td>{{$earn->status}}</td>
                 </tr>
                 @endforeach
                 @else
                     <tr>
                         <td class="no_found" style="width:132px !important;"colspan="11">No Earnings found !</td>
                     </tr>
                 @endif
             </tbody>
         </table>
   </div>
</div>
</div>
</div>
</section>
@endsection