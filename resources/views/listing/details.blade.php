@extends('template')

@section('main')
<div class="margin-top-85">
	<div class="row m-0">
		<!-- sidebar start-->
		@include('users.sidebar')
		<!--sidebar end-->
			<div class="col-md-12 p-0">
		         @include('listing.sidebar')
		    </div>
			<div class="col-md-12 pl-0">
				<div class="row">
				
					<div class="col-md-6 p-0 sv_step_first step-three">
                        <img src="{{ url('public/images/step3.jpg') }}" class="img-fluid">
    			        <div>
               				<h3 class="text-center text-52 font-weight-700">{{trans('messages.listing_description.add_more')}} {{trans('messages.listing_description.detail')}} {{trans('messages.listing_description.detail_data')}}.</h3>
                        </div>
					</div>

					<div class="col-md-6 mt-4 pl-4 pr-4 pb-4">
						<form method="post" action="{{url('listing/'.$result->id.'/'.$step)}}" class='signup-form login-form' accept-charset='UTF-8' id="listing_det">
							{{ csrf_field() }}
							
							
							<div class="form-group col-md-12 pb-3 pt-3">
								<h4 class="text-18 font-weight-700 pl-3 text-capitalize">{{trans('messages.listing_description.detail')}}</h4>
							</div>
							
							<div class="container">
								<ul class="nav nav-tabs mt-5 ml-3">
									<li><a data-toggle="tab" href="#collapseen">En</a></li>
									  @foreach($languages_new as $key => $language)
									  @php  if($language->short_name == 'en'){continue;} @endphp 
									  
										<li><a data-toggle="tab" class="active" href="#collapse{{ $language->short_name }}">{{ $language->short_name }}</a></li>
									@endforeach
								</ul> 
								<div class="tab-content mt-5">
									
									<div id="collapseen" class="tab-pane fade">
										<div class="box-body">
											<div class="col-md-12 pb-4 p-0 rounded-3">
												
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.about_place')}}</label>
														<textarea class="form-control mt-2" name="about_place" rows="4" placeholder="">{{ $result->property_description->about_place }}</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.great_place')}}</label>
														<textarea class="form-control mt-2" name="place_is_great_for" rows="4" placeholder="">{{ $result->property_description->place_is_great_for }}</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.guest_access')}}</label>
														<textarea class="form-control mt-2" name="guest_can_access" rows="4" placeholder="">{{ $result->property_description->guest_can_access }}</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.guest_interaction')}}</label>
														<textarea class="form-control mt-2" name="interaction_guests" rows="4" placeholder="">{{ $result->property_description->interaction_guests }}</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.house_rules')}}</label>
													<textarea class="form-control mt-2" name="other" rows="4" placeholder="">{{ $result->property_description->other }}</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.listing_description.overview')}}</label>
													<textarea class="form-control mt-2" name="about_neighborhood" rows="4" placeholder="">{{ $result->property_description->about_neighborhood }}</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.listing_description.getting_around')}}</label>
													<textarea class="form-control mt-2" name="get_around" rows="4" placeholder="">{{ $result->property_description->get_around }}</textarea>
													</div>
												</div>
										</div>
										
										
										</div>
									</div>
								
								 @foreach($languages_new as $key => $language)
								  @php if($language->short_name == 'en'){continue;}  @endphp 
								  <?php
										$other_description = App\Models\PropertyMeta::where([ ['property_id',$result->id],['lang_id', $language->id ]])->first();
								 ?>
							  
								<div id="collapse{{ $language->short_name }}" class="tab-pane fadein active">
									<div class="box-body">
										<input type="hidden" name="{{ $language->short_name }}[id]" value="{{$language->id}}">
												
										<div class="col-md-12 pb-4 p-0 rounded-3 mt-4">
											<!--<div class="form-group col-md-12 pb-3 pt-3">
												<h4 class="text-18 font-weight-700 pl-3 text-capitalize">{{trans('messages.listing_description.detail')}}</h4>
											</div>-->
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.about_place')}}</label>
														<textarea class="form-control mt-2" placeholder="Ejemplo: Este apartamento moderno cuenta con una decoración elegante y todas las comodidades necesarias para una estancia cómoda. Ubicado en un vecindario tranquilo, es el lugar perfecto para relajarse después de un día explorando la ciudad." name="{{ $language->short_name }}[about_place1]" rows="4" placeholder="">@if(!empty($other_description->about_place)) {{ $other_description->about_place }} @endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.great_place')}}</label>
														<textarea placeholder="Ejemplo: El lugar es ideal para viajeros de negocios, parejas y familias. Su ubicación céntrica y el ambiente tranquilo lo hacen perfecto para trabajar o descansar." class="form-control mt-2" name="{{ $language->short_name }}[place_is_great_for1]" rows="4" placeholder="">@if(!empty($other_description->place_is_great_for)) {{ $other_description->place_is_great_for }} @endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.guest_access')}}</label>
														<textarea placeholder="Ejemplo: Los huéspedes tendrán acceso a todas las áreas del apartamento, incluyendo la cocina, el salón, y la terraza. También hay una piscina comunitaria y un gimnasio disponibles." class="form-control mt-2" name="{{ $language->short_name }}[guest_can_access1]" rows="4" placeholder="">@if(!empty($other_description->guest_can_access)) {{ $other_description->guest_can_access }} @endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.guest_interaction')}}</label>
														<textarea placeholder="Ejemplo: Me gusta dar espacio a mis huéspedes, pero estoy disponible para cualquier pregunta o ayuda que necesiten durante su estancia. Puedes contactarme por teléfono o mensaje de texto." class="form-control mt-2" name="{{ $language->short_name }}[interaction_guests1]" rows="4" placeholder="">@if(!empty($other_description->interaction_guests)) {{ $other_description->interaction_guests }} @endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.listing_description.thing_note')}}</label>
													<textarea placeholder="Ejemplo: No se permiten fiestas ni eventos. Por favor, respeta a los vecinos y mantén el ruido al mínimo después de las 10 PM. No se permite fumar dentro del apartamento." class="form-control mt-2" name="{{ $language->short_name }}[other1]" rows="4" placeholder="">@if(!empty($other_description->other)) {{ $other_description->other }} @endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.listing_description.overview')}}</label>
													<textarea placeholder="Ubicado en una calle tranquila, este apartamento es un refugio perfecto con fácil acceso a las principales atracciones de la ciudad. Con una decoración moderna y todas las comodidades, es ideal para una estancia cómoda y relajante." class="form-control mt-2" name="{{ $language->short_name }}[about_neighborhood1]" rows="4" placeholder="">@if(!empty($other_description->about_neighborhood)) {{ $other_description->about_neighborhood }} @endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.listing_description.getting_around')}}</label>
													<textarea placeholder="La propiedad está cerca de varias líneas de autobuses y estaciones de metro. Además, hay opciones de alquiler de bicicletas y scooters en la zona, así como taxis y servicios de transporte por aplicación disponibles las 24 horas." class="form-control mt-2" name="{{ $language->short_name }}[get_around1]" rows="4" placeholder="">@if(!empty($other_description->get_around)) {{ $other_description->get_around }} @endif</textarea>
													</div>
												</div>
										</div>
													
													
									</div>
								</div>
							  
								  @endforeach
								  </div>
								</div>
							
							
							
							<div class="col-md-12">
                                <hr class="step-hr">
                            </div>
							
							
							
												

							<div class="col-md-12 mt-4 mb-5">
								<div class="row m-0 justify-content-between">
									<div class="mt-4 ml-4">
										<a  href="{{ url('listing/'.$result->id.'/description') }}" class="btn btn-outline-danger secondary-text-color-hover text-16 font-weight-700  pt-3 pb-3 pl-5 pr-5">
										{{trans('messages.listing_description.back')}}
										</a>
									</div>

									<div class="mt-4 mr-4">
										<button type="submit" class="btn vbtn-default text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3 pl-5 pr-5" id="btn_next"><i class="spinner fa fa-spinner fa-spin d-none" ></i>
											<span id="btn_next-text">{{trans('messages.listing_basic.next')}}</span>
											 
										</button>
									</div>
								</div>  
							</div>
						</form>
					</div>
				</div>
			</div>
		
	</div>
</div>
@stop

@push('scripts')
<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#listing_det').validate({
			submitHandler: function(form)
            {
                $("#btn_next").on("click", function (e)
                {	
                	$("#btn_next").attr("disabled", true);
                    e.preventDefault();
                });
                
                $(".spinner").removeClass('d-none');
                $("#btn_next-text").text("{{trans('messages.listing_basic.next')}} ..");
                return true;
            }
		});
	});
</script>
@endpush