@extends('template')

@section('main')
<div class="margin-top-85">
    <div class="row m-0">
        @include('users.sidebar')

        <div class="col-lg-12">
            <div class="main-panel mb-5">
                <div class="container-fluid container-fluid-90">
                    <div class="col-md-12 p-0 mb-3 mt-5">
                        <span class="text-18 pt-4 pb-4 font-weight-700"> {{trans('messages.experience.my_experience')}} </span>
                        
                        <div class="row mt-4">
                            <div class="col-md-3 p-0">
                            <div class="mt-3 rounded-3 border">
                                        
                                        <div class="">
                                            <form action="{{ url('/experience') }}" method="POST" id="listing-form">
                                                {{ csrf_field() }}
                                                <select class="form-control text-14" id="listing_select" name="status">
                                                    <option value="All" {{ @$status == "All" ? ' selected="selected"' : '' }}> {{trans('messages.filter.all')}}</option>
                                                    <option value="Listed" {{ @$status == "Listed" ? ' selected="selected"' : '' }}>{{trans('messages.property.listed')}}</option>
                                                    <option value="Unlisted" {{ @$status == "Unlisted" ? ' selected="selected"' : '' }}>{{trans('messages.property.unlisted')}}</option>
                                                </select>
                                            </form>
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
                                               
                                                <?php 
                                               
                                                    if($property->type == "experience" && $property->exp_booking_type == "3" ) { 
                                                    $query = DB::table('family_package')->where('property_id', $property->id )->orderBy('price', 'ASC')->first();
                                                    if(isset($query->price))
                                                    {
                                                        $exp_price= $query->price;
                                                    }
                                                    else
                                                    {
                                                        $exp_price = "";
                                                    }
                                                    
                                                ?>
                                                    <div class="pr-5">
                                                        <span class="font-weight-700 text-20">@if(isset($property->property_price->currency->symbol)) {!! moneyFormat( $property->property_price->currency->symbol, $exp_price) !!} @endif</span>
                                                    </div>
                                                <?php } else {  ?>

                                                <div class="pr-5">
                                                    <span class="font-weight-700 text-20">@if(isset($property->property_price->currency->symbol)) {!! moneyFormat( $property->property_price->currency->symbol, $property->property_price->price) !!} @endif</span>
                                                </div>
                                                <?php }  ?>
                                            </div>
                                        </div>

                                        
                                    </div>

                                    <div class="col-md-3 col-xl-2 p-0">
                                        <div class="mt-3">
                                            <div class="align-self-center">
                                                <div class="row">
                                                    <div class="col-12 col-sm-12">
                                                        <div class="main-toggle-switch text-left text-sm-center mob-txt-center">
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
                                                        <a href="{{ url('experience/'.$property->id.'/basics') }}">
                                                            <div class="text-color text-danger text-color-hover text-13 text-right text-sm-center mob-txt-center mt-0 mt-md-3">
                                                                <i class="fas fa-edit"></i>   {{trans('messages.experience.edit_experience')}}

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
</div>
@stop

@push('scripts')
<script type="text/javascript">
    $(document).on('click', '#status', function(){
        var id = $(this).attr('data-id');
        var datastatus = $(this).attr('data-status');
        var dataURL = APP_URL+'/experience/update_status';
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
        document.getElementById('listing_select').size=3;
    });
    
</script>
@endpush

   
