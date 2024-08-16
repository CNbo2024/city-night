@extends('admin.template')
@section('main')
<div class="content-wrapper sv_content_wrapper">
      <!-- Main content -->
<section class="content-header">
    <h1>
        Description
        <small>Description</small>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="{{url('/')}}/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a>
      </li>
    </ol>
</section>
<section class="content">
    <div class="col-md-3">
      @include('admin.common.property_bar')
    </div>

  <div class="col-md-9">
  
  <form method="post" action="{{url('admin/listing/'.$result->id.'/'.$step)}}" class='signup-form login-form' accept-charset='UTF-8'>
    {{ csrf_field() }}
    <div class="box box-info">
    <div class="box-body mt-0">
	
		<ul class="nav nav-tabs mt-5 ml-3 sv_translation_tab">
			<li class="active"><a data-toggle="tab" href="#collapseen">En</a></li>
				@foreach($languages_new as $key => $language)
				  @php  if($language->short_name == 'en'){continue;} @endphp 
					<li><a data-toggle="tab" href="#collapse{{ $language->short_name }}">{{ $language->short_name }}</a></li>
				@endforeach
		</ul> 
		
		
		
		
		<div class="tab-content mt-5">
									
									<div id="collapseen" class="tab-pane fadein active">
										<div class="box-body">
											<div class="col-md-12 pb-4 p-0 rounded-3">
												
												 <div class="row">
        <div class="col-md-8">
          <h4>{{trans('messages.listing_description.trip')}}</h4>
          <label class="label-large">{{trans('messages.listing_description.about_place')}}</label>
          <textarea class="form-control" name="about_place" rows="4" placeholder="">{{ $result->property_description->about_place }}</textarea>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <label class="label-large">{{trans('messages.listing_description.great_place')}}</label>
          <textarea class="form-control" name="place_is_great_for" rows="4" placeholder="">{{ $result->property_description->place_is_great_for }}</textarea>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <label class="label-large">{{trans('messages.listing_description.guest_access')}}</label>
          <textarea class="form-control" name="guest_can_access" rows="4" placeholder="">{{ $result->property_description->guest_can_access }}</textarea>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <label class="label-large">{{trans('messages.listing_description.guest_interaction')}}</label>
          <textarea class="form-control" name="interaction_guests" rows="4" placeholder="">{{ $result->property_description->interaction_guests }}</textarea>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <label class="label-large">{{trans('messages.listing_description.thing_note')}}</label>
          <textarea class="form-control" name="other" rows="4" placeholder="">{{ $result->property_description->other }}</textarea>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8">
          <h4>{{trans('messages.listing_description.neighborhood')}}</h4>
          <label class="label-large">{{trans('messages.listing_description.overview')}}</label>
          <textarea class="form-control" name="about_neighborhood" rows="4" placeholder="">{{ $result->property_description->about_neighborhood }}</textarea>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <label class="label-large">{{trans('messages.listing_description.getting_around')}}</label>
          <textarea class="form-control" name="get_around" rows="4" placeholder="">{{ $result->property_description->get_around }}</textarea>
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
							 
								<div id="collapse{{ $language->short_name }}" class="tab-pane fade">
									<div class="box-body">
										<input type="hidden" name="{{ $language->short_name }}[id]" value="{{$language->id}}">
												
										<div class="col-md-12 pb-4 p-0 rounded-3 mt-4">
											
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.about_place')}}</label>
														<textarea class="form-control mt-2" name="{{ $language->short_name }}[about_place1]" rows="4" placeholder="">@if(!empty($other_description->about_place)){{ $other_description->about_place }}@endif</textarea>
													</div>
												</div>
									 
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.great_place')}}</label>
														<textarea class="form-control mt-2" name="{{ $language->short_name }}[place_is_great_for1]" rows="4" placeholder="">@if(!empty($other_description->place_is_great_for)){{ $other_description->place_is_great_for }}@endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.guest_access')}}</label>
														<textarea class="form-control mt-2" name="{{ $language->short_name }}[guest_can_access1]" rows="4" placeholder="">@if(!empty($other_description->guest_can_access)){{ $other_description->guest_can_access }}@endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
														<label class="label-large">{{trans('messages.listing_description.guest_interaction')}}</label>
														<textarea class="form-control mt-2" name="{{ $language->short_name }}[interaction_guests1]" rows="4" placeholder="">@if(!empty($other_description->interaction_guests)){{ $other_description->interaction_guests }}@endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.listing_description.thing_note')}}</label>
													<textarea class="form-control mt-2" name="{{ $language->short_name }}[other1]" rows="4" placeholder="">@if(!empty($other_description->other)){{ $other_description->other }}@endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.listing_description.overview')}}</label>
													<textarea class="form-control mt-2" name="{{ $language->short_name }}[about_neighborhood1]" rows="4" placeholder="">@if(!empty($other_description->about_neighborhood)){{ $other_description->about_neighborhood }}@endif</textarea>
													</div>
												</div>
									
												<div class="row mt-4">
													<div class="col-md-12">
													<label class="label-large">{{trans('messages.listing_description.getting_around')}}</label>
													<textarea class="form-control mt-2" name="{{ $language->short_name }}[get_around1]" rows="4" placeholder="">@if(!empty($other_description->get_around)){{ $other_description->get_around }}@endif</textarea>
													</div>
												</div>
										</div>
													
													
									</div>
								</div>
							  
								  @endforeach
								  </div>
		
	
     
      <br>
      <div class="row mt20">
        <div class="col-md-6 text-left">
            <a data-prevent-default="" href="{{ url('admin/listing/'.$result->id.'/description') }}" class="btn btn-large btn-primary">{{trans('messages.listing_description.back')}}</a>
        </div>
        <div class="col-md-6 text-right">
          <button type="submit" class="btn btn-large btn-primary next-section-button">
          {{trans('messages.listing_basic.next')}} 
          </button>
        </div>
      </div>
    </div>
  </div>

  </form>

  </section>
    <div class="clearfix"></div>
</div>
@stop