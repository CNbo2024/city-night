@extends('admin.template')
@section('main')
  <div class="content-wrapper sv_content_wrapper">
        <!-- Main content -->
  <section class="content-header">
          <h1>
          Booking
        </h1>
       
  </section>

    <section class="content">
        <div class="row">
        <div class="col-md-3">
          @include('admin.common.experience_bar')
        </div>
      <div class="col-md-9">
          <div class="box box-info">
          <div class="box-body mt-0">
          <form method="post" action="{{url('admin/experience/'.$result->id.'/'.$step)}}" class='signup-form login-form' accept-charset='UTF-8'>
            {{ csrf_field() }}
              <div class="row">
                  <div class="col-md-12">
                  <h3>{{trans('messages.listing_book.booking_title')}}</h3>
                  <p class="text-muted">{{trans('messages.listing_book.booking_data')}}.</p>
                  </div>
              <div class="row">
                <div class="col-md-8">
                  <div class="col-md-12">
                    <label class="label-large">{{trans('messages.listing_book.booking_type')}}  <span class="text-danger">*</span></label>
                    <select name="booking_type" id="select-booking_type" class="form-control">
                        <option value="request" {{ ($result->booking_type == 'request') ? 'selected' : '' }}>{{trans('messages.listing_book.review_request')}}</option>
                        <option value="instant" {{ ($result->booking_type == 'instant') ? 'selected' : '' }}>{{trans('messages.listing_book.guest_instant')}}</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="clear-both"></div>
			  <br>
			  <div class="col-md-12 p-0 mt-4 border rounded pb-4 m-0">
									<div class="form-group col-md-12 main-panelbg pb-3 pt-3 pl-4">
											<h4 class="text-16 font-weight-700">{{trans('messages.listing_sidebar.terms')}}</h4>
									</div>
									<div class="row m-0 pl-5 pr-5">
										<div class="col-md-12 p-0">
											<p>{{trans('messages.listing_sidebar.terms_desc')}} <span class="text-danger">*</span></p>
										</div>
									</div>
									
									<div class="row m-0">
										<div class="col-md-12 pl-5 pr-5">
											<label>{{trans('messages.listing_sidebar.cancellation_policy')}}</label>
											<select name="cancellation" id="cancellation" class="form-control text-16 mt-2">
												<option value="Flexible" {{ ($result->cancellation == 'Flexible') ? 'selected' : '' }}>Flexible: Full refund 1 day prior to arrival, except fees</option>
    											<option value="Moderate" {{ ($result->cancellation == 'Moderate') ? 'selected' : '' }}> Moderate: Full refund 5 days prior to arrival, except fees </option>
												<option value="Strict" {{ ($result->cancellation == 'Strict') ? 'selected' : '' }}>Strict: 50% refund up until 1 week prior to arrival, except fees</option>

											</select>
										</div>
									</div>
								</div>
			  
			  
			  <div class="clear-both"></div>
              <br>
              <div class="col-md-12">
                <div class="col-md-10 col-sm-6 col-xs-6 l-pad-none text-left">
                  <a data-prevent-default="" href="{{ url('admin/experience/'.$result->id.'/pricing') }}" class="btn btn-large btn-default">{{trans('messages.listing_description.back')}}</a>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-6 text-right">
                  <button type="submit" class="btn btn-large btn-primary next-section-button">Complete
                  </button>
                </div>
              </div>
          </form>
      </div>
      </div>
      </div>
      </div>
      </section>
  </div>
@stop