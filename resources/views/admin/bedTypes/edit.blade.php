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
            <h3 class="box-title">Edit Bed Form</h3>
          </div>
          <!-- form start -->
		  
          <form class="form-horizontal" action="{{url('admin/settings/edit-bed-type/'.$result->id)}}" id="edit_bed" method="post" name="edit_bed" accept-charset='UTF-8'>
            {{ csrf_field() }}

            <div class="box-body">
                   <input type="hidden" name="en[id]" value="1">

              <div class="form-group">
               <label for="exampleInputPassword1" class="control-label col-sm-3">Name<span class="text-danger">*</span></label>
               <div class="col-sm-6">
                 <input type="text" class="form-control" value="{{$result->name}}" name="en[name]" required>
               </div>
             </div>
                       
       	 
			 <div class="box-group" id="accordion">
                @foreach($languages as $key => $language)
                @php if($language->short_name == 'en'){continue;} @endphp 
                
				<?php 
					$query = App\Models\BedType::where([ ['temp_id',$result->id],['lang_id', $language->id ]])->first();
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

						  <div class="form-group">
							   <label for="exampleInputPassword1" class="control-label col-sm-3">Name<span class="text-danger">*</span></label>
							   <div class="col-sm-6">
									<input type="text" class="form-control" value="@if(!empty($query->name)) {{ $query->name }} @endif" name="{{ $language->short_name }}[name]" id="">
							   </div>
							</div>
                     
					 <br>
                                         
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
			 			 
           </div>
           <div class="box-footer">
            <button type="submit" class="btn btn-info" id="submitBtn">Submit</button>
            <a href="{{url('admin/settings/bed-type')}}" class="btn btn-danger btn-sm">
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

<script type="text/javascript">
$(document).ready(function () {
	$('#edit_bed').validate({
		rules: {
			name: {
				//required: true
			}
		}
	});
});
</script>