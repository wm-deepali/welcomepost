$(document).ready(function () {
    "use strict";
    //getsource();
    $(".ban-short-links").addClass("ani");
    setTimeout(function () {
        $(".ani-quo").addClass("ani-quo-act")
    }, 7000);
    setTimeout(function () {
        $(".ani-quo").removeClass("ani-quo-act")
    }, 14000);
    setTimeout(function () {
        $(".btn-ser-need-ani").fadeIn();
    }, 15000);

    //TOP MENU OPEN AND CLOSE
    $('.menu').on('click', function () {
        //$('.pop-menu').fadeIn();
        $('.pop-menu').toggleClass("ani");
        $(this).toggleClass("ani");
    });
    $('.clopme').on('click', function () {
        //$('.pop-menu').fadeOut();
        $('.pop-menu').removeClass("ani");
        $('.menu').removeClass("ani");
    });
    ////DASHBOARD PROFILE OPEN AND CLOSE NEW
    $('.near-pro-cta').on('click', function () {
        $('.db-menu').addClass("act");
    });
    $('.db-menu-noti').on('click', function () {
        $('.top-noti-win').addClass("act");
    });
    $('.db-menu-clo').on('click', function () {
        $('.db-menu, .top-noti-win').removeClass("act");
    });


    $('.ani-quo, .btn-ser-need-ani').on('click', function () {
        $('.ani-quo-form').addClass('ani-quo-form-act');
    });
    $('.ani-req-clo').on('click', function () {
        $('.ani-quo-form').removeClass('ani-quo-form-act');
    });
    // $('.qvv').on('click', function() {
    //     $('.list-qview').addClass('qview-show');
    // });
    // $('.list-qview').on('mouseleave', function() {
    //     $('.list-qview').removeClass('qview-show');
    // });
    $('.clo-list').on('click', function () {
        $('.list-qview').removeClass('qview-show');
    });
    $('.mob-me-ic').on('click', function () {
        $('.mob-me-all').addClass('mobmenu-show');
    });
    $('.mob-me-clo').on('click', function () {
        $('.mob-me-all').removeClass('mobmenu-show');
    });
    $('.ll-1').on('click', function () {
        $('.log-1').slideDown();
        $('.log-2, .log-3, .log-4').slideUp();
    });
    $('.ll-2').on('click', function () {
        $('.log-2').slideDown();
        $('.log-1, .log-3, .log-4').slideUp();
    });
    $('.ll-3').on('click', function () {
        $('.log-3').slideDown();
        $('.log-1, .log-2, .log-4').slideUp();
    });
    $('.ll-4').on('click', function () {
        $('.log-4').slideDown();
        $('.log-1, .log-2, .log-3').slideUp();
    });
    //FILTER ON ALL LISTING PAGE - MOBILE VIEW ONLY
    $('.fil-mob').on('click', function () {
        $('.fil-mob-view').addClass("fil-mmob-act");
    });
    $('.fil-mob-clo').on('click', function () {
        $('.fil-mob-view').removeClass("fil-mmob-act");
    });
    //ALL LISTING GRID AND LIST VIEW CHANGE
    $('.vfilter i').on('click', function () {
        $('.vfilter i').removeClass('act')
        $(this).addClass('act')
    });
    $('.ic1').on('click', function () {
        $(".list-map").hide();
        $(".all-list-bre, .all-listing").show();
        $('.all-list-sh').removeClass('cview3');
        $('.all-list-sh').addClass('cview1');
    });
    $('.ic2').on('click', function () {
        $(".list-map").hide();
        $(".all-list-bre, .all-listing").show();
        $('.all-list-sh').removeClass('cview1');
        $('.all-list-sh').removeClass('cview3');
    });
    $('.ic3').on('click', function () {
        $(".all-list-bre, .all-listing").hide();
        $(".list-map").show();
    });
    //NOTIFICATION POPUP SHOW AND HIDE
    $('.btn-sure').on('click', function () {
        $('.noti-sure').addClass('cnoti-show');
        setTimeout(function () {
            $('.noti-sure').removeClass('cnoti-show');
        }, 5000);
    });
    setTimeout(function () {
        $('.status_message, .log-suc').fadeOut();
    }, 3000);
    $('.no').on('click', function () {
        $('.noti-sure').removeClass('cnoti-show');
    });
    //HOW TO TAB OPTIONS
    $('.how-to-coll li h4').on('click', function () {
        $('.how-to-coll li h4').removeClass('colact');
        $('.how-to-coll li div').slideUp();
        $(this).addClass('colact');
        $(this).next("div").slideDown();
    });
    //CREATE DUPLICATE LISTING
    $('.cre-dup-btn').on('click', function () {
        $('.cre-dup-form').slideDown();
    });
    //DASHBOARD PROFILE OPEN AND CLOSE OLD
    /*$('.al').on('mouseenter', function () {
     $('.db-menu').stop().slideDown();
     $('.ud').addClass('op1');
     });
     $('.al').on('mouseleave', function () {
     $('.db-menu').stop().slideUp();
     $('.ud').removeClass('op1');
     });*/
    $('.dropdown-menu li').on('click', function () {
        var getValue = $(this).text();
        $('.dropdown-select').text(getValue);
        //alert(getValue);
    });
    //CREATE USER - SHOW USER TYPE SECTION
    $(".ca-check-plan").on('change', function () {
        if ($(this).val() == "Service provider") {
            $('.ca-sh-plan').slideDown();
        }
        else {
            $('.ca-sh-plan').slideUp();
        }
    });
    //USER PROFILE TAB
    $('.us-pro-nav ul li span').on('click', function () {
        $('.us-pro-nav ul li span').removeClass('act');
        $(this).addClass('act');
    });
    $('.us-pro-nav ul li:nth-child(1) span').on('click', function () {
        $('.us-ppg-com').slideDown();
    });
    $('.us-pro-nav ul li:nth-child(2) span').on('click', function () {
        $('.us-ppg-com').slideUp();
        $('.us-ppg-listings').slideDown();
    });
    $('.us-pro-nav ul li:nth-child(3) span').on('click', function () {
        $('.us-ppg-com').slideUp();
        $('.us-ppg-blog').slideDown();
    });
    $('.us-pro-nav ul li:nth-child(4) span').on('click', function () {
        $('.us-ppg-com').slideUp();
        $('.us-ppg-event').slideDown();
    });
    $('.us-pro-nav ul li:nth-child(5) span').on('click', function () {
        $('.us-ppg-com').slideUp();
        $('.us-ppg-follow').slideDown();
    });
    //SERVICES LIST ADD - APPEND
    $(".lis-ser-add-btn").on('click', function () {
        $(".add-list-ser ul li:last-child").after('<li><div class="row"> <div class="col-md-6"> <div class="form-group"> <label>Service name :</label>  <input type="text" name="service_id[]" class="form-control" placeholder="Ex: Plumbile"> </div> </div> <div class="col-md-6"> <div class="form-group"><div class="fil-img-uplo"> <span class="dumfil">Service Image</span> <input type="file" name="service_image[]" accept="image/*,.jpg,.jpeg,.png" class="form-control"> </div></div> </div> </div></li>');
    });
    //SERVICES OFFER LIST REMOVE - APPEND
    $(".lis-ser-rem-btn").on('click', function () {
        var _removListSer = $(".add-list-ser ul li").length;
        if(_removListSer >= 2){
            $(".add-list-ser ul li:last-child").remove();
        }
        else{
            alert("Sorry! you are not allowed to remove the last one.");
        }
    });
    //SPECIAL OFFER LIST ADD - APPEND
    $(".lis-add-off").click(function () {
        $(".add-list-off ul li:last-child").after('<li><div class="row"> <div class="col-md-6"> <div class="form-group"> <input type="text" name="service_1_name[]" class="form-control" placeholder="Offer name *"> </div> </div> <div class="col-md-6"> <div class="form-group"> <input type="text" class="form-control" name="service_1_price[]" onkeypress="return isNumber(event)" placeholder="Price"> </div> </div> </div><div class="row"> <div class="col-md-12"> <div class="form-group"> <textarea class="form-control" name="service_1_detail[]" placeholder="Details about this offer"></textarea> </div> </div> </div><div class="row"> <div class="col-md-12"> <div class="form-group"><div class="fil-img-uplo"> <span class="dumfil">Choose offer image</span> <input type="file" name="service_1_image[]" accept="image/*,.jpg,.jpeg,.png" class="form-control"> </div></div> </div> </div><div class="row"> <div class="col-md-12"> <div class="form-group"> <input type="text" name="service_1_view_more[]" class="form-control" placeholder="View More Link"></div></div></div></li>');
    });
    //SPECIAL OFFER LIST REMOVE - APPEND
    $(".lis-add-rem").on('click', function () {
        var _removListOff = $(".add-list-off ul li").length;
        if(_removListOff >= 2){
            $(".add-list-off ul li:last-child").remove();
        }
        else{
            alert("Sorry! you are not allowed to remove the last one.");
        }
    });
    //SPECIAL OFFER LIST ADD - APPEND
    $(".lis-add-oad").on('click', function () {
        $(".add-lis-oth ul li:last-child").after('<li> <div class="row"> <div class="col-md-5"> <div class="form-group"> <input type="text" name="listing_info_question[]" class="form-control" placeholder="Type your information"> </div> </div><div class="col-md-2"> <div class="form-group"> <i class="material-icons">arrow_forward</i> </div> </div> <div class="col-md-5"> <div class="form-group"> <input type="text" name="listing_info_answer[]" class="form-control" placeholder="yes"> </div> </div> </div> </li>');
    });
    //SPECIAL OFFER LIST REMOVE - APPEND
    $(".lis-add-ore").on('click', function () {
        var _removListOthe = $(".add-lis-oth ul li").length;
        if(_removListOthe >= 2){
            $(".add-lis-oth ul li:last-child").remove();
        }
        else{
            alert("Sorry! you are not allowed to remove the last one.");
        }
    });
    //MOBILE MENU - DASHBOARD BOARD MENU SHOW
    $(".mv-pro").on('click', function () {
        $(".mv-pro-menu").slideToggle();
    });
    //BOOTSTRAP TOOL TIP
    $('[data-toggle="tooltip"]').tooltip();

    //PRODUCT SPECIFICATION LIST ADD - APPEND
    $(".prod-add-oad").on('click', function () {
        $(".add-prod-oth ul li:last-child").after('<li> <div class="row"> <div class="col-md-5"> <div class="form-group"> <input type="text" name="product_info_question[]" class="form-control" placeholder="Type your information"> </div> </div><div class="col-md-2"> <div class="form-group"> <i class="material-icons">arrow_forward</i> </div> </div> <div class="col-md-5"> <div class="form-group"> <input type="text" name="product_info_answer[]" class="form-control" placeholder="yes"> </div> </div> </div> </li>');
    });
    //PRODUCT SPECIFICATION LIST REMOVE - APPEND
    $(".prod-add-ore").on('click', function () {
        var _removProSpe = $(".add-prod-oth ul li").length;
        if(_removProSpe >= 2){
            $(".add-prod-oth ul li:last-child").remove();
        }
        else{
            alert("Sorry! you are not allowed to remove the last one.");
        }
    });

    //PRODUCT HIGHLIGHTS LIST ADD - APPEND
    $(".prod-add-high-oad").on('click', function () {
        $(".add-prod-high-oth ul li:last-child").after('<li> <div class="row"> <div class="col-md-12"> <div class="form-group"> <input type="text" name="product_highlights[]" class="form-control" placeholder="Type your highlights"> </div> </div> </div> </li>');
    });
    //PRODUCT HIGHLIGHTS LIST REMOVE - APPEND
    $(".prod-add-high-ore").on('click', function () {
        var _removProHig = $(".add-prod-high-oth ul li").length;
        if(_removProHig >= 2){
            $(".add-prod-high-oth ul li:last-child").remove();
        }
        else{
            alert("Sorry! you are not allowed to remove the last one.");
        }
    });


    //VIDEO LIST ADD - APPEND
    $(".lis-add-oadvideo").on('click', function () {
        $(".add-list-map ul li:last-child").after('<li> <div class="row"> <div class="col-md-12"> <div class="form-group"> <textarea id="listing_video" name="listing_video[]" class="form-control" placeholder="Paste Your Youtube Url here"></textarea> </div> </div> </div> </li>');
    });
    //VIDEO LIST REMOVE - APPEND
    $(".lis-add-orevideo").on('click', function () {
        var _removVid = $(".add-list-map ul li").length;
        if(_removVid >= 2){
            $(".add-list-map ul li:last-child").remove();
        }
        else{
            alert("Sorry! you are not allowed to remove the last one.");
        }
    });

    //ENQUIRY AND REVIEW LIKE
    // $(".enq-sav i").click(function(){
    //   $(this).toggleClass('sav-act');
    // });

    //ENQUIRY AND REVIEW LIKE
    // $(".ldelik").click(function(){
    //   $(this).toggleClass('sav-act');
    // });

    //INPUT FOCUS TOOL TIP SHOW
    $(".form-group input, .form-group textarea").focus(function(){
        $(this).siblings(".inp-ttip").fadeIn();
    });
    $(".form-group input, .form-group textarea").blur(function(){
        $(this).siblings(".inp-ttip").fadeOut();
    });
    $(".form-group input, .form-group textarea").mouseleave(function(){
        $(this).siblings(".inp-ttip").fadeOut();
    });

    //IMAGE FILE UPLOAD GET FILE NAME
    $(".fil-img-uplo input").on("change", function(){
        var _upldfname = $(this).val().replace(/C:\\fakepath\\/i, '');
        $(this).siblings(".dumfil").html(_upldfname);
    });

    //MOBILE SEARCH SHOW
    $(".mob-sear").on('click', function () {
        $(".top-ser").toggleClass("top-ser-act");;
    });

    //ADS TOTAL DAYS CALCULATION
    $("#stdate, #endate, #adposi").change(function () {
        var firstDate = $("#stdate").val();
        var secondDate = $("#endate").val();
        var millisecondsPerDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var startDay = new Date(firstDate);
        var endDay = new Date(secondDate);
        var diffDays = Math.abs((startDay.getTime() - endDay.getTime()) / (millisecondsPerDay));
        $(".ad-tdays").text(diffDays);
        $("#ad_total_days").val(diffDays);
        var adpocost = $('#adposi').find('option:selected', this).attr('mytag');
        $(".ad-pocost").text(adpocost);
        $("#ad_cost_per_day").val(adpocost);
        var totcost = diffDays * adpocost;
        $(".ad-tcost").text(totcost);
        $("#ad_total_cost").val(totcost);
    });

    //ADS TOTAL DAYS CALCULATION
    $("#start-date, #end-date").change(function () {
        var firstDate = $("#start-date").val();
        var secondDate = $("#end-date").val();
        var millisecondsPerDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var startDay = new Date(firstDate);
        var endDay = new Date(secondDate);
        var diffDays = Math.abs((startDay.getTime() - endDay.getTime()) / (millisecondsPerDay));
        $(".ad-tdays").text(diffDays);
        $("#ad_total_days").val(diffDays);
        // var adpocost = $('#adposi').find('option:selected', this).attr('mytag');
        var adpocost = $('#adposi').val();
        $(".ad-pocost").text(adpocost);
        $("#ad_cost_per_day").val(adpocost);
        var totcost = diffDays * adpocost;
        $(".ad-tcost").text(totcost);
        $("#ad_total_cost").val(totcost);
    });

    //Buy Point Cost CALCULATION Starts

    $('#new_points').on('input', function() {
        var new_points = $("#new_points").val();
        var cost_per_point = $("#cost_per_point").val();
        var cost_symbol = $("#cost_symbol").val();
        var totcost = new_points * cost_per_point;
        var button_msg = 'Click To Pay '+cost_symbol + totcost;
        $("#buy_points_submit").text(button_msg);
        $("#all_cost").val(totcost);
    });

    //Buy Point Cost CALCULATION Ends

    //COPY RIGHTS YEAR
    $('#cry').text("2050");

    //PRE LOADING
    $('#status').fadeOut();
    $('#preloader').delay(350).fadeOut('slow');
    $('body').delay(350).css({
        'overflow': 'visible'
    });

    //BLOG POST SEARCH - BLOG DETAIL PAGE
    $("#pg-sear").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#pg-resu *").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //BIZBOOK EDITOR
    $(".bixedtr-tools").html("<div class='fore-wrapper'><i class='material-icons' style='color:#10a5ef;'>format_color_text</i> <div class='fore-palette'> </div> </div> <div class='back-wrapper'><i class='material-icons' style='background:#cbeeff;'>format_color_text</i> <div class='back-palette'> </div> </div> <a href='javascript:void(0)' data-command='bold'><i class='material-icons'>format_bold</i></a> <a href='javascript:void(0)' data-command='italic'><i class='material-icons'>format_italic</i></a> <a href='javascript:void(0)' data-command='underline'><i class='material-icons'>format_underlined</i></a> <a href='javascript:void(0)' data-command='justifyLeft'><i class='material-icons'>format_align_left</i></a> <a href='javascript:void(0)' data-command='justifyCenter'><i class='material-icons'>format_align_center</i></a> <a href='javascript:void(0)' data-command='justifyRight'><i class='material-icons'>format_align_right</i></a> <a href='javascript:void(0)' data-command='justifyFull'><i class='material-icons'>format_align_justify</i></a> <a href='javascript:void(0)' data-command='indent'><i class='material-icons'>format_indent_increase</i></a> <a href='javascript:void(0)' data-command='outdent'><i class='material-icons'>format_indent_decrease</i></a> <a href='javascript:void(0)' data-command='insertUnorderedList'><i class='material-icons'>format_list_bulleted</i></a> <a href='javascript:void(0)' data-command='insertOrderedList'><i class='material-icons'>format_list_numbered</i></a> <a href='javascript:void(0)' data-command='h2'>H2</a> <a href='javascript:void(0)' data-command='h4'>H4</a> <a href='javascript:void(0)' data-command='createlink'><i class='material-icons'>insert_link</i></a> <a href='javascript:void(0)' data-command='unlink'><i class='material-icons'>leak_remove</i></a><a href='javascript:void(0)' data-command='p'>P</a>");

    var colorPalette = ['000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFFFF', '004fff'];
    var forePalette = $('.fore-palette');
    var backPalette = $('.back-palette');

    for (var i = 0; i < colorPalette.length; i++) {
        forePalette.append('<a href="#" data-command="forecolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></a>');
        backPalette.append('<a href="#" data-command="backcolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></a>');
    }

    $('.biz-toolbar a').click(function(e) {
        var command = $(this).data('command');
        if (command == 'h2' || command == 'h4' || command == 'p') {
            document.execCommand('formatBlock', false, command);
        }
        if (command == 'forecolor' || command == 'backcolor') {
            document.execCommand($(this).data('command'), false, $(this).data('value'));
        }
        if (command == 'createlink' || command == 'insertimage') {
            url = prompt('Enter the link here: ', 'http:\/\/');
            document.execCommand($(this).data('command'), false, url);
        } else document.execCommand($(this).data('command'), false, null);
    });


});

//COMMON DATEPICKER
$( function() {
    $( "#newdate" ).datepicker({ minDate: 0});
    $('.datepicker').each(function(){
        $(this).datepicker({ minDate: 0});
    });
} );

//GET URL SOURCE
function urlParam(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    } else {
        return results[1] || 0;
    }
}
//URL PAREM VALUE
$("#source").val(urlParam("source"));
if (urlParam("login") == "register") {
    $('.log-1, .log-3').slideUp();
    $('.log-2').slideDown();
}
if (urlParam("login") == "forgot") {
    $('.log-1, .log-2').slideUp();
    $('.log-3').slideDown();
}
//CATEGORY FILTER ONLOAD ACTIVE
if (urlParam("features") == "trending") {
    $('.lhs-featu input:checkbox').removeAttr('checked');
    $('#trending').attr('checked', 'checked');
}

//SHARE URL
var _cururl = window.location.href;
$("#shareurl").val(_cururl);
function shareurl() {
    var copyText = document.getElementById("shareurl");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);

    var tooltip = document.getElementById("myTooltip");
    tooltip.innerHTML = "Copied";
}

function shareurlout() {
    var tooltip = document.getElementById("myTooltip");
    tooltip.innerHTML = "Copy to clipboard";
}

//SEARCH FUNCTION
$(function () {
    var availlistings = [
        "Rn53 themes",
        "Real estates",
        "Room rent",
        "Directory and listing",
        "Health care in new york",
        "Child specality",
        "Plumping services",
        "Care and bike services",
        "Care showrooms",
        "Bike showroom",
        "Best t-shirts for men",
        "Tution center",
        "IT training institute",
        "Ui developer training center",
        "Job openings",
        "Lifestyle shops",
        "Medicale and health care",
        "Child care hospital",
        "Univercity and colleges",
        "Trust and help center",
        "Drinking water service",
        "Travel and transport",
        "Events"
    ];
    $("#search").autocomplete({
        source: availlistings
    });
});

//CITY SEARCH
$(function () {
    var availlcity = [
        "Alexandria",
        "Aurora",
        "Austin",
        "Chennai",
        "Delhi",
        "Mumbai",
        "Bangalure",
        "Chandler",
        "Dallas",
        "Dayton",
        "Elizabeth",
        "Eugene",
        "Gilbert",
        "Houston",
        "Jackson",
        "Lincoln",
        "Madison",
        "Memphis",
        "Orlando",
        "Phoenix",
        "Savannah",
        "Warren"
    ];
    $("#city").autocomplete({
        source: availlcity
    });
});

//DATE PICKER
$(function () {
    $("#event_start_date").datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });
    $("#stdate").datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });
    $("#endate").datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });
});


//FOLLOW & UN FOLLOW Logic Starts




//URL SOURCE TRACKING
// function getsource() {
//     var queryParams = {},
//         param;
//     var params = window.location.search.substring(1);
//     for (var i = 0; i < params.length; i++) {
//         param = params[i].split('=');
//         queryParams[param[0]] = param[1];
//     }
//    document.getElementById("source").value = params;
//     return params;
// }


//Number Only Input box

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

//Search Results Page Search Box Process Starts
jQuery(document).ready(function ($) {
    $("#search_result_page_filter_submit").click(function () {
        event.preventDefault();

        if(webpage_full_link != null){
            var link = webpage_full_link;
        }else{
            var link = '';
        }

        var expert_select_search = $("#select-search").val();
        window.location.href = link + "search_box_process.php?expert_select_search=" + expert_select_search;

    });
});
//Search Results Page Search Box Process Ends


//Header Page Search Box Process Starts
jQuery(document).ready(function ($) {
    $("#filter_submit").click(function () {
        event.preventDefault();

        if(webpage_full_link != null){
            var link = webpage_full_link;
        }else{
            var link = '';
        }

        var explor_select = $("#explor_select").val();
        var expert_select_search = $("#expert-select-search").val();
        var select_city = $("#city_check").val();
        window.location.href = link + "search_box_process.php?explor_select=" + explor_select + "&expert_select_search=" + expert_select_search + "&select_city=" + select_city;

    });
});
//Header Page Search Box Process Ends

//Header Page Top Search Box with enter key press Process Starts

$('#top-select-search').keypress(function(e) {
    var key = e.which;
    if (key == 13) // the enter key code
    {
        event.preventDefault();
        if(webpage_full_link != null){
            var link = webpage_full_link;
        }else{
            var link = '';
        }
        var expert_select_search = $("#top-select-search").val();
        var select_city = $("#select-city").val();
        window.location.href = link + "search_box_process.php?expert_select_search=" + expert_select_search + "&select_city=" + select_city;

        //return false;
    }
});
//Header Page Top Search Box with enter key press Process ends

//Header Page Top Search Box Process Starts
jQuery(document).ready(function ($) {
    $("#top_filter_submit").click(function () {
        event.preventDefault();

        if(webpage_full_link != null){
            var link = webpage_full_link;
        }else{
            var link = '';
        }

        var select_search = $("#top-select-search").val();
        var select_city = $("#select-city").val();

        window.location.href = link + "search_box_process.php?select_search=" + select_search + "&select_city=" + select_city;

    });
});
//Header Page Top Search Box Process Ends

//Job Page Top Search Box Process Starts
jQuery(document).ready(function ($) {
    $("#job_filter_submit").click(function () {

        event.preventDefault();

        if(webpage_full_link != null){
            var link = webpage_full_link;
        }else{
            var link = '';
        }

        var select_search = $("#job-select-search").val();
        var select_city = $("#job-select-city").val();

        //alert(select_search);

        window.location.href = link + "jobs/job_search_box_process.php?select_search=" + select_search + "&select_city=" + select_city;

    });
});
//Job Page Top Search Box Process Ends


//Places Page Top Search Box Process Starts
jQuery(document).ready(function ($) {
    $("#place_filter_submit").click(function () {

        event.preventDefault();

        if(webpage_full_link != null){
            var link = webpage_full_link;
        }else{
            var link = '';
        }

        var select_search = $("#place-select-search").val();

        //alert(select_search);

        window.location.href = link + "places/place_search_box_process.php?select_search=" + select_search;

    });
});
//Places Page Top Search Box Process Ends


$('.count1').each(function () {
    $(this).prop('Counter', 0).animate({
        Counter: $(this).text()
    }, {
        duration: 5000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});

//Get User Location info
// jQuery(document).ready(function ($) {

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);

    function showPosition(position) {
        // alert("Latitude: " + position.coords.latitude +
        //     "<br>Longitude: " + position.coords.longitude);

        if(webpage_full_link != null){
            var link = webpage_full_link +'geo-location-process.php';
        }else{
            var link = 'geo-location-process.php';
        }

        var main_coordinates = "latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude;
        $.ajax({
            type: "POST",
            url: link,
            data: main_coordinates,
            cache: false,
            success: function (html) {
                return true;
            }
        });
    }
}
// });


//HOME PAGE SEARCH - INTERNAL PAGE SEARCH
$("#select-search").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $("#tser-res li").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

//HOME PAGE SEARCH - INTERNAL PAGE SEARCH
$("#top-select-search").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $("#tser-res1 li").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

//SEARCH JS
var htmlElement = document.getElementById("res");
//SEARCH EVENTS
$(".search-field").focus(function(){
    $(".tser-res1").addClass("act");
});
$(".search-field").click(function(){
    $(".tser-res1").addClass("act");
});
$(".ad-dash").click(function(){
    $(".tser-res1").removeClass("act");
});
$(".ban-search").mouseleave(function(){
    $(".tser-res1").removeClass("act");
});

$("#top-select-search").focus(function(){
    $(".tser-res2").addClass("act");
});
$("#top-select-search").click(function(){
    $(".tser-res2").addClass("act");
});
$(".hom-top").mouseleave(function(){
    $(".tser-res2").removeClass("act");
});


if ($(".chosen-select").length  > 0) {
    $(function () {
        $('.chosen-select').chosen();
    });
}

var li = $('#tser-res li');
var liSelected;
$(window).keydown(function(e){

    if(e.which === 40){
        if(liSelected){
            liSelected.removeClass('selected');
            next = liSelected.next();
            if(next.length > 0){
                liSelected = next.addClass('selected');
            }else{
                liSelected = li.eq(0).addClass('selected');
            }
        }else{
            liSelected = li.eq(0).addClass('selected');
        }
    }else if(e.which === 38){
        if(liSelected){
            liSelected.removeClass('selected');
            next = liSelected.prev();
            if(next.length > 0){
                liSelected = next.addClass('selected');
            }else{
                liSelected = li.last().addClass('selected');
            }
        }else{
            liSelected = li.last().addClass('selected');
        }
    }
});

$("li.selected a").keypress(function(e) {
    if (e.keyCode == 13) {
        $(this).click();
    }
});

//Chat Box On Change ajax call starts

$('#chat_to_user').change(function() {
    var sender_id = $(this).attr("data-id");
    var receiver_id = $(this).val();
    var sender_checklist = "&sender_id="+sender_id;
    var receiver_checklist = "&receiver_id="+receiver_id;
    var main_string = sender_checklist+receiver_checklist;
    main_string = main_string.substring(1, main_string.length);

    if(webpage_full_link != null){
        var link = webpage_full_link +'filter_chat.php';
    }else{
        var link = 'filter_chat.php';
    }

    $.ajax({
        type: "POST",
        url: link,
        data: main_string ,
        cache: false,
        success: function(html){
            //alert(html);
            $(".chat-box-messages").html(html);
            $(".chat-box-messages").css("opacity",1);

            // $("#loaderID").css("opacity",0);
        }
    });
});

//Chat Box On Change ajax call ends
$(document).ready(function(){ 
    $('.tab-a').click(function(){  
      $(".tab").removeClass('tab-active');
      $(".tab[data-id='"+$(this).attr('data-id')+"']").addClass("tab-active");
      $(".tab-a").removeClass('active-a');
      $(this).parent().find(".tab-a").addClass('active-a');
     });
});
//Clear All Notifications button On Click ajax call starts

$('#noti-clr-noti').click(function() {

    var main_string = 1;

    if(webpage_full_link != null){
        var link = webpage_full_link +'clear_all_notification.php';
    }else{
        var link = 'clear_all_notification.php';
    }

    $.ajax({
        type: "POST",
        url: link,
        data: main_string ,
        cache: false,
        success: function(res){
            //alert(res);
            $("#noti-clr-noti").css("display", "none");
            $("#all-notif-ul li").hide("slide", {direction: "right"}, 2000);
            setTimeout(function(){
                $("#no-noti-clr-noti").css("display", "block");
            }, 3000);
            setTimeout(function(){
                $("#noti-count").html("00");
            }, 3000);
        }
    });
});

///
$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 250) {
        $(".hom-top").addClass("dmact");
    }
    else {
        $(".hom-top").removeClass("dmact");
    }
});
$('.multiple-items').slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    responsive: [{
        breakpoint: 992,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: false
        }
    }]

});

$('.travel-sliser-auto').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    responsive: [{
        breakpoint: 992,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: false
        }
    }]

});

var items = $(".blog-wrapper .blog-item");
var numItems = items.length;
var perPage = 12;

items.slice(perPage).hide();

$('#blog-pagination-container').pagination({
    items: numItems,
    itemsOnPage: perPage,
    prevText: "&laquo;",
    nextText: "&raquo;",
    onPageClick: function (pageNumber) {
        var showFrom = perPage * (pageNumber - 1);
        var showTo = showFrom + perPage;
        items.hide().slice(showFrom, showTo).show();
        $("html, body").animate({ scrollTop: 0 }, "fast");
        return false;
    }
});
$('.multiple-items1').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    responsive: [{
        breakpoint: 992,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: false
        }
    }]

});
//Clear All Notifications button On Click ajax call ends