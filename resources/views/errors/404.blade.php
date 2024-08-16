@extends('template')

@section('main')
    <div class="container-fluid container-fluid-90 min-height margin-top-85 mb-5">
      <div class="error_width " >
        <div class="notfound position-center">
            <div>
              <h1 class="error-reply">{{trans('messages.error.error_msg')}}</h1>
            </div>
            <h2 class="text-center">{{trans('messages.error.error_data_1')}}  {{trans('messages.error.error_data_2')}}</h2>
            <h5>Error code: 404</h5>
          </div>
      </div>
    </div>  
@stop
