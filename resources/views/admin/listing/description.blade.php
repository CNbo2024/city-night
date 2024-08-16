@extends('admin.template')
@section('main')
  <div class="content-wrapper sv_content_wrapper">
         <!-- Main content -->
  <section class="content-header">
      <h1>
          Description
      </h1>
     
  </section>

  <section class="content">
      <div class="col-md-3">
        @include('admin.common.property_bar')
      </div>

      <div class="col-md-9">
      <form id="list_des" method="post" action="{{url('admin/listing/'.$result->id.'/'.$step)}}" class='signup-form login-form' accept-charset='UTF-8'>
        {{ csrf_field() }}

      <div class="box box-info">
      <div class="box-body mt-0">

		<ul class="nav nav-tabs mt-3 ml-3 sv_translation_tab">
									<li class="active"><a class="" data-toggle="tab" href="#collapseen">En</a></li>
									  @foreach($languages_new as $key => $language)
									  @php  if($language->short_name == 'en'){continue;} @endphp 
									  
										<li><a data-toggle="tab" href="#collapse{{ $language->short_name }}">{{ $language->short_name }}</a></li>
									@endforeach
								</ul> 
		
	  
								<div class="tab-content mt-5 ">
									
									<div id="collapseen" class="tab-pane fadein active">
										<div class="box-body">
											 <div class="row">
												<div class="col-md-8 col-sm-12 col-xs-12 mb20">
												  <label class="label-large">{{trans('messages.listing_description.listing_name')}} <span class="text-danger">*</span></label>
												  <input type="text" name="name" class="form-control" value=" {{ old('name', $description->properties->name)  }}" placeholder="" maxlength="100">
												  <span class="text-danger">{{ $errors->first('name') }}</span>
												</div>
											  </div>
											  <div class="row">
												<div class="col-md-8  col-sm-12 col-xs-12 mb20">
												  <label class="label-large">{{trans('messages.listing_description.summary')}} <span class="text-danger">*</span></label>
												  <textarea class="form-control" name="summary" rows="6" placeholder="" ng-model="summary">{{ old('summary', $description->summary)  }}</textarea>
												  <span class="text-danger">{{ $errors->first('summary') }}</span>
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
											{{trans('messages.listing_description.listing_name')}} 
											<input type="text" class="form-control" value="@if(!empty($other_description->name)) {{ $other_description->name }} @endif" name="{{ $language->short_name }}[name1]" id="">
											<br>
											{{trans('messages.listing_description.summary')}}
											<textarea id="description" name="{{ $language->short_name }}[description1]" class="form-control" style="height: 200px">@if(!empty($other_description->summary)) {{ $other_description->summary }} @endif</textarea>
										
										</div>
									</div>
							  
								  @endforeach
							  </div>
		  
		    
		  
		  
          <div class="row">
            <div class="col-md-6  col-sm-6 col-xs-6 text-left">
                <a data-prevent-default="" href="{{ url('admin/listing/'.$result->id.'/basics') }}" class="btn btn-large btn-default">{{trans('messages.listing_description.back')}}</a>
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
    </section>
    <!-- /.content -->
     <div class="clearfix"></div>      
    </div>
@stop

@section('validate_script')
<script type="text/javascript">
   $(document).ready(function () {

            $('#list_des').validate({
                rules: {
                    name: {
                        required: true
                    },
                    summary: {
                        required: true
                    }
                }
            });

        });
</script>
@endsection