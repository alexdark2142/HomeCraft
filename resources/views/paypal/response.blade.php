@extends('templates.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="message-box {{ $status }}">
                    @if($status === 'success')
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    @endif

                    <h2>{{ $title }}</h2>
                    <p>{{ $message }}</p>

                    <div class="oh button-wrap">
                        <a class="button button-primary button-ujarak" href="{{ url('/') }}">
                            Return to Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if ({{ json_encode($status === 'success') }}) {
                localStorage.removeItem('cart');
            }
        });
    </script>
@endsection
