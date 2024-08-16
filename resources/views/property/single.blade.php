@extends('template')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/daterangepicker.min.css')}}" />
<link  rel="stylesheet" type="text/css" href="{{ url('public/css/glyphicon.css') }}"/>
<link  rel="stylesheet" type="text/css"  href="{{ url('public/js/ninja/ninja-slider.min.css') }}" />
@endpush
@section('main')
<input type="hidden" id="front_date_format_type" value="{{ Session::get('front_date_format_type')}}">
   <style>
       .footer-fixed-nav{
           z-index:8 !important;
       }
       .morecontent span 
        {
            display: none;
        }
		
   </style>
   
<?php
$current_lang 	= Session::get('language');
   if(Auth::check())
   {
	 $authid=Auth::user()->id;
   }
   else
   {
	 $authid="";
   }

   if (! $result->users) {
      $result->users = (object) [
         'full_name' => '',
         'display_name' => '',
         'profile_src' => '',
         'first_name' => '',
         'created_at' => '',
         'id' => '',
         'reviews' => [],
      ];
   }
?>
   
<?php if($result->type == "experience" && $result->exp_booking_type == "3" ) { ?>
<style>
#booking_table tr.append_date, .additional_price, .security_price, .cleaning_price {
    display: none;
} 
</style>
<?php } ?>						   

<?php if( $result->admin_approval==1 || $result->host_id==$authid) {  ?>

<section class="sv_single_prop " style="display:none">
   <nav class="animated fadeIn "> 
      <div class="container">
         <ul class="prop-nav">
            <li><a href="#photos">{{trans('messages.property_single.photos')}} </a></li>
            <li><a href="#about">{{trans('messages.property_single.overview')}}</a></li>
            <!--<li><a href="#listMargin">Amenities</a></li>-->
            <li><a href="#reviews">{{trans('messages.property_single.reviews')}}</a></li>
            <li><a href="#location1">{{trans('messages.property_single.location')}}</a></li>
         </ul>
         <div class="pull-right ul-price-fixed">
            <ul>
                <?php if($result->type == "experience" && $result->exp_booking_type == "3") { ?>
                    <li class="text-20">{{trans('messages.experience.packages')}}</li>
               <?php } else { ?>
                    <li class="text-20">{!! moneyFormat($result->property_price->currency->symbol, $result->property_price->price) !!}</li>
                    <li id="per_night" class="per-night">/ {{trans('messages.property_single.night')}}</li>
               <?php } ?>
               <li id="per_month" class="per-month display-off">/ {{trans('messages.property_single.month')}}<i id="price-info-tooltip" class="fa fa-question hide" data-behavior="tooltip"></i></li>
               <li id="chk-availability"><a href="javascript:void(0)" id="check_availability" class="btn vbtn-default">{{trans('messages.property_single.check_availability')}} </a></li>
            </ul>
         </div>
      </div>
   </nav>
</section>
<!--popup slider end-->

<div style="clear:both;margin-top:10rem;"></div>

<div class="container">
   <div class="property-title">

      @if(Session::has('message'))
         <div class="row mt-5">
            <div class="col-md-12 text-13 alert mb-0 {{ Session::get('alert-class') }} alert-dismissable fade in  text-center opacity-1">
               <a href="#"  class="close " data-dismiss="alert" aria-label="close">&times;</a>
               {{ Session::get('message') }}
            </div>
         </div>
      @endif 

      <div class="">
         <h3 class="text-24 mt-2"><strong>@if(empty($other_lang->name)) {{ $result->name }} @else {{ $other_lang->name }} @endif</strong></h3>
         <!--<span class="text-14 pr-3"><i class="fa fa-user secondary-text-color"></i> {{ $result->users->full_name }}</span>-->
         @if($result->avg_rating)
         <span class="pr-4"> <i class="fa fa-star secondary-text-color"></i> {{sprintf("%.1f",$result->avg_rating )}} ({{ $result->guest_review }})</span>
         @endif
         <span class="text-15 underline"> {{ $result->property_address->city }} @if($result->property_address->city !=''),@endif {{ $result->property_address->state}} @if($result->property_address->state !=''),@endif {{ $result->property_address->countries->name }}</span>
        
		<?php $count=count($property_photos); ?>
         <div class="pull-right <?php if(count($property_photos) == 0) echo "no-img-pull-right";?>" >
            <span class="share-option" data-toggle="modal" data-target="#share"> <i class="fa fa-share-alt" aria-hidden="true"></i> <span class="mob-hide">{{trans('messages.property_single.share')}}</span></span>
            <span class="svwishlist">
            <?php
               if(Auth::check())
               {
               $countid = \App\Models\Wishlist::where('userid','=', \Auth::user()->id )->where('propertyid','=', $result->id)->get();
               $totalCount = count($countid);
               
               if($totalCount>0){
               
               $status =  \App\Models\Wishlist::where('userid','=', \Auth::user()->id )->where('propertyid','=', $result->id)->first()->status;
               
                if($status==1){ ?>
            <span onclick="removethis(<?php echo $result->id;?>);"><i class="icon icon-heart text-15" id="closedid" ></i> <span class="mob-hide">{{trans('messages.property_single.unsave')}}</span> </span>
            <?php }else{ ?>
            <span onclick="addthis(<?php echo $result->id;?>);" ><i class="icon icon-heart-alt text-15" id="wishlistid"  ></i> <span class="mob-hide">{{trans('messages.property_single.save')}}</span> </span>
            <?php }
               }else{ ?><span onclick="addthis(<?php echo $result->id;?>);" ><i class="icon icon-heart-alt text-15" id="wishlistid" ></i> <span class="mob-hide">{{trans('messages.property_single.save')}}</span> </span>
            <?php } ?>
            <?php } else { ?><span class="b-login"><i class="icon icon-heart-alt text-15" id="wishlistid"  ></i> <span class="mob-hide">{{trans('messages.property_single.save')}}</span> </span>
            <?php } ?>
            </span>
            <!--Share modal-->
            <div class="modal fade" id="share" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLongTitle">{{trans('messages.property_single.share_txt')}}</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <div class="row" align="center">
                           <div class="col-md-12 col-12">
                              @php
                              echo '<iframe src="https://www.facebook.com/plugins/share_button.php?href='.url()->current().'&layout=button&size=large&mobile_iframe=true&width=73&height=28&appId" width="76" height="28" class="overflow-hidden border-0" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';
                              @endphp                              
                           </div>

                           <div class="col-md-12 col-12">
                              <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=".$title data-size="large" aria-label="tweet"><i class="fab fa-twitter"></i> Tweet</a>
                           </div>

                           <div class="col-md-12 col-12">
                              <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ $title }}&summary={{ $result->property_description->summary }}" aria-label="linkedin" onclick="window.open(this.href, 'mywin','left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false;" class="shareButton">
                              <i class="fab fa-linkedin-in"></i> {{trans('messages.property_single.share')}}</a>
                           </div>

                           <div class="col-md-12 col-12">
                              <a href="#" onclick="copyLink()" class="btn btn-secondary mt-3"> <i class="fa fa-copy"></i> {{ __('Copy link') }} </a>
                           </div>

                           <div class="col-md-12 col-12">
                              <a href="whatsapp://send?text={{ url()->current() }}" target="_blank" class="btn btn-success mt-3"> <i class="fab fa-whatsapp"></i> {{ __('Whatsapp') }} </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!--Share modal end-->
         </div>
      </div>
   </div>

   <div style="clear:both;"></div>
   <?php $count=count($property_photos); $video_count = count($property_videos); ?>
   @if(count($property_photos) > 0)
   <div class="d-none d-sm-block sv_photo_div">
      <div id="photos" class="row <?php if($count >= 5 ) echo "sv_fifth_row"; ?> <?php if($count == 3 ) echo "sv_three_col"; ?> <?php if($count == 4 ) echo "sv_four_col"; ?>">
         @php $i=0 @endphp
         @foreach($property_photos as $row_photos)
         @if($count == 1)
         <div class="col-md-12 col-sm-12 mb-2 mt-2 p-2">
            <div class="ex-image-container slider-image-container prop-image1" onclick="lightbox({{$i}})" style="background-image:url('{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}');">
            </div>
         </div>
         @elseif($count == 2)
         <div class="col-md-6 col-sm-6 col-6 mb-2 mt-2 p-2 prop-image2">
            <div class="ex-image-container slider-image-container" onclick="lightbox({{$i}})" style="background-image:url('{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}');">
            </div>
         </div>
         @elseif($count == 3)
         <?php if($i==0) { ?>
         <div class="col-md-6 col-sm-6 col-6 mb-2 mt-2 p-2 prop-image3">
            <div class="ex-image-container slider-image-container" onclick="lightbox({{$i}})" style="background-image:url('{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}');">
            </div>
         </div>
         <?php } else { ?>
         <div class="p-2 col-md-3 sv_third_col">
            <div class="ex-image-container slider-image-container" onclick="lightbox({{$i}})" style="background-image:url('{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}');">
            </div>
         </div>
         <?php } ?>
         @elseif($count == 4)
         <div class="col-md-6 col-sm-6 col-6 p-2 sv_fourth_col_img <?php if($i==0 || $i==1) { echo "sv_fourth_col"; }?>">
            <div class="slider-image-container" onclick="lightbox({{$i}})" style="background-image:url('{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}');">
            </div>
         </div>
         @elseif($count >= 5 )
         <?php if($i==0) { ?>
         <div class="sv_large_size mb-2 mt-2 p-2 col-md-6">
            <img src="{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}" alt="property-photo" class="img-fluid" onclick="lightbox({{$i}})" />
         </div>
         <?php } else { ?>
         <div class="p-2 sv_fifth_col <?php if($i==1 || $i==3) echo "mt-2"; ?> <?php if($i>4) echo "hide"; ?> ">
            <img src="{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}" alt="property-photo" class="img-fluid" onclick="lightbox({{$i}})" />
         </div>
         <?php } ?>  
         @endif
         @php $i++ @endphp
         @endforeach
      </div>
      <div class="sv_all_photos pt-2 pb-2 pl-3 pr-3 text-14" onclick="lightbox()" >
        <i class="fa fa-th"></i> {{trans('messages.experience.show_all_photos')}}
    </div>
   </div>
   
    
    
   <section class="customer-logos slider d-block d-sm-none">
      @foreach($property_photos as $row_photos)
      <div class="slide mt-3 mb-3">
         <div class="mob-overlay"></div>
         <img class="img-fluid" src="{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}">
      </div>
      @endforeach
   </section>
   @endif
</div>

<div class="container">
   <div class="row" id="mainDiv">
      <div class="col-lg-8 col-xl-8 pl-0">
         <div  id="sideDiv">
            <div class="d-flex mt-1">
               <div class="col-md-11 pl-0">
                  <h3 class="text-20"><strong>{{trans('messages.property_single.entire_rental_unit_hosted_by')}} @if( $result->users->display_name=="") {{ $result->users->full_name }} @else {{ $result->users->display_name }}  @endif</strong></h3>
				  <?php if($result->type=="property") { ?>

                  <span class="text-15"> {{ $result->accommodates }} {{trans('messages.property_single.guest')}}</span> . <span class="text-15">{{ $result->beds}} {{trans('messages.property_single.bed')}} </span>
                  . <span class="text-15"> {{ @$result->bedrooms }} {{trans('messages.property_single.bedroom')}}</span> . <span class="text-15">{{ @$result->bathrooms}} {{trans('messages.property_single.bathroom')}} </span>
				  <?php } else { ?>
				  <span class="text-15">{{trans('messages.experience.max_people')}}:<b> {{ $result->accommodates }} </b></span> 
				   . <span class="text-15">{{trans('messages.experience.duration')}}: <b>{{ $result->duration }} </b></span> 
				   
				  . <span class="text-15">{{trans('messages.experience.type')}}:@if(!empty($category->name)) <b>{{ $category->name }}</b> @endif</span> 
				  <?php } ?>
			   </div>
               <div class="text-center">
                  <a href="{{ url('users/show/'.$result->host_id) }}" >
                  <img alt="User Profile Image" class="img-fluid rounded-circle mr-4 img-60x60" src="{{ $result->users->profile_src }}" title="{{$result->users->first_name}}">
                  </a>
               </div>
            </div>
            <hr>
          
         </div>
      </div>

      <div class="col-lg-4 col-xl-4 mb-4 p-0">
         <div id="sticky-anchor" class="d-none d-md-block"></div>
         <div class="price-card">
            <div id="booking-price" class="panel panel-default">
               <div  class="" id="booking-banner" class="">
                  <div class="">
                     <div class="col-lg-12 p-0">
                        <div class="p-3">
                           <div class="book-price"> 
							<?php if($result->type == "experience" && $result->exp_booking_type == "3") { } else { ?>						   
                              <span class="book-price-txt">{!! moneyFormat($result->property_price->currency->symbol, $result->property_price->price) !!} </span>
                              
                              <?php if($result->type == "experience" && $result->exp_booking_type == "1" || $result->exp_booking_type == "2") { ?>
                                <span class="text-17">{{trans('messages.experience.person')}}</span> 
                              <?php } else if($result->type == "experience" && $result->exp_booking_type == "3") { ?>
                              <span class="text-17">{{trans('messages.experience.packages')}}</span> 
                              <?php } else { ?>
                              <span class="text-17">{{trans('messages.property_single.night')}}</span> 
                              <?php } ?>
                              
                              <span class="text-14 pull-right mt-2"> <b><i class="fa fa-star"></i> {{sprintf("%.1f",$result->avg_rating )}} </b> . <span class="underline text-14">{{ $result->guest_review }} {{trans('messages.sidenav.reviews')}}</span> </span>

							 <?php if($result->type == "experience" && $result->exp_booking_type != "1") { if($result->type == "experience" && $result->exp_booking_type != "2") { ?>
							 <div id="per_night" class="per-night pl-2">/ {{trans('messages.property_single.night')}} </div>
							 <?php } } ?>

							<?php } ?> 
                              <div id="per_month" class="per-month display-off pl-2">/ {{trans('messages.property_single.month')}}
                                 <i id="price-info-tooltip" class="fa fa-question hide" data-behavior="tooltip"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="">
                  <form accept-charset="UTF-8" method="post" action="{{ url('payments/book/'.$property_id) }}" id="booking_form">
                     {{ csrf_field() }}
                     <div class="row">
                        <div class="col-md-12 border-r-10 p-0">
						
						<?php if($result->type == "experience" && $result->exp_booking_type == "3") { ?>

						<div class="cart_div" id="cart_div">
						   <table class="table service-table">
                    			<thead class="thead-inverse">
                        			<tr>
                        				<th class="text-14">{{trans('messages.experience.packages_name')}}</th>
                        				<th class="text-14">{{trans('messages.experience.no_of_qty')}}</th>
                        				<th class="text-14">{{trans('messages.experience.price')}}</th>
                        				<th class="text-14">{{trans('messages.experience.action')}}</th>
                        			</tr>
                    			</thead>
		                        <?php $pack_price =""; $total="0"; ?>        
                                @if(session('cart'))
                                    @foreach(session('cart') as $id => $details)
                                        
                                    <?php
                                        if($result->id == $details['property_id'])
                                        {
                                        $total +=  $details['quantity'] * $details['price'];
                                    ?>
                    			<tbody>
                        			<tr data-id="{{ $id }}"> 
                        				<td class="text-14">{{ $details['name'] }}</td>
                        				<td class="text-14">{{ $details['quantity'] }}</td>
                        				<td class="text-14">{!! $result->property_price->currency->symbol !!}{!! currency_fix($details['price'], $result->property_price->currency->code) !!}</td>
                                        <td>
                                            <!--<button type="button" class="remove_from_cart{{ $id }} border-0 text-danger text-14" ><i class="fa fa-trash"></i></button>-->
                                            <a href="javascript:cart_del('<?php echo $id; ?>');" class="border-0 text-danger text-14 text-center"><i class="fa fa-trash remove" aria-hidden="true"></i></a>
                                        </td>
                        			</tr>  
                        		</tbody>
                               <?php } ?>
                        			  @endforeach
                        			  
                        			<tr>			
                        				<td colspan="3" align="right" class="text-14">Total: <b>{!! $result->property_price->currency->symbol !!}{!! currency_fix($total, $result->property_price->currency->code) !!} </b></td>
                        			</tr>
			
                                    @endif
	                    	</table>
	                    		<input type="hidden" id="time_slot" name="time_slot" value="{!! currency_fix($total, $result->property_price->currency->code) !!}">
						        <input type="hidden" id="family_id" name="family_id" value="">
						       
						</div>
						
						<?php foreach($family as $family)
							{
						?>
						
						<!--<div class="">
							<input type="radio" name="family_price" data-id="{{ $family->id }}" id="family_price{{ $family->id }}" value="{!! currency_fix($family->price, $result->property_price->currency->code) !!}" checked> {{ $family->title }} - <span class="secondary-text-color">{!! $result->property_price->currency->symbol !!} {!! currency_fix($family->price, $result->property_price->currency->code) !!}</span> </input>
							<p>{{trans('messages.experience.no_adults')}}: <b>{{ $family->adults }} </b> {{trans('messages.experience.child')}}: <b>{{ $family->children }}</b> {{trans('messages.experience.infants')}}: <b>{{ $family->infants }}</b></p>
						</div>-->
						
						@push('scripts')
						<script>
						$(document).ready(function (){
							 $('#family_price{{ $family->id }}').on('change', function(){
								 $('#time_slot').val(this.value);
								 var idd = $("#family_price{{ $family->id }}").data("id");
								 $('#family_id').val(idd);
								 price_calculation('', '', '');
							 });
						  });
						</script>
						@endpush
						<?php } ?> 			
					
						<?php } ?>

						<?php if($result->type == "property") { ?>
                           <div class="row svborder" id="daterange-btn">
                              <div class="col-6 p-2">
                                 <label class="pl-3 mb-0 font-weight-600">{{trans('messages.property_single.check_in')}}</label>
                                 <div class="mr-2">
                                    <input class="form-control checkin-input" id="startDate" name="checkin" value="{{ $dates[$result->id.'.checkin'] ?? onlyFormat(date('d-m-Y')) }}" type="text" required>
                                 </div>
                              </div>
                              <div class="col-6 p-2 border-left-chk">
                                 <label class="pl-3 mb-0 font-weight-600">{{trans('messages.property_single.check_out')}}</label>
                                 <div class="ml-2">
                                    <input class="form-control checkout-input" id="endDate" name="checkout" value="{{ $dates[$result->id.'.checkout'] ?? onlyFormat(date('d-m-Y', time() + 86400)) }}" placeholder="dd-mm-yyyy" type="text" required>
                                 </div>
                              </div>
                           </div>
						<?php } ?>
						   
						    <input type="hidden" id="property_id" value="{{ $property_id }}">
                              <input type="hidden" id="room_blocked_dates" value="" >
                              <input type="hidden" id="calendar_available_price" value="" >
                              <input type="hidden" id="room_available_price" value="" >
                              <input type="hidden" id="price_tooltip" value="" >
                              <input type="hidden" id="url_checkin" value="{{$checkin}}" >
                              <input type="hidden" id="url_checkout" value="{{$checkout }}" >
                              <input type="hidden" id="url_guests" value="{{ $guests }}" >
                              <input type="hidden" name="booking_type" id="booking_type" value="{{ $result->booking_type }}" >
                             
						<?php if($result->type == "experience" ) { ?>
						    <div class="col-12 p-2">
                                 <label class="mb-0 font-weight-600">{{trans('messages.property_single.check_in')}}</label>
                                 <div class="mr-2">
                                    <input type="" class="form-control" id="startDate" name="checkin" value="<?php echo date('Y-m-d'); ?>"  required>
                                 </div>
                            </div>	 
                            <link rel="stylesheet" type="text/css" href="{{ url('public/css/jquery-ui.min.css')}}" />
                            @push('scripts')
                            <script src="{{ url('public/js/jquery-ui.js') }}"></script>

                            <script>
                                $("#startDate").datepicker({
                                     minDate: 0,
                                });
                            </script>
                            @endpush 
						<?php } ?>
						
						
							<?php if($result->type == "experience" && $result->exp_booking_type == "2") { ?>
							 <div class="col-12 p-2">
                                 <label class="mb-0 font-weight-600">{{trans('messages.experience.time')}}</label>
                                 <div class="mr-2">
                                    <select class="form-control" id="time_slot" name="time_slot">
										@foreach($time as $time)
											<option value="{{ $time->start_time }}-{{ $time->end_time }}">{{ $time->start_time }} - {{ $time->end_time }}</option>
										@endforeach
									</select>
                                 </div>
                            </div>	
						    <?php } ?>
						   <input type="hidden" class="form-control" id="type" name="type" value="{{ $result->type }}" >
						   
                           <div class="row svborder pb-2 svv<?php echo $result->type; ?> <?php if($result->exp_booking_type == "3") { ?> hide <?php } else { ?> show <?php } ?>">
                              <div class="col-md-12 p-0">
                                 <div class=" ml-2 mr-2 pl-3 mt-2 rtl_text_right">
                                    <label class="font-weight-600 mb-0 ">{{trans('messages.property_single.guest')}}</label>
                                    <div class="">
                                       <select id="number_of_guests" class="form-control" name="number_of_guests">
                                          @for($i=1;$i<= $result->accommodates;$i++)
                                          <option value="{{ $i }}" <?= $guests == $i?'selected':''?>>{{ $i }}</option>
                                          @endfor
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
						
						   
                        </div>
                     </div>

                     <div id="book_it" class="mt-4 mb-4">
                      
                        <div id="book_it_disabled" class="text-center d-none">
                           <p id="book_it_disabled_message" class="icon-rausch">
                              {{trans('messages.property_single.date_not_available')}}
                           </p>
                           <a href="{{URL::to('/')}}/search?location={{$result->property_address->city }}" class="btn btn-large btn-block text-14" id="view_other_listings_button">
                           {{trans('messages.property_single.view_other_list')}}
                           </a>
                        </div>
                        <div id="minimum_disabled" class="text-center d-none">
                           <p  class="icon-rausch text-danger">
                              {{trans('messages.property_single.you_have_book')}} <span id="minimum_disabled_message"></span>  {{trans('messages.property_single.night_dates')}}
                           </p>
                           <a href="{{URL::to('/')}}/search?location={{$result->property_address->city }}" class="btn btn-large btn-block text-14" id="view_other_listings_button">
                           {{trans('messages.property_single.view_other_list')}}
                           </a>
                        </div>
						
						<div id="maximum_disabled" class="text-center d-none">
                           <p  class="icon-rausch text-danger">
                              {{trans('messages.property_single.you_have_book1')}} <span id="maximum_disabled_message"></span>  {{trans('messages.property_single.night_dates')}}
                           </p>
                           <a href="{{URL::to('/')}}/search?location={{$result->property_address->city }}" class="btn btn-large btn-block text-14" id="view_other_listings_button">
                           {{trans('messages.property_single.view_other_list')}}
                           </a>
                        </div>
						
                        <div class="book_btn1 col-md-12 text-center">
                               <button type="submit" class="btn button-default vbtn-success text-14 font-weight-700 mt-3 pl-5 pr-5 pt-3 pb-3 w-100 <?php if($result->type == "experience" && $result->exp_booking_type == "3") { ?> hide <?php } ?>" id="save_btn">
                               <i class="spinner fa fa-spinner fa-spin d-none"></i>
                               <span class="{{ ($result->booking_type != 'instant') ? '' : 'display-off' }}">
                               {{trans('messages.property_single.request_book')}}
                               </span>
                               <span class="{{ ($result->booking_type == 'instant') ? '' : 'display-off' }}">
                               <i class="icon icon-bolt text-beach h4"></i>
                               {{trans('messages.property_single.instant_book')}}
                               </span>
                               </button>

                              <a href="/inbox?user_id={{ $result->host_id }}" class="mt-2 btn btn-primary text-14">
                                 {{ __('messages.send_message') }}
                              </a>
                          
                        </div>
                        <p class="col-md-12 text-center mt-3 text-14">{{trans('messages.property_single.review_of_pay')}}</p>
                        
                        <div class="js-subtotal-container booking-subtotal panel-padding-fit">
                           <div id="loader" class="display-off single-load">
                              <img src="{{URL::to('/')}}/public/front/img/green-loader.gif" alt="loader">
                           </div>
                           <div class="table-responsive price-table-scroll p-0" >
                              <table class="table price_table" id="booking_table">
                                 <tbody>
                                    <div id="append_date">
                                    </div>
                                    <tr class="sv_append_date">
                                       <td class="pl-4 w-50 underline">
                                          <span  id="total_night_count" value=""><?php if($result->type == "experience" && $result->exp_booking_type == "3") { ?>{{ trans('messages.experience.packages'). ' '.trans('messages.experience.price')}} <?php } else { echo "0"; } ?></span><?php if($result->type == "property") { ?> {{trans('messages.property_single.night')}} <?php } ?>
                                       </td>
                                       <td class="pl-4 text-right"><span  id="total_night_price" value=""> 0 </span> <span id="custom_price" class="fa fa-info-circle secondary-text-color" data-html="true" data-toggle="tooltip" data-placement="top" title=""></span></td>
                                    </tr>
                                    <tr>
                                       <td class="pl-4 underline">
                                          {{trans('messages.property_single.service_fee')}}
                                       </td>
                                       <td class="pl-4 text-right"><span  id="service_fee" value=""> 0 </span></td>
                                    </tr>
                                    
                                    <tr class ="additional_price">
                                       <td class="pl-4 underline">
                                          {{trans('messages.property_single.additional_guest_fee')}}
                                       </td>
                                       <td class="pl-4 text-right">{!! $result->property_price->currency->symbol !!}<span  id="additional_guest" value=""> 0 </span></td>
                                    </tr>
                                    <tr class = "security_price">
                                       <td class="pl-4 underline">
                                          {{trans('messages.property_single.security_fee')}}
                                       </td>
                                       <td class="pl-4 text-right">{!! $result->property_price->currency->symbol !!}<span  id="security_fee" value=""> 0 </span></td>
                                    </tr>
                                    <tr class = "cleaning_price">
                                       <td class="pl-4 underline">
                                          {{trans('messages.property_single.cleaning_fee')}}
                                       </td>
                                       <td class="pl-4 text-right">{!! $result->property_price->currency->symbol !!}<span  id="cleaning_fee" value=""> 0 </span></td>
                                    </tr>
                                    <tr class="iva_tax">
                                       <td class="pl-4 underline">
                                          {{trans('messages.property_single.iva_tax')}}
                                       </td>
                                       <td class="pl-4 text-right"> <span  id="iva_tax" value=""> 0 </span></td>
                                    </tr>
                                    <tr class="accomodation_tax">
                                       <td class="pl-4 underline">
                                          {{trans('messages.property_single.accommodatiton_tax')}}
                                       </td>
                                       <td class="pl-4 text-right"> <span  id="accomodation_tax" value=""> 0 </span></td>
                                    </tr>
                                    <tr class="sv_discount">
                                       <td class="pl-4 underline">
                                          Discount
                                       </td>
                                       <td class="pl-4 text-right"><span>- </span>{!! $result->property_price->currency->symbol !!} <span  id="discount" value=""> 0 </span></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                           <label id="show-price">{{trans('messages.property_single.show_price_details')}} <i class="fa fa-chevron-down" aria-hidden="true"></i></label>
                        </div>
                        
                        
                        <hr>
                        <div class="col-md-12">
                           <span class="font-weight-700">{{trans('messages.property_single.total')}}</span>
                           <span class="font-weight-700 float-right"><span  id="total" value=""> 0 </span></span>
                        </div>
                     </div>
                     <input id="hosting_id" name="hosting_id" type="hidden" value="{{ $result->id }}">
                  </form>
               </div>
               <div class="clearfix"></div>
            </div>
         </div>
      </div>
   </div>


   {{-- erorr check here --}}
   <div class="row mt-4 mt-sm-0">
      <div class="col-lg-8 col-xl-8 col-sm-12 p-0">
         <div class="pb-5"  id="listMargin">
            <div class="row" id="about">
               <h2><strong>{{trans('messages.property_single.about_list')}}</strong> </h2>
               <p class="mt-4 text-justify text-14 pr-5" > @if(empty($other_lang->summary)) {{ $result->property_description->summary }} @else {{ $other_lang->summary }} @endif</p>
            </div>

            <div class="mt-3">
               <hr> 
			   <?php if($result->type != "experience") { ?>
               <div class="row">
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="mb-3">
                        <div class="align-self-center">
                           <h2 class="font-weight-700 text-17"> {{trans('messages.property_single.the_space')}}</h2>
                        </div>
                     </div>
                  </div>
				 
				  <?php 
			          
			          if($current_lang=="")
					  {
						  $current_lang = "en";
					  }
					  else
					  {
						  $current_lang = Session::get('language');
					  }					
					
						  $property_type_id = $result->property_type;  
						  $sv_ptype			= App\Models\PropertyType::where('id', $property_type_id)->first();
						  $temp_id 			=  $sv_ptype->temp_id ?? ''; 
						  $sv_ptype_query	= App\Models\PropertyType::where('temp_id', $temp_id)->where('lang', $current_lang)->first();
						  $other_langname   = $sv_ptype_query->name;
						  
						 
						  /* Bed Type */
						  $bed_id  			= $result->bed_type; 
						  $sv_btype			= App\Models\BedType::where('id', $bed_id)->first();
						  $temp_id1 		= $sv_btype->temp_id ?? ''; 
						  $sv_bedtype_query	= App\Models\BedType::where('temp_id', $temp_id1)->where('lang', $current_lang)->first();
						  $other_lang_bed   = $sv_bedtype_query->name ?? '';
					  
			        ?>
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="row">
                        <div class="col-md-6 col-sm-6 p-0">
                           @if(@$result->bed_types->name != NULL)
                           <div class="text-15"><span  class=""><strong>{{trans('messages.property_single.bed_type')}}:</strong></span> @if(empty($other_lang_bed)) {{ @$result->bed_types->name }} @else {{ $other_lang_bed }} @endif</div>
                           @endif
                           <div class="text-15"><strong>{{trans('messages.property_single.property_type')}}:</strong> @if(empty($other_langname)) {{ $result->property_type_name }} @else {{ $other_langname }}  @endif</div>
                           <div class="text-15"><strong>{{trans('messages.property_single.accommodate')}}:</strong> {{ @$result->accommodates }}</div>
                        </div>
                        <div class="col-md-6 col-sm-6 p-0">
                           <div class="text-15"><strong>{{trans('messages.property_single.bedroom')}}:</strong> {{ @$result->bedrooms }}</div>
                           <div class="text-15"><strong>{{trans('messages.property_single.bathroom')}}:</strong> {{ @$result->bathrooms }}</div>
                           <div class="text-15"><strong>{{trans('messages.property_single.bed')}}:</strong> {{ @$result->beds }}</div>
                        </div>
                     </div>
                  </div>
				
               </div>
               <hr>
			   <?php } ?>

			   
			    <?php if($result->type=="property") { ?>
               <div class="row">
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="mb-3">
                        <div class="align-self-center">
                           <h2 class="font-weight-700 text-17">  {{trans('messages.property_single.amenity')}}</h2>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 col-sm-12 p-0">
                        <!--show less amenities-->
                        <div class="row showless_amenities">
                            @php $i = 1 @endphp
                            @php $count = round(count($amenities)/2) @endphp
                            @foreach($amenities as $all_amenities)
                            
                                @if($all_amenities->status == null)
									<del class="d-none"> 
                                @endif
								  
								<?php
									  $aid  			= $all_amenities->id; 
									  $sv_atype			= App\Models\Amenities::where('id', $aid)->first();
									  $temp_id2 		= $sv_atype->temp_id;
									  $sv_atype_query	= App\Models\Amenities::where('temp_id', $temp_id2)->where('lang', "en")->first();
									  $other_lang_amen  = $sv_atype_query->title;
								?>
									<div class="col-md-6 col-sm-6 p-0 text-15 pb-2">
										<i class="text-25 pr-2 icon icon-{{ $sv_atype_query->symbol }}" aria-hidden="true"></i> 
										@if(!empty( $all_amenities->title )) {{ $all_amenities->title }} @else {{ $other_lang_amen }} @endif
									</div>
									</del>
									 
                            @endforeach
                        </div>
                        <!--<a href="javascript:void(0)" id="amenities_trigger" data-rel="amenities" class="moreall-btn"><strong>+ {{trans('messages.property_single.more')}}</strong></a>-->
                        <!--End show less amenities-->
                        
                        
                        <!--show all amenities-->
                        <div class="row showall_amenities d-none">
                        @foreach($amenities as $all_amenities)
                        <div class="col-md-6 col-sm-6 p-0">
                           <div class="text-15">
                              <i class="icon icon-{{ $all_amenities->symbol }}" aria-hidden="true"></i> 
                              @if($all_amenities->status == null)
                              <!--<del> 
                              @endif
                              {{ $all_amenities->title }}
                              @if($all_amenities->status == null)
                              </del>--> 
                              @endif
                           </div>
                        </div>
                        @endforeach
                        </div>
                        <a href="javascript:void(0)" id="amenities_less" data-rel="amenities" class="moreless-btn d-none"><strong>- {{trans('messages.property_single.less')}}</strong></a>
                        <!--End show all amenities-->
                  </div>
               </div>
               <hr>
				<?php } else { ?>
				
				  <div class="row">
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="mb-3">
                        <div class="align-self-center">
                           <h2 class="font-weight-700 text-17">{{trans('messages.experience.inclusion')}}</h2>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 col-sm-12 p-0">
                        <div class="row showless_amenities">
                            @foreach($inclusion as $inclusion)
							 @if(in_array($inclusion->id, $property_inclusion))
                               <div class="col-md-6 col-sm-6 p-0 text-15">
								<i class="fa fa-check"></i>	{{ $inclusion->name }} 
								</div>
							@endif
                            @endforeach
                        </div>
                 
                  </div>
               </div>
               <hr>

			   	  <div class="row">
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="mb-3">
                        <div class="align-self-center">
                           <h2 class="font-weight-700 text-17">{{trans('messages.experience.exclusion')}}</h2>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 col-sm-12 p-0">
                        <div class="row showless_amenities">
                            @foreach($exclusion as $exclusion)
							 @if(in_array($exclusion->id, $property_exclusion))
                               <div class="col-md-6 col-sm-6 p-0 text-15">
								<i class="fa fa-times-circle" aria-hidden="true"></i> {{ $exclusion->name }} 
								</div>
							@endif
                            @endforeach
                        </div>
                 
                  </div>
               </div>
               <hr>
				<?php } ?>
				
				
               <div class="row">
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="mb-3">
                        <div class="align-self-center">
                           <h2 class="font-weight-700 text-17">{{trans('messages.property_single.price')}}</h2>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="row">
					 <?php if($result->type=="property") { ?>
                        <div class="col-md-6 col-sm-6 p-0">
                           <div class="text-15">{{trans('messages.property_single.extra_people')}}:
                              <strong> 
                              @if($result->property_price->guest_fee !=0)                
                              <span> {!! moneyFormat($result->property_price->currency->symbol, $result->property_price->guest_fee) !!} / {{trans('messages.property_single.after_night')}} {{$result->property_price->guest_after}} {{trans('messages.property_single.guests')}}</span>
                              @else
                              <span >{{trans('messages.property_single.no_charge')}}</span>
                              @endif
                              </strong>
                           </div>
                           <div class="text-15">
                              {{trans('messages.property_single.weekly_discount')}} (%): 
                              @if($result->property_price->weekly_discount != 0)
                              <strong> <span id="weekly_price_string">{{ $result->property_price->weekly_discount }}</span> /{{trans('messages.property_single.week')}}</strong>
                              @else
                              <strong><span id="weekly_price_string">{{ $result->property_price->weekly_discount }}</span> /{{trans('messages.property_single.week')}}</strong>
                              @endif
                           </div>
                        </div>
					 <?php } ?>
                        <div class="col-md-6 col-sm-6 p-0">
						<?php if($result->type=="property") { ?>
                           <div class="text-15">
                              {{trans('messages.property_single.monthly_discount')}} (%):
                              @if($result->property_price->monthly_discount != 0)
                              <strong> 
                              <span id="weekly_price_string">{{ $result->property_price->monthly_discount }}</span> /{{trans('messages.property_single.month')}}
                              </strong>
                              @else
                              <strong><span id="weekly_price_string">{{ $result->property_price->monthly_discount }}</span> /{{trans('messages.property_single.month')}}</strong>
                              @endif
                           </div>
                           <!-- weekend price -->
                           @if($result->property_price->weekend_price > 0)
                           <div class="text-15">
                              {{trans('messages.listing_price.weekend_price')}}:
                              <strong> 
                              <span id="weekly_price_string">{!! $result->property_price->currency->symbol !!} {{ $result->property_price->weekend_price }}</span> / {{trans('messages.listing_price.weekend')}}
                              </strong>
                           </div>
                           @endif
							<?php } ?>
                           
                           <div class="text-15">
                              {{trans('messages.listing_sidebar.cancellation_policy')}}:
                              <strong> 
                              <span class="secondary-text-color"><a href="{{ url('cancellation-policies') }}" class="text-danger" target="_blank">{{ $result->cancellation }}</a></span>
                              </strong>
                           </div>

                           <!-- end weekend price -->
                        </div>
                     </div>
                  </div>
               </div>
               
            
               <div class="row">
                  
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="row">
                        <div class="col-md-6 col-sm-6 p-0">
                           <!--<div class="text-15">1 {{trans('messages.property_single.night')}} - </div>-->
                           <div class="align-self-center">
                           <h2 class="font-weight-700 text-17">{{trans('messages.property_single.avialability')}}</h2>
                        </div>
                        </div>
                        <div class="col-md-6 col-sm-6 p-0">
                           <a id="view-calendar" href="javascript:void(0)" class="text-color text-15 text-color-hover secondary-text-color"><strong>{{trans('messages.property_single.view_calendar')}}</strong></a>
                        </div>
                     </div>
                  </div>
               </div>
			   
               @if(@$result->property_description->about_place !='' || $result->property_description->place_is_great_for !='' || $result->property_description->guest_can_access !='' || $result->property_description->interaction_guests !='' || $result->property_description->other || $result->property_description->about_neighborhood || $result->property_description->get_around) 
               <hr>
               <div class="row">
                  <div class="col-md-12 col-sm-12 p-0">
                     <div class="mb-3">
                        <div class="align-self-center">
                           <h2 class="font-weight-700 text-18">{{trans('messages.property_single.description')}}</h2>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 col-sm-12 p-0"> 
                     @if($result->property_description->about_place)
					 <strong class="font-weight-700">{{trans('messages.property_single.about_place')}}</strong>
					 @if(empty($other_lang->about_place))
                       <p class="text-justify comment more">{{ $result->property_description->about_place}}</p> 
					 @else 
					 <p class="text-justify comment more"> {{ $other_lang->about_place }} </p>
					 @endif
                     @endif
                     
                     @if($result->property_description->place_is_great_for)
                     <strong class="font-weight-700">{{trans('messages.property_single.place_great_for')}}</strong>
				     @if(empty($other_lang->place_is_great_for))
                     <p  class="text-justify comment more">{{ $result->property_description->place_is_great_for}} </p>
				     @else 
					 <p class="text-justify comment more">{{ $other_lang->place_is_great_for }}</p>
					@endif
                     @endif
                     
                     <!--<a href="javascript:void(0)" id="description_trigger" data-rel="description" class="more-btn"><strong>+ {{trans('messages.property_single.more')}}</strong></a>-->
                     <div class="" id='description_after'>
                         
                        @if($result->property_description->guest_can_access)
                        <strong class="font-weight-700">{{trans('messages.property_single.guest_access')}}</strong>
						@if(empty($other_lang->guest_can_access))
                        <p  class="text-justify comment more">{{ $result->property_description->guest_can_access}}</p>
						@else
						<p class="text-justify comment more">{{ $other_lang->guest_can_access }}</p>
						@endif
						
						
                        @if($result->property_description->interaction_guests)
                        <strong class="font-weight-700">{{trans('messages.property_single.interaction_guest')}}</strong>
						@if(empty($other_lang->interaction_guests))
                        <p  class="text-justify comment more"> {{ $result->property_description->interaction_guests}}</p>
						@else
						<p class="text-justify comment more">{{ $other_lang->interaction_guests }}</p>
						@endif
						
						@if($result->property_description->other)
                        <strong class="font-weight-700">{{trans('messages.listing_description.thing_note')}}</strong>
						@if(empty($other_lang->other))
                        <p  class="text-justify comment more">{{ $result->property_description->other}}</p>
						@else
						<p class="text-justify comment more">{{ $other_lang->other }}</p>
						@endif
										
                        @endif
                        @if($result->property_description->about_neighborhood)
                        <strong class="font-weight-700">{{trans('messages.listing_description.overview')}}</strong>
						@if(empty($other_lang->about_neighborhood))
                        <p  class="text-justify comment more"> {{ $result->property_description->about_neighborhood}}</p>
						@else
						<p class="text-justify comment more">{{ $other_lang->about_neighborhood }}</p>
						@endif
                        @endif
                       
                        @endif
                        @if($result->property_description->get_around)
                        <strong class="font-weight-700">{{trans('messages.property_single.get_around')}}</strong>
						@if(empty($other_lang->get_around))
                        <p  class="text-justify comment more">{{ $result->property_description->get_around}}</p>
						@else
						<p class="text-justify comment more">{{ $other_lang->get_around }}</p>
						@endif
                        @endif
                       
                        @endif
                        <!--<a href="javascript:void(0)" id="description_less" data-rel="description" class="less-btn"><strong>- less</strong></a>-->
                     </div>
                  </div>
               </div>
               @endif
               <hr>
             
                   
				<?php if($result->type == "experience" && $result->exp_booking_type == "3") { ?>
				<h2 id="packages" class="d-block mt-4"><strong>{{trans('messages.experience.packages')}}</strong></h2>
				<div class="row">
    				<?php
    					$id= $result->id;
    					$family_new  = DB::table('family_package')->where('property_id', $id)->get();
    					foreach($family_new as $family1)
						{   
					?>
					<div class="col-md-12 col-lg-5 col-xl-12 pl-3 pr-3 pb-3 mt-4 sv_package_list ">
                        <div class="h-100 card-shadow card-1">
                            <div class="row">
                                <h3 class="font-weight-700 mt-3 text-16 col-md-12 pb-3 text-uppercase">{{ $family1->title }}</h3>
            					 <div class="col-md-3">
            					     @if($family1->file!="")
                                        <img src="{{url('public/images/experience/'.$result->id.'/'.$family1->file)}}" class="mb-3" width="100%" height="130">
                                    @else
                                        <img src="{{url('public/images/default-image.png')}}" class="mb-3" width="100%" height="130">
                                    @endif
                                    
                                    <div class="">
            						    <p class="text-14"><i class="fa fa-male" aria-hidden="true"></i> {{trans('messages.experience.no_adults')}}: <b><span id="sv_divers1{{ $family1->id }}">{{ $family1->adults }}</span> </b> <br> <i class="fa fa-child" aria-hidden="true"></i> {{trans('messages.experience.child')}}: <b><span id="sv_dives1{{ $family1->id }}">{{ $family1->children }}</span></b> <br><i class="fa fa-child" aria-hidden="true"></i> {{trans('messages.experience.infants')}}: <b>{{ $family1->infants }}</b></p>
            					    </div>
            					    <div class="mt-2 mb-2">
                					    @if($family1->itinerary!="")
                					        <button type="button" class="text-primary text-13 font-weight-700" style="background:transparent;border:0;padding:0" data-toggle="modal" data-target="#myModal{{ $family1->id }}">{{trans('messages.experience.view_itinerary')}}</button>
                					    @endif
            					    </div>
                                </div>
                            
            					<div class="col-md-6">
            					    <div class="">
            					        <h3 class="text-14 font-weight-700">{{trans('messages.experience.full_details')}}</h3>
            						    <p class="text-14" style="white-space: pre-line;text-align : left;">{{ $family1->full_details }} </p>
            					    </div>
            					</div>
            					
            					<div class="col-md-3">
            					     <span class="d-block mb-2 text-14 font-weight-600 cart_txt{{$family1->id}}">{{trans('messages.experience.no_of_qty')}}</span>

            					    <input type="button" class="decrementValue" onclick="decrementValue{{$family1->id}}()" value="-" />
                                    <input type="text" readonly  name="qty" value="1" id="qty{{$family1->id}}"  size="1">
                                    <input type="button" class="incrementValue" onclick="incrementValue{{$family1->id}}()" value="+" />
                                    
                                    <div class="mt-4">
		    						    <!--<input type="checkbox" class="" id="price_calc{{$family1->id}}" data-name="{{$family1->title}}">
            					        <span class="text-14 font-weight-600 cart_txt{{$family1->id}}">{{trans('messages.experience.add_to_cart')}}</span>-->
            					        
            					        <button class="btn btn-danger text-14" id="price_calc{{$family1->id}}" data-name="{{$family1->title}}">{{trans('messages.experience.add_to_cart')}}</button>
            					    </div>
            					    
            					    
            					</div>
            				     
            				     @push('scripts')
            				        <script>
            				        	$(document).ready(function (){
            							
            							  $('#price_calc{{ $family1->id }}').on('click', function(){
            								 var idd = $("#family_price{{ $family1->id }}").data("id");
            								 var qty = $("#qty{{ $family1->id }}").val();
						                     add_packages('{{ $family1->id }}', qty, '{{ $result->id }}');
							                 //price_calculation('', '', '');
            							 });
            							 
            						  });
            						  
            						  function incrementValue{{ $family1->id }}()
                                        {
                                            var value = parseInt(document.getElementById('qty{{ $family1->id }}').value, 10);
                                            value = isNaN(value) ? 0 : value;
                                            if(value<20){
                                                value++;
                                                    document.getElementById('qty{{ $family1->id }}').value = value;
                                                    /* if($('#price_calc{{$family1->id}}').prop("checked") == true)
							                        {
                                                        add_packages('{{ $family1->id }}', value, '{{ $result->id }}');
                                                        price_calculation('', '', '');
							                        } */    
                                            }
                                        }
                                        function decrementValue{{ $family1->id }}()
                                        {
                                            var value = parseInt(document.getElementById('qty{{ $family1->id }}').value, 10);
                                            value = isNaN(value) ? 0 : value;
                                            if(value>1){
                                                value--;
                                                    document.getElementById('qty{{ $family1->id }}').value = value;
                                                   /*  if($('#price_calc{{$family1->id}}').prop("checked") == true)
							                        {
                                                        add_packages('{{ $family1->id }}', value, '{{ $result->id }}');
                                                        price_calculation('', '', '');
							                        } */
                                            }
                                        }
                                       
                                        $('.svtot').show();
            						</script>
            				     @endpush
            					
            					<!-- Modal -->
                                <div id="myModal{{ $family1->id }}" class="modal fade" role="dialog">
                                  <div class="modal-dialog">
                                
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                      <div class="modal-header  d-block text-center">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title font-weight-600">{{ $family1->title }}</h4>
                                      </div>
                                      <div class="modal-body">
                                          <?php  
                                               $package_itinerary = $family1->itinerary;
                                          ?>
                                        <p class="text-14" style="white-space: pre-line;text-align : left;"><?php echo $package_itinerary; ?> </p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default text-13" data-dismiss="modal">{{trans('messages.listing_calendar.close')}}</button>
                                      </div>
                                    </div>
                                
                                  </div>
                                </div>
            					
				            </div>
				        </div>
				    </div>
				    
					<?php } ?> 
					</div>
					<?php } ?>
			   
		
               <!--popup slider-->
               <div class="d-none" id="showSlider">
                  <div id="ninja-slider">
                     <div class="slider-inner">
                        <ul>
                           @foreach($property_photos as $row_photos)
                           <li>
                              <a class="ns-img" href="{{url('public/images/property/'.$property_id.'/'.$row_photos->photo)}}" aria-label="photo"></a>
                           </li>
                           @endforeach
                         
                        </ul>
                        <div id="fsBtn" class="fs-icon" title="Expand/Close"></div>
                     </div>
                  </div>
               </div>
			   
               @if(count($property_videos) > 0)
               @foreach($property_videos as $row_videos)
               <div class="row mt-4">
                  <div class="col-md-12 col-sm-12">
                     <div class="resp-container">
                        <iframe class="resp-iframe" src="{{ $row_videos->photo }}" allow="autoplay" allowfullscreen></iframe>
                     </div>
                  </div>
               </div>
               @endforeach
               @endif
               <!--popup slider end-->
              
            </div>
            <!--Start Reviews-->
            <div id="reviews" class="col-md-12">
               @if(!$result->reviews->count())
               <div class="mt-5 mb-3">
                  <div class="row">
                     <div class="col-md-12">
                        <h2><strong>{{ trans('messages.reviews.no_reviews_yet') }}</strong></h2>
                     </div>
                     @if(count($result->users->reviews))
                     <div class="col-md-12">
                        <p>{{ trans_choice('messages.reviews.review_other_properties', $result->users->guest_review, ['count'=>$result->users->guest_review]) }}</p>
                        <p class="text-center mt-5 mb-4">
                           <a href="{{ url('users/show/'.$result->users->id) }}" class="btn btn vbtn-outline-success text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3">{{ trans('messages.reviews.view_other_reviews') }}</a>
                        </p>
                     </div>
                     @endif
                  </div>
               </div>
               @else
               <div class="mt-5">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="d-flex">
                           <div>
                              <h2 class="text-20"><strong> {{ trans_choice('messages.reviews.review',$result->guest_review) }}</strong></h2>
                           </div>
                           <div class="ml-4">
                              <p> <i class="fa fa-star secondary-text-color"></i> {{sprintf("%.1f",$result->avg_rating )}} ({{ $result->guest_review }})</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               
               
               
               @if($result->type == "property")
               <div class="mt-3">
                  <div class="row">
                     <div class="col-md-12">
                        <h3 class="font-weight-700 text-16">{{ trans('messages.property_single.summary') }}</h3>
                     </div>
                     <div class="col-md-12">
                        <div class="mt-3 p-4 pt-3 pb-3 border rounded">
                           <div class="row justify-content-between">
                              <div class="col-md-6 col-xl-5">
                                 <div class="d-flex justify-content-between">
                                    <div>
                                       <h4>{{ trans('messages.reviews.accuracy') }}</h4>
                                    </div>
                                    <div >
                                       <progress max="5" value="{{$result->accuracy_avg_rating}}">
                                          <div class="progress-bar">
                                             <span></span>
                                          </div>
                                       </progress>
                                       <span> {{sprintf("%.1f",$result->accuracy_avg_rating)}}</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6 col-xl-5">
                                 <div class="d-flex justify-content-between">
                                    <div>
                                       <h4>{{ trans('messages.reviews.location') }}</h4>
                                    </div>
                                    <div>
                                       <progress max="5" value="{{$result->location_avg_rating}}">
                                          <div class="progress-bar">
                                             <span></span>
                                          </div>
                                       </progress>
                                       <span> {{sprintf("%.1f",$result->location_avg_rating)}}</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6 col-xl-5">
                                 <div class="d-flex justify-content-between">
                                    <div>
                                       <h4 class="text-truncate">{{ trans('messages.reviews.communication') }}</h4>
                                    </div>
                                    <div>
                                       <progress max="5" value="{{$result->communication_avg_rating}}">
                                          <div class="progress-bar">
                                             <span></span>
                                          </div>
                                       </progress>
                                       <span> {{sprintf("%.1f",$result->communication_avg_rating)}}</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6 col-xl-5">
                                 <div class="d-flex justify-content-between">
                                    <div>
                                       <h4>{{ trans('messages.reviews.checkin') }}</h4>
                                    </div>
                                    <div>
                                       <progress max="5" value="{{$result->checkin_avg_rating}}">
                                          <div class="progress-bar">
                                             <span></span>
                                          </div>
                                       </progress>
                                       <span> {{sprintf("%.1f",$result->checkin_avg_rating)}}</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6 col-xl-5">
                                 <div class="d-flex justify-content-between">
                                    <div>
                                       <h4>{{ trans('messages.reviews.cleanliness') }}</h4>
                                    </div>
                                    <div>
                                       <progress max="5" value="{{$result->cleanliness_avg_rating}}">
                                          <div class="progress-bar">
                                             <span></span>
                                          </div>
                                       </progress>
                                       <span> {{sprintf("%.1f",$result->cleanliness_avg_rating)}}</span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6 col-xl-5">
                                 <div class="d-flex justify-content-between">
                                    <div>
                                       <h4>{{ trans('messages.reviews.value') }}</h4>
                                    </div>
                                    <div>
                                       <ul>
                                          <li>
                                             <progress max="5" value="{{$result->value_avg_rating}}">
                                                <div class="progress-bar">
                                                   <span></span>
                                                </div>
                                             </progress>
                                             <span> {{sprintf("%.1f",$result->value_avg_rating)}}</span>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               @endif
               <div class="mt-5">
                  <div class="row">
                     @foreach($result->reviews as $row_review)
                     @if($row_review->reviewer == 'guest')
                     <div class="col-12 mt-4 mb-2">
                        <div class="d-flex">
                           <div class="">
                              <div class="media-photo-badge text-center">
                                 <a href="{{ url('users/show/'.$row_review->users->id) }}" ><img alt="{{ $row_review->users->first_name }}" class="" src="{{ $row_review->users->profile_src }}" title="{{ $row_review->users->first_name }}"></a>
                              </div>
                           </div>
                           <div class="ml-2 pt-2">
                              <a href="{{ url('users/show/'.$row_review->users->id) }}" >
                                 <h2 class="text-16 font-weight-700"> @if($row_review->users->display_name=="") {{ $row_review->users->full_name }} @else {{ $row_review->users->display_name }} @endif</h2>
                              </a>
                              <p class="text-14 text-muted"><i class="far fa-clock"></i> {{ dateFormat($row_review->date_fy) }}</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-12 mt-2">
                        <div class="background text-15"  >
                           @for($i=1; $i <=5 ; $i++)
                           @if($row_review->rating >= $i)
                           <i class="fa fa-star secondary-text-color"></i>
                           @else
                           <i class="fa fa-star"></i>
                           @endif
                           @endfor
                        </div>
                        <p class="mt-2 text-justify pr-4">{{ $row_review->message }}</p>
                     </div>
                     @endif
                     @endforeach
                  </div>
               </div>
               <div class="mt-4">
                  @if($result->users->reviews->count() - $result->reviews->count())
                  <div class="row">
                     <div class="col-md-12">
                        <p class="text-center mt-2">
                           <a target="blank" class="btn vbtn-outline-success text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3 pl-5 pr-5" href="{{ url('users/show/'.$result->users->id) }}">
                           <span>{{ trans('messages.reviews.view_other_reviews') }}</span>
                           </a>
                        </p>
                     </div>
                  </div>
                  @endif
               </div>
               @endif
            </div>
            <hr>
            <!--End Reviews-->
           
         </div>
      </div>
   </div>
</div>

<div class="container mt-70" id="location1">
   <div class="row mt-5">
      <div class="col-md-12">
         <div id="room-detail-map" class="single-map-w mb-5"></div>
      </div>
   </div>
</div>

<div class="container">
    <hr>
 <div class="mt-5 abt_host col-md-12">
                
                 <div class="row">
               <div class="col-md-6">
                  <div class="clearfix"></div>
                  <h2><strong>{{trans('messages.property_single.about_host')}}</strong></h2>
                  <div class="d-flex mt-4">
                     <div class="">
                        <div class="media-photo-badge text-center">
                           <a href="{{ url('users/show/'.$result->host_id) }}"><img alt="{{ $result->users->first_name }}" class="" src="{{ $result->users->profile_src }}" title="{{ $result->users->first_name }}"></a>
                        </div>
                     </div>
                     <div class="ml-2 pt-3">
                        <a href="{{ url('users/show/'.$result->host_id) }}">
                           <h2 class="text-16 font-weight-700">@if($result->users->display_name=="") {{ $result->users->full_name }} @else {{ $result->users->display_name }} @endif </h2>
                        </a>
                        <p>{{trans('messages.users_show.member_since')}} {{ date('F Y', strtotime($result->users->created_at))  }}</p>
                     </div>
                  </div>
                  
                   <?php
                    $user_info = \App\Models\UserDetails::where('user_id','=', $result->users->id)->pluck('value', 'field')->toArray();
                ?>
                 @if(isset($user_info['about']))
					<p class="text-16 mt-4">{{$user_info['about']}}</p>
				@endif
               </div>
               
                <div class="col-md-6">
                     <h2><strong>{{trans('messages.experience.things_to_know')}}</strong></h2>
                     <p class="text-16 font-weight-700">{{trans('messages.experience.house_rules')}}</p>
                     
                     <?php
                     	$start_time = $result->check_in_after;
                		$end_time   = $result->check_out_before; 
                					
                		if($start_time>12)
                		{
                			$start=$start_time-12;
                			$stime=$start."PM";
                		}
                		else
                		{
                			$stime=$start_time."AM";
                		}
                		if($end_time>12)
                		{
                			$end=$end_time-12;
                			$etime=$end."PM";
                		}
                		else
                		{
                			$etime=$end_time."AM";
                		}
                     ?>
                     @if($result->check_in_after!="")
                     <p>
                         <i class="fa fa-clock" aria-hidden="true"></i>
                         {{trans('messages.experience.check_in_after')}}: {{ $stime }}
                     </p>
                     @endif
                     
                     @if($result->check_out_before!="")
                      <p>
                         <i class="fa fa-clock" aria-hidden="true"></i>
                         {{trans('messages.experience.check_out_before')}}: {{ $etime }}
                     </p>
                     @endif
                      <div class="text-15">
                              {{trans('messages.listing_sidebar.cancellation_policy')}}:
                              <strong> 
                              <span class="secondary-text-color"><a href="{{ url('cancellation-policies') }}" class="text-danger" target="_blank">{{ $result->cancellation }}</a></span>
                              </strong>
                           </div>
                </div>
               
            </div>
            </div>
    </div>

<div class="container mt-70 mb-5 mb-5">
   @if(count($similar)!= 0)
   <div class="row">
      <div class="col-md-12">
         <h2 class="text-center-sm text-20 font-weight-700">{{trans('messages.property_single.similar_list')}}</h2>
      </div>
   </div>
   <div class="row m-0 mb-5 pb-5 similar_listing">
      @foreach($similar->slice(0, 8) as $row_similar)
      <div class="col-md-6 col-lg-4 col-xl-3 p-2 mt-4 pl-4 pr-4">
         <div class="card h-100 card-shadow card-1">
            <a href="{{ $row_similar->slug }}">
               <figure class="">
                  <img src="{{ $row_similar->cover_photo }}" class="room-image-container200" alt="img11"/>
               </figure>
            </a>
            <div class="wishicon svwishlist<?php echo $row_similar->id;?>">
               <?php
                  if(Auth::check())
                  {
                      $countid = \App\Models\Wishlist::where('userid','=', \Auth::user()->id )->where('propertyid','=', $row_similar->id)->get();
                      $totalCount = count($countid);
                  
                      if($totalCount>0)
                      {
                          $status =  \App\Models\Wishlist::where('userid','=', \Auth::user()->id )->where('propertyid','=', $row_similar->id)->first()->status;
                          if($status==1){ 
                      ?>
               <i class="icon icon-heart" id="closedid<?php echo $row_similar->id;?>"  onclick="removethis<?php echo $row_similar->id;?>(<?php echo $row_similar->id;?>);" ></i>
               <?php } else { ?>
               <i class="icon icon-heart-alt" id="wishlistid<?php echo $row_similar->id;?>" onclick="addthis<?php echo $row_similar->id;?>(<?php echo $row_similar->id;?>);"  ></i>
               <?php } } else { ?>
               <i class="icon icon-heart-alt" id="wishlistid<?php echo $row_similar->id;?>" onclick="addthis<?php echo $row_similar->id;?>(<?php echo $row_similar->id;?>);"  ></i>
               <?php } ?>
               <?php } else { ?>
               <i class="icon icon-heart-alt b-login" id="wishlistid<?php echo $row_similar->id;?>" ></i>
               <?php } ?>
            </div>
            <script>
               function addthis<?php echo $row_similar->id;?>(id) {
                 $.ajax({
                   type: 'post',
                   url: '{{url('/wishlist/')}}',
                   data:{
                     wishid:id,
                    '_token': '{{csrf_token()}}'
                   },
                   success: function (data)
                   {
                      $(".svwishlist<?php echo $row_similar->id;?>").load(location.href + " .svwishlist<?php echo $row_similar->id;?>");
                      document.getElementById('wishlistid<?php echo $row_similar->id;?>').style.display='none';
                      document.getElementById('closedid<?php echo $row_similar->id;?>').style.display='block';
                   }
                 });
               } 
            </script>
            <script>
               function removethis<?php echo $row_similar->id;?>(id) {
                $.ajax({
                  type: 'post',
                  url: '{{url('wishlistremove/')}}',
                  data:{
                    wishid:id,
                    '_token': '{{csrf_token()}}'
                  },
                  success: function (data)
                  {
                     $(".svwishlist<?php echo $row_similar->id;?>").load(location.href + " .svwishlist<?php echo $row_similar->id;?>");
                     document.getElementById('wishlistid<?php echo $row_similar->id;?>').style.display='block';
                     document.getElementById('closedid<?php echo $row_similar->id;?>').style.display='none';
                  }
                });
               } 
            </script>
            <div class="card-body p-0 pl-1 pr-1">
               <div class="d-flex">

                <?php
					$similar_other_lang = \App\Models\PropertyMeta::where('lang', $current_lang)->where('property_id',$row_similar->id)->first();
					if(isset($similar_other_lang->name))
					{
						$row_similar_name = $similar_other_lang->name;
					}
					else
					{	
						$row_similar_name = $row_similar->name;
					}

				 ?>
				 
                  <div class="text">
                     <a class="text-color text-color-hover" href="{{ url('properties/'.$row_similar->id.'/'.$row_similar->slug) }}">
                        <p class="text-14 font-weight-700 text margin-bottom-zero"> {{ $row_similar_name }}</p>
                     </a>
                  </div>
               </div>
               <div>
                  <ul class="list-inline">
                      @if($row_similar->type=="experience")
                         <li class="list-inline-item text-dark">
                            <div class="vtooltip">
                               <span class="text-13 text-muted">{{ $row_similar->accommodates }} {{trans('messages.experience.people')}}</span>
                            </div>
                         </li>
                         <li class="list-inline-item">
                            <div class="vtooltip">
                               <span class="text-13 text-muted">{{$row_similar->duration}} {{trans('messages.experience.duration')}}</span>
                            </div>
                         </li>
                        @endif
                     
                        @if($row_similar->type=="property")
                         <li class="list-inline-item text-dark">
                            <div class="vtooltip">
                               <span class="text-13 text-muted">{{ $row_similar->accommodates }} {{trans('messages.property_single.guest')}}</span>
                            </div>
                         </li>
                         <li class="list-inline-item">
                            <div class="vtooltip">
                               <span class="text-13 text-muted">{{ $row_similar->bedrooms }} {{trans('messages.property_single.bedroom')}}</span>
                            </div>
                         </li>
                         <li class="list-inline-item">
                            <div class="vtooltip"> 
                               <span class="text-13 text-muted">{{ $row_similar->bathrooms }} {{trans('messages.property_single.bathroom')}}</span>
                            </div>
                         </li>
                     @endif
                  </ul>
               </div>
               <div class="review-0">
                  <div class="d-flex justify-content-between">
                     <div>
                        <p class="text-14 mb-0 text"><i class="fas fa-map-marker-alt"></i> {{$row_similar->property_address->city}}</p>
                     </div>
                     <div>
                        <span><i class="fa fa-star text-14 yellow_color"></i> 
                        @if( $row_similar->reviews_count)
                        {{ $row_similar->avg_rating }}
                        @else
                        0
                        @endif
                        ({{ $row_similar->reviews_count }})</span>
                     </div>
                  </div>
               </div>
               <div class="svred text-14">
                   @if($row_similar->exp_booking_type=="3")
                    <span class="font-weight-700"> {{trans('messages.experience.packages')}} </span>
                   @elseif($row_similar->exp_booking_type=="1" || $row_similar->exp_booking_type=="2")
                      <span class="font-weight-700">{!! moneyFormat( $row_similar->property_price->currency->symbol, $row_similar->property_price->price) !!} </span>

                   @else
                     <span class="font-weight-700">{!! moneyFormat( $row_similar->property_price->currency->symbol, $row_similar->property_price->price) !!} / {{trans('messages.property_single.night')}}</span>
                   @endif
					@if($row_similar->booking_type=="instant")
						<i class="icon icon-instant-book yellow_color text-25" aria-hidden="true"></i>
					@endif
			   </div>
              
            </div>
         </div>
      </div>
      @endforeach
   </div>
   @endif
</div>

   @if(showNextStepsSection() && $result->host_id == auth()->user()->id)
        <div class="row footer-bg py-5">
            <div class="col-md-12">
                <div class="container">
                    <h1 class="mt-5">{{ __('Next steps') }}</h1>
                    <p>{{ __('Its time to review a couple of current configurations.') }}</p>

                    <div class="row mt-5 mb-5">
                        <div class="col-md-4">
                            <div class="card p-5">
                                <div class="card-body text-center">
                                    <a href="/users/payout" class="card-title">
                                        <i class="fa fa-money-bill"></i>

                                        <div>
                                            {{ __('Set up your payment account') }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card p-5">
                                <div class="card-body text-center">
                                    <a href="/users/edit-verification" class="card-title">
                                        <i class="fa fa-check"></i>

                                        <div>
                                            {{ __('Verify your account') }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card p-5">
                                <div class="card-body text-center">
                                    <a href="/properties" class="card-title">
                                        <i class="fa fa-building"></i>

                                        <div>
                                            {{ __('Check your posts and properties') }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>          
                </div>
            </div>
        </div>
    @endif

 <?php } else { ?>
<div style="clear:both;margin-top:10rem;"></div>
<p class="text-center">{{trans('messages.experience.property_status')}} </p>
 <?php } ?>
 

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
<script type="text/javascript" src="{{ url('public/js/locationpicker.jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/ninja/ninja-slider.js') }}"></script>
<!-- daterangepicker -->
<script type="text/javascript" src="{{ url('public/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/daterangepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ url('public/js/daterangecustom.js')}}"></script>
<!--Mobile gallery slider-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<!---->


<script>
   function addthis(id) {
     $.ajax({
       type: 'post',
       url: '{{url('/wishlist/')}}',
       data:{
         wishid:id,
        '_token': '{{csrf_token()}}'
       },
       success: function (data)
       {
          $(".svwishlist").load(location.href + " .svwishlist");
         document.getElementById('wishlistid').style.display='none';
         document.getElementById('closedid').style.display='block';
       }
     });
   } 
</script>
<script>
   function removethis(id) {
    $.ajax({
      type: 'post',
      url: '{{url('wishlistremove/')}}',
      data:{
        wishid:id,
        '_token': '{{csrf_token()}}'
      },
      success: function (data)
      {
        //window.location.href = "{{ url('properties/') }}"+"/"+id;
         $(".svwishlist").load(location.href + " .svwishlist");
   
        document.getElementById('wishlistid').style.display='block';
        document.getElementById('closedid').style.display='none';
      }
    });
   } 
</script>
<script>
   $(document).ready(function(){
       $(window).bind('scroll', function() {
             if ($(window).scrollTop() > 300) {
                 $('.sv_single_prop').show();
                 $('.sv_single_prop nav').addClass('fixed');
                 /*$('#booking-banner .secondary-bg').addClass('price_fixed',1000);*/
                 
             }
             else {
                 $('.sv_single_prop').hide();
                 $('.sv_single_prop nav').removeClass('fixed');
                 /*$('#booking-banner .secondary-bg').removeClass('price_fixed',1000);*/
             }
        });
    });
</script>
<script>
   /*$(document).ready(function(){
   
    // click-to-scroll behavior
    $(".sv_single_prop li a").click(function (e) {
      e.preventDefault();
      var section = this.href;
      var sectionClean = section.substring(section.indexOf("#"));
      //alert(sectionClean);
      $("html, body").animate({
        scrollTop: $(sectionClean).offset().top
      }, 1000, function () {
        window.location.hash = sectionClean;
        
      });
    });
   });*/
</script>   
<script>
   $(function() {
       $('.sv_single_prop li a[href*=\\#]:not([href=\\#])').on('click', function() {
           var target = $(this.hash);
           target = target.length ? target : $('[name=' + this.hash.substr(1) +']');
           if (target.length) {
               $('html,body').animate({
                   scrollTop: target.offset().top-120
               }, 1000);
               return false;
           }
       });
   });
</script>
<script type="text/javascript">
   $(function() {
       var checkin = $('#startDate').val();
       var checkout = $('#endDate').val();
       var page = 'single';
       dateRangeBtn(checkin,checkout,page);
       
   });
 
</script> 

<script>
      $(document).ready(function (){
            $("#view-calendar,#check_availability").click(function (){
                $('html, body').animate({
                    scrollTop: $("#startDate").offset().top-150
                }, 500);
            });
        });

</script>
<script type="text/javascript">
   function copyLink()
   {
      navigator.clipboard.writeText(window.location.href);
      toastr.success('Link copied!', '', {timeOut: 5000})
   }

   $("#view-calendar,#check_availability").on("click", function() {
       return $("#startDate").click(); 
   })
   
   /* $( window ).resize(function() {
       if ($(window).width() < 760) {
           $("#listMargin").css({"margin-top": "0px"});
       } else {
           sticky_relocate();
       }
   }); */
   
   
   
   /* function sticky_relocate() {
       var window_top = $(window).scrollTop();
       var list_div = $("#listMargin").height();
   
       var div_top = $('#sticky-anchor').offset().top;
       if (window_top > div_top && $(window).width() > 2000) {
           $('#booking-price').addClass('stick');
           $('#sticky-anchor').height($('#sticky').outerHeight());
           $("#listMargin").addClass('mt-25');
           $("#listMargin").css({"margin-top": "25px"});
           divAdjust();
       } else {
           $('#booking-price').removeClass('stick');
           $('#sticky-anchor').height(0);
           divAdjust();
       }
       if(window_top > list_div){
           $('#booking-price').addClass('d-none');
       }else{
           $('#booking-price').removeClass('d-none');
       }
   } */
   
   function divAdjust() {
       if ($(window).width() > 992) {
           var mainDiv = $("#mainDiv").height();
           var sideDiv = $("#sideDiv").height();
           var listMargin = (mainDiv - sideDiv)-40;
           $("#listMargin").css({"margin-top": "-"+listMargin +"px"});
       }
       else {
               // More than 960
       }
   }
   
   $(function(){
       var checkin     = $('#url_checkin').val();
       var checkout    = $('#url_checkout').val();
       var guest       = $('#url_guests').val();
       price_calculation(checkin, checkout, guest);
   });
   
    $('#startDate').on('change', function(){
       price_calculation('', '', '');
   });
   
   $('#number_of_guests').on('change', function(){
       price_calculation('', '', '');
   });
   
   $('#time_slot').on('change', function(){
       price_calculation('', '', '');
   });
   
   
    function add_packages(package_id, qty, property_id)
    {  
       var checkout = "<?php echo date("Y-m-d"); ?>";
	   var checkin  = $('#startDate').val();
	   var guest    = $('#number_of_guests').val();
       
        $.ajax({
          type: 'post',
          url: '{{url('/updateqty/')}}',
          data:{
            package_id:package_id,
            qty:qty,
            property_id:property_id,
            checkin:checkin,
            checkout:checkout,
            guest:guest,
           '_token': '{{csrf_token()}}'
          },
          success: function (data)
          {
              $('#cart_div').load(location.href+(' #cart_div'));
              window.scrollTo({ top: 400, behavior: 'smooth' });
              price_calculation('', '', '');
              $('#save_btn').show();
          },
           complete: function (data) {
                  price_calculation('', '', '');
                }
                
        });
    }
    
     //$(".remove-from-cart").click(function (e) {
        //e.preventDefault();
        
    function cart_del(id) 
    {
        var ele      = id;
       var checkout = "<?php echo date("Y-m-d"); ?>";
	   var checkin  = $('#startDate').val();
	   var guest    = $('#number_of_guests').val();
	   var property_id = "<?php echo $result->id; ?>";
  
        if(confirm("Are you sure want to remove?")) {
            $.ajax({
                url: '{{url('/remove-from-cart/')}}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id,
                    checkin:checkin,
                    checkout:checkout, 
                    guest:guest,
                    property_id:property_id,
                }, 
                success: function (response) {
                    $('#cart_div').load(location.href+(' #cart_div'));
                    $('#book_it').load(location.href+(' #book_it'));
                    
                    price_calculation('', '', '');
                    //alert(response);
                }, 
                complete: function (data) {
                  price_calculation('', '', '');
                }
     
            });
        }
    }        
        
    /* function remove_packages(id)
    {  
       var ele      = id;
       var checkout = "<?php echo date("Y-m-d"); ?>";
	   var checkin  = $('#startDate').val();
	   var guest    = $('#number_of_guests').val();
	   var property_id = "<?php echo $result->id; ?>";
  
        if(confirm("Are you sure want to remove?")) {
            $.ajax({
                url: '{{url('/remove-from-cart/')}}',
                type: 'post',
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id,
                    checkin:checkin,
                    checkout:checkout, 
                    guest:guest,
                    property_id:property_id,
                }, 
                success: function (response) {
                    $('#cart_div').load(location.href+(' #cart_div'));
                   
                    //alert(response);
                }, 
                complete: function (data) {
                  price_calculation('', '', '');
                }
     
            });
        }
    } */
    
   function price_calculation(checkin, checkout, guest){
	   var type = $('#type').val();
       if(type=="experience")
	   {
		  var checkout = "<?php echo date("Y-m-d"); ?>";
	   }
	   else
	   {
          var checkout = checkout != ''? checkout:$('#endDate').val();
	   }
	   var time_slot     = $('#time_slot').val();
       var checkin = checkin != ''? checkin:$('#startDate').val();
       var guest = guest != ''? guest:$('#number_of_guests').val();
	   
       if(checkin != '' && checkout != '' &&  guest != ''){
       var property_id     = $('#property_id').val();
	   
       var dataURL = '{{url("property/get-price")}}';
           $.ajax({
               url: dataURL,
               data: {
                   "_token": "{{ csrf_token() }}",
                   'checkin': checkin,
                   'checkout': checkout,
                   'guest_count': guest, 
                   'property_id': property_id,
				   'time_slot' : time_slot,
               },
               type: 'post',
               dataType: 'json',
               beforeSend: function (){
                    $('.price_table').addClass('d-none');
                   show_loader();
               },
               success: function (result) {
                   //console.log(result);
                   $('.append_date').remove();
                   if(result.status == 'Not available'){
                       $('.book_btn').addClass('d-none');
                       $('.booking-subtotal').addClass('d-none');
                       $('#book_it_disabled').removeClass('d-none');
                   }
                   else if(result.status == 'minimum stay')
                   {
                       $('.book_btn').addClass('d-none');
                       $('.booking-subtotal').addClass('d-none');
                       $('#book_it_disabled').addClass('d-none');
                       $('#minimum_disabled').removeClass('d-none');
                       $('#minimum_disabled_message').text(result.minimum);
                   }
				   else if(result.status == 'maximum stay')
                   {
                       $('.book_btn').addClass('d-none');
                       $('.booking-subtotal').addClass('d-none');
                       $('#book_it_disabled').addClass('d-none');
                       $('#maximum_disabled').removeClass('d-none');
                       $('#maximum_disabled_message').text(result.maximum);
                   }
                   else
                   {
   
                       //showing custom price in info icon
                       if(!jQuery.isEmptyObject(result.different_price_dates)){
                           var output = "{{trans('messages.listing_price.custom_price')}} <br/>";
                           for (var ical_date in result.different_price_dates) {
                               output += "{{__('messages.account_transaction.date')}}: "+ical_date+" | {{__('messages.utility.price')}}: "+"{{$result->property_price->currency->symbol}}"+ result.different_price_dates[ical_date]+" <br>";
                           }
                           
                           $("#custom_price").attr("data-original-title", output);
                           $('#custom_price').tooltip({ 'placement': 'top' });   
                           $('#custom_price').show();
   
                       }else{
                           $('#custom_price').addClass('d-none');
                       }
   
   
                       var append_date = ""
   
                       for(var i=0; i<result.date_with_price.length; i++){
   
                       append_date +=      '<tr class="append_date">'
                                               + '<td class="pl-4">'
                                                   + result.date_with_price[i]['date']+
                                               '</td>'
                                               + '<td class="pl-4 text-right"> <span  id="" value="">'+ result.date_with_price[i]['price'] +'</span></td>'
                                           + '</tr>';
                       
                       }
   
                       var tableBody = $("table tbody");
                       tableBody.first().prepend(append_date);
                       console.log(result);
   
                       $('.additional_price').removeClass('d-none');
                       $('.security_price').removeClass('d-none');
                       $('.cleaning_price').removeClass('d-none');
                       $('.iva_tax').removeClass('d-none');
                       $('.accomodation_tax').removeClass('d-none');
                       $('.sv_discount').removeClass('d-none');
                        
                       $("#total_night_count").html(result.total_nights);
                       $('#total_night_price').html(result.total_night_price_with_symbol);
                       $('#service_fee').html(result.service_fee_with_symbol);
                       $('#discount').html(result.discount);
                           
                       if(result.iva_tax != 0) $('#iva_tax').html(result.iva_tax_with_symbol);
                       else $('.iva_tax').addClass('d-none');
                       if(result.accomodation_tax != 0) $('#accomodation_tax').html(result.accomodation_tax_with_symbol);
                       else $('.accomodation_tax').addClass('d-none');
   
                       if(result.additional_guest != 0) $('#additional_guest').html(result.additional_guest);
                       else $('.additional_price').addClass('d-none');
                       if(result.security_fee != 0) $('#security_fee').html(result.security_fee);
                       else $('.security_price').addClass('d-none');
                       if(result.cleaning_fee != 0) $('#cleaning_fee').html(result.cleaning_fee);
                       else $('.cleaning_price').addClass('d-none');
                       
                       if(result.discount != 0) $('#discount').html(result.discount);
                       else $('.sv_discount').addClass('d-none');
                       
                       $('#total').html(result.total_with_symbol);
                       //$('#total_night_price').html(result.total_night_price);
   
                       $('.booking-subtotal').removeClass('d-none');
                       $('#book_it_disabled').addClass('d-none');
                       $('#minimum_disabled').addClass('d-none');
					    $('#maximum_disabled').addClass('d-none');
                       $('.book_btn').removeClass('d-none');
                   }
   
                   var host = "{{ ($result->host_id == @Auth::guard('users')->user()->id) ? '1' : '' }}";
                   if(host == '1') $('.book_btn').addClass('d-none');
               },
               error: function (request, error) {
                   // This callback function will trigger on unsuccessful action
                   console.log(error, request);
               },
               complete: function(){
                   $('.price_table').removeClass('d-none');
                   hide_loader();
               }
           });
       }
   }
   
   /* $(function() {
       $(window).scroll(sticky_relocate);
       sticky_relocate();
   }); 
   
   document.addEventListener('readystatechange', event => { 
       if (event.target.readyState === "complete") {
               setTimeout(function() { 
                   sticky_relocate();
               }, 1000);
       }
   }); */
   
   $(document).ready(function() {
       $('#booking_form').validate({        
           submitHandler: function(form)
           {
               $("#save_btn").on("click", function (e) {
                   $("#save_btn").attr("disabled", true);
                   e.preventDefault();
               });
               
               $(".spinner").removeClass('d-none');
               $("#save_btn-text").text("{{trans('messages.users_profile.save')}} ..");
               return true;
           }
       });
   });
   
   $('.more-btn').on('click', function(){
       var name = $(this).attr('data-rel');
       $('#'+name+'_trigger').addClass('d-none');
       $('#'+name+'_after').removeClass('d-none');
   });
   
   $('.less-btn').on('click', function(){
       var name = $(this).attr('data-rel');
       $('#'+name+'_trigger').removeClass('d-none');
       $('#'+name+'_after').addClass('d-none');
   });
   
   
   //for Amenities show all
   $('.moreall-btn').on('click', function(){
       $('.moreless-btn').removeClass('d-none');
       $('.showall_amenities').removeClass('d-none');
       $('.moreall-btn').addClass('d-none');
       $('.showless_amenities').addClass('d-none');
   });
   
   $('.moreless-btn').on('click', function(){
       $('.moreless-btn').addClass('d-none');
       $('.showless_amenities').removeClass('d-none');
       $('.moreall-btn').removeClass('d-none');
       $('.showall_amenities').addClass('d-none');
   });
   
   
   
   setTimeout(function(){
   
       $('#room-detail-map').locationpicker({
           location: {
               latitude: "{{$result->property_address->latitude}}",
               longitude: "{{ $result->property_address->longitude }}"
           },
           radius: 0,
           addressFormat: "",
           markerVisible: false,
           markerInCenter: false,
           enableAutocomplete: true,
           scrollwheel: false,
           oninitialized: function (component) {
               setCircle($(component).locationpicker('map').map);
           }
   
       });
   
   }, 5000);
   
   
   function setCircle(map){
       var citymap = {
       loccenter: {
           center: {lat: 41.878, lng: -87.629},
           population: 240
       },
       };
   
       var cityCircle = new google.maps.Circle({
           strokeColor: '#329793',
           strokeOpacity: 0.8,
           strokeWeight: 2,
           fillColor: '#329793',
           fillOpacity: 0.35,
           map: map,
           center: {lat: {{$result->property_address->latitude}}, lng: {{ $result->property_address->longitude }} },
           radius: citymap['loccenter'].population
       });
   }
   
   function lightbox(idx) {
       //show the slider's wrapper: this is required when the transitionType has been set to "slide" in the ninja-slider.js
       $('#showSlider').removeClass("d-none");
       nslider.init(idx);
       $("#ninja-slider").addClass("fullscreen");
   }
   
   function fsIconClick(isFullscreen) { //fsIconClick is the default event handler of the fullscreen button
       if (isFullscreen) {
           $('#showSlider').addClass("d-none");
       }
   }
   
   function show_loader(){
       $('#loader').removeClass('d-none');
       $('#pagination').addClass('d-none');
   }
   
   function hide_loader(){
       $('#loader').addClass('d-none');
       $('#pagination').removeClass('d-none');
   }
   
   window.twttr = (function(d, s, id) {
       var js, fjs = d.getElementsByTagName(s)[0],
           t = window.twttr || {};
       if (d.getElementById(id)) return t;
       js = d.createElement(s);
       js.id = id;
       js.src = "https://platform.twitter.com/widgets.js";
       fjs.parentNode.insertBefore(js, fjs);
       t._e = [];
       t.ready = function(f) {
           t._e.push(f);
       };
   
       return t;
       }(document, "script", "twitter-wjs"));
</script>
<!--Mobile Gallery slider-->
<script type="text/javascript">
   $(document).ready(function(){
      $('.customer-logos').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: false,
          autoplaySpeed: 1500,
          arrows: false,
          dots: false,
          pauseOnHover: false,
              });
   });
</script>
<!--Mobile Gallery slider End-->
<!--show price toggle-->
<script>
   $(document).ready(function() {
       $('#show-price').click(function() {
           $('.price-table-scroll').slideToggle("slow");
           $("i", this).toggleClass("fa fa-chevron-up fa fa-chevron-down");
   });
   });
</script>
<!---->
<script>
   $('body').on('click', '.b-login', function() {
       $("#loginmodel").modal('show');
   
   }); 
   
  
    
</script>
 <script>
    $(document).ready(function() {
	var showChar = 100;
	var ellipsestext = "...";
	var moretext = "{{trans('messages.property_single.more')}}";
	var lesstext = "{{trans('messages.property_single.less')}}";
	$('.more').each(function() {
		var content = $(this).html();

		if(content.length > showChar) {

			var c = content.substr(0, showChar);
			var h = content.substr(showChar-1, content.length - showChar);

			var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink secondary-text-color font-weight-600">' + moretext + '</a></span>';

			$(this).html(html);
		}

	});

	$(".morelink").click(function(){
		if($(this).hasClass("less")) {
			$(this).removeClass("less");
			$(this).html(moretext);
		} else {
			$(this).addClass("less");
			$(this).html(lesstext);
		}
		$(this).parent().prev().toggle();
		$(this).prev().toggle();
		return false;
	});
	

});
</script>
@endpush 
@stop