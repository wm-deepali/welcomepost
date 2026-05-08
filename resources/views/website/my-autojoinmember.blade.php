@extends('website.layout.layout')
@section('title', $page)
@section('content')
@include('website.partials.user_sidebar')
<div class="col-sm-12 col-md-9">
   <div class="profile-cont" style="overflow:auto;">
      <h3>
        Auto Seeds
        
      </h3>
      
      <table class="table">
         <thead>
            <tr>
               
               <th>Date & Time</th>
               <th>Subscription Id</th>
               <th>Total Seeds</th>
               <th>Completed </th>
               <th>Remaining</th>
               <th>Level Status</th>
               <th>Status</th>
              
            </tr>
         </thead>
         <tbody id="sort-ads-html">
             
         @php
            
            @endphp
            @foreach ($autojoins as $items)
            <tr>
            <td class="myfontsize" >{{ date('d-M-Y', strtotime($items->created_at)) }}</td>
            <td class="myfontsize" >{{ '#'.$items->subscription_number }}</td>
            <td class="myfontsize" >{{ $items->auto_join_member ?? 0 }}</td>
            <td>{{$items->total_joined ?? 0}}</td>
            <td>{{$items->auto_join_member - $items->total_joined ?? 0 }}</td>
            <td>{{ $items->type }}</td>
            <td>{{ $items->join_complete == "yes" ? "Completed":"Pending" }}</td>
           </tr>
            @endforeach
         </tbody>
      </table>
   </div>
</div>
</div>
</div>
</section>


@stop