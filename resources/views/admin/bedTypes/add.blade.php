@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    
    @include('admin.common.breadcrumb')
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- right column -->
      <div class="col-md-12">
        <!-- Horizontal Form -->
        <div class="box">
          <!-- /.box-header -->
          <div class="box-header with-border">
            <h3 class="box-title">Add Bed Type</h3>
          </div>
          <!-- form start -->
          <form class="form-horizontal" action="{{url('admin/settings/add-bed-type')}}" id="add_bed" method="post" name="add_bed" accept-charset='UTF-8' enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
               <label for="exampleInputPassword1" class="control-label col-sm-3">Name<span class="text-danger">*</span></label>
               <div class="col-sm-6">
                    <input type="hidden" name="en[id]" value="1">
					<input type="text" class="form-control" value="" name="en[name]" id="name" required>
               </div>
             </div>
                          
        
			
				  <div class="box-group" id="accordion">
                @foreach($languages as $key => $language)
                @php if($language->short_name == 'en'){continue;} @endphp 
                
				
                <div class="panel box">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $language->short_name }}" aria-expanded="false" class="collapsed">
                        {{ $language->name }}
                      </a>
                    </h4>
                  </div>
                  <div id="collapse{{ $language->short_name }}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
						<input type="hidden" name="{{ $language->short_name }}[id]" value="{{$language->id}}">
						
						  <div class="form-group">
							   <label for="exampleInputPassword1" class="control-label col-sm-3">Name</label>
							   <div class="col-sm-6">
									<input type="text" class="form-control" value="" name="{{ $language->short_name }}[name]" >
							   </div>
						  </div>
					
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
			 
						 

           </div>
           <!-- /.box-body -->
           <div class="box-footer">
				<button type="submit" class="btn btn-info" id="submitBtn">Submit</button>
				<a href="{{url('admin/settings/bed-type')}}" class="btn btn-danger btn-sm">
				  Cancel
				</a>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
      <!-- /.box -->

      <!-- /.box -->
    </div>
    <!--/.col (right) -->
  </div>
</section>
</div>
@endsection

<script type="text/javascript">
    $(document).ready(function () {
            $('#add_bed').validate({
                rules: {
                    name: {
                        //required: true
                    }
                }
            });
        });
</script>