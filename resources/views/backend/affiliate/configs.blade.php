@extends('backend.layouts.app')

@section('content')

    <!-- Commission Configuration -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom">
                    <h5 class="mb-0 h6 fw-600 text-dark">{{ translate('Global Commission Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliate.configs.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label fs-13">{{ translate('Global Commission Value') }}</label>
                            <div class="col-sm-8">
                                <input type="number" lang="en" class="form-control" name="configs[global_commission_value]"
                                    value="{{ \App\Models\AffiliateConfig::where('type', 'global_commission_value')->first()?->value }}"
                                    placeholder="{{ translate('Value') }}" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label fs-13">{{ translate('Global Commission Type') }}</label>
                            <div class="col-sm-8">
                                <select name="configs[global_commission_type]" class="form-control aiz-selectpicker" required>
                                    @php
                                        $global_type = \App\Models\AffiliateConfig::where('type', 'global_commission_type')->first()?->value;
                                    @endphp
                                    <option value="percent" @if($global_type == 'percent') selected @endif>{{ translate('Percentage (%)') }}</option>
                                    <option value="fixed" @if($global_type == 'fixed') selected @endif>{{ translate('Fixed Amount') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary btn-sm px-4">{{ translate('Save Settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Withdrawal Settings -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom">
                    <h5 class="mb-0 h6 fw-600 text-dark">{{ translate('Withdrawal Limits & Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliate.configs.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label fs-13">{{ translate('Minimum Withdrawal Amount') }}</label>
                            <div class="col-sm-8">
                                <input type="number" lang="en" class="form-control" name="configs[minimum_withdrawal_amount]"
                                    value="{{ \App\Models\AffiliateConfig::where('type', 'minimum_withdrawal_amount')->first()?->value }}"
                                    placeholder="e.g. 100" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label fs-13">{{ translate('Maximum Withdrawal Amount') }}</label>
                            <div class="col-sm-8">
                                <input type="number" lang="en" class="form-control" name="configs[maximum_withdrawal_amount]"
                                    value="{{ \App\Models\AffiliateConfig::where('type', 'maximum_withdrawal_amount')->first()?->value }}"
                                    placeholder="e.g. 5000" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label fs-13">{{ translate('Withdrawal Frequency (Days)') }}</label>
                            <div class="col-sm-8">
                                <input type="number" lang="en" class="form-control" name="configs[withdrawal_frequency]"
                                    value="{{ \App\Models\AffiliateConfig::where('type', 'withdrawal_frequency')->first()?->value }}"
                                    placeholder="e.g. 7 (once a week)" min="0" step="1" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary btn-sm px-4">{{ translate('Save Settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Validation Time Settings -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom">
                    <h5 class="mb-0 h6 fw-600 text-dark">{{ translate('Validation Time Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliate.configs.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="validation_time">
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label fs-13">{{ translate('Validation Time (in hours)') }}</label>
                            <div class="col-sm-8">
                                <input type="number" lang="en" class="form-control" name="value"
                                    value="{{ \App\Models\AffiliateConfig::where('type', 'validation_time')->first() ? \App\Models\AffiliateConfig::where('type', 'validation_time')->first()->value : '' }}"
                                    placeholder="{{ translate('Validation Time') }}" min="0" step="1">
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary btn-sm px-4">{{ translate('Save Settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Category Wise Rates Mapping -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom">
                    <h5 class="mb-0 h6 fw-600 text-dark">{{ translate('Category-wise Commission Rates') }}</h5>
                </div>
                <div class="card-body" style="max-height: 480px; overflow-y: auto;">
                    <form action="{{ route('affiliate.configs.store') }}" method="POST">
                        @csrf
                        @foreach ($categories as $category)
                            @php
                                $cat_rate_config = \App\Models\AffiliateConfig::where('type', 'category_rate_' . $category->id)->first();
                                $cat_rate_details = $cat_rate_config ? json_decode($cat_rate_config->value, true) : null;
                                $cat_rate = $cat_rate_details ? $cat_rate_details['rate'] : '';
                                $cat_type = $cat_rate_details ? $cat_rate_details['type'] : 'percent';
                            @endphp
                            <div class="border-bottom py-2 mb-3">
                                <div class="font-weight-bold text-dark mb-2 fs-13">{{ $category->getTranslation('name') }}</div>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" lang="en" class="form-control form-control-sm" 
                                            name="configs[category_rate_{{ $category->id }}][rate]"
                                            value="{{ $cat_rate }}" placeholder="{{ translate('Commission Rate') }}" min="0" step="0.01">
                                    </div>
                                    <div class="col-6">
                                        <select name="configs[category_rate_{{ $category->id }}][type]" class="form-control form-control-sm">
                                            <option value="percent" @if($cat_type == 'percent') selected @endif>{{ translate('Percentage (%)') }}</option>
                                            <option value="fixed" @if($cat_type == 'fixed') selected @endif>{{ translate('Fixed Amount') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group mb-0 text-right mt-3">
                            <button type="submit" class="btn btn-primary btn-sm px-4">{{ translate('Save Category Rates') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Verification Form HTML -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom">
                    <h5 class="mb-0 h6 fw-600 text-dark">{{ translate('Verification Form Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('affiliate.configs.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="verification_form">
                        <div class="form-group row">
                            <label class="col-sm-4 col-from-label fs-13">{{ translate('Verification Form HTML') }}</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="value" rows="7"
                                    placeholder="{{ translate('Verification Form HTML') }}">{{ \App\Models\AffiliateConfig::where('type', 'verification_form')->first() ? \App\Models\AffiliateConfig::where('type', 'verification_form')->first()->value : '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary btn-sm px-4">{{ translate('Save Settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
