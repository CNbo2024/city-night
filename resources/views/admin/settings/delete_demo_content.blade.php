@extends('admin.template')
@section('main')

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3 settings_bar_gap">
          @include('admin.common.settings_bar')
        </div>
        <!-- right column -->
        <div class="col-md-9">
        
          <div class="box box-muted">
				<form id="smsform" method="post" action="{{ url('admin/settings/delete-demo')}}" class="form-horizontal" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="box-body">
						<a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-danger">Delete Content</a>
              
                        
                        <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete Content</h4>
      </div> 
      <div class="modal-body"> 
          <h4 class="text-danger">Are you sure you want to delete content? </h4>
          <h5>Deleting the Content will Remove the following Data from the Website</h5>
          <br> 
        <p><i class="fa fa-times text-danger"></i> All Users except Admin</p> 
        <p><i class="fa fa-times text-danger"></i> All Listings</p>
		<p><i class="fa fa-times text-danger"></i> All Bookings</p>
		
		<p><i class="fa fa-times text-danger"></i> All Messages</p>
		<p><i class="fa fa-times text-danger"></i> Payouts</p>
		<p><i class="fa fa-times text-danger"></i> Penalty</p>
		
		<p><i class="fa fa-times text-danger"></i> All Reviews</p>
		<p><i class="fa fa-times text-danger"></i> Wallets</p>
		<p><i class="fa fa-times text-danger"></i> Wishlist</p>
		<p><i class="fa fa-times text-danger"></i> Contacts</p>
      </div>
      <div class="modal-footer">
	    <button type="button" class="btn btn-success"><a style="color:#fff" href="{{ url('admin/settings/deletedemo')}}">Ok</a></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
              
					</div>
      
				</form>
          </div>
          <!-- /.box -->

          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  @endsection

