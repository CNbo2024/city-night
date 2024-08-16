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

                            <a href="/cards/create" class="btn btn-secondary">New</a>
                        </h1>

                        <div class="row">
                            <div class="col-12">                                
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>{{ __('messages.type') }}</th>
                                            <th>{{ __('messages.number') }}</th>
                                            <th>{{ __('messages.cvc') }}</th>
                                            <th>{{ __('messages.expiry_date') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($cards as $card)
                                            <tr>
                                                <td>{{ $card->id }}</td>
                                                <td>{{ $card->type == '001' ? 'Visa' : 'Mastercard' }}</td>
                                                <td>{{ $card->number }}</td>
                                                <td>{{ $card->cvc }}</td>
                                                <td>{{ $card->expiry_date }}</td>

                                                <td>
                                                    <div class="row">
                                                        <div class="col">
                                                            <a href="/cards/{{ $card->id }}/edit" class="btn btn-secondary btn-sm">
                                                                <i class="fa fa-eye"></i> Editar
                                                            </a>
                                                        </div>

                                                        <div class="col">
                                                            <form action="/cards/{{ $card->id }}/destroy">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button class="btn btn-danger" type="submit">
                                                                    <i class="fa fa-trash"></i> Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
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
        </div>
    </div>
</div>
@stop

   
