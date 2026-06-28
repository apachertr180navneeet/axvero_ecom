@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 text-center">{{ translate('Product Sharing') }}</h5>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateAffiliateOption(this, 'product_sharing')"
                            @if ($affiliate_options->where('type', 'product_sharing')->first() && $affiliate_options->where('type', 'product_sharing')->first()->status == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 text-center">{{ translate('Category Wise Affiliate') }}</h5>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateAffiliateOption(this, 'category_wise_affiliate')"
                            @if ($affiliate_options->where('type', 'category_wise_affiliate')->first() && $affiliate_options->where('type', 'category_wise_affiliate')->first()->status == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                    <div class="mt-3" id="details_category_wise_affiliate"
                        @if (!($affiliate_options->where('type', 'category_wise_affiliate')->first() && $affiliate_options->where('type', 'category_wise_affiliate')->first()->status == 1)) style="display: none" @endif>
                        <textarea class="form-control" id="details_text_category_wise_affiliate" rows="4"
                            placeholder="{{ translate('Category Wise Affiliate Details') }}">{{ $affiliate_options->where('type', 'category_wise_affiliate')->first() ? $affiliate_options->where('type', 'category_wise_affiliate')->first()->details : '' }}</textarea>
                        <button type="button" class="btn btn-primary btn-sm mt-2"
                            onclick="updateAffiliateDetails('category_wise_affiliate')">{{ translate('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 text-center">{{ translate('User Registration First Purchase') }}</h5>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateAffiliateOption(this, 'user_registration_first_purchase')"
                            @if ($affiliate_options->where('type', 'user_registration_first_purchase')->first() && $affiliate_options->where('type', 'user_registration_first_purchase')->first()->status == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function updateAffiliateOption(el, type) {
            var value = $(el).is(':checked') ? 1 : 0;

            $.post('{{ route('affiliate.store') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                status: value
            }, function(data) {
                if (type == 'category_wise_affiliate') {
                    if (value == 1) {
                        $('#details_category_wise_affiliate').show();
                    } else {
                        $('#details_category_wise_affiliate').hide();
                    }
                }
                AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
            });
        }

        function updateAffiliateDetails(type) {
            var details = $('#details_text_' + type).val();

            $.post('{{ route('affiliate.store') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                status: 1,
                details: details
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
            });
        }
    </script>
@endsection
