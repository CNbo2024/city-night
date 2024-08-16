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
            <h3 class="box-title">Add Banners</h3>
          </div>
          <!-- form start -->

          <form class="form-horizontal" action="{{url('admin/settings/add-banners')}}" id="add_banners" method="post" name="add_banners" accept-charset='UTF-8' enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
               <label for="exampleInputPassword1" class="control-label col-sm-3">Heading<span class="text-danger">*</span></label>
               <div class="col-sm-6">
					<input type="text" class="form-control"  name="heading" placeholder="" id="heading" required>
               </div>
             </div>
                          
             <div class="form-group">
                 <label for="inputEmail3" class="col-sm-3 control-label">Subheading<span class="text-danger">*</span></label>  
                 <div class="col-sm-6">
                   <input id="subheading" name="subheading" class="form-control" required>
                 </div>
             </div>
			 
			 <div class="form-group">
               <label for="" class="control-label col-sm-3">Image<span class="text-danger">*</span> </label>
               <div class="col-sm-6">
					<input type="file" class="form-control"  name="image" id="image" required> (Width:1920px and Height:860px)
					@if($errors->has('image'))
						<div class="error" style="color:#e00000;">{{ $errors->first('image') }}</div>
					@endif
               </div>
             </div>
        
              <div class="form-group">
				  <label for="" class="control-label col-sm-3">Status</label>
				   <div class="col-sm-6">
					 <select name="status" class="form-control" id="sv_en_lang_status" required>
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
						   <label for="exampleInputPassword1" class="control-label col-sm-3">Heading<span class="text-danger">*</span></label>
						   <div class="col-sm-6">
								<input type="text" class="form-control" name="{{ $language->short_name }}[heading1]" id="" >
						   </div>
						</div>
                          
						 <div class="form-group">
							 <label for="inputEmail3" class="col-sm-3 control-label">Subheading<span class="text-danger">*</span></label>  
							 <div class="col-sm-6">
							   <input id="" name="{{ $language->short_name }}[subheading1]" class="form-control">
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
				<a href="{{url('admin/settings/banners')}}" class="btn btn-danger btn-sm">
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
@push('scripts')
<script src="{{ asset('public/backend/js/additional-method.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
             $('#add_banners').validate({
                rules: {
                    heading: {
                        required: true
                    },
                    image: {
                        required: true,
                        //extension: "jpg|png|jpeg"
                        accept: "image/jpg,image/jpeg,image/png"
                    }
                },
                messages: {
                    image: {
                        accept: 'The file must be an image (jpg, jpeg or png)'
                    }
                } 
            }); 
        });
</script>
@endpush