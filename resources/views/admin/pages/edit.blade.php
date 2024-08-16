@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    
    @include('admin.common.breadcrumb')
  </section>
  <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- right column -->
      <div class="col-md-12">
        <!-- Horizontal Form -->
        <div class="box">
          <!-- /.box-header -->
          <div class="box-header with-border">
            <h3 class="box-title">Edit Page Form</h3>
          </div>
          <!-- form start -->
		  
          <form class="form-horizontal" action="{{url('admin/edit-page/'.$result->id)}}" id="edit_page" method="post" name="edit_page" accept-charset='UTF-8' {{-- onsubmit="return contentValidate();" --}}>
            {{ csrf_field() }}

            <div class="box-body">
                   <input type="hidden" name="en[id]" value="1">

              <div class="form-group">
               <label for="exampleInputPassword1" class="control-label col-sm-3">Name<span class="text-danger">*</span></label>
               <div class="col-sm-6">
                 <input type="text" class="form-control" value="{{$result->name}}" name="en[name]" required id="geturl">
               </div>
             </div>
             <div class="form-group">
               <label for="exampleInputPassword1" class="control-label col-sm-3">URL<span class="text-danger">*</span></label>
               <div class="col-sm-6">
                 <input type="text" class="form-control" name="en[url]" id="page_url" placeholder="" value="{{$result->url}}" required>
               </div>
             </div>
            
             <div class="form-group">
               <label for="inputEmail3" class="col-sm-3 control-label">Content<span class="text-danger">*</span></label>  
               <div class="col-sm-6">
                 <textarea id="content" name="en[content]" placeholder="" rows="10" cols="80" class="" required>{{$result->content}}</textarea>
                 <span id="content-validation-error"></span>
               </div>
             </div>

             <div class="form-group">
              <label for="exampleInputPassword1" class="control-label col-sm-3">Position</label>
               <div class="col-sm-6">
                 <select name="en[position]" class="form-control" id="sv_en_lang_position" required>
                   <option value="first" {{ ($result->position) == 'first' ? 'selected': ""}}> First Column </option>
                   <option value="second" {{ ($result->position) == 'second' ? 'selected': ""}}> Second Column </option>
                 </select>
               </div>
             </div>
              <div class="form-group">
              <label for="exampleInputPassword1" class="control-label col-sm-3">Status</label>
               <div class="col-sm-6">
                 <select name="en[status]" class="form-control" id="sv_en_lang_status" required> 
                   <option value="Active" {{ ($result->status) == 'Active' ? 'selected': ""}}> Active </option>
                   <option value="Inactive" {{ ($result->status) == 'Inactive' ? 'selected': ""}}>Inactive</option>
                 </select>
               </div>
             </div>
			 
			 
			 
			 <div class="box-group" id="accordion">
                @foreach($languages as $key => $language)
                @php if($language->short_name == 'en'){continue;} @endphp 
                
				<?php 
					$query = App\Models\Page::where([ ['temp_id',$result->id],['lang_id', $language->id ]])->first();
				?>

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
						<input type="hidden" class="form-control" name="{{ $language->short_name }}[url]" id="page_url" placeholder="" value="">
					
					 <div class="form-group">
						<label for="" class="control-label col-sm-3">Name</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="@if(!empty($query->name)) {{ $query->name }} @endif" name="{{ $language->short_name }}[name]" placeholder="" id="">
							</div>
					 </div>
					
					<div class="form-group">
						<label for="" class="control-label col-sm-3">Content</label>
							<div class="col-sm-6">
								 <textarea id="compose-textarea<?php echo $language->short_name; ?>" name="{{ $language->short_name }}[content]" class="form-control" style="height: 300px">
									  @if(empty($query->content)) Body @else {{ $query->content }} @endif
								 </textarea>
							</div>
					</div>

 					 <input type="hidden" class="form-control" name="{{ $language->short_name }}[position]" id="sv_other_lang_position<?php echo $language->short_name; ?>" value="{{ $result->position }}">
 					 <input type="hidden" class="form-control" name="{{ $language->short_name }}[status]" id="sv_other_lang_status<?php echo $language->short_name; ?>" value="{{ $result->status }}">

					@push('scripts')
					<script>
						  CKEDITOR.replace( 'compose-textarea<?php echo $language->short_name; ?>' );
	                    CKEDITOR.config.allowedContent = true;
					    $(document).ready(function() {
							$("#sv_en_lang_position").on('change', function(){
								var str=$(this).val();
								$("#sv_other_lang_position<?php echo $language->short_name; ?>").val(str);
							});	
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
           <div class="box-footer">
            <button type="submit" class="btn btn-info" id="submitBtn">Submit</button>
            <a href="{{url('admin/pages')}}" class="btn btn-danger btn-sm">
              Cancel
            </a>
           
          </div>
        </form>
		
		
			
		
		
		
		
      </div>

    </div>
  </div>
</section>
</div>
@endsection
@push('scripts')
<!--<script src="https://cdn.tiny.cloud/1/6x27spxm2msw5k1wek643ucn0c9bb96owxpafusz7og60lfr/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>-->
 
<script>

  window.onload = function() {
     CKEDITOR.replace( 'content', {
       filebrowserUploadUrl: '{{ route('upload',['_token' => csrf_token() ]) }}',
       filebrowserUploadMethod: 'form'
    });
	CKEDITOR.config.allowedContent = true;
	 
	
  /*  tinymce.init({
    selector: '#content',
    plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste imagetools wordcount'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
  }); */
  };
</script>
    <script type="text/javascript">
    	$(function () {
		    $(".editor").wysihtml5();
		  });

	    $("#available").on('click', function(){
	      var className = $('#variable').attr('class');
	      if(className == 'box-header hidden'){
	          $("#variable").removeClass('hidden');
	      }else{
	        $("#variable").addClass('hidden');
	      }
	    });
    </script>

<script>

  $(document).ready(function() {
   $(document).on('submit', 'form', function() {
     $('button').attr('disabled', 'disabled');
   });
 });
</script>
<script>
   $(document).ready(function() {
   $('#geturl').on('blur keyup', function() {
     var pagUrl = $('#geturl').val();
         pagUrl = pagUrl.toLowerCase();
         pagUrl = pagUrl.replace(/[^a-zA-Z0-9]+/g,'-');
       if (pagUrl !='') {

      $('#page_url').val(pagUrl);
    } 

  });
 }); 
</script>

<script type="text/javascript">
 $(document).ready(function () {


  $('#edit_page').validate({
    ignore: [],
    rules: {
      name: {
        //required: true
      },
      url:{
        //required:true
      },
      /*  content: {

        required: function(textarea) {
                    CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                    return editorcontent.length === 0;
                  }
                } */
              },
              errorPlacement: function (error, element) {
                if (element.prop('type') === 'textarea') {
                  $('#content-validation-error').html(error);
                } else {
                  error.insertAfter(element);
                }
              }
      });
	  
	  
	    
	  
});

</script>
@endpush