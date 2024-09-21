	@extends('template')
	@section('main')
	
	@push('css')
	<link rel="stylesheet" href="{{ url('public/dropzone/css/dropzone.min.css')}}">
	@endpush 

	<div class="margin-top-85">
		<div class="row m-0">
			<!-- sidebar start-->
			@include('users.sidebar')
			<!--sidebar end-->
			<div class="col-lg-12 p-0 mt-5">
				<div class="container-fluid min-height">
					<div class="col-md-12">
						<div class="row">
						
						<div class="col-md-3 mt-5"> 
						    @include('users.profile_nav')
						</div>
						
						<div class="col-md-9 mt-5"> 

						<!--Success Message -->
						@if(Session::has('message'))
							<div class="row">
								<div class="col-md-12  alert {{ Session::get('alert-class') }} alert-dismissable fade in top-message-text opacity-1">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									{{ Session::get('message') }}
								</div>
							</div>
						@endif 
						
						<div class="row justify-content-center">
							<div class="col-md-12 p-0">
								<div class="card ">
								<div class="main-panel border main-panelbg">
									<p class="p-2 pl-4 font-weight-700">{{ trans('messages.sign_up.doc_verification') }}</p>
								</div>

									<div class="pl-5 pr-5 p-3">
										<div class="row">
											<div class="col-4">
												<div class="form-group">
													<label for="ci"> {{  __('messages.ci') }} </label>
													<input type="text" name="ci" value="{{ auth()->user()->ci }}" class="form-control">
												</div>																							
											</div>
										</div>

										<hr class="mb-4">

										@php
											$db = DB::table('sv_doc_verification')
												->where('user_id', auth()->user()->id)
												->where('status', 'accept')
												->get();
										@endphp

										@if(! $db->count())
										<div class="dropzone margin-bottom-20" id="myId">
											<div class="fallback">
												<input id="files" accept="image/*" multiple="true" name="files[]" type="file">
									    	</div>
						
										  <div class="dz-message needsclick"> 
											<p><i class="fa fa-cloud-upload"></i></p>
											 <h3>{{ trans('messages.sign_up.click_to_select_img') }}</h3>
											<span class="note needsclick"><h3 class="margin-top-10">{{ trans('messages.sign_up.img_type') }}</h3></span>
										  </div>
										</div>
										@endif
										
										@if(!$document->isEmpty())
											<ul class="mt-4 mb-4 d-flex svdoc">
											@foreach($document as $document)
												<li class="mr-4 ">
													@if($document->type=="image/png" || $document->type=="image/jpeg")
												    <a download href="{{ url('public/images/doc/') }}/{{ $document->doc }}" target="_blank">

														<img width="150px" height="150px" @if($document->comments) style="border: 2px solid red" @endif @if($document->status == 'accept') style="border: 2px solid green" @endif class="rounded-4" src="{{ url('public/images/doc/') }}/{{ $document->doc }} ">
												    </a>
													@elseif($document->type!="image/png" || $document->type!="image/jpeg")
														<p>
														    <a download href="{{ url('public/images/doc/') }}/{{ $document->doc }}" target="_blank">
														        <img @if($document->status == 'accept') style="border: 2px solid green" @endif @if($document->comments) style="border: 2px solid red" @endif class="border rounded-4" src="{{ url('public/images/dicon.png') }}" width="150px" height="150px">
														    </a>
														 </p>
													@endif

													@if($document->status != 'accept')
														<i class="fa fa-trash text-danger" id="closedid"  onclick="removethis(<?php echo $document->id;?>);" ></i>
													@endif

												</li>
										
											
											@endforeach
											</ul>
										@endif

										<div>
											@if(! $db->count())
											<div>Sube tu documento de identidad anverso y reverso (puedes seleccionar hasta 2 imágenes)</div>
											@endif

											@foreach(DB::table('sv_doc_verification')->whereNotNull('comments')->where('user_id', auth()->user()->id)->get() as $doc)
												<li style="color: red">{{ $doc->comments }}</li>
											@endforeach
										</div>
									
									</div>
								</div>
							</div>


							<div class="col-md-12 mt-4 p-0 mb-5">
						
							</div>
						</div>
						</div>
						
						
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
@push('scripts')
   <script src="{{ url('public/dropzone/js/dropzone.js')}}"></script>
    <script>
       function removethis(id) {
        $.ajax({
          type: 'post',
          url: '{{url('docremove/')}}',
          data:{
            did:id,
            '_token': '{{csrf_token()}}'
          },
          success: function (data)
          {
            window.location.href = "{{ url('documentVerification') }}";

          }
        });
      } 

      $(document).ready(function () {
      	$('[name=ci]').keyup(function () {
      		ci = $(this).val();

      		$.ajax({
      			type: 'POST',
      			data: {
      				ci: ci,
      				_token: '{{ csrf_token() }}'
      			},
      			url: '/saveCi',
      			success: function (response) {
      				console.log(response);

      			},
      			error: function (error) {
      				$('body').html(error.responseText);
      			}
      		});
      	});
      });
    </script>
	
<script>

			Dropzone.autoDiscover = false;

		var baseUrl = "{{ url('/') }}";
            var token = "{{ Session::token() }}";

	$("div#myId").dropzone({
	paramName: 'file',
    acceptedFiles: ' .jpeg, .jpg, .png, .jpe, .pdf',
	url: baseUrl+'/create/dropzone-image-upload',
	addRemoveLinks: true,
			params: {
                    _token: token
                },
                dictDefaultMessage: "Drop or click to upload images",
                dictRemoveFile : "remove",
                clickable: true,
	success: function(file,response) {

    $('#formUpload').append('<input id="' + response.success + '" type="hidden" name="gallery[]" value="' + response.success + '">')
	window.location.href = window.location.href;
    
   file.upload.filename = response.success;
   file.filename = response.success;
    if (file.previewElement) {
      return file.previewElement.classList.add("dz-success");
    }
  },		
  error: function (error, error2) {
  	console.log(error, error2);
  },
 
 removedfile: function(file) {
	 
	 //console.log(file);
	 
	 var name = file.upload.filename;
	 var edit_id =0;
       var token = "{{ Session::token() }}";       
   $.ajax({
     type: 'POST',
	 url: baseUrl+'/create/dropzone-image-delete',
     data: {name: name,_token: token,edit_id:edit_id},
     success: function(response){
         
	$('#formUpload').find('input[name="gallery[]"][value="' + name +  '"]').remove();
		
     }
     
   });
   var _ref;
    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
  
 }


});
        
</script>		
@endpush


	@stop