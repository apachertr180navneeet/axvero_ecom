@extends('backend.layouts.blank')
@section('content')
    <div class="container pt-5">
        <div class="d-flex justify-content-center mt-5">
            <div class="card install-card position-relative">
                <div class="card-body install-card-body h-100 w-100 z-3 position-relative">
                    <div class="text-center">
                        <p>Redirecting...</p>
                    </div>
                </div>
                @include('update.common')
            </div>
        </div>
    </div>
@endsection
