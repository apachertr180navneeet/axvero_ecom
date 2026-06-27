@forelse($agents as $agent)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $agent->name }}</td>
    <td>{{ $agent->email }}</td>
    <td>{{ $agent->mobile }}</td>
    <td>{{ $agent->manager_name }}</td>
    <td>{{ $agent->manager_company_email }}</td>
    <td>
        @if($agent->referred_person_name)
            {{ $agent->referred_person_name }}<br>
            <small class="text-muted">{{ $agent->referred_person_mobile }}</small>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>₹{{ $agent->amount }}</td>
    <td>
        @if($agent->payment_status == 'success')
            <span class="badge bg-success">Success</span>
        @elseif($agent->payment_status == 'pending')
            <span class="badge bg-warning text-dark">Pending</span>
        @else
            <span class="badge bg-danger">Failed</span>
        @endif
    </td>
    <td>{{ $agent->created_at->format('d M Y') }}</td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center text-muted py-3">No agents found</td>
</tr>
@endforelse