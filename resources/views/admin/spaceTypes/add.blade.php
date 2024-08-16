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
            <h3 class="box-title">Add Space Type</h3>
          </div>
          <!-- form start -->
          <form class="form-horizontal" action="{{url('admin/settings/add-space-type')}}" id="add_space" method="post" name="add_space" accept-charset='UTF-8' enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
               <label for="exampleInputPassword1" class="control-label col-sm-3">Name<span class="text-danger">*</span></label>
               <div class="col-sm-6">
                    <input type="hidden" name="en[id]" value="1">
					<input type="text" class="form-control" value="" name="en[name]" placeholder="" id="name" required>
               </div>
             </div>
                          
             <div class="form-group">
                 <label for="inputEmail3" class="col-sm-3 control-label">Description<span class="text-danger">*</span></label>  
                 <div class="col-sm-6">
                   <textarea id="description" name="en[description]" rows="10" cols="50" class="form-control" required></textarea>
                   <span id="content-validation-error"></span>
                 </div>
             </div>
        
              <div class="form-group">
              <label for="exampleInputPassword1" class="control-label col-sm-3">Status</label>
               <div class="col-sm-6">
                 <select name="en[status]" class="form-control" id="sv_en_lang_status" required>
                   <option value="Active"> Active </option>
                   <option value="Inactive">Inactive</option>
                 </select>
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
						   <label for="exampleInputPassword1" class="control-label col-sm-3">Name<span class="text-danger">*</span></label>
						   <div class="col-sm-6">
								<input type="text" class="form-control" value="" name="{{ $language->short_name }}[name]" >
						   </div>
						 </div>
					
						<div class="form-group">
						   <label for="exampleInputPassword1" class="control-label col-sm-3">Description<span class="text-danger">*</span></label>
						   <div class="col-sm-6">
								<textarea id="description" name="{{ $language->short_name }}[description]" class="form-control" style="height: 200px"></textarea>
							</div>
						</div>
					 <input type="hidden" class="form-control" name="{{ $language->short_name }}[status]" id="sv_other_lang_status<?php echo $language->short_name; ?>" value="Active">

						
					@push('scripts')
					<script>
					    $(document).ready(function() {
							$("#sv_en_lang_status").on('change', function(){
								var str=$(this).val();
								$("#sv_other_lang_status<?php echo $language->short_name; ?>").val(str);
							});	
						});	
					</script>
					@endpush
						
                    
                      
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
			 
						 

           </div>
           <!-- /.box-body -->
           <div class="box-footer">
				<button type="submit" class="btn btn-info" id="submitBtn">Submit</button>
				<a href="{{url('admin/settings/space-type')}}" class="btn btn-danger btn-sm">
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

       /*      $('#add_space').validate({
                rules: {
                    name: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                }
            }); */

        });
</script>