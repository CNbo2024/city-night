	@extends('admin.template')
	@section('main')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>{{ $page_title ?? '' }}</h1>
		</section>

		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">{{ $form_name ?? '' }}</h3>
						</div>
						<form id="{{ $form_id ?? ''}}" method="post" action="{{ $action ?? ''}}" onsubmit="return contentValidate();" class="form-horizontal {{ $form_class ?? '' }}" {{ isset($form_type) && $form_type == 'file'? "enctype=multipart/form-data":"" }}>
							{{ csrf_field() }}
							<div class="box-body">
								@foreach($fields as $field)
									@include("admin.common.fields.".$field['type'], ['field' => $field])
								@endforeach
							</div>

							<div class="box-footer">
								<button type="submit" class="btn btn-primary btn-space">Submit</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
	@endsection