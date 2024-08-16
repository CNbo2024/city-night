	@extends('admin.template')
	@section('main')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Customers Document Verification</h1>
			@include('admin.common.breadcrumb')
		</section>

		<section class="content">
			
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Document Verification</h3>
						</div>
					
						<div class="box-body">
						    <div class="table-responsive">
							<table class="table table-striped table-hover" id="example">
								<thead>
									<tr>
										<th>Id</th>
										<th>User Name</th>
										<th>View Document</th>
										<th>Status</th>
										<th>Updated Date</th>
										<th>Action</th>
									</tr>
								</thead>
								
								<tbody>
								<?php $i=0; ?>
								@if(!$user->isEmpty())
									@foreach($user as $user)
									<?php 
										$doc_verification = DB::table('sv_doc_verification')->where('user_id', $user->id)->get();
										$doc_verification_count = DB::table('sv_doc_verification')->where('user_id', $user->id)->count();
										$user_verification = App\Models\UsersVerification::where('user_id', $user->id)->first();
										
										$doc_tme = DB::table('sv_doc_verification')->where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
									?>
									@if($doc_verification_count!="0")
									@if($user_verification->document=="no")
									
									<?php $i++; ?>
									<tr>
										<td>{{ $i }}</td>
										<td><a href="{{ url('admin/edit-customer') }}/{{ $user->id }}" target="_blank">{{ $user->first_name }}</a></td>
										<td>
										@if($doc_verification_count!="0")
											<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal{{$user->id }}">View Document</button>
										@else
											-
										@endif
										</td>
										<td>@if($user_verification->document=="yes") Approved @else Disapproved @endif </td>
										<td>@if($doc_tme) {{ dateFormat($doc_tme->created_at) }} @endif</td>

										<td>
										@if($doc_verification_count!="0")
											<a href="{{ url('admin/customer/approve/') }}/{{$user->id}}" class="btn btn-success">Approve</a>
											<!--<a href="#" data-toggle="modal" data-target="#Modal{{$user->id }}" class="btn btn-primary">Disapprove</a>-->
										@endif
										</td>
									</tr>
										@endif
									@endif
									<!-- Trigger the modal with a button -->

									<!-- Modal -->
									<div id="myModal{{ $user->id }}" class="modal fade" role="dialog">
									  <div class="modal-dialog">

										<!-- Modal content-->
										<div class="modal-content">
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title text-center font-weight-600">{{ $user->first_name }}</h4>
										  </div>
										  <div class="modal-body">
											
											@if($doc_verification_count!="0")
											<ul class="mt-4 mb-4 sv_view_doc">
											@foreach($doc_verification as $document)
												<li class="mr-3">
    											    <a download href="{{ url('public/images/doc/') }}/{{ $document->doc }}" target="_blank">
    													@if($document->type=="image/png" || $document->type=="image/jpeg")
    														<img class="border rounded-4" width="150px" height="150px" src="{{ url('public/images/doc/') }}/{{ $document->doc }} ">
    													@elseif($document->type!="image/png" || $document->type!="image/jpeg")
    													   <img class="border rounded-4" src="{{ url('public/images/dicon.png') }}" width="150px" height="150px">

    													@endif
                                                    </a>
												</li>
										
											
											@endforeach
											</ul>
										@endif
											
										  </div>
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										  </div>
										</div>

									  </div>
									</div>
									
									<!-- disapprove -->
									<div id="Modal{{ $user->id }}" class="modal fade" role="dialog">
									  <div class="modal-dialog">

										<!-- Modal content-->
										<div class="modal-content">
										  <div class="modal-header border">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title text-center font-weight-600">{{ $user->first_name }}</h4>
										  </div>
										  <div class="modal-body">
										  
											<form method="get" action="{{ url('admin/customer/disapprove/') }}/{{$user->id}}">
												<label>Reason for disapprove</label>
												<input type="hidden" name="userid" id="userid" value="{{$user->id}}">
												<textarea style="color:#000 !important" class="form-control" id="reason_for_disapprove" required name="reason_for_disapprove"></textarea>
												<br>
												<button type="submit" class="btn btn-success mt-2">Submit</button>
											</form>
										  </div>
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										  </div>
										</div>

									  </div>
									</div>
									
									
									@endforeach
								@endif
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	@endsection
	<style>
	    .btn-success, .btn-primary{
	        color:#fff !important;
	    }
	    .sv_view_doc
	    {
	        list-style:none;
	    }
	    .sv_view_doc li
	    {
	        float:left;    margin-right: 20px;
            border: 1px solid #ddd;margin-bottom:30px;
	    }
	    .modal-footer{clear:both;}
	</style>

	@push('scripts')
	<script src="{{ asset('public/backend/plugins/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('public/backend/plugins/Responsive-2.2.2/js/dataTables.responsive.min.js') }}"></script>
	
	<script type="text/javascript">
		$(document).ready(function() {
		 $('#example').DataTable( {
        "order": [[ 2, "desc" ]]
    } );

		});
	</script>
	@endpush

	@section('validate_script')
	<script type="text/javascript">


	
	</script>
	@endsection