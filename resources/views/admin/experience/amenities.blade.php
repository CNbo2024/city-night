	@extends('admin.template')
	@section('main')
	<div class="content-wrapper sv_content_wrapper">
		<!-- Main content -->
		<section class="content-header">
				<h1>Amenities</h1>
		
		</section>

		<section class="content">
			<div class="row">
				<div class="col-md-3">
					@include('admin.common.experience_bar')
				</div>

				<div class="col-md-9">
					<form method="post" action="{{url('admin/experience/'.$result->id.'/'.$step)}}" class='signup-form login-form' accept-charset='UTF-8'>
						{{ csrf_field() }}
						<div class="box box-info">
							<div class="box-body mt-0">
								
												<div class="col-md-12 pr-4 pt-0 pb-4">
													<div class="row">
													    <div class="col-md-12 pl-4 mb-2">
													        <h4 class="text-18 font-weight-700 pl-0 pr-0 pb-2">{{trans('messages.experience.inclusion')}}</h4>
												        </div>
												
														@foreach($inclusion as $inclusion)
														<?php
																$sv_btype			= App\Models\Inclusion::where('id', $inclusion->id)->first();
																$temp_id1 			= $sv_btype->temp_id;
																$query				= App\Models\Inclusion::where('temp_id', $temp_id1)->get();
														    ?>
																<div class="col-xl-4 col-lg-6 p-0">
																	<label class="text-14 label-large label-inline amenity-label mb-3">
																		<input type="checkbox" value="<?php foreach($query as $query) { echo $query->id.','; } ?>" name="inclusion[]" {{ in_array($inclusion->id, $property_inclusion) ? 'checked' : '' }}>
																		<span>{{ $inclusion->name }}</span>
																	</label>
																</div>
														@endforeach
														<span class="ml-4"  id="at_least_one"><br></span>
													</div>
												</div>
														<div class="col-md-12 pl-4 mb-2">
													<h4 class="text-18 font-weight-700 pl-0 pr-0 pb-2">{{trans('messages.experience.exclusion')}}</h4>
												</div>

												<div class="col-md-12 pr-4 pt-0 pb-4">
													<div class="row">
														@foreach($exclusion as $exclusion)
													<?php
																$sv_btype1			= App\Models\Exclusion::where('id', $exclusion->id)->first();
																$temp_id2 			= $sv_btype1->temp_id;
																
																$query1				= App\Models\Exclusion::where('temp_id', $temp_id2)->get();
														    ?>
																<div class="col-xl-4 col-lg-6 p-0">
																	<label class="text-14 label-large label-inline amenity-label mb-3">
																		<input type="checkbox" value="<?php foreach($query1 as $query1) { echo $query1->id.','; } ?>" name="exclusion[]" {{ in_array($exclusion->id, $property_exclusion) ? 'checked' : '' }}>
																		<span>{{ $exclusion->name }}</span>
																	</label>
																</div>
														@endforeach
														<span class="ml-4"  id="at_least_one"><br></span>
													</div>
												</div>
								<br>
								<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-6 text-left">
										<a data-prevent-default="" href="{{ url('admin/experience/'.$result->id.'/location') }}" class="btn btn-large btn-default">{{trans('messages.listing_description.back')}}</a>
									</div>
									
									<div class="col-md-6 col-sm-6 col-xs-6 text-right">
										<button type="submit" class="btn btn-large btn-primary next-section-button">
										{{trans('messages.listing_basic.next')}} 
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div> 
			</div>
		</section> 
		<div class="clearfix"></div>
	</div>
	@stop