@extends('admin.template')

@section('main')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Set min amount for payout request</h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
            <div class="box">
                <form class="svform" action="{{url('admin/min-request')}}" id="add_customer" method="post" name="add_customer" accept-charset='UTF-8'>
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="form-group col-md-12">
                            <label for="amount" class="control-label col-sm-3">Min amount for payout request<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" required class="form-control" name="amount" id="amount" value="{{ $amount ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                        <a href="/admin" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </section>
</div>
@endsection