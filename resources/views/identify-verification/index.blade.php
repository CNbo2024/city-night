@extends('admin.template')
@section('main')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Identify verification</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                        </div>
                    
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-hover dt-responsive svusertable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Usuario</th>
                                            <th>Documento</th>
                                            <th>Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($identities as $identity)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ fullname($identity->user_id) }}</td>

                                                <td>
                                                    <a target="_blank" href="{{ url('public/images/doc/') }}/{{ $identity->doc }}">
                                                        {{ $identity->doc }}
                                                    </a>
                                                </td>

                                                <td>{{ $identity->status ?? 'Pendiente' }}</td>

                                                <td>
                                                    @if(! $identity->status)
                                                        <a data-href="/admin/identify-verification/accept/{{ $identity->id }}" class="confirm-action btn btn-success">Accept</a>
                                                        <a data-href="/admin/identify-verification/decline/{{ $identity->id }}" class="decline-action btn btn-danger">Decline</a>
                                                    @endif
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

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.confirm-action').click(function (event) {
                event.preventDefault();
                href = $(this).attr('data-href');

                if (confirm('¿Está seguro que desea realizar esta acción?')) {
                    window.location.href = href;
                }
            });

            $('.decline-action').click(function (event) {
                event.preventDefault();
                href = $(this).attr('data-href');

                reason = prompt('Por favor indica una razón');

                if (reason) {
                    window.location.href = href + '/' + reason;

                } else {
                    alert('Debe indicar una rezón obligatoriamente si desea rechazar un documento');
                }
            });
        });
    </script>
@endpush
