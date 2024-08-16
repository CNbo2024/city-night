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

                        <form action="/cards/{{ $card->id }}/update" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="type">{{ trans('messages.type') }}</label>

                                <select class="form-control" required name="type">
                                    <option value=""></option>
                                    <option {{ $card->type == '001' ? 'selected' : '' }} value="001">Visa</option>
                                    <option {{ $card->type == '002' ? 'selected' : '' }} value="002">Mastercard</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="number">{{ trans('messages.number') }}</label>
                                <input type="text" required name="number" class="form-control" value="{{ $card->number }}">
                            </div>

                            <div class="form-group">
                                <label for="number">{{ trans('messages.cvc') }}</label>
                                <input type="text" required name="cvc" class="form-control" value="{{ $card->cvc }}">
                            </div>

                            <div class="form-group">
                                <label for="expiry_date">{{ trans('messages.expiry_date') }}</label>
                                <input type="month" required name="expiry_date" class="form-control" value="{{ $card->expiry_date }}">
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

   
