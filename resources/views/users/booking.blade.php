	<div class="w-100 overflow-auto right-inbox p-3">
		@if($images->count())
			<div id="carouselExampleSlidesOnly" class="carousel slide p-3" data-ride="carousel">
			  <div class="carousel-inner">
			    @foreach($images as $image)
					<div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
						<img class="d-block w-100" src="{{url('public/images/property/'.$booking->properties->id.'/'.$image->photo)}}" alt="{{ $booking->properties->name }}">
					</div>
				@endforeach
			  </div>

			  <a class="carousel-control-prev" href="#carouselExampleSlidesOnly" role="button" data-slide="prev">
			    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="carousel-control-next" href="#carouselExampleSlidesOnly" role="button" data-slide="next">
			    <span class="carousel-control-next-icon" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a>
			</div>
		@endif


		<a href="{{ url('/') }}/properties/{{ $booking->properties->id}}/{{ $booking->properties->slug}}"><h4 class="text-left text-15 font-weight-700 pl-2 ">{{$booking->properties->name}}</h4></a>	 
		<span class="street-address text-muted text-14 pl-4">
			<i class="fas fa-map-marker-alt mr-2"></i>{{$booking->properties->property_address->address_line_1}}
		</span>
		<div class="row p-2">
			<div class="col-md-12 border p-2">
				<div class="d-flex  justify-content-between">
					<div>
						<div class="text-16"><strong>{{trans('messages.header.check_in')}}</strong></div>
						<div class="text-14">{{ onlyFormat($booking->start_date) }}</div>
					</div>
					
                    @if($booking->properties->type=="property")
    					<div>
    						<div class="text-16"><strong>{{trans('messages.header.check_out')}}</strong></div>
    						<div class="text-14">{{ onlyFormat($booking->end_date) }}</div>
    					</div>
                    @endif
				</div>
			</div>
		</div>

        @if($booking->properties->type=="property")
	    	<div class="row p-2">
			    <div class="col-md-12 col-sm-6 col-xs-6  border-success pl-3 pr-3 text-center pt-3 pb-3 mt-3 rounded-3">
				    <p class="text-16 font-weight-700 text-danger pt-0 m-0">
					<i class="fas fa-bed text-20 d-none d-sm-inline-block pr-2 text-danger"></i><strong>{{$booking->guest}}</strong> <!-- <br> --> {{trans('messages.header.guest')}} </p>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 p-2">
					<h5 class="text-16 mt-3"><strong>{{trans('messages.payment.payment')}}</strong></h5>
				</div>
			</div>
			@endif

			<div class="row">
				<div class="col-md-12 p-2">
					<div class="full-table mt-3 border text-dark rounded-3 pt-3 pb-3 mb-4">
					    	@if($booking->properties->type=="experience" && $booking->properties->exp_booking_type=="3")
					    	@if(isset($booking_packages))
    									<div class="">
    									  
                                                 <table class="table service-table">
                                        			<thead class="thead-inverse">
                                            			<tr>
                                            				<th class="text-14">{{trans('messages.experience.packages_name')}}</th>
                                            				<th class="text-14">{{trans('messages.experience.no_of_qty')}}</th>
                                            				<th class="text-14">{{trans('messages.experience.price')}}</th>
                                            			</tr>
                                        			</thead>
        		                                        @foreach($booking_packages as $booking_packages)
                                                        <?php
                                                            $pid = $booking_packages->packages_id;
                                                            $query =  DB::table('family_package')->where('id', $pid)->first();
                                                        ?>
                                                		<tbody>
                                            			<tr data-id="">
                                            				<td class="text-14">{{ $query->title }}</td>
                                            				<td class="text-14">{{ $booking_packages->qty }}</td>
                                            				<td class="text-14">{!! $booking->currency->symbol !!}{!! currency_fix($query->price, $booking->currency->code) !!}</td>
                                                           
                                            			</tr>  
                                            		</tbody>
                                            		@endforeach
	                                        	</table>
    									</div>
									@endif
										@endif
									
					    @if(isset($booking_info->value)) 
    						 <p class="row margin-top10 text-justify text-16">
    							<span class="text-left col-sm-6 text-14">{{trans('messages.experience.packages')}}</span>
    							<span class="text-right col-sm-6 text-14">{!! $booking->currency->symbol !!} {!! currency_fix($booking_info->value, $booking->currency->code) !!}</span>
    						</p>
						@endif
						
						@if($booking->properties->type=="experience" && $booking->properties->exp_booking_type!="3")
						<p class="row margin-top10 text-justify text-16">
							<span class="text-left col-sm-6 text-14"> {!! $booking->currency->symbol !!} {{$booking->per_night}} x {{$booking->total_night}} {{trans('messages.property_single.night')}} </span>
							<span class="text-right col-sm-6 text-14">{!! $booking->currency->symbol !!} {{$booking->per_night * $booking->total_night}} </span>
						</p>
						@endif

						<p class="row margin-top10 text-justify text-16">
							<span class="text-left col-sm-6 text-14">{{trans('messages.property_single.service_fee')}}</span>
							<span class="text-right col-sm-6 text-14">{!! $booking->currency->symbol.$booking->service_charge !!}</span>
						</p>
						
						 

						@if($booking->accomodation_tax)
						<p class="row margin-top10 text-justify text-16">
							<span class="text-left col-sm-8 text-14">{{trans('messages.property_single.accommodatiton_tax')}}</span>
							<span class="text-right col-sm-4 text-14">{!! $booking->currency->symbol.$booking->service_charge !!}</span>
						</p>
						@endif

						@if($booking->iva_tax)
						<p class="row margin-top10 text-justify text-16">
							<span class="text-left col-sm-6 text-14">{{trans('messages.property_single.iva_tax')}}</span>
							<span class="text-right col-sm-6 text-14">{!! $booking->currency->symbol.$booking->iva_tax !!}</span>
						</p>
						@endif

						<p class="row mt-3 text-justify text-16">
							<span class="text-left col-sm-6 text-16 font-weight-600">{{trans('messages.property_single.total')}}</span>
							<span class="text-right col-sm-6 text-16 font-weight-600">{!! $booking->currency->symbol.$booking->total !!}</span>
						</p>
					</div>	
				</div>
			</div>

		</div>
