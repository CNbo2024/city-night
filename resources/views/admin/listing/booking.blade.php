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
          @include('admin.common.property_bar')
        </div>
      <div class="col-md-9">
          <div class="box box-info">
          <div class="box-body mt-0">
          <form method="post" action="{{url('admin/listing/'.$result->id.'/'.$step)}}" class='signup-form login-form' accept-charset='UTF-8'>
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
									
									<br>
											<div class="row mt-5">
										<div class="col-md-6 pl-5 pr-5">
											<label>{{trans('messages.experience.check_in_after')}}</label>
											<select name="check_in_after" id="check_in_after" class="form-control text-16 mt-2" required>
											    <option value="">None</option>
                        						<option value="0" <?php if($result->check_in_after=="0"){ echo "selected"; } ?>>12:00 AM</option>
                        						<option value="1" <?php if($result->check_in_after=="1"){ echo "selected"; } ?>>01:00 AM</option>
                        						<option value="2" <?php if($result->check_in_after=="2"){ echo "selected"; } ?>>02:00 AM</option>
                        						<option value="3" <?php if($result->check_in_after=="3"){ echo "selected"; } ?>>03:00 AM</option>
                        						<option value="4" <?php if($result->check_in_after=="4"){ echo "selected"; } ?>>04:00 AM</option>
                        						<option value="5" <?php if($result->check_in_after=="5"){ echo "selected"; } ?>>05:00 AM</option>
                        						<option value="6" <?php if($result->check_in_after=="6"){ echo "selected"; } ?>>06:00 AM</option>
                        						<option value="7" <?php if($result->check_in_after=="7"){ echo "selected"; } ?>>07:00 AM</option>
                        						<option value="8" <?php if($result->check_in_after=="8"){ echo "selected"; } ?>>08:00 AM</option>
                        						<option value="9" <?php if($result->check_in_after=="9"){ echo "selected"; } ?>>09:00 AM</option>
                        						<option value="10" <?php if($result->check_in_after=="10"){ echo "selected"; } ?>>10:00 AM</option>
                        						<option value="11" <?php if($result->check_in_after=="11"){ echo "selected"; } ?>>11:00 AM</option>
                        						<option value="12" <?php if($result->check_in_after=="12"){ echo "selected"; } ?>>12:00 PM</option>
                        						<option value="13" <?php if($result->check_in_after=="13"){ echo "selected"; } ?>>01:00 PM</option>
                        						<option value="14" <?php if($result->check_in_after=="14"){ echo "selected"; } ?>>02:00 PM</option>
                        						<option value="15" <?php if($result->check_in_after=="15"){ echo "selected"; } ?>>03:00 PM</option>
                        						<option value="16" <?php if($result->check_in_after=="16"){ echo "selected"; } ?>>04:00 PM</option>
                        						<option value="17" <?php if($result->check_in_after=="17"){ echo "selected"; } ?>>05:00 PM</option>
                        						<option value="18" <?php if($result->check_in_after=="18"){ echo "selected"; } ?>>06:00 PM</option>
                        						<option value="19" <?php if($result->check_in_after=="19"){ echo "selected"; } ?>>07:00 PM</option>
                        						<option value="20" <?php if($result->check_in_after=="20"){ echo "selected"; } ?>>08:00 PM</option>
                        						<option value="21" <?php if($result->check_in_after=="21"){ echo "selected"; } ?>>09:00 PM</option>
                        						<option value="22" <?php if($result->check_in_after=="22"){ echo "selected"; } ?>>10:00 PM</option>
                        						<option value="23" <?php if($result->check_in_after=="23"){ echo "selected"; } ?>>11:00 PM</option>
                        					</select>
										</div>
										
										<div class="col-md-6 pl-5 pr-5">
											<label>{{trans('messages.experience.check_out_before')}}</label>
											<select name="check_out_before" id="check_out_before" class="form-control text-16 mt-2" required>
											    <option value="">None</option>
                        						<option value="0" <?php if($result->check_out_before=="0"){ echo "selected"; } ?>>12:00 AM</option>
                        						<option value="1" <?php if($result->check_out_before=="1"){ echo "selected"; } ?>>01:00 AM</option>
                        						<option value="2" <?php if($result->check_out_before=="2"){ echo "selected"; } ?>>02:00 AM</option>
                        						<option value="3" <?php if($result->check_out_before=="3"){ echo "selected"; } ?>>03:00 AM</option>
                        						<option value="4" <?php if($result->check_out_before=="4"){ echo "selected"; } ?>>04:00 AM</option>
                        						<option value="5" <?php if($result->check_out_before=="5"){ echo "selected"; } ?>>05:00 AM</option>
                        						<option value="6" <?php if($result->check_out_before=="6"){ echo "selected"; } ?>>06:00 AM</option>
                        						<option value="7" <?php if($result->check_out_before=="7"){ echo "selected"; } ?>>07:00 AM</option>
                        						<option value="8" <?php if($result->check_out_before=="8"){ echo "selected"; } ?>>08:00 AM</option>
                        						<option value="9" <?php if($result->check_out_before=="9"){ echo "selected"; } ?>>09:00 AM</option>
                        						<option value="10" <?php if($result->check_out_before=="10"){ echo "selected"; } ?>>10:00 AM</option>
                        						<option value="11" <?php if($result->check_out_before=="11"){ echo "selected"; } ?>>11:00 AM</option>
                        						<option value="12" <?php if($result->check_out_before=="12"){ echo "selected"; } ?>>12:00 PM</option>
                        						<option value="13" <?php if($result->check_out_before=="13"){ echo "selected"; } ?>>01:00 PM</option>
                        						<option value="14" <?php if($result->check_out_before=="14"){ echo "selected"; } ?>>02:00 PM</option>
                        						<option value="15" <?php if($result->check_out_before=="15"){ echo "selected"; } ?>>03:00 PM</option>
                        						<option value="16" <?php if($result->check_out_before=="16"){ echo "selected"; } ?>>04:00 PM</option>
                        						<option value="17" <?php if($result->check_out_before=="17"){ echo "selected"; } ?>>05:00 PM</option>
                        						<option value="18" <?php if($result->check_out_before=="18"){ echo "selected"; } ?>>06:00 PM</option>
                        						<option value="19" <?php if($result->check_out_before=="19"){ echo "selected"; } ?>>07:00 PM</option>
                        						<option value="20" <?php if($result->check_out_before=="20"){ echo "selected"; } ?>>08:00 PM</option>
                        						<option value="21" <?php if($result->check_out_before=="21"){ echo "selected"; } ?>>09:00 PM</option>
                        						<option value="22" <?php if($result->check_out_before=="22"){ echo "selected"; } ?>>10:00 PM</option>
                        						<option value="23" <?php if($result->check_out_before=="23"){ echo "selected"; } ?>>11:00 PM</option>
                        					</select>
										</div>
									</div>
									
									
								</div>
			  
			  
			  <div class="clear-both"></div>
              <br>
              <div class="col-md-12">
                <div class="col-md-10 col-sm-6 col-xs-6 l-pad-none text-left">
                  <a data-prevent-default="" href="{{ url('admin/listing/'.$result->id.'/pricing') }}" class="btn btn-large btn-default">{{trans('messages.listing_description.back')}}</a>
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