@extends('template')

@section('main')
<div class="margin-top-85">
    <div class="row m-0">
        @include('users.sidebar')

        <div class="col-lg-12">
            <div class="main-panel mb-5">
                <div class="container-fluid container-fluid-90">
                    <div class="col-md-12 p-0 mb-3 mt-5">
                        <span class="text-18 pt-4 pb-4 font-weight-700">{{trans('messages.sidenav.my_listing')}} </span>
                        
                        <div class="row mt-4">
                            <div class="col-md-3 p-0">
                            <div class="mt-3 rounded-3 border">
                                
                                        <!--<div class="pl-3 mt-3 pb-3">
                                            <span class="text-14 font-weight-700">{{trans('messages.users_dashboard.sort_by')}}</span>
                                        </div>-->
                                        
                                        <div class="">
                                            <form action="{{ url('/properties') }}" method="POST" id="listing-form">
                                                {{ csrf_field() }}
                                                <select class="form-control text-14" id="listing_select" name="status">
                                                    <option value="All" {{ @$status == "All" ? ' selected="selected"' : '' }}> {{trans('messages.filter.all')}}</option>
                                                    <option value="Listed" {{ @$status == "Listed" ? ' selected="selected"' : '' }}>{{trans('messages.property.listed')}}</option>
                                                    <option value="Unlisted" {{ @$status == "Unlisted" ? ' selected="selected"' : '' }}>{{trans('messages.property.unlisted')}}</option>
                                                    <option value="Posted by me" {{ @$status == "Posted by me" ? ' selected="selected"' : '' }}>{{trans('messages.posted_by_me')}}</option>
                                                    <option value="Managed by me" {{ @$status == "Managed by me" ? ' selected="selected"' : '' }}>{{trans('messages.managed_by_me')}}</option>
                                                </select>
                                            </form>
                                        </div>

                            </div>

                            <button class="btn btn-primary btn-lg mt-3" data-toggle="modal" data-target="#cohosts" type="button">
                                {{ __('View and set up co-hosts') }}
                            </button>

                            <div class="modal" id="cohosts" tabindex="-1" role="dialog">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="/cohosts" method="POST">
                                      <div class="modal-header">
                                        <h5 class="modal-title">{{ __('Co-hosts') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <p>{{ __('Write the email of the co-hosts separating them by line breaks.') }}</p>

                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        <textarea name="cohosts" class="form-control" rows="5">{{  str_replace(',', "\r\n", auth()->user()->cohosts) }}</textarea>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                      </div>
                                    </form>
                                </div>
                              </div>
                            </div>

                            </div>
                       
                    <div class="col-md-9 p-0">
                        <div class="pl-md-5">

                    <div class="alert alert-success d-none" role="alert" id="alert">
                        <span id="messages"></span>
                    </div>
                    
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{!! \Session::get('success') !!}</li>
                            </ul>
                        </div>
                    @endif
                    
                    <div id="products" class="row mt-3">

                        @forelse($properties as $property)
                            <div class="col-md-12 p-0 mb-4">
                                <div class=" row  border p-2 rounded-3">
                                    <div class="col-md-3 col-xl-4 p-2">
                                        <div class="img-event">
                                            <a href="properties/{{ $property->id }}/{{ $property->slug }}">
                                                <img class="room-image-container200 rounded" src="{{ $property->cover_photo }}" alt="cover_photo">
                                            </a>  
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xl-6 p-2">
                                        <div>
                                            <a href="properties/{{ $property->id }}/{{ $property->slug }}">
                                                <p class="mb-0 text-18 text-color font-weight-700 text-color-hover">{{ ($property->name == '') ? '' : $property->name }}</p>     
                                            </a>
                                        </div>

                                        <p class="text-14 mt-3 text-muted mb-0">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{$property->property_address->address_line_1}}
                                        </p>
                                        
                                        <ul class="list-inline mt-2 pb-3">
                                            <li class="list-inline-item rounded-3 p-1 border-success text-muted">
                                                <p class="text-center mb-0">
                                                    <i class="fas fa-bed text-15 d-none d-sm-inline-block text-muted"></i> 
                                                    {{ $property->accommodates }}
                                                    <span class="text-muted text-13 font-weight-700">{{trans('messages.property_single.bed')}}</span> 
                                                </p>
                                            </li>
                                            <li class="list-inline-item  rounded-3 p-1  pl-3 border-success text-muted">
                                                <p  class="text-center mb-0" >
                                                    <i class="fas fa-user-friends d-none d-sm-inline-block text-15 text-muted"></i>
                                                    {{ $property->bedrooms }}
                                                    <span class=" text-13 font-weight-700">{{trans('messages.property_single.guest')}}</span> 
                                                </p>
                                            </li>
                                            <li class="list-inline-item  rounded-3 p-1  pl-3 border-success text-muted">
                                                <p  class="text-center mb-0">
                                                    <i class="fas fa-bath text-15  d-none d-sm-inline-block text-muted"></i>
                                                    {{ $property->bathrooms }}
                                                    <span class="text-13 font-weight-700">{{trans('messages.property_single.bathroom')}}</span> 
                                                </p>
                                            </li>
                                        </ul>

                                        <div class="review-0 mt-4">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span><i class="fa fa-star text-14 yellow_color"></i>
                                                        @if( $property->reviews_count)
                                                            {{ $property->avg_rating }}
                                                        @else
                                                            0
                                                        @endif 
                                                        ({{ $property->reviews_count }})
                                                    </span>
                                                </div>
                                            
                                                <div class="pr-5">
                                                    <span class="font-weight-700 text-20">{!! moneyFormat( $property->property_price->currency->symbol, $property->property_price->price) !!}</span> / {{trans('messages.property_single.night')}}</span>
                                                </div>
                                            </div>
                                        </div>

                                        
                                    </div>

                                    <div class="col-md-3 col-xl-2 p-0">
                                        <div class="mt-3">
                                            <div class="align-self-center">
                                                <div class="row">
                                                    <div class="col-12 col-sm-12">
                                                      
                                                        <div class="main-toggle-switch text-left text-sm-center">
                                                            @if($property->steps_completed == 0)
                                                            <label class="toggleSwitch large" onclick="">
                                                                <input type="checkbox" id="status" data-id="{{ $property->id}}" data-status="{{$property->status}}"  {{ $property->status == "Listed" ? 'checked' : '' }}/>
                                                                <span>
                                                                    <span>{{trans('messages.property.unlisted')}}</span>
                                                                    <span>{{trans('messages.property.listed')}}</span>
                                                                </span>
                                                                <a href="#" aria-label="toggle"></a>
                                                            </label>
                                                            @else

                                                            <span class="badge badge-warning p-3 pl-4 pr-4 text-14 border-r-25">{{ $property->steps_completed }} {{trans('messages.property.step_listed')}}</span>
                                                            @endif
                                                        </div>
                                                        @if($property->status == "Listed")
                                                            <a class="btn btn-host step_count mt-2 btn btn-primary text-center w-100 disable_after_click text-14" href="{{ url('/duplicate/'.$property->id) }}">
                                                               {{trans('messages.experience.duplicate')}}
                                                            </a>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="col-12 col-sm-12 pr-0">
                                                        <a href="{{ url('listing/'.$property->id.'/basics') }}">
                                                            <div class="text-color text-danger text-color-hover text-13 text-right text-sm-center mob-txt-center mt-0 mt-md-3">
                                                                <i class="fas fa-edit"></i> 
                                                                {{trans('messages.property.manage_list_cal')}} 
                                                            </div>
                                                        </a>
														
														@if($property->admin_approval==0)
															<p class="text-14 text-center text-danger">{{trans('messages.property.disapproved')}}</p>
														@else
															<p class="text-14 text-center text-success">{{trans('messages.property.approved')}}</p>
													    @endif
                                                        
                                                        @if($property->steps_completed == 0)
                                                        <a href="properties/{{ $property->id }}/{{ $property->slug }}" target="_blank">
                                                            <div class="text-color text-primary text-color-hover text-13 text-right text-sm-center mob-txt-center mt-0 mt-md-3">
                                                                <i class="fas fa-eye"></i> 
                                                                {{trans('messages.property.preview')}} 
                                                            </div>
                                                        </a>
                                                        @endif
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="row jutify-content-center position-center w-100 p-4 mt-4">
                                <div class="text-center w-100">
                                    <img src="{{ url('public/img/unnamed.png')}}" class="img-fluid"   alt="Not Found">
                                    <p class="text-center">{{trans('messages.message.empty_listing')}}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="row justify-content-between overflow-auto  pb-3 mt-4 mb-5">
                        {{ $properties->appends(request()->except('page'))->links('paginate')}}
                    </div>
                    </div>
                    </div>
                    
                </div>
                
                
                </div>
                    </div>
                
                
            </div>
        </div>
    </div>

    @if(showNextStepsSection())
        <div class="row footer-bg py-5">
            <div class="col-md-12">
                <div class="container">
                    <h1 class="mt-5">{{ __('Next steps') }}</h1>
                    <p>{{ __('Its time to review a couple of current configurations.') }}</p>

                    <div class="row mt-5 mb-5">
                        <div class="col-md-4">
                            <div class="card p-5">
                                <div class="card-body text-center">
                                    <a href="/users/payout" class="card-title">
                                        <i class="fa fa-money-bill"></i>

                                        <div>
                                            {{ __('Set up your payment account') }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card p-5">
                                <div class="card-body text-center">
                                    <a href="/users/edit-verification" class="card-title">
                                        <i class="fa fa-check"></i>

                                        <div>
                                            {{ __('Verify your account') }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card p-5">
                                <div class="card-body text-center">
                                    <a href="/properties" class="card-title">
                                        <i class="fa fa-building"></i>

                                        <div>
                                            {{ __('Check your posts and properties') }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>          
                </div>
            </div>
        </div>
    @endif
</div>
@stop

@push('scripts')
<script type="text/javascript">
    $(document).on('click', '#status', function(){
        var id = $(this).attr('data-id');
        var datastatus = $(this).attr('data-status');
        var dataURL = APP_URL+'/listing/update_status';
        $('#messages').empty();
        $.ajax({
            url: dataURL,
            data:{
                "_token": "{{ csrf_token() }}",
                'id':id,
                'status':datastatus,
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                $("#status").attr('data-status', data.status)
                $("#messages").append("");
                $("#alert").removeClass('d-none');
                $("#messages").append(data.name+" "+"{{trans('messages.experience.has_been')}}"+" "+data.status+".");
                var header = $('#alert');
                setTimeout(function() {
                    header.addClass('d-none');
                }, 4000);
            }
        });
    });

     $(document).on('change', '#listing_select', function(){

            $("#listing-form").trigger("submit"); 
              
    });
    
    
    $(document).ready(function()
    {
        document.getElementById('listing_select').size=5;
    });
    
</script>
@endpush

   
