@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Affiliate User Verification') }}</h5>
        </div>
        <div class="card-body row">
            <div class="col-md-5">
                <h6 class="mb-4">{{ translate('User Info') }}</h6>
                <p class="text-muted">
                    <strong>{{ translate('Name') }} :</strong>
                    <span class="ml-2">{{ $affiliate_user->user->name }}</span>
                </p>
                <p class="text-muted">
                    <strong>{{ translate('Email') }} :</strong>
                    <span class="ml-2">{{ $affiliate_user->user->email }}</span>
                </p>
                @if ($affiliate_user->user->phone)
                    <p class="text-muted">
                        <strong>{{ translate('Phone') }} :</strong>
                        <span class="ml-2">{{ $affiliate_user->user->phone }}</span>
                    </p>
                @endif
            </div>
            <div class="col-md-7">
                <h6 class="mb-4">{{ translate('Verification Info') }}</h6>
                @php
                    $verification_form = \App\Models\AffiliateConfig::where('type', 'verification_form')->first();
                @endphp
                @if ($verification_form && $verification_form->value != null)
                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <tbody>
                            @foreach (json_decode($verification_form->value) as $key => $element)
                                <tr>
                                    <th class="text-muted">{{ $element->label }}</th>
                                    @if ($element->type == 'text' || $element->type == 'select' || $element->type == 'radio')
                                        <td>{{ isset($element->value) ? $element->value : '' }}</td>
                                    @elseif ($element->type == 'multi_select')
                                        <td>
                                            {{ isset($element->value) ? implode(', ', json_decode($element->value)) : '' }}
                                        </td>
                                    @elseif ($element->type == 'file')
                                        <td>
                                            @if (isset($element->value))
                                                <a href="{{ my_asset($element->value) }}" target="_blank"
                                                    class="btn btn-info btn-sm">{{ translate('Click here') }}</a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ translate('No verification form data found') }}</p>
                @endif
            </div>
        </div>
    </div>

@endsection
