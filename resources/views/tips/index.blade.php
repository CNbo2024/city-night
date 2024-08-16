@extends('admin.template')
@section('main')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Customers</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-right"><a class="btn btn-primary" href="{{ url('admin/tips/create') }}">Add Tip</a></div>
                        </div>
                    
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-hover dt-responsive svusertable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Content</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($tips as $tip)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $tip->title }}</td>
                                                <td>{{ $tip->content }}</td>
                                                <td>
                                                    <a class="btn btn-default btn-sm text-white" href="/admin/tips/{{ $tip->id }}/edit">Edit</a>
                                                    <a class="btn btn-danger btn-sm text-white" href="/admin/tips/{{ $tip->id }}/detroy">Remove</a>
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
