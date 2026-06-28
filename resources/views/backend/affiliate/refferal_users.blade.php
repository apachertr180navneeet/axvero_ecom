@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Referral Users') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('User') }}</th>
                        <th>{{ translate('Referral Code') }}</th>
                        <th>{{ translate('Referred By') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($referral_users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 + ($referral_users->currentPage() - 1) * $referral_users->perPage() }}</td>
                            <td>
                                {{ $user->name }}
                                <br>
                                <span class="text-muted small">{{ $user->email }}</span>
                            </td>
                            <td>{{ $user->referral_code }}</td>
                            <td>
                                @if ($user->referred_by)
                                    @php
                                        $referred_by_user = \App\Models\User::where('id', $user->referred_by)->first();
                                    @endphp
                                    @if ($referred_by_user)
                                        {{ $referred_by_user->name }}
                                    @else
                                        {{ translate('Unknown') }}
                                    @endif
                                @else
                                    {{ translate('N/A') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $referral_users->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
