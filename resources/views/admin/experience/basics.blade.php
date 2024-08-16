@extends('admin.template')
@section('main')
  <div class="content-wrapper sv_content_wrapper">
  <!-- Main content -->
  <section class="content-header">
          <h1>
          List Your Experience
        </h1>
        
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
        
          <div class="row">
              	<div class="form-group col-md-12 pl-5 pr-5">
					<label>{{trans('messages.experience.title')}} <span class="text-danger">*</span></label>
					<input type="text" name="name" id="name" class="form-control text-16 mt-2" value="{{ $result->name }}" placeholder="" maxlength="100">
					<span class="text-danger">{{ $errors->first('name') }}</span>
				</div>
				
				<div class="form-group col-md-6 pl-5 pr-5">
									<label for="inputState">{{trans('messages.experience.experience_type')}}</label>
									<select name="experience_type"  class="form-control text-16 mt-2">
									@foreach($category as $category)
										<option value="{{ $category->id }}" @if($category->id==$result->experience_type) selected @endif>{{ $category->name }}</option>
									@endforeach
									</select>
								</div>

							
								<div class="form-group col-md-6 pr-5 mob-pd">
									<label for="inputState">{{trans('messages.experience.max_people')}}</label>
									<input type="number" name="accommodates" id="basics-select-accommodates" class="form-control text-16 mt-2" value="{{ $result->accommodates }}">
								</div>
								
								<div class="form-group col-md-12 pr-5 pl-5 mob-pd">
									<label for="">{{trans('messages.experience.duration_ex')}}</label> 
									<input type="text" name="duration" id="duration" class="form-control text-16 mt-2" value="{{ $result->duration }}">
									
								</div>
								
       
       
          </div>

          <div class="row">
            <div class="col-md-4">
              <label class="label-large">Recomended</label>
              <select name="recomended" id="basics-select-recomended" class="form-control">
                  <option value="1" {{ ( $result->recomended == 1) ? 'selected' : '' }}>Yes</option>
                  <option value="0" {{ ( $result->recomended == 0) ? 'selected' : '' }}>No</option>
              </select>
            </div> 
          </div>
          <div class="row">
          <br>
            <div class="col-md-12 text-right">
              <button type="submit" class="btn btn-large btn-primary next-section-button">
                {{trans('messages.listing_basic.next')}}
              </button>
            </div>
          </div>
        </div>
        </div>
        </div>
      </form>
      </div>
    </div>
    </section>
    <!-- /.content --> 
 <div class="clearfix"></div>
    </div>
@stop