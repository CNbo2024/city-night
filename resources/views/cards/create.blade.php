@extends('template')

@section('main')
<div class="margin-top-85">
    <div class="row m-0">
        @include('users.sidebar')

        <div class="col-lg-12">
            <div class="main-panel mb-5">
                <div class="container-fluid container-fluid-90">                    
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{!! \Session::get('success') !!}</li>
                            </ul>
                        </div>
                    @endif

                    <div>
                        <h1 class="mt-5">
                            {{ trans('messages.cards') }}
                        </h1>

                        <form action="/cards" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="type">{{ trans('messages.type') }}</label>

                                <select class="form-control" required name="type">
                                    <option value=""></option>
                                    <option value="001">Visa</option>
                                    <option value="002">Mastercard</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="number">{{ trans('messages.number') }}</label>
                                <input type="text" required name="number" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="number">{{ trans('messages.cvc') }}</label>
                                <input type="text" required name="cvc" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="expiry_date">{{ trans('messages.expiry_date') }}</label>
                                <input type="month" required name="expiry_date" class="form-control">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary">{{ trans('messages.save') }}</button>
                                <a href="/cards" class="btn btn-danger">{{ trans('messages.back') }}</a>
                            </div>
                        </form>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
@stop

   
