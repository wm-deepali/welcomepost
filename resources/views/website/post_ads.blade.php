@extends('website.layout.layout')
@section('title', $page)
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<style>
    .list-category {
        list-style: none;
        border: 1px solid #e0dfdf;
        padding: 0;
        margin: 0;
        padding: 10px;
        border-top: none;
    }
    .list-category li a {
        color: rgb(59, 60, 147);
        padding: 10px;
        display: block;
        transition: all 0.3s ease;
    }
    .list-category li a:hover {
        background-color: #f5f5f5;
    }
    .responsive-dv {
        padding-top: 20px;
    }
    @media only screen and (max-width: 768px) {
        .mobile-none {
            display: none;
        }
        .row.mobile-view {
            display: block !important;
        }
    }
</style>
<section class="news-hom-big news-details">
    <div class="container">
        <h2 class="text-center"><b>POST YOUR AD</b></h2>
        <h4 class="text-center"><b>Select a category</b></h4>
        <div class="row mobile-none">
            <div class="col-sm-4">
                @foreach($allcategories as $key => $orderDetails)
                <div id="cat{{$orderDetails->id}}" class="category-item card p-4 mb-3" style="cursor: pointer;">
                    {{$orderDetails->name}}
                </div>
                <script>
                    $(document).ready(function(){
                        $("#cat{{$orderDetails->id}}").click(function(){
                            $(".ok").hide();
                            $("#demo{{$orderDetails->id}}").fadeIn();
                        });
                    });
                </script>
                @endforeach
            </div>
            <div class="col-sm-8">
                @foreach($allcategories as $key => $orderDetails)
                <ul id="demo{{$orderDetails->id}}" class="ok" style="display:none;">
                    <?php $subcat = DB::table('subcategories')->where('category_id', $orderDetails->id)->where('delete_status',0)->get();?>
                    @foreach($subcat as $key => $suborderDetails)
                    <a href="{{url('ad-forms',['formtype'=>$orderDetails->formtype,'category_id'=>$orderDetails->id,'subcategory_id'=>$suborderDetails->id])}}">
                        <li class="list-group-item card p-3 mb-3">{{$suborderDetails->name}}</li>
                    </a>
                    @endforeach
                </ul>
                @endforeach
            </div>
        </div>
        <div class="container responsive-dv">
            <div class="row mobile-view" style="display:none">
                @foreach($allcategories as $categoryDetail)
                <div class="col-sm-12">
                    <div id="catm{{$categoryDetail->id}}" class="category-item" style="border:1px solid rgb(14 4 5 / 20%);padding:20px; cursor: pointer;">
                        {{ucfirst($categoryDetail->name)}}
                        <i style="float: right;">▼</i>
                    </div>
                    <div id="sub-lists{{$categoryDetail->id}}" class="ok sublist" style="display:none;">
                        <ul class="list-category">
                            @php 
                                $subcat = DB::table('subcategories')->where('category_id', $categoryDetail->id)->where('delete_status',0)->get();
                            @endphp
                            @foreach($subcat as $key => $suborderDetails)
                            <li>
                                <a href="{{url('ad-forms',['formtype'=>$categoryDetail->formtype,'category_id'=>$categoryDetail->id,'subcategory_id'=>$suborderDetails->id])}}">{{ucfirst($suborderDetails->name)}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){
        $(".category-item").click(function() {
            if ($(".mobile-view").css("display") !== "none") {
                var targetId = $(this).attr("id").replace("catm", "sub-lists");
                $(".sublist").not("#" + targetId).slideUp(); // Hide all other lists
                $("#" + targetId).slideToggle(); // Toggle the clicked list
            }
        });
    });
</script>
@stop