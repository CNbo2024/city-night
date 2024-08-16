@extends('admin.template')

@section('main')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Promotion</h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form class="svform" action="{{url('admin/promotion/' . $promotion->id . '/update')}}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="box-body">
                            <div class="form-group col-md-12">
                                <label for="title" class="control-label col-sm-3">
                                    Code <span class="text-danger">*</span>
                                </label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="code" id="code" value="{{ $promotion->code }}">
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="title" class="control-label col-sm-3">
                                    Discount (%) <span class="text-danger">*</span>
                                </label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="discount" id="discount" value="{{ $promotion->discount }}">
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection