@extends('template')

@section('main')
<div class="margin-top-85">
    <div class="row m-0">
        @include('users.sidebar')

        <div class="col-lg-12">
            <div class="main-panel mb-5">
                <div class="container-fluid container-fluid-90">                    
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{!! \Session::get('success') !!}</li>
                            </ul>
                        </div>
                    @endif

                    <div>
                        <h1 class="mt-5">Tips</h1>


                        <div class="row">
                            <div class="col-12">
                                @foreach($tips as $tip)
                                  <div class="card mt-5">
                                    <div class="card-header">
                                        {{ $tip->title }}
                                    </div>

                                    <div class="card-body">
                                        {{ $tip->content }}
                                    </div>
                                  </div>
                                @endforeach
                            </div>                           
                        </div>
                    </div>

                    <div class="row justify-content-between overflow-auto  pb-3 mt-4 mb-5">
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

   
