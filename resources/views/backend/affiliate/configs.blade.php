@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Validation Time') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliate.configs.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="validation_time">
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label">{{ translate('Validation Time (in hours)') }}</label>
                            <div class="col-sm-8">
                                <input type="number" lang="en" class="form-control" name="value"
                                    value="{{ \App\Models\AffiliateConfig::where('type', 'validation_time')->first() ? \App\Models\AffiliateConfig::where('type', 'validation_time')->first()->value : '' }}"
                                    placeholder="{{ translate('Validation Time') }}" min="0" step="1">
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Verification Form') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliate.configs.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="verification_form">
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label">{{ translate('Verification Form HTML') }}</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="value" rows="10"
                                    placeholder="{{ translate('Verification Form') }}">{{ \App\Models\AffiliateConfig::where('type', 'verification_form')->first() ? \App\Models\AffiliateConfig::where('type', 'verification_form')->first()->value : '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
