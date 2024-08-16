@extends('maptemplate')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/daterangepicker.min.css')}}" />
<link href="{{ url('public/css/bootstrap-slider.min.css') }}" rel="stylesheet" type="text/css" />
<link  rel="stylesheet" type="text/css" href="{{ url('public/css/glyphicon.css') }}"/>

@endpush
@section('main')
<style>
 .switch {
  position: relative;
  display: inline-block;
  width: 90px;
  height: 34px;
}
.switch input {display:none !important;}

.slider.round {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  -webkit-transition: .4s;
  transition: .4s;
   border-radius: 34px;
}
/*.slider.slider-horizontal{
    left:25px !important;
    top:15px !important;
}*/
.slider.round{
    background-color:red;
}
.slider.round:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #2ab934;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(55px);
}

/*------ ADDED CSS ---------*/
.slider.round:after
{
 content:'OFF';
 color: #fff;
 display: block;
 position: absolute;
 transform: translate(-50%,-50%);
 top: 50%;
 left: 50%;
 font-size: 12px;font-weight:600;
}

input:checked + .slider:after
{  
  content:'ON';
}

/*--------- END --------*/
 
</style>
    <div class="container-fluid bg-white main-panel border-0 p-0 sv_search_page margin-top-100">
        <div class="row d-none d-sm-block">
            <div class="col-md-12 mt-md-4">
                <h2 class="mb-0">{{trans('messages.search.results_for')}} <strong class="text-24">{{$location}}</strong></h2>
            </div>
        </div>
        <!--New filter toggle-->
        <div class="d-block d-sm-none row">
            <p class="col-12 mb-0 text-center font-weight-600" id="mobile-filter-option">{{trans('messages.filter.filter')}} <i class="fas fa-sliders-h"></i></p>
        </div>
        <!--New filter toggle end-->
        <div id="mob-filter-area" class="hide-filter">  <!--newly added div for filter toggle-->
        <div class="d-flex justify-content-between search-page-flex" id="filter-bar">
                    <div>
                        <ul class="list-inline sv_list pl-4">
                            <li class="list-inline-item mt-4">
                                <div class="dropdown">
                                    <button class="btn text-16 border border-r-25 pl-4 pr-4 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{trans('messages.trips_active.location')}}
                                    </button>
                                    
                                    <div class="w-100">
                                        <div class="dropdown-menu dropdown-menu-location" aria-labelledby="dropdownMenuButton">
                                            <div class="row p-3">
                                                <form id="front-search-form" method="post" action="{{url('search')}}">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3 class="font-weight-700 text-14">{{trans('messages.header.where_are_you_going')}} </h3>
                                                            <div class="input-group mt-4">
                                                                <input class="form-control p-3 text-14" id="front-search-field" value="{{$location}}" autocomplete="off" name="location" type="text" required>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-12 p-0">
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <div class="d-flex" id="daterange-btn">
                                                                        <div class="pr-2">
                                                                            <h3 class="font-weight-700 mt-4 text-14">{{trans('messages.search.check_in')}}</h3>
                                                                            <div class="input-group mr-2" >
                                                                                <input class="form-control p-3 border-right-0 border text-14 checkinout" name="checkin" id="startDate" type="text" placeholder="{{trans('messages.search.check_in')}}" value="{{$checkin}}" autocomplete="off" readonly="readonly" required>
                                                                                <span class="input-group-append">
                                                                                    <div class="input-group-text">
                                                                                        <i class="fa fa-calendar success-text text-14"></i>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                        </div>
    
                                                                        <div>
                                                                            <h3 class="font-weight-700 mt-4 text-14">{{trans('messages.search.check_out')}}</h3>
                                                                            <div class="input-group ml-2">
                                                                                <input class="form-control p-3 border-right-0 border text-14 checkinout" name="checkout" id="endDate" type="text" placeholder="{{trans('messages.search.check_out')}}"  value="{{$checkout}}" readonly="readonly" required>
                                                                                <span class="input-group-append">
                                                                                    <div class="input-group-text">
                                                                                    <i class="fa fa-calendar success-text text-14"></i>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-md-3">
                                                                    <h3 class="font-weight-700 mt-4 text-14">{{trans('messages.search.guest')}}</h3>
                                                                    <select class="form-control text-16"  id="front-search-guests" name="guests">
                                                                        @for($i=1;$i<=16;$i++)
                                                                        <option value="{{ $i }}" <?php if($guest==$i) echo "selected"; ?> > {{ ($i == '16') ? $i.'+ ' : $i }} </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                            
                                                        <div class="col-md-12 mt-5 text-center">
                                                            <button class="btn vbtn-outline-success text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3" type="submit">
                                                                <i class="fa fa-search" aria-hidden="true"></i>
                                                            {{trans('messages.header.find_place')}} 
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="list-inline-item  mt-4">
                                <button class="btn text-16 border border-r-25 pl-4 pr-4 dropdown-toggle" type="button" id="dropdownRoomType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('messages.search.room_type')}}
                                </button>
    
                                <div class="dropdown-menu dropdown-menu-room-type" aria-labelledby="dropdownRoomType">
                                    <div class="row p-3">
                                        @foreach($space_type as $rws=>$value)
                                            <div class="col-md-12">
                                              
                                                    <div class="d-flex justify-content-between pr-4">
                                                        <div>
                                                            <p class="text-16"><i class="icon icon-entire-place"></i> {{ $value }}</p>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" id="space_type_{{ $rws }}" name="space_type[]" value="{{ $rws }}" class="form-check-input" {{ in_array($rws, $space_type_selected)?'checked':'' }}>
                                                        </div>
                                                    </div>
                                               
                                            </div>
                                        @endforeach
                                        <div class="col-md-12 text-right mt-4">
                                            <button class="btn vbtn-success text-16 font-weight-700  rounded" id="btnRoom">{{trans('messages.utility.apply')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="list-inline-item  mt-4">
                                <button class="btn text-16 border border-r-25 pl-4 pr-4 dropdown-toggle" type="button" id="dropdownBookingType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('messages.listing_book.booking_type')}}
                                </button>
    
                                <div class="dropdown-menu caed-raise dropdown-menu-room-type" aria-labelledby="dropdownRoomType">
                                    <div class="row p-3">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between pr-4">
                                                <div>
                                                    <p class="text-16"><i class="fa fa-clock text-beach"></i> {{trans('messages.property_single.request_book')}}</p>
                                                </div>
                                                <div>
                                                    <input type="checkbox" name="book_type[]" class="form-check-input" value="request">
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between pr-4">
                                                <div>
                                                    <p class="text-16"><i class="fa  fa-bolt text-beach"></i>  {{trans('messages.property_single.instant_book')}}</p>
                                                </div>
                                                <div>
                                                    <input type="checkbox" name="book_type[]" class="form-check-input"  value="instant">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-right mt-4">
                                            <button class="btn vbtn-success text-16 font-weight-700  rounded" id="btnBook">{{trans('messages.utility.apply')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                             <li class="list-inline-item  mt-4 hide">
                                <button class="btn text-16 border border-r-25 pl-4 pr-4 dropdown-toggle" type="button" id="dropdownType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('messages.experience.type')}}
                                </button>
    
                                <div class="dropdown-menu caed-raise dropdown-menu-room-type" aria-labelledby="dropdownRoomType">
                                    <div class="row p-3">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between pr-4">
                                                <div>
                                                    <p class="text-16">Property</p>
                                                </div>
                                                <div>
                                                    <input type="checkbox" name="type[]" class="form-check-input" value="property" <?php if($type=="property") { echo "checked"; } ?>>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-between pr-4">
                                                <div>
                                                    <p class="text-16"> Experience</p>
                                                </div>
                                                <div>
                                                    <input type="checkbox" name="type[]" class="form-check-input"  value="experience" <?php if($type=="experience") { echo "checked"; } ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-right mt-4">
                                            <button class="btn vbtn-success text-16 font-weight-700  rounded" id="btnBook">{{trans('messages.utility.apply')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
    
                            <li class="list-inline-item  mt-4">
                                <button class="btn text-16 border border-r-25 pl-4 pr-4 dropdown-toggle" type="button" id="dropdownPrice" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('messages.search.price_range')}}
                                </button>
                                
                                
                                <div class="dropdown-menu dropdown-menu-price p-4" aria-labelledby="dropdownPrice">
                                    <div class="row p-3">
                                        <div class="btn text-16 border price-btn  pl-4 pr-4">
                                            <span><?php if($default_currency->symbol=="") { echo "$"; } else { echo $default_currency->symbol; } ?></span>
                                            <span  id="minPrice">{{ $min_price }}</span>
                                        </div>
            
                                        <div class="pl-4 pr-4 pt-2 min-w-250">
                                            <input id="price-range" data-provide="slider" data-slider-min="{{ $min_price }}" data-slider-max="{{ $max_price }}" data-slider-value="[{{ $min_price }},{{ $max_price }}]"/>
                                        </div>
            
                                        <div class="btn text-16 border price-btn  pl-4 pr-4 ">
                                            <span><?php if($default_currency->symbol=="") { echo "$"; } else { echo $default_currency->symbol; } ?></span>
                                            <span  id="maxPrice">{{ $min_price }}</span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-right mt-4">
                                            <button class="btn vbtn-success text-16 font-weight-700  rounded" id="btnPrice">{{ trans('messages.utility.apply') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
    
                            <li class="list-inline-item  mt-4">
                                <button type="button"  id="more_filters"   class="font-weight-500 btn text-16 border border-r-25 pl-4 pr-4" data-toggle="modal" data-target="#exampleModalCenter">
                                    {{ trans('messages.search.more_filters') }}
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="pr-5 d-flex map-switch">
                        <div class="show-map d-none" id="showMap">
                            <a href="#" class="btn text-16 border"><i class="fas fa-map-marked-alt"></i> {{ trans('messages.search.show_map') }}</a>
                        </div>
                       <span class="mt-1 text-15">{{ trans('messages.search.show_map') }}</span>
                        <label class="switch ml-3">
                            <input type="checkbox" id="togBtn" checked>
                            <div class="slider round"></div>
                        </label>
                    </div>
                </div>
            </div>
        <hr class="mb-0">
        
        <div class="row">
            <!-- Filter section start-->
            <div class="col-md-8  hidden-pod filter-h" id="listCol">
                
                <!-- No result found section start -->
                <div class="row mt-4">
                    <div id="loader" class="display-off loader-img position-center">
                        <img src="{{URL::to('/')}}/public/front/img/green-loader.gif" alt="loader">
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div id="properties_show" class="row w-100">
                        <div class="text-center justify-content-center w-100 position-center">
                            <!-- not found image -->
                        </div>
                    </div>
                </div>
                <!-- No result found section end -->

                <!-- Pagination start -->
                <div class="row mt-4 mb-5">
                    <div id="pagination">
                        <ul class="pager ml-4 pagination" id="pager">
                        <!--Pagination -->
                        </ul>
                        <div class="pl-3 text-16 mt-4"><span id="page-from">0</span> – <span id="page-to">0</span> {{ trans('messages.search.of') }} <span id="page-total">0</span> {{trans('messages.search.rentals')}}</div>
                    </div>
                </div>
                <!-- Pagination end -->
            </div>
            <!-- Filter section end -->

            <!--Map section start -->
            <div class="col-md-4 p-0" id="mapCol">
                <!--<div class="map-close" id="closeMap"><i class="fas fa-times text-24 p-3 pl-4 text-center"></i></div>-->
                <div id="map_view" class="map-view sv_mob_map"></div>
                
            </div>
            <!--Map section end -->
        </div>
        
        <!--Newly added map button-->
            <div class="d-block d-sm-none">
                <button id="map-btn" class="">{{ trans('messages.search.map') }} <i class="fas fa-map-marker-alt" aria-hidden="true"></i></button>
                <button id="res-btn" class="">{{ trans('messages.search.result') }} <i class="fa fa-list-ul" aria-hidden="true"></i></button>
            </div>
            <!--Newly added end -->

            <!-- Modal -->
            <div class="modal fade " id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-full" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="w-100 pt-3">
                                <h5 class="modal-title text-20 text-center font-weight-700" id="exampleModalLongTitle">{{ trans('messages.search.more_filters') }}</h5>
                            </div>

                            <div>
                                <button type="button" class="close text-28 mr-2" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                        </div>

                        <div class="modal-body modal-body-filter">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5 class="font-weight-700 text-24 mt-2 p-4" for="user_birthdate">{{ trans('messages.search.size') }}</h5>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row filter-size">
                                        <div class="select col-sm-4">
                                           
                                            <p class="mt-3">{{ trans('messages.search.bedrooms') }}</p>
											<input type="hidden" id="map-search-min-bedrooms" name="min_bedrooms" value="{{ $bedrooms }}">
											<div class="d-flex align-items-center mt-2">
    											<div class="value-button filter-add-value" id="bedrooms_decrease" value="Decrease Value">-</div>
											     <input type="number" id="bedrooms_number" value="0" />
    											 <div class="value-button filter-add-value" id="bedrooms_increase" value="Increase Value">+</div>
											</div>

                                        </div>

                                        <div class="select col-sm-4">
                                           
                                            <p class="mt-3">{{ trans('messages.search.bathrooms') }}</p>
                                            <input type="hidden" id="map-search-min-bathrooms" name="min_bathrooms" value="{{ $bathrooms }}">
											<div class="d-flex align-items-center mt-2">
    											<div class="value-button filter-add-value" id="bathrooms_decrease" value="Decrease Value">-</div>
											    <input type="number" id="bathrooms_number" value="0"  />
    											 <div class="value-button filter-add-value" id="bathrooms_increase" value="Increase Value">+</div>
											</div>
                                        </div>

                                        <div class="select col-sm-4">
                                          
                                            <p class="mt-3">{{ trans('messages.search.beds') }}</p>
                                            <input type="hidden" id="map-search-min-beds" name="min_beds" value="{{ $beds }}">
											<div class="d-flex align-items-center mt-2">
    											<div class="value-button filter-add-value" id="beds_decrease" value="Decrease Value">-</div>
    												  <input type="number" id="beds_number" value="0"  />
    											 <div class="value-button filter-add-value" id="beds_increase" value="Increase Value">+</div>
											</div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <h5 class="font-weight-700 text-24 pl-4" for="user_birthdate">{{ trans('messages.search.amenities') }}</h5>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row">
                                        @php $row_inc = 1 @endphp

                                        @foreach($amenities as $row_amenities)
                                            @if($row_inc <= 4)
                                                <div class="col-md-6">
                                                    <div class="form-check mt-4">
                                                        <input type="checkbox" name="amenities[]" value="{{ $row_amenities->id }}" class="form-check-input mt-2 amenities_array" id="map-search-amenities-{{ $row_amenities->id }}">
                                                        <label class="form-check-label mt-2 ml-25" for="exampleCheck1"> {{ $row_amenities->title }}</label>
                                                    </div>
                                                </div>
                                            @endif

                                            @php $row_inc++ @endphp
                                        @endforeach

                                        <div class="collapse" id="amenities-collapse">
                                            <div class="row">
                                                @php $amen_inc = 1 @endphp
                                                @foreach($amenities as $row_amenities)
                                                    @if($amen_inc > 4)
                                                        <div class="col-md-6 mt-4">
                                                            <div class="form-check">
                                                                <input type="checkbox" name="amenities[]" value="{{ $row_amenities->id }}" class="form-check-input mt-2 amenities_array" id="map-search-amenities-{{ $row_amenities->id }}" ng-checked="{{ (in_array($row_amenities->id, $amenities_selected)) ? 'true' : 'false' }}">
                                                                <label class="form-check-label mt-2 ml-25" for="exampleCheck1"> {{ $row_amenities->title }}</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @php $amen_inc++ @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="cursor-pointer" data-toggle="collapse" data-target="#amenities-collapse" >
                                        <span class="font-weight-600 ml-4"><u> Show all amenities</u></span>
                                        <i class="fa fa-plus"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-sm-12">
                                    <h5 class="font-weight-700 text-24 pl-4" for="user_birthdate">{{ trans('messages.search.property_type') }}</h5>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row mt-2">
                                        @php $pro_inc = 1 @endphp
                                        @foreach($property_type as $row_property_type =>$value_property_type)
                                            @if($pro_inc <= 4)
                                                <div class="col-md-6">
                                                    <div class="form-check mt-4">
                                                        <input type="checkbox" name="property_type[]" value="{{ $row_property_type }}" class="form-check-input mt-2" id="map-search-property_type-{{ $row_property_type }}">
                                                        <label class="form-check-label mt-2 ml-25" for="exampleCheck1"> {{ $value_property_type}}</label>
                                                    </div>
                                                </div>
                                            @endif
                                            @php $pro_inc++ @endphp
                                        @endforeach

                                        <div class="collapse" id="property-collapse">
                                            <div class="row">
                                                @php $property_inc = 1 @endphp
                                                @foreach($property_type as $row_property_type =>$value_property_type)
                                                    @if($property_inc > 4)
                                                        <div class="col-md-6 mt-4">
                                                            <div class="form-check">
                                                                <input type="checkbox" name="property_type[]" value="{{ $row_property_type }}" class="form-check-input mt-2" id="map-search-property_type-{{ $row_property_type }}">
                                                                <label class="form-check-label mt-2 ml-25" for="exampleCheck1"> {{ $value_property_type}}</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @php $property_inc++ @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="cursor-pointer" data-toggle="collapse" data-target="#property-collapse" >
                                        <span class="font-weight-600 text-16 ml-4"><u> Show all property type</u></span>
                                        <i class="fa fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer p-4">
                            <button class="btn btn-outline-danger text-16 pl-3 pr-3 mr-4"  data-dismiss="modal">{{ trans('messages.search.cancel') }}</button>
                            <button class="btn vbtn-outline-success filter-apply text-16 mr-5 pl-3 pr-3 ml-2" data-dismiss="modal">{{ trans('messages.search.apply_filter') }}</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>

	
	 {{--Language Modal --}}
    <div class="modal fade mt-5 z-index-high" id="languageModalCenter" tabindex="-1" role="dialog" aria-labelledby="languageModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header lang-modal-header">
                    <div class="w-100 pt-3">
                       <!-- <h5 class="modal-title text-20 text-center font-weight-700" id="languageModalLongTitle">{{ trans('messages.home.choose_language') }}</h5>-->
                    </div>

                    <div>
                        <button type="button" class="close text-28 mr-2 filter-cancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                </div>

                <div class="modal-body pb-5 language-modal">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ trans('messages.home.language') }}</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ trans('messages.home.currency') }}</a>
                              </li>
                              
                            </ul>
                            <div class="tab-content" id="myTabContent">
                              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                  <div class="row">
                                        @foreach($languagefoot as $key => $value)
                							<div class="col-md-3 col-6 mt-5 p-3">
                							    <div class="language-list p-3 text-16 {{ (Session::get('language') == $key) ? 'currency-active' : '' }}">
                							        <a href="javascript:void(0)" class="language_footer" data-lang="{{$key}}">{{$value}}</a>
                							    </div>
                							</div>
                						@endforeach
                					</div>
                              </div>
                              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                  	<div class="row">
                						@foreach($currencies as $key => $value)
                						<div class="col-6 col-sm-3 mt-5 p-3">
                							<div class="currency pl-3 pr-3 text-16 {{ (Session::get('currency') == $value->code) ? 'border rounded-5 currency-active' : '' }}">
                								<a href="javascript:void(0)" class="currency_footer " data-curr="{{$value->code}}">
                									<p class="m-0 mt-2  text-16">{{$value->name}}</p>
                									<p class="m-0 text-muted text-16">{{$value->code}} - {!! $value->org_symbol !!} </p> 
                								</a>
                							</div>
                						</div>
                						@endforeach
                
                					</div>

                              </div>
                            </div>
                        </div>
                    </div>
                    
				</div>
			</div>
		</div>
	</div>

   
    @push('scripts')
    <script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
	<script type="text/javascript" src="{{ url('public/js/front.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('public/js/jquery-ui.js') }}"></script>
	<!-- daterangepicker -->
	<script type="text/javascript" src="{{ url('public/js/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('public/js/daterangepicker.min.js')}}"></script>
    <script src="{{ url('public/js/locationpicker.jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/js/daterangecustom.js')}}"></script>
    <script type="text/javascript">
		$(function() {
            var checkin = $('#startDate').val();
            var checkout = $('#endDate').val();
			dateRangeBtn(checkin,checkout);
		});
	</script>

    <script>
        $.fn.slider = null;
    </script>
    <script src="{{ url('public/js/bootstrap-slider.min.js') }}"></script>
    <script type="text/javascript">
        var markers      = [];
        var allowRefresh = true;
        var loadPage = '{{url("search/result")}}';

        $("#price-range").slider();
        

        $("#price-range").on("slideStop", function(slideEvt) {
            var range       = $('#price-range').attr('data-value');
            range           = range.split(',');
            var min_price       = range[0];
            var max_price       = range[1];
            $('#minPrice').html(min_price);
            $('#maxPrice').html(max_price);
        });

        $('#header-search-form').on('change', function(){
            allowRefresh = true;
            deleteMarkers();
            loadPage = '{{url("search/result")}}';
            getProperties($('#map_view').locationpicker('map').map);
        });

        $("#search-pg-checkin").datepicker({
            dateFormat:"{{ Session::get('front_date_format_type')}}",
            minDate: 0,
            onSelect: function(e) {
                var t = $("#search-pg-checkin").datepicker("getDate");
                t.setDate(t.getDate() + 1), $("#search-pg-checkout").datepicker("option", "minDate", t), setTimeout(function() {
                    $("#search-pg-checkout").datepicker("show")
                }, 20);
                allowRefresh = true;
                loadPage = '{{url("search/result")}}';
                getProperties($('#map_view').locationpicker('map').map);
            }
        });

        $("#search-pg-checkout").datepicker({
            dateFormat:"{{ Session::get('front_date_format_type')}}",
            minDate: 1,
            onClose: function() {
                var e = $("#checkin").datepicker("getDate"),
                    t = $("#header-search-checkout").datepicker("getDate");
                if (e >= t) {
                    var a = $("#search-pg-checkout").datepicker("option", "minDate");
                    $("#search-pg-checkout").datepicker("setDate", a)
                }
            }, onSelect: function(){
                allowRefresh = true;
                loadPage = '{{url("search/result")}}';
                getProperties($('#map_view').locationpicker('map').map);
            }
        });

        $(document.body).on('click', '.page-data', function(e){
            e.preventDefault();
            var hr = $(this).attr('href');
            loadPage = hr;
            allowRefresh = true;
            getProperties($('#map_view').locationpicker('map').map, hr);
        });

        function addMarker(map, features){

            var infowindow = new google.maps.InfoWindow();
            for (var i = 0, feature; feature = features[i]; i++) {
				
				console.log(features);
				
				var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(feature.latitude, feature.longitude),
                    //icon: feature.icon !== undefined ? feature.icon : undefined,
                    map: map,
                    title: feature.title !== undefined? feature.title : undefined,
                    content: feature.content !== undefined? feature.content : undefined,
                     label: {
						  text: '  ' +feature.curr+ ' '+feature.svprice +' ',
						  className: "sv_map_label",
						},
					icon: " ",
                });
                markers.push(marker);

                google.maps.event.addListener(marker, 'click', function (e) {
                    
                    if(this.content){
                        infowindow.setContent(this.content);
                        infowindow.open(map, this);
                    }
                });

            }
        }

        // Sets the map on all markers in the array.
        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        // Removes the markers from the map, but keeps them in the array.
        function clearMarkers() {
            setMapOnAll(null);
        }

        // Deletes all markers in the array by removing references to them.
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        function moneyFormat(symbol, value) {
            var symbolPosition = '<?php echo currencySymbolPosition(); ?>';
            if (symbolPosition == "before") {
            val = symbol + ' ' + value;
            } else {
                val = value + ' ' + symbol;
            }
            return val;
        }

        function getProperties(map,url){

            if(loadPage) {
                url = url||'';
            p = map;
            var a = p.getZoom(),
                t = p.getBounds(),
                o = t.getSouthWest().lat(),
                i = t.getSouthWest().lng(),
                s = t.getNorthEast().lat(),
                r = t.getNorthEast().lng(),
                l = t.getCenter().lat(),
                n = t.getCenter().lng();

            var range       = $('#price-range').attr('data-value');
            range           = range.split(',');
            var map_details = a + "~" + t + "~" + o + "~" + i + "~" + s + "~" + r + "~" + l + "~" + n;
            var location    = $('#location').val();

            //Input Search value set
            $('#header-search-form').val(location);
            //Input Search value set
            var min_price       = range[0];
            var max_price       = range[1];
            $('#minPrice').html(min_price);
            $('#maxPrice').html(max_price);

            var amenities       = getCheckedValueArray('amenities');
            var property_type   = getCheckedValueArray('property_type');
            var book_type       = getCheckedValueArray('book_type');
            var space_type      = getCheckedValueArray('space_type');
            var beds            = $('#map-search-min-beds').val();
            var bathrooms       = $('#map-search-min-bathrooms').val();
            var bedrooms        = $('#map-search-min-bedrooms').val();
            var checkin         = $('#startDate').val();
            var checkout        = $('#endDate').val();
            var guest           = $('#front-search-guests').val();
            var type           = getCheckedValueArray('type');
            //var map_details = map_details;
            var dataURL = loadPage;
            // if(url != '') dataURL = url;

var rowNum = 0;
            if($('#more_filters').css('display') != 'none'){
                $.ajax({
                    url: dataURL,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'location': location,
                        'min_price': min_price,
                        'max_price': max_price,
                        'amenities': amenities,
                        'property_type': property_type,
                        'book_type':book_type,
                        'space_type': space_type,
                        'beds': beds,
                        'bathrooms': bathrooms,
                        'bedrooms': bedrooms,
                        'checkin': checkin,
                        'checkout': checkout,
                        'guest': guest,
                        'map_details': map_details,
                        'type':type,
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function (){
                        $('#properties_show').html("");
                        show_loader();
                    },
                    success: function (result) {
                        $('#page-total').html(result.total);
                        $('#page-from').html(result.from);
                        $('#page-to').html(result.to);

                        allowRefresh = false;

                        var pager = '';
                        if(result.total > 0) {
                            if(result.current_page > 1 ) pager +=  '<li class="page-item"><a class="page-data page-link" href="'+result.prev_page_url+'">Previous</a></li>';
                            if(result.current_page){
                                for(var i=1; i<= result.last_page; i++){
                                    if(result.current_page == i) {
                                        pager +=  '<li class="page-item active"><a  href="'+APP_URL+'/search/result?page='+i+'" class="page-data page-link">'+i+'</a></li>';
                                    } else {
                                        pager +=  '<li class="page-item"><a  href="'+APP_URL+'/search/result?page='+i+'" class="page-data page-link">'+i+'</a></li>';

                                    }
                                }
                            }
                    
                            if(result.next_page_url) pager +=  '<li class="page-item"><a class="page-data page-link" href="'+result.next_page_url+'">Next</a></li>';
                            $('#pager').html(pager);
                            $('#pagination').removeClass('d-none');
                        } else {
                            $('#pagination').addClass('d-none');
                        }


                        var properties = result.data;
						//console.log(properties);
                        var room_point = [];
                        var room_div   = "";
						var property_othername   = "";
                        for (var key in properties) {
                            if (properties.hasOwnProperty(key)) {
								
								var photos=properties[key].property_photos;
								//console.log(photos);
								
								 var pmeta=properties[key].sv_property_meta;
								 if (pmeta != null)
								 {
									if(properties[key].sv_property_meta.name != "undefined" && properties[key].sv_property_meta.name !== null) 
									{
										property_othername ='<p class="text-14 font-weight-700 text mb-0">' +properties[key].sv_property_meta.name +'</p>';
									}
									else
									{
										property_othername ='<p class="text-14 font-weight-700 text mb-0">' +properties[key].name +'</p>';
									}
								}
								else
								{
									property_othername ='<p class="text-14 font-weight-700 text mb-0">' +properties[key].name +'</p>';
								}
								
								 var ss=0;
								 var moneySymbol = properties[key].property_price.currency.symbol;
                                 var price       = properties[key].property_price.price;
								 var curr         = properties[key].property_price.default_code;

                                room_point[key] = {
                                   
                                    latitude: properties[key].property_address.latitude,
                                    longitude: properties[key].property_address.longitude,
                                    title: properties[key].name,
									pid: properties[key].id,
									svprice: price,
									curr:curr,
                                
                                    content: '<a href="'+APP_URL+'/properties/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" class="media-cover" target="_blank">'
                                    +'<img class="map-property-img" src="'+properties[key].cover_photo+'"alt="'+properties[key].name+'">'
                                    +'</a>'
                                    +'<div class="map-property-name">'
										
                                        +'<div class="col-xs-12 p-1">'
                                            +'<div class="location-title"><h5>'+property_othername+'</h5></div>'
                                        +'</div>'
                                    +'</div>'
												 
									
									
									
                                };
								//console.log(room_point);
                                var avg_rating = properties[key].avg_rating;
                                reviews_count = 0;
                                if(properties[key].reviews_count == 1) reviews_count = properties[key].reviews_count;
                                else if(properties[key].reviews_count > 0) reviews_count = properties[key].reviews_count;

                                    var moneySymbol = properties[key].property_price.currency.symbol;
                                    var price       = properties[key].property_price.price;
                                    var symbolWithPrice = moneyFormat(moneySymbol, price);

                                    var colDiv ='col-md-6 col-lg-4 p-2';
                                    var divCol = $('#listCol').hasClass('col-md-8');
                                    if (divCol == false) {
                                        room_div += '<div class="col-md-6 col-lg-3 p-2 pl-4 pr-4">'
                                                    +'<div class="card card-shadow card-1">'
                                                     +'<div class="">';
                                                          	var photos=properties[key].property_photos;
															console.log(photos);
															if(photos.length==0)
															{
															    if(properties[key].type=="property")
															    {
															      room_div +='<a href="'+APP_URL+'/properties/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">'
                                                                            +'<figure class="">'
                                                                                +'<img src="'+properties[key].cover_photo+'" class="room-image-container200" alt="'+properties[key].name+'"/>'
                                                                                +'<figcaption>'
                                                                                +'</figcaption>'     
                                                                            +'</figure>'        
                                                                            +'</a>';
															    }
															    else
															    {
															         room_div +='<a href="'+APP_URL+'/experiences/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">'
                                                                            +'<figure class="">'
                                                                                +'<img src="'+properties[key].cover_photo+'" class="room-image-container200" alt="'+properties[key].name+'"/>'
                                                                                +'<figcaption>'
                                                                                +'</figcaption>'     
                                                                            +'</figure>'        
                                                                            +'</a>';
															    }
															    
															}
															else {
															room_div += '<div id="multi-item-example'+properties[key].id+'" class="carousel slide carousel-multi-item" data-ride="carousel">'
																		
																		  +'<div class="controls-top">'
																		  +'<a class="btn-floating svleft sv'+photos.length+'" href="#multi-item-example'+properties[key].id+'" data-slide="prev"><i class="fa fa-chevron-left"></i></a>'
																		  +'<a class="btn-floating svright sv'+photos.length+'" href="#multi-item-example'+properties[key].id+'" data-slide="next"><i class="fa fa-chevron-right"></i></a>'
																		  +'</div>'
																		  
																		  +'<ol class="carousel-indicators sv'+photos.length+'">';
																		  var ss=0;
																		  $.each(photos,function(key,value)
																		  {
																			room_div += '<li data-target="#multi-item-example'+photos[key].property_id+'" data-slide-to="'+ss+'" class="active"></li>';
																						//+'<li data-target="#multi-item-example'+photos[key].id+'" data-slide-to="2"></li>';
																			
																			
																			if(ss==0)
																			{
																			   $('.svleft').hide();
																			   $('.svright').hide();
																			}
																			ss++;
																		   });
																		  room_div +='</ol>'
																		
																			+'<div class="carousel-inner" role="listbox">';
																			if(properties[key].type=="property")
															                {
															                    room_div +='<a href="'+APP_URL+'/properties/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">';
                                                                            }
															                else
															                {
																			    room_div +='<a href="'+APP_URL+'/experiences/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">';
															                }
															$.each(photos,function(key,value){
																if(photos[key].cover_photo=="1")
																{
																	room_div +=	'<div class="carousel-item active">';
																}
																else
																{
																	room_div +=	'<div class="carousel-item ">';
																}	
																	
																if(photos[key].type=="photo")
																{
																	room_div +=	'<div class="mb-2">'
																					+'<img class="card-img-top room-image-container200" src="'+APP_URL+'/public/images/property/'+photos[key].property_id+'/'+photos[key].photo+'" alt="Card image cap">'
																					
																				  +'</div>';
																}
																else
																{
																	room_div +=	'<div class="mb-2">'
																					+'<iframe class="card-img-top room-image-container200" src="'+photos[key].photo+'" alt="Card image cap"></iframe>'
																					
																				  +'</div>';
																}
																	
																  room_div +='</div>';			
																		
															  });
															  
															  room_div += '</div>'
																			+'</a>'
																		  +'</div>'
																			+'</div>';
															}
                                                        room_div +='</div>'
														
														 +'<div class="wishicon svwishlist'+properties[key].id+'">';
															var wish=properties[key].wishlist;
															var uid = <?php if(Auth::check()) { echo Auth::id(); } else { echo "0";} ?>;
															
														   <?php
																if(Auth::check())
																{
																	?>
																	if (wish != null)
																	{ 
																	    
																		if(properties[key].wishlist.status=="1")
																		{
																			if( properties[key].wishlist.userid==uid )
																		    {
																			    room_div +='<i class="svclose icon icon-heart" data-id="'+properties[key].id+'" id="closedid'+properties[key].id+'" ></i>';
																		    }
																		    else
																		    {
																		        room_div +='<i class="svadd icon icon-heart-alt" data-id="'+properties[key].id+'" id="wishlistid'+properties[key].id+'" ></i>';  
																		    }
																		}
																		else 
																		{ 
																			room_div +='<i class="svadd icon icon-heart-alt" data-id="'+properties[key].id+'" id="wishlistid'+properties[key].id+'" ></i>';
																		}
																	}
																	else 
																	{ 
																		room_div +='<i class="svadd icon icon-heart-alt" data-id="'+properties[key].id+'" id="wishlistid'+properties[key].id+'" ></i>';
																	} 
															
																	<?php } else { ?>
																		room_div +='<i class="icon icon-heart-alt sv_befr_login" id="wishlistid'+properties[key].id+'" ></i>';
																	<?php } ?>
																	
													   room_div +='</div>'
														
														
                                                        +'<div class="card-body p-0 pl-1 pr-1">'
                                                            +'<div class="d-flex">'
                                                                +'<div class="text">';
                                                                if(properties[key].type=="experience")
                                                                {
                                                                    room_div +='<a class="text-color text-color-hover" href="'+APP_URL+'/experiences/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">';
                                                                }
                                                                else
                                                                {
                                                                    room_div +='<a class="text-color text-color-hover" href="'+APP_URL+'/properties/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">';
                                                                }     
																	   var pmeta=properties[key].sv_property_meta;
																		if (pmeta != null)
																		{
																			if(properties[key].sv_property_meta.name != "undefined" && properties[key].sv_property_meta.name !== null) 
																			{
																				room_div +='<p class="text-14 font-weight-700 text mb-0">' +properties[key].sv_property_meta.name +'</p>';
																			}
																			else
																			{
																				room_div +='<p class="text-14 font-weight-700 text mb-0">' +properties[key].name +'</p>';
																			}
																		}
																		else
																		{
																			room_div +='<p class="text-14 font-weight-700 text mb-0">' +properties[key].name +'</p>';
																		}
																		
																			room_div +='<ul class="list-inline">';
																			if(properties[key].type=="property")
																		{
                                                                            room_div +='<li class="list-inline-item">'
                                                                                +'<p  class="text-center mb-0 text-muted text-13" >'
                                                                                    +properties[key].bedrooms
                                                                                    +'<span class=""> {{trans('messages.property_single.bedroom')}}</span>' 
                                                                                +'</p>'
                                                                            +'</li>'
                                                                            +'<li class="list-inline-item p-1">'
                                                                                +'<p class="text-center mb-0 text-muted text-13">'
                                                                                    +properties[key].bathrooms
                                                                                    +'<span class=""> {{trans('messages.property_single.bathroom')}}</span>' 
                                                                                +'</p>'
                                                                            +'</li>'
            																
            																+'<li class="list-inline-item p-1">'
                                                                                +'<p class="text-center mb-0 text-muted text-13">'
                                                                                    +properties[key].beds
                                                                                    +'<span class=""> {{trans('messages.property_single.bed')}}</span>' 
                                                                                +'</p>'
                                                                            +'</li>';
																		}
																		else
																		{
																		    room_div +='<li class="list-inline-item">'
                                                                                +'<p  class="text-center mb-0 text-muted text-13" >'
                                                                                    +properties[key].accommodates
                                                                                    +'<span class=""> {{trans('messages.experience.max_people')}} </span>' 
                                                                                +'</p>'
                                                                            +'</li>'
                                                                            +'<li class="list-inline-item p-1">'
                                                                                +'<p class="text-center mb-0 text-muted text-13">'
                                                                                    +properties[key].duration
                                                                                    +'<span class=""> {{trans('messages.experience.duration')}} </span>' 
                                                                                +'</p>'
                                                                            +'</li>'
																		}
                                                                
                                                               
                                                            room_div +='</ul>'
																		
                                                                    +'</a>'
                                                                +'</div>'
																
																
                                                            +'</div>'
                                                            
                                                            +'<div class="review-0">'
                                                                +'<div class="d-flex justify-content-between">'
																	+'<div>'
                                                                    +'<p class="text-13 mt-2 mb-0 text"><i class="fas fa-map-marker-alt"></i> '+ properties[key].property_address.city+'</p>'
																	+'</div>'
                                                                    +'<div>'
                                                                        +'<span><i class="fa fa-star text-14 yellow_color"></i>'+' '+ avg_rating
                                                                            +' '+ '('+reviews_count+')</span>'
                                                                    +'</div>'
                                                                                                                                   
                                                                +'</div>'
                                                            +'</div>'
                                                            
															 +'<div class="svred text-14">';
    															 if(properties[key].type=="experience" && properties[key].exp_booking_type=="3")
    															 {
    															     room_div +='<span class="font-weight-700">{{trans('messages.experience.packages')}} </span>';
    															 }
    															 else if(properties[key].type=="experience" && properties[key].exp_booking_type=="1" || properties[key].type=="experience" && properties[key].exp_booking_type=="2")
    															 {
    															      room_div +='<span class="font-weight-700">'+symbolWithPrice+' </span>';
                                                                 }
    															 else
    															 {
    															       room_div +='<span class="font-weight-700">'+symbolWithPrice+' / {{trans('messages.property_single.night')}} '+'</span>';
                                                                 }
															 
															room_div +=	'</div>'

                                                          
                                                        +'</div>'
                                                        +'</div>'
                                                    +'</div>';
                                    } else{
                                        
                                         room_div += '<div class="col-md-6 col-lg-4 p-2 pl-4 pr-4">'
                                                    +'<div class="card card-shadow card-1">'
                                                        +'<div class="">';
                                                          	var photos=properties[key].property_photos;
															//console.log(photos.length);
														
															if(photos.length==0)
															{
															    if(properties[key].type=="experience") 
															    {
															      room_div +='<a href="'+APP_URL+'/experiences/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">'
                                                                            +'<figure class="">'
                                                                                +'<img src="'+properties[key].cover_photo+'" class="room-image-container200" alt="'+properties[key].name+'"/>'
                                                                                +'<figcaption>'
                                                                                +'</figcaption>'     
                                                                            +'</figure>'        
                                                                            +'</a>';
															    }
															    else
															    {
															         room_div +='<a href="'+APP_URL+'/properties/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">'
                                                                            +'<figure class="">'
                                                                                +'<img src="'+properties[key].cover_photo+'" class="room-image-container200" alt="'+properties[key].name+'"/>'
                                                                                +'<figcaption>'
                                                                                +'</figcaption>'     
                                                                            +'</figure>'        
                                                                            +'</a>';
															    }
															    
															}
															else {
															room_div += '<div id="multi-item-example'+properties[key].id+'" class="carousel slide carousel-multi-item" data-ride="carousel">'
																		
																		  +'<div class="controls-top">'
																		  +'<a class="btn-floating svleft sv'+photos.length+'" href="#multi-item-example'+properties[key].id+'" data-slide="prev"><i class="fa fa-chevron-left"></i></a>'
																		  +'<a class="btn-floating svright sv'+photos.length+'"" href="#multi-item-example'+properties[key].id+'" data-slide="next"><i class="fa fa-chevron-right"></i></a>'
																		  +'</div>'
																		  
																		  +'<ol class="carousel-indicators sv'+photos.length+'">';
																		  var ss=0;
																		  $.each(photos,function(key,value)
																		  {
																			room_div += '<li data-target="#multi-item-example'+photos[key].property_id+'" data-slide-to="'+ss+'" class="active"></li>';
																						//+'<li data-target="#multi-item-example'+photos[key].id+'" data-slide-to="2"></li>';
																			ss++;
																			
																		   });
																		  room_div +='</ol>'
																		
																			+'<div class="carousel-inner" role="listbox">';
																			if(properties[key].type=="experience")
																			{
																			    room_div +='<a href="'+APP_URL+'/experiences/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">';
																			}
																			else
																			{
    																			room_div +='<a href="'+APP_URL+'/properties/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">';
																			}
																	
															$.each(photos,function(key,value){
																if(photos[key].cover_photo=="1")
																{
																	room_div +=	'<div class="carousel-item active">';
																}
																else
																{
																	room_div +=	'<div class="carousel-item ">';
																}	
																if(photos[key].type=="photo")
																{
																	room_div +=	'<div class="mb-2">'
																					+'<img class="card-img-top room-image-container200" src="'+APP_URL+'/public/images/property/'+photos[key].property_id+'/'+photos[key].photo+'" alt="Card image cap">'
																					+'</div>';
																}
																else
																{
																	room_div +=	'<div class="mb-2">'
																					+'<iframe class="card-img-top room-image-container200" src="'+photos[key].photo+'" alt="Card image cap"></iframe>'
																					
																				  +'</div>';
																}	
																  room_div +='</div>';		  
																		
															  });
															  
															  room_div += '</div>'
																			+'</a>'
																		  +'</div>'
																			+'</div>';
															}
                                                        room_div +='</div>'
                                                        
                                                       
                                                       +'<div class="wishicon svwishlist'+properties[key].id+'">';
															var wish=properties[key].wishlist;
															var uid = <?php if(Auth::check()) { echo Auth::id(); } else { echo "0";} ?>;

															//alert(uid);
															
														   <?php
																if(Auth::check())
																{
																	?>
																	if (wish != null)
																	{ 
																		if(properties[key].wishlist.status=="1")
																		{
																		    if( properties[key].wishlist.userid==uid )
																		    {
																			    room_div +='<i class="svclose icon icon-heart" data-id="'+properties[key].id+'" id="closedid'+properties[key].id+'" ></i>';
																		    }
																		    else
																		    {
																		        room_div +='<i class="svadd icon icon-heart-alt" data-id="'+properties[key].id+'" id="wishlistid'+properties[key].id+'" ></i>';  
																		    }
																		}
																		else 
																		{ 
																			room_div +='<i class="svadd icon icon-heart-alt" data-id="'+properties[key].id+'" id="wishlistid'+properties[key].id+'" ></i>';
																		}
																	}
																	else 
																	{ 
																		room_div +='<i class="svadd icon icon-heart-alt" data-id="'+properties[key].id+'" id="wishlistid'+properties[key].id+'" ></i>';
																	} 
															
																	<?php } else { ?>
																		room_div +='<i class="icon icon-heart-alt sv_befr_login" id="wishlistid'+properties[key].id+'" ></i>';
																	<?php } ?>
																	
													   room_div +='</div>'
                                                        
                                                        
                                                        +'<div class="card-body p-0 pl-1 pr-1">'
                                                            +'<div class="d-flex">'
                                                                +'<div class="text">';
                                                              	 if(properties[key].type=="experience")
                                                                 {
                                                                    room_div +='<a class="text-color text-color-hover" href="'+APP_URL+'/experiences/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">';
                                                                 }
                                                                 else
                                                                 {
                                                                    room_div +='<a class="text-color text-color-hover" href="'+APP_URL+'/properties/'+properties[key].slug+'?checkin='+checkin+'&checkout='+checkout+'&guests='+guest+'" target="_blank">';
                                                                 }
                                                                 
                                                                        var pmeta=properties[key].sv_property_meta;
																		if (pmeta != null)
																		{
																			if(properties[key].sv_property_meta.name != "undefined" && properties[key].sv_property_meta.name !== null) 
																			{
																				room_div +='<p class="text-14 font-weight-700 text mb-0">' +properties[key].sv_property_meta.name +'</p>';
																			}
																			else
																			{
																				room_div +='<p class="text-14 font-weight-700 text mb-0">' +properties[key].name +'</p>';
																			}
																		}
																		else
																		{
																			room_div +='<p class="text-14 font-weight-700 text mb-0">' +properties[key].name +'</p>';
																		}
																		
																		room_div +='<ul class="list-inline">';
																		if(properties[key].type=="property")
																		{
                                                                            room_div +='<li class="list-inline-item">'
                                                                                +'<p  class="text-center mb-0 text-muted text-13" >'
                                                                                    +properties[key].bedrooms
                                                                                    +'<span class=""> {{trans('messages.property_single.bedroom')}}</span>' 
                                                                                +'</p>'
                                                                            +'</li>'
                                                                            +'<li class="list-inline-item p-1">'
                                                                                +'<p class="text-center mb-0 text-muted text-13">'
                                                                                    +properties[key].bathrooms
                                                                                    +'<span class=""> {{trans('messages.property_single.bathroom')}}</span>' 
                                                                                +'</p>'
                                                                            +'</li>'
            																
            																+'<li class="list-inline-item p-1">'
                                                                                +'<p class="text-center mb-0 text-muted text-13">'
                                                                                    +properties[key].beds
                                                                                    +'<span class=""> {{trans('messages.property_single.bed')}}</span>' 
                                                                                +'</p>'
                                                                            +'</li>';
																		}
																		else
																		{
																		    room_div +='<li class="list-inline-item">'
                                                                                +'<p  class="text-center mb-0 text-muted text-13" >'
                                                                                    +properties[key].accommodates
                                                                                    +'<span class=""> {{trans('messages.experience.max_people')}} </span>' 
                                                                                +'</p>'
                                                                            +'</li>'
                                                                            +'<li class="list-inline-item p-1">'
                                                                                +'<p class="text-center mb-0 text-muted text-13">'
                                                                                    +properties[key].duration
                                                                                    +'<span class=""> {{trans('messages.experience.duration')}} </span>' 
                                                                                +'</p>'
                                                                            +'</li>'
																		}
																		
                                                            room_div +='</ul>'
																		
                                                                    +'</a>'
                                                                +'</div>'
																
																
                                                            +'</div>'
                                                            
                                                            +'<div class="review-0">'
                                                                +'<div class="d-flex justify-content-between">'
																	+'<div>'
                                                                    +'<p class="text-13 mt-2 mb-0 text"><i class="fas fa-map-marker-alt"></i> '+ properties[key].property_address.city+'</p>'
																	+'</div>'
                                                                    +'<div>'
                                                                        +'<span><i class="fa fa-star text-14 yellow_color"></i>'+' '+ avg_rating
                                                                            +' '+ '('+reviews_count+')</span>'
                                                                    +'</div>'
                                                                                                                                   
                                                                +'</div>'
                                                            +'</div>'
															 +'<div class="svred text-14">';
															 if(properties[key].type=="experience" && properties[key].exp_booking_type=="3")
															 {
															     room_div +='<span class="font-weight-700">{{trans('messages.experience.packages')}} </span>';
															 }
															 else if(properties[key].type=="experience" && properties[key].exp_booking_type=="1" || properties[key].type=="experience" && properties[key].exp_booking_type=="2")
															 {
															      room_div +='<span class="font-weight-700">'+symbolWithPrice+' </span>';
                                                             }
															 else
															 {
															       room_div +='<span class="font-weight-700">'+symbolWithPrice+' / {{trans('messages.property_single.night')}} '+'</span>';
                                                             }
																    if(properties[key].booking_type=="instant") {
																	room_div += '<i class="icon icon-instant-book yellow_color text-25" aria-hidden="true"></i>';
																   } 
															   
																room_div += '</div>'

                                                          
                                                        +'</div>'
                                                        +'</div>'
                                                    +'</div>';
                                                    
                                                    
                                       
                                    }
                                }
                            }
                            if(room_div != '') $('#properties_show').html(room_div);
                            else $('#properties_show').html(' <div class="text-center justify-content-center w-100 position-center search-no-result"><img src="{{ url('public/img/not-found.png')}}" class="img-fluid not-found" alt="not-found"><h4 class="text-center text-20 font-weight-700">{{trans('messages.search.no_result_found')}}<br>{{trans('messages.search.get_results')}}</h4><ul class="search-no-res-list"><li>{{trans('messages.search.zoom_map')}}</li><li>{{trans('messages.search.change_filters')}}</li><li>{{trans('messages.search.specific_city')}}</li></ul></div>');

                            //deleteMarkers();
                            addMarker(map, room_point);
                        },
                        error: function (request, error) {
                            allowRefresh = false;
                            // This callback function will trigger on unsuccessful action
                            console.log(error);
                        },
                        complete: function(){
                            hide_loader();
                        }
                });
            }

            }

        
        }

        $('#btnBook, #btnRoom, #btnPrice, .filter-apply').on('click', function(){
            allowRefresh = true;
            deleteMarkers();
            loadPage = '{{url("search/result")}}';
            getProperties($('#map_view').locationpicker('map').map);
            $('.room_filter').addClass('display-off');
            $('#more_filters').show();
            $('.dropdown-menu-price').removeClass('show');
        });
            

        function getCheckedValueArray(field_name){
            var array_Value = '';
            array_Value = $('input[name="' + field_name + '[]"]:checked').map(function() {
                return this.value;
            })
                .get()
                .join(',');

            return array_Value;
        }

        
        $(document.body).on('click','#map_view',function(){
            allowRefresh = true;
            loadPage = '{{url("search/result")}}';
            getProperties($('#map_view').locationpicker('map').map);
        });

        $('#map_view').locationpicker({
            location: {
                latitude: {{"$lat"}},
                longitude: {{"$long"}}
            },
			
			mapOptions: {
               zoom: 12,
			   zoomControl: true,
               zoomControlOptions: {
					position: google.maps.ControlPosition.LEFT_TOP,
					style: google.maps.ZoomControlStyle.SMALL
				},
               mapTypeId:google.maps.MapTypeId.ROADMAP,
               disableDefaultUI: true
            },
			
            radius: 0,
            zoom: 12,
			
			addressFormat: "",
            markerVisible: false,
            markerInCenter: false,
            inputBinding: {
                latitudeInput: $('#latitude'),
                longitudeInput: $('#longitude'),
                locationNameInput: $('#address_line_1')
            },
            enableAutocomplete: true,
            draggable: true,
            onclick: function (currentLocation, radius, isMarkerDropped) {
                if (allowRefresh == true) {
                    getProperties($(this).locationpicker('map').map);
                }
            },
			
				
            oninitialized: function (component) {
                var addressComponents = $(component).locationpicker('map').location.addressComponents;
				//var map = new google.maps.Map(document.getElementById("map_view"),mapOptions);

            }
        });

        $('.slider-selection').trigger('click');

        function show_loader(){
            $('#loader').removeClass('display-off');
            $('#pagination').hide();
        }

        function hide_loader(){
            $('#loader').addClass('display-off');
            $('#pagination').show();
        }

        // Map Close
        $('#togBtn').on('click', function()
        {
            if($(this).prop("checked") == true)
            {
                $('#listCol').removeClass('col-md-12');
                $('#listCol').addClass('col-md-8');
                $('#mapCol').removeClass('d-none');
                $('#showMap').addClass('d-none');
                allowRefresh = true;
                loadPage = '{{url("search/result")}}';
                getProperties($('#map_view').locationpicker('map').map);
          
            }
            else
            {  
                $('#listCol').removeClass('col-md-8');
                $('#listCol').addClass('col-md-12');
                $('#mapCol').addClass('d-none');
                allowRefresh = true;
                loadPage = '{{url("search/result")}}';
                getProperties($('#map_view').locationpicker('map').map);
             }

        });
        // Map show
        $('#showMap').on('click', function(){
            $('#listCol').removeClass('col-md-12');
            $('#listCol').addClass('col-md-7');
            $('#mapCol').removeClass('d-none');
            $('#showMap').addClass('d-none');
            allowRefresh = true;
            loadPage = '{{url("search/result")}}';
            getProperties($('#map_view').locationpicker('map').map);
        });

        $( window ).on( "load", function() {
                allowRefresh = true;
                loadPage = '{{url("search/result")}}';
                getProperties($('#map_view').locationpicker('map').map);
        });
    </script>
	
	 <script type="text/javascript">
		$(document).ready(function() 
		{
			
	       $('body').on('click', '.wishicon .svadd', function() {
				var id=$(this).attr("data-id");
	 			  $.ajax({
          type: 'post',
          url: '{{url('/wishlist/')}}',
          data:{
            wishid:id,
           '_token': '{{csrf_token()}}'
          },
          success: function (data)
          {
             window.location.href = "<?php echo url()->full(); ?>";
          }
        }); 
		
			});
			
			
		$('body').on('click', '.wishicon .svclose', function() {
			var id=$(this).attr("data-id");
	 			  $.ajax({
					  type: 'post',
					  url: '{{url('wishlistremove/')}}',
					  data:{
						wishid:id,
					   '_token': '{{csrf_token()}}'
					  },
					  success: function (data)
					  {
						 window.location.href = "<?php echo url()->full(); ?>";
					  }
				}); 
		});
			
		
		$('body').on('click', '.sv_befr_login', function() {
		    //alert("Login User Only");
            $("#loginmodel").modal('show');

		});    
		
		$('body').on('click', '#bedrooms_increase', function() 
		{
		   var newQty = +($("#bedrooms_number").val()) + 1;
		   $("#bedrooms_number").val(newQty);
		   $("#map-search-min-bedrooms").val(newQty);
		});

        $('body').on('click', '#bedrooms_decrease', function() {
          var newQty = +($("#bedrooms_number").val()) - 1;
          if(newQty < 0)newQty = 0;
            $("#bedrooms_number").val(newQty);
        	$("#map-search-min-bedrooms").val(newQty);
        });
			
		$('body').on('click', '#bathrooms_increase', function() 
		{
		   var newQty = +($("#bathrooms_number").val()) + 0.5;
		   $("#bathrooms_number").val(newQty);
		   $("#map-search-min-bathrooms").val(newQty);
		});

        $('body').on('click', '#bathrooms_decrease', function() {
          var newQty = +($("#bathrooms_number").val()) - 0.5;
          if(newQty < 0)newQty = 0;
        	  $("#bathrooms_number").val(newQty);
        	  $("#map-search-min-bathrooms").val(newQty);
          
        });
        
        $('body').on('click', '#beds_increase', function() 
		{
		   var newQty = +($("#beds_number").val()) + 1;
		   $("#beds_number").val(newQty);
		   $("#map-search-min-beds").val(newQty);
		});

        $('body').on('click', '#beds_decrease', function() {
          var newQty = +($("#beds_number").val()) - 1;
          if(newQty < 0)newQty = 0;
            $("#beds_number").val(newQty);
        	$("#map-search-min-beds").val(newQty);
        });
				
			
		});
    </script>
	 
	
    <script  type="text/javascript">
        $(document).on('click', '.dropdown-menu-price', function (e) {
            e.stopPropagation();
        });
    </script>
    
    <!--Newly added mobile map-->
    <script>
        $(document).ready(function(){
           $('#res-btn').hide(); //hide result btn on page load
         
         //on map click btn function
          $("#map-btn").click(function(){
            $("#res-btn").show(); //show result btn
            $('#mapCol').css('display', 'block'); //show map area
            $('.map-view').removeClass('sv_mob_map'); //show map area

            $('#listCol').css('display', 'none'); //hide result area
            $("#map-btn").hide();  //hide map btn
            //$('#filter-bar .sv_list ').hide(); //hide filter
            allowRefresh = true;
            loadPage = '{{url("search/result")}}';
            getProperties($('#map_view').locationpicker('map').map);
          });
        
          // on result click function
          $("#res-btn").click(function(){
              $("#map-btn").show();  // show map btn
              $('#listCol').css('display', 'block');  //show result area
              $('#mapCol').css('display', 'none');  //hide map area
              $('#res-btn').hide();  //hide result btn
              $('#filter-bar .sv_list ').show(); //show filter
              allowRefresh = true;
              loadPage = '{{url("search/result")}}';
              getProperties($('#map_view').locationpicker('map').map);
          });
        });
    </script>
    <!--Newly added mobile map end-->
    
    <!--New mobile filter toggle-->
    <script>
        $(document).ready(function(){
           $("#mobile-filter-option").click(function(){
               $('#mob-filter-area').toggleClass('hide-filter');
           });
        });
    </script>
    @endpush
	
	
@endsection