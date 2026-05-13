
<div class="card shadow-sm mt-4">

    <div class="card-body">

                        <form id="login_form" name="login_form" method="post" action="{{url('admin/post-job-form')}}"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="heading t">
                                <h3><b>ADD Details</b></h3>
                            </div>

                            <div class="add-type">

                                <div class="add-heading">
                                    Salary period *
                                </div>

                                <div class="select-add-type">

                                    @if(old('salary_period') != "")

                                        <button value="{{old('salary_period')}}" type="button" class="add-type1 salary_period">

                                            {{old('salary_period')}}

                                        </button>

                                    @endif

                                    <button value="Hourly" type="button" class="add-type1 salary_period">

                                        Hourly

                                    </button>

                                    <button value="Weekly" type="button" class="add-type1 salary_period">

                                        Weekly

                                    </button>

                                    <button value="Monthly" type="button" class="add-type1 salary_period">

                                        Monthly

                                    </button>

                                    <button value="Yearly" type="button" class="add-type1 salary_period">

                                        Yearly

                                    </button>

                                    <input type="hidden" name="salary_period" id="hidden-salary-period">

                                </div>

                            </div>

                            <div class="add-type">

                                <div class="add-heading">
                                    Position Type
                                </div>

                                <div class="select-add-type">

                                    @if(old('position_type') != "")

                                        <button value="{{old('position_type')}}" type="button" class="add-type1 salary_period">

                                            {{old('position_type')}}

                                        </button>

                                    @endif

                                    @if(isset($jobs))

                                        @foreach($jobs as $orderDetail)

                                            <button value="{{ ucfirst($orderDetail->jobtype) }}" type="button"
                                                class="add-type1 position_type">

                                                {{ ucfirst($orderDetail->jobtype)}}

                                            </button>

                                        @endforeach

                                    @endif

                                    <input type="hidden" name="position_type" id="hidden-position-type">

                                </div>

                            </div>

                            <div class="add-type">

                                <div class="add-heading">
                                    Salary from *
                                </div>

                                <input type="number" autocomplete="off" value="{{ old('salary_from') }}" name="salary_from"
                                    id="salary_from" class="form-control" placeholder="Enter Salary From*" required>

                            </div>

                            <div class="add-type">

                                <div class="add-heading">
                                    Salary to *
                                </div>

                                <input type="number" autocomplete="off" value="{{ old('salary_to') }}" name="salary_to"
                                    id="salary_to" class="form-control" placeholder="Enter Salary To*" required>

                                <p class="text-danger mt-2">
                                    If you dont want to show the price, than input 0 in price field*
                                </p>

                            </div>

                            <div class="add-type">

                                <div class="add-heading">
                                    Post Title
                                </div>

                                <input type="text" autocomplete="off" value="{{ old('ad_title') }}" name="ad_title"
                                    id="ad_title" class="form-control" placeholder="Enter post title*" required>

                            </div>

                            <div class="add-type">

                                <div class="add-heading">
                                    Description *
                                </div>

                                <textarea autocomplete="off" id="description" class="form-control" maxlength="4096"
                                    name="description" placeholder="Enter Description">{{ old('description') }}</textarea>

                                <span class="float-right counter-text">
                                    <span id="counter-display">0</span>/4096
                                </span>

                            </div>

                            <hr class="add-post-hr">

                            <div class="add-type">
                                <div class="add-heading price"> Upload up to 5 photos</div>
                                <div class="select-add-type">

                                    <div class="upload-photo-cont active" id="image">
                                        <span class="close closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" id="OpenImgUpload" type="file">
                                            <img style="height:100px;width:100px;display:none" id="blah"
                                                src="{{ old('file') }}" alt="your image" />
                                            <svg width="36px" class="blah" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file" value="{{ old('file') }}" id="imgupload"
                                                style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo">Add photo</span>
                                        </div>
                                    </div>
                                    <div class="upload-photo-cont" id="image1">
                                        <span class="close1 closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" id="Image_1" type="input">

                                            <img style="height:100px;width:100px;display:none" id="blah_1"
                                                src="{{ old('file1') }}" alt="your image" />
                                            <svg width="36px" class="blah_1" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file1" id="imgupload_1" value="{{ old('file1') }}"
                                                style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_1">Add photo</span>
                                        </div>
                                    </div>
                                    <div class="upload-photo-cont" id="image2">
                                        <span class="close2 closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" type="input" id="Image_2">

                                            <img style="height:100px;width:100px;display:none" id="blah_2"
                                                src="{{ old('file2') }}" alt="your image" />
                                            <svg width="36px" class="blah_2" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file2" id="imgupload_2" value="{{ old('file2') }}"
                                                style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_2">Add photo</span>
                                        </div>
                                    </div>
                                    <div class="upload-photo-cont" id="image3">
                                        <span class="close3 closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" type="input" id="Image_3">

                                            <img style="height:100px;width:100px;display:none" id="blah_3"
                                                src="{{ old('file3') }}" alt="your image" />
                                            <svg width="36px" class="blah_3" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file3" id="imgupload_3" value="{{ old('file3') }}"
                                                style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_3">Add photo</span>
                                        </div>
                                    </div>
                                    <div class="upload-photo-cont" id="image4">
                                        <span class="close4 closed" style="display:none;cursor: pointer;">&times;</span>
                                        <div class="sing-img-preview" type="input" id="Image_4">

                                            <img style="height:100px;width:100px;display:none" id="blah_4"
                                                src="{{ old('file4') }}" alt="your image" />
                                            <svg width="36px" class="blah_4" height="36px" viewBox="0 0 1024 1024"
                                                data-aut-id="icon" class="" fill-rule="evenodd">
                                                <path class="rui-2qwuD"
                                                    d="M861.099 667.008v78.080h77.568v77.653h-77.568v77.141h-77.568v-77.184h-77.611v-77.611h77.611v-78.080h77.568zM617.515 124.16l38.784 116.437h165.973l38.827 38.827v271.659l-38.827 38.357-38.741-38.4v-232.832h-183.125l-38.784-116.48h-176.853l-38.784 116.48h-183.083v426.923h426.667l38.784 38.357-38.784 39.253h-465.493l-38.741-38.869v-504.491l38.784-38.827h165.973l38.827-116.437h288.597zM473.216 318.208c106.837 0 193.92 86.955 193.92 194.048 0 106.923-87.040 194.091-193.92 194.091s-193.963-87.168-193.963-194.091c0-107.093 87.083-194.048 193.963-194.048zM473.216 395.861c-64.213 0-116.352 52.181-116.352 116.395 0 64.256 52.139 116.437 116.352 116.437 64.171 0 116.352-52.181 116.352-116.437 0-64.213-52.181-116.437-116.352-116.437z">
                                                </path>
                                            </svg><br>
                                            <input type="file" name="file4" id="imgupload_4" value="{{ old('file4') }}"
                                                style="display:none;" accept="image/jpeg, image/png">
                                            <span class="text-center" id="add_photo_4">Add photo</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-type">
                                <span class="text-danger">Atleast 1 Image is required*</span>
                            </div>

                            <hr class="add-post-hr">

                            <div class="add-type">

                                <div class="add-heading">
                                    Confirm your location
                                </div>

                                <ul class="tabs">

                                    <li class="tab-link current current_list" data-tab="list">

                                        Custom Location

                                    </li>

                                    <li class="tab-link get_current_location" data-tab="c-location">

                                        Current Location

                                    </li>

                                </ul>

                                <div id="list" class="tab-content current">

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Select State</label>

                                                <select name="state" id="state" class="form-control chosen-select">

                                                    @if(old('state') != "")

                                                        <option value="{{old('state')}}">

                                                            {{\App\Models\States::findOrFail(old('state'))->name}}

                                                        </option>

                                                    @else

                                                        <option value="">
                                                            Select State
                                                        </option>

                                                    @endif

                                                    @foreach($state as $orderDetails)

                                                        <option value="{{$orderDetails->id}}">
                                                            {{$orderDetails->name}}
                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Select City</label>

                                                <select name="city" id="city" class="form-control chosen-select select-box">

                                                    @if(old('city') != "")

                                                        <option value="{{old('city')}}">

                                                            {{\App\Models\City::findOrFail(old('city'))->name}}

                                                        </option>

                                                    @else

                                                        <option value="">
                                                            Select City
                                                        </option>

                                                    @endif

                                                </select>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Neighbourhood *</label>

                                                <input type="text" name="neibourhood" id="neibourhood"
                                                    value="{{old('neibourhood')}}" placeholder="Enter Neighbourhood"
                                                    class="form-control">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div id="c-location" class="tab-content">
                                    <div class="live location">
                                        <ul class="list-style-none">
                                            <li>State<span
                                                    class="float-right state_name">{{ @$locationinfo->regionName}}</span>
                                            </li>
                                            <input type="hidden" class="hidden_state_name" value="" name="state_name">
                                            <li>City<span class="float-right city_name">{{ @$locationinfo->cityName}}</span>
                                            </li>
                                            <input type="hidden" class="hidden_neibourhood" value=""
                                                name="neibourhood_name">
                                            <li>Neighbourhood<span
                                                    class="float-right">{{ @$locationinfo->latitude ?? ''}}{{ $locationinfo->longitude ?? ''}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>

                            <input type="hidden" name="fullname" value="{{$userinfo->name}}" />

                            <input type="hidden" name="mobile" value="{{$userinfo->mobile}}" />

                            <input type="hidden" name="email" value="{{$userinfo->email}}" />

                            <input type="hidden" name="location" value="{{$userinfo->address}}" />

                            <input type="hidden" name="user_id" value="{{$userinfo->id}}" />

                            <input type="hidden" name="formtype" value="{{$form_id}}" />

                            <input type="hidden" name="category_id" value="{{$categoryid}}" />

                            <input type="hidden" name="subcatid" value="{{$subcatid}}" />

                            <div class="mt-4">

                                <button type="submit" name="login_submit" value="submit"
                                    class="btn btn-primary form-control">

                                    Post Advertisement

                                </button>

                            </div>

                        </form>

                    </div>
                    </div>

    <script>

        $(document).on("click", ".salary_period", function () {

            var id = $(this).val();

            $(".salary_period").removeClass("active");

            $(this).addClass('active');

            $("#hidden-salary-period").val(id);

        });

        $(document).on("click", ".position_type", function () {

            var id = $(this).val();

            $(".position_type").removeClass("active");

            $(this).addClass('active');

            $("#hidden-position-type").val(id);

        });

       $(document).on("keyup", "#description", function () {

            var textlen = 0 + $(this).val().length;

            $('#counter-display').text(textlen);

        });

       $(document).on("click", "ul.tabs li", function () {

            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');

            $('.tab-content').removeClass('current');

            $(this).addClass('current');

            $("#" + tab_id).addClass('current');

        });

        $(document).on("click", ".get_current_location", function () {

            var state_name = $(".state_name").html();

            var city_name = $(".city_name").html();

            $(".hidden_state_name").val(state_name);

            $(".hidden_city_name").val(city_name);

        });

       $(document).on("click", ".current_list", function () {

            var empty_data = '';

            $(".hidden_state_name").val(empty_data);

            $(".hidden_city_name").val(empty_data);

        });

      $(document).on("change", "#state", function () {

    var state_id = $('#state').val();

    $.ajax({

        url: '{{url("get-city")}}',

        method: 'POST',

        data: {
            state_id: state_id,
            '_token': "{{csrf_token()}}"
        },

        success: function (data) {

            $('#city').html(data);

            $('#city').trigger("chosen:updated");

        }

    });

});

        function restrictNumber(e) {

            var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");

            this.value = newValue;
        }

        $(document).on('input', '#salary_from', restrictNumber);

$(document).on('input', '#salary_to', restrictNumber);

       
   
    
                            </script>
