@extends('template')

@section('main')

<div class="container margin-top-85 p-0 mb-5 min-height" id="element-to-print">
  <div class="panel-body text-success pt-5">
   <h6 class="text-16">{{trans('messages.trips_receipt.receipt')}} # {{ $booking->id }}</h6>
 </div>
 <div class="card">
  <div class="card-header pt-3 pb-4">
    <strong class="font-weight-700">{{trans('messages.trips_receipt.customer_receipt')}}</strong> 
    <span class="float-right"> <strong class="font-weight-700">{{trans('messages.trips_receipt.confirmation_code')}} :</strong> {{ $booking->code }}</span>
  </div>

  <div class="card-body pt-0 pb-0 pl-4 pr-4">
    <div class="row mb-4 mt-5">
      <div class="col-md-6 l-pad-none p-0">
       <img src="{{@$logo}}" width="150px"> 
       <p class="mt-4">  {{ $invoice_description }} </p>
     </div>

     <div class="col-md-6 print-div text-right p-0" id="print-div">
      <a href="#" onclick="print_receipt()" class="btn vbtn-outline-success text-14 font-weight-700 pt-2 pb-2 mt-2 pl-3 pr-4 button">Imprimir</a>
      <a href="#" onclick="download_receipt()" class="btn vbtn-outline-success text-14 font-weight-700 pt-2 pb-2 mt-2 pl-3 pr-4 button">Descargar</a>
      <a href="/"  class="btn vbtn-outline-success text-14 font-weight-700 pt-2 pb-2 mt-2 pl-3 pr-4 button">Volver a inicio</a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 mt-3 ow rpt pl-0">
      <div class="p-0">
        <span> <strong class="font-weight-700 text-18">{{trans('messages.trips_receipt.name')}} :</strong> 
        @if( $booking->users->display_name=="")
            {{ $booking->users->first_name }}
        @else
            {{ $booking->users->display_name }}
        @endif
                                                        
        </span>
        
        <span class="text-right">
    	    <a class="btn btn-primary text-13" href="#">
    			<i class="fa fa-money-bill"></i> @if($booking->payment_method_id == "1" ) Paypal @elseif( $booking->payment_method_id == "2" ) Stripe @elseif( $booking->payment_method_id == "3" ) Wallet @elseif( $booking->payment_method_id == "5" ) Razorpay @endif
    		</a>
    	</span>
    		
      </div>
    </div>
    <div class="col-md-6 text-right pt-4 pr-0">
      <h4></h4>
    </div>
  </div>

  <div class="row rpt border pt-3 mb-5 mt-2">
    <div class="col-md-3 col-sm-3 col-xs-12"><!-- card pt-4 mb-5 mt-2 rounded-3 -->
      <h4 class="margin-top20"><strong>{{trans('messages.trips_receipt.accommodatoin_address')}}</strong></h4>
      <h5 class="margin-top20">
        <p class="text-lead">
            <strong>{{ @$booking->properties->name }}</strong>
        </p>
       
      <p class="text-lead">{{ @$booking->properties->property_address->address_line_1 }}<br>{{ @$booking->properties->property_address->city }}, {{ @$booking->properties->property_address->state }} {{ @$booking->properties->property_address->postal_code }}<br>{{ @$booking->properties->property_address->country_name }}<br>
      </h5>
      
       
      </div>
     
      <div class="col-md-3 col-sm-3 col-xs-12">
        <h4><strong>{{trans('messages.trips_receipt.travel_destination')}}</strong></h4>
        <h5 class="margin-top20">{{ @$booking->properties->property_address->city }}</h5>
        <h4 class="margin-top20"><strong>{{trans('messages.trips_receipt.accommodation_host')}}</strong></h4>
        <h5 class="margin-top20">{{ @$booking->properties->users->full_name }}</h5>
      </div>

      <div class="col-md-3 col-sm-3 col-xs-12">
        <h4><strong>{{trans('messages.trips_receipt.duration')}}</strong></h4>
		<?php
			$current_lang = Session::get('language');

			$exp_booking_type = $booking->properties->exp_booking_type; 
			$type 			  = $booking->properties->type; 
			$experience_type  = $booking->properties->experience_type;
			$category         = App\Models\ExperienceCategory::where('lang', $current_lang)->where('temp_id',$experience_type)->first();
		?>
		@if( $type == "experience" )
			<h5 class="margin-top20">{{ $booking->properties->duration }}</h5>
		@else
			<h5 class="margin-top20">{{ $booking->total_night }} {{trans('messages.trips_receipt.night')}}</h5>
		@endif
		
	<?php
                     	$start_time = $booking->properties->check_in_after;
                		$end_time   = $booking->properties->check_out_before; 
                					
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
        <h4 class="margin-top20"><strong>{{trans('messages.trips_receipt.check_in')}}</strong></h4>
       
        
        <h5 class="margin-top20 mb-10">{{ $booking->startdate_dmy }}</h5>
        
        @if($booking->properties->check_in_after!="")
            <h5 class="mt-10">
                <i class="fa fa-clock" aria-hidden="true"></i>
                   {{trans('messages.experience.check_in_after')}}: {{ $stime }}
            </h5>
        @endif
         
      </div>

      <div class="col-md-3 col-sm-3 col-xs-12">
		@if( $type == "experience" )
			 <h4><strong>{{trans('messages.experience.type')}}</strong></h4>
			 <h5 class="margin-top20">@if(isset($category->name)) {{ $category->name }} @endif</h5>
			 <?php if($exp_booking_type == 3) { ?>
				 
				 @foreach ($booking_details as $booking_details)
					 <?php if($booking_details->field !="price" && $booking_details->field !="itinerary" ) { ?>
						<h4 class="text-capitalize">{{ $booking_details->field }}  <strong> - {{ $booking_details->value }}</strong></h4> 
					 <?php } ?>
				 @endforeach
			 <?php } ?>	 
			 
			
		@else
			
        <h4><strong>{{trans('messages.trips_receipt.accommodation_type')}}</strong></h4>
        <h5 class="margin-top20">{{ @$booking->properties->property_type_name }}</h5>
        <h4 class="margin-top20"><strong>{{trans('messages.trips_receipt.check_out')}}</strong></h4>
        <h5 class="margin-top20">{{ $booking->enddate_dmy }}</h5>
        @if($booking->properties->check_out_before!="")
            <h5>
                <i class="fa fa-clock" aria-hidden="true"></i>
                 {{trans('messages.experience.check_out_before')}}: {{ $etime }}
            </h5>
        @endif
		@endif
		
		@if( $type == "experience" && $exp_booking_type == "2")
		<h4 class="margin-top20"><strong>{{trans('messages.experience.time')}}</strong></h4>
        <h5 class="margin-top20"> {{ $booking->time_slot }} </h5>
		@endif
      </div>
    </div>

    <div class="table-responsive mt-3"> 
      <table class="table table-bordered table-hover p-0 m-0">
        <thead class="thead-dark">
          <tr>
            <th colspan="6">{{trans('messages.trips_receipt.booking_charge')}}</th>
          </tr>
        </thead>
        <tbody class="border">
		@if( $type == "property" )
          @if($date_price)
            @foreach($date_price as $datePrice )           
              <tr>
                <td>{{ onlyFormat($datePrice->date) }}</td>
                <td class="text-right pr-4">{!! $booking->currency->symbol.currency_fix($datePrice->price, $booking->currency_code) !!}  </td>
              </tr>
            @endforeach
          @endif
          <tr>
            <td>{!! $booking->currency->symbol.$booking->per_night !!} x {{ $booking->total_night }} {{trans('messages.trips_receipt.night')}}</td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.$booking->per_night * $booking->total_night !!}</td>
          </tr>
		  @endif
		  
		  
		  @if( $type == "experience" && $exp_booking_type == "1" || $type == "experience" && $exp_booking_type == "2")
			<tr>
				<td>{!! $booking->currency->symbol.$booking->per_night !!} x {{ $booking->guest }} {{trans('messages.home.guest')}}</td>
				<td class="text-right pr-4">{!! $booking->currency->symbol.$booking->per_night * $booking->guest !!}</td>
			</tr>
		  @endif
		  
		   <?php if($exp_booking_type == 3) { ?>
		   
		   @if(isset($booking_packages))
		        @foreach($booking_packages as $booking_packages)
                    <?php
                        $pid = $booking_packages->packages_id;
                        $query =  DB::table('family_package')->where('id', $pid)->first();
                   ?>
                                                         <tr>
                                                            <td class=""> {{trans('messages.experience.packages_name')}} </td>
                                                            <td class="text-right pr-4">{{ $query->title }}</td>
                                                        </tr>
                                                         <tr>
                                                            <td class=""> {{trans('messages.experience.no_of_qty')}} </td>
                                                            <td class="text-right pr-4">{{ $booking_packages->qty }}</td>
                                                        </tr>
                                                         <tr>
                                                            <td class=""> {{trans('messages.experience.price')}} </td>
                                                            <td class="text-right pr-4">{!! $booking->currency->symbol !!}{!! currency_fix($query->price, $booking->currency->code) !!}</td>
                                                        </tr>
                                                
                                            		@endforeach
	                                 
									@endif
		   
		   
		    <?php /* ?><tr>
            <td>{{trans('messages.experience.package_price')}}</td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.currency_fix($booking_details_price->value, $booking->currency_code) !!} </td>
          </tr><?php */ ?>
		   <?php } ?>
		  
          @if($booking->guest_charge)
          <tr>
            <td class=""> {{trans('messages.trips_receipt.additional_guest_fee')}} </td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.$booking->guest_charge !!}</td>
          </tr>
          @endif
          @if($booking->cleaning_charge)
          <tr>
            <td class=""> {{trans('messages.trips_receipt.cleaning_fee')}} </td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.$booking->cleaning_charge !!}</td>
          </tr>
          @endif
          @if($booking->security_money)
          <tr>
            <td class=""> {{trans('messages.trips_receipt.security_fee')}} </td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.$booking->security_money !!}</td>
          </tr>
          @endif
          @if($booking->iva_tax)
          <tr>
            <td class="">{{trans('messages.property_single.iva_tax')}} </td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.$booking->iva_tax !!}</td>
          </tr>
          @endif
           @if($booking->accomodation_tax)
          <tr>
            <td class="">{{trans('messages.property_single.accommodatiton_tax')}}  </td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.$booking->accomodation_tax !!}</td>
          </tr>
          @endif
          <tr>
            <td>{{ $site_name }} {{trans('messages.trips_receipt.service_fee')}}</td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.$booking->service_charge !!}</td>
          </tr>
          <tr>
            <td>{{trans('messages.trips_receipt.total')}}</td>
            <td class="text-right pr-4">{!! $booking->currency->symbol.$booking->total !!}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="row">
      <div class="col-lg-3 col-sm-5 ml-auto pr-0 sv_total_val">
        <table class="table table-clear">
          <tbody>
            <tr>
              <td class="left">
                <strong>{{trans('messages.trips_receipt.payment_received')}}:{{ $booking->receipt_date }}</strong>
              </td>
              
              @if($booking->payment_method_id == "3")
                <td class="text-right pr-4"> {!! $booking->currency->symbol.$booking->total !!}</td>
              @else
                <td class="text-right pr-4"> {!! $booking->transaction_id ?  $booking->currency->symbol.$booking->total: 0 !!}</td>
              @endif
            </tr>
            
          </tbody>
        </table>

      </div>

    </div>

  </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.2/html2pdf.bundle.min.js" integrity="sha512-MpDFIChbcXl2QgipQrt1VcPHMldRILetapBl5MPCA9Y8r7qvlwx1/Mc9hNTzY+kS5kX6PdoDq41ws1HiVNLdZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script ttype="text/javascript">
  function print_receipt()
  {
    document.getElementById("print-div").classList.add("d-none");
    document.getElementById("footer").classList.add("d-none");
    window.print();

     $("#print-div").removeClass("d-none");
  }

  function download_receipt()
  {
    var element = document.getElementById('element-to-print');
    html2pdf(element);
  }

</script>
@stop