@extends('admin.template')
@section('main')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ 'Promotions' }}</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-right"><a class="btn btn-primary" href="{{ url('admin/promotion/create') }}">Add Code</a></div>
                        </div>
                    
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-hover dt-responsive svusertable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Discount (%)</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($promotions as $promotion)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $promotion->code }}</td>
                                                <td>{{ $promotion->discount }}</td>
                                                <td>
                                                    <a class="btn btn-default btn-sm text-white" href="/admin/promotion/list/{{ $promotion->id }}">List of clients who have used this code</a>

                                                    <a class="btn btn-default btn-sm text-white" href="/admin/promotion/{{ $promotion->id }}/edit">Edit</a>

                                                    <a class="btn btn-danger btn-sm text-white" href="/admin/promotion/{{ $promotion->id }}/detroy">Remove</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
