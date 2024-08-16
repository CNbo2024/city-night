	@extends('admin.template')
	@section('main')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Delete Customer</h1>
		</section>

		<section class="content">
			<div class="row">
				<div class="col-xs-12">
				<div class="box"> 
					<div class="box-body">
					<form class="form-horizontal" enctype='multipart/form-data' action="{{ url('admin/delete-customer') }}" method="POST" accept-charset="UTF-8">
						{{ csrf_field() }}
						<input type="hidden" id="delete_user_id" name="delete_user_id" value="{{ $delete_user_id }}">
						<div class="col-md-12"> 
							<div class="row">  
							
    							<div class="col-md-12 col-sm-12 col-xs-12">
    								 <input type="radio" id="delete_type_all" name="delete_type_all" value="all" checked> Delete all service data.
    								 <br>
    								 <div class="notice all mt-2" style="margin-top:20px;">
                                        <p>If you select option <b>"Delete all service data"</b>, all the data of this user will be deleted including: </p>
                                        <ul>
                                            <li>User information (Email, location, phone)</li>
                                            <li>User media</li>
                                            <li>User review/comment</li>
                                            <li>User payout data</li>
                                            <li>User Properties/Bookings</li>
                                        </ul>
                                    </div>
    							</div> 
    							 
    							<div class="col-md-12 col-sm-12 col-xs-12">
    								 <input type="radio" id="delete_type_all" name="delete_type_all" value="assign"> Assign data to another user.
    								 <br>
    								 <div class="notice assign mt-2" style="display:none">
                                        <p style="margin-top:20px;">If you select option <b>"Assign data to another user"</b>, the user information will be deleted and listings of this user will be assigned to user you selected (Properties, Bookings, Wallets)</p>
                                    </div>
    							</div> 
								 
								<div class="col-md-3 col-sm-12 col-xs-12" style="display:none" id="assign_user">
									<label>Select Customer</label>
									<select class="form-control select2" name="assign_user_id" id="assign_user_id">
										<option value="">select</option>
										@if(!empty($customers))
											@foreach($customers as $customer)
											    <?php if($delete_user_id!=$customer->id) { ?>
												    <option value="{{$customer->id}}">{{$customer->first_name." ".$customer->last_name."(".$customer->email.")"}}</option>
												<?php } ?>
											@endforeach
										@endif 
									</select> 
								</div>
								 
								<div class="col-md-1 col-sm-2 col-xs-4 mt-5">
									<br>
									<button type="submit" name="btn" class="btn btn-primary">Confirm Deletion</button>
								</div>
								
							</div>
						</div>
		</section>
	</div>
	@endsection


@push('scripts')
    <script> 
	    $(document).on("change", "#delete_type_all", function(event)
	    {
	        var str = this.value;
		    if(str=="assign")
		    {
		        $('#assign_user').show();
		        $('.assign').show();
		        $('.all').hide();
		    }
		    else
		    {
		         $('#assign_user').hide();
		         $('.assign').hide();
		         $('.all').show();
		    }
		});
	</script>
@endpush












	