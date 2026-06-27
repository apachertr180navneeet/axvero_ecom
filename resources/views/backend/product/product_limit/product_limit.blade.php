@extends('backend.layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@php
    $limit = \App\Models\ProductLimit::first();
@endphp

<div class="col-lg-12 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Product Limit') }}</h5>
        </div>

        <form action="{{ route('product_limit.store') }}" method="POST">
            @csrf
            <div class="card-body">

                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">
                        {{ translate('Product Limit') }} <span class="text-danger">*</span>
                    </label>

                    <div class="col-sm-6">
                        <input type="number"
                           name="product_limit"
                           class="form-control @error('product_limit') is-invalid @enderror"
                           value="{{ old('product_limit', $limit->product_limit ?? '') }}"
                           placeholder="{{ translate('Enter product limit') }}"
                           required>

                        @error('product_limit')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="col-sm-3 form-group text-right">
                    <button type="submit" class="btn btn-primary">
                        {{ translate('Save') }}
                    </button>
                </div>
                </div>

                

            </div>
        </form>
    </div>
</div>

@endsection
