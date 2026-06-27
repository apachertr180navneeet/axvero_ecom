@extends('backend.layouts.app')
@section('content')

<style>
.members-card { background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.08); overflow:hidden; }
.members-card .card-top { padding:16px 24px; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.members-card .card-top h5 { margin:0; font-size:16px; font-weight:600; color:#1a1a2e; }
.filter-row { display:flex; flex-wrap:wrap; gap:10px; padding:16px 24px; background:#f8f9fb; border-bottom:1px solid #f0f0f0; align-items:center; }
.filter-row input, .filter-row select { height:38px; border:1px solid #dde1e7; border-radius:8px; padding:0 12px; font-size:13px; background:#fff; color:#333; outline:none; transition:border-color 0.2s; min-width:0; box-sizing:border-box; }
.filter-row input:focus, .filter-row select:focus { border-color:#4f8ef7; }
.f-email  { flex:2 1 180px; }
.f-name   { flex:1.5 1 140px; }
.f-date   { flex:1 1 130px; }
.f-status { flex:1 1 130px; }
.f-reset  { flex:0 0 auto; }
.btn-reset { height:38px; padding:0 18px; border-radius:8px; border:1px solid #dde1e7; background:#fff; font-size:13px; font-weight:500; color:#555; cursor:pointer; white-space:nowrap; }
.btn-reset:hover { background:#f0f0f0; }
.btn-export { padding:8px 18px; border-radius:8px; background:#22c55e; color:#fff; font-size:13px; font-weight:600; border:none; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
.btn-export:hover { background:#16a34a; color:#fff; }
.ajax-loader { text-align:center; padding:8px; font-size:13px; color:#555; display:none; background:#fff8e1; border-bottom:1px solid #ffe082; }
.table-wrap { overflow-x:auto; }
table.members-table { width:100%; border-collapse:collapse; font-size:13px; }
table.members-table thead tr { background:#f8f9fb; border-bottom:2px solid #e8eaed; }
table.members-table thead th { padding:12px 14px; font-weight:600; color:#555; white-space:nowrap; text-align:left; }
table.members-table tbody tr { border-bottom:1px solid #f0f2f5; }
table.members-table tbody tr:hover { background:#fafbff; }
table.members-table tbody td { padding:12px 14px; color:#333; vertical-align:middle; }
table.members-table .sub { font-size:11px; color:#888; margin-top:2px; }
.badge-success { background:#dcfce7; color:#166534; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; display:inline-block; }
.badge-pending { background:#fef9c3; color:#854d0e; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; display:inline-block; }
.badge-failed  { background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; display:inline-block; }
.pagination-wrap { padding:16px 24px; display:flex; justify-content:center; }
.empty-row td { text-align:center; padding:40px 0; color:#aaa; font-size:14px; }
@media (max-width:768px) {
    .members-card .card-top, .filter-row { padding:12px 16px; }
    .f-email, .f-name, .f-date, .f-status { flex:1 1 calc(50% - 5px); }
    .f-reset { flex:1 1 calc(50% - 5px); }
    .btn-reset { width:100%; }
}
@media (max-width:480px) {
    .f-email,.f-name,.f-date,.f-status,.f-reset { flex:1 1 100%; }
}
</style>
@if(session('success'))
    <div style="background:#dcfce7; color:#166534; padding:10px 15px; border-radius:8px; margin-bottom:10px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2; color:#991b1b; padding:10px 15px; border-radius:8px; margin-bottom:10px;">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div style="background:#fee2e2; color:#991b1b; padding:10px 15px; border-radius:8px; margin-bottom:10px;">
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="members-card">
  <div class="card-top">
    <h5>Members List</h5>

    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">

        <!-- Import -->
        <form action="{{ route('join-agent.import') }}" method="POST" enctype="multipart/form-data" style="display:flex; gap:6px;">
            @csrf
            <input type="file" name="import_file" accept=".xlsx,.xls,.csv" required
                   style="font-size:12px; border:1px solid #ddd; padding:5px; border-radius:6px;">

            <button type="submit" class="btn-export" style="background:#3b82f6;">
                Import
            </button>
        </form>

        <!-- Export -->
        <a href="#" id="export-btn" class="btn-export">Export</a>

    </div>
</div>

    <div class="filter-row">
        <input type="text" class="f-email" id="filter-email"     placeholder="Search by email..."  value="{{ request('email') }}">
        <input type="text" class="f-name"  id="filter-name"      placeholder="Search by name..."   value="{{ request('name') }}">
        <input type="date" class="f-date"  id="filter-date-from" value="{{ request('date_from') }}">
        <input type="date" class="f-date"  id="filter-date-to"   value="{{ request('date_to') }}">
        <select class="f-status" id="filter-status">
            <option value="">All Statuses</option>
            <option value="success" {{ request('status')=='success' ? 'selected' : '' }}>Success</option>
            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed"  {{ request('status')=='failed'  ? 'selected' : '' }}>Failed</option>
        </select>
        <div class="f-reset">
            <button id="reset-btn" class="btn-reset">&#10005; Reset</button>
        </div>
    </div>

    <div id="ajax-loader" class="ajax-loader">Loading...</div>

    <div class="table-wrap">
        <table class="members-table">
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Email</th><th>Mobile</th>
                    <th>Manager</th><th>Manager Email</th><th>Referred By</th>
                    <th>Amount</th><th>Payment Status</th><th>Date</th>
                </tr>
            </thead>
            <tbody id="agents-table-body">
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
                            {{ $agent->referred_person_name }}
                            <div class="sub">{{ $agent->referred_person_mobile }}</div>
                        @else <span style="color:#bbb">—</span>
                        @endif
                    </td>
                    <td>₹{{ $agent->amount }}</td>
                    <td>
                        @if($agent->payment_status == 'success') <span class="badge-success">Success</span>
                        @elseif($agent->payment_status == 'pending') <span class="badge-pending">Pending</span>
                        @else <span class="badge-failed">Failed</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap">{{ $agent->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr class="empty-row"><td colspan="10">No agents found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="pagination-links" class="pagination-wrap">
        {{ $agents->links() }}
    </div>
</div>

{{-- ✅ Script OUTSIDE @push — works even without @stack in layout --}}
<script>
(function() {
    // ✅ Wait for jQuery to be available
    function waitForJQuery(cb) {
        if (window.jQuery) { cb(window.jQuery); }
        else { setTimeout(function(){ waitForJQuery(cb); }, 100); }
    }

    waitForJQuery(function($) {

        var AJAX_URL = '{{ route("join-agent") }}';
        var searchTimer;

        function getFilters() {
            return {
                ajax:      1,
                email:     $('#filter-email').val().trim(),
                name:      $('#filter-name').val().trim(),
                date_from: $('#filter-date-from').val(),
                date_to:   $('#filter-date-to').val(),
                status:    $('#filter-status').val(),
            };
        }

        function fetchAgents(page) {
            page = page || 1;
            var params = $.extend({}, getFilters(), { page: page });

            console.log('[AgentFilter] Sending:', params);
            $('#ajax-loader').show();

            $.ajax({
                url:     AJAX_URL,
                type:    'GET',
                data:    params,
                success: function(res) {
                    console.log('[AgentFilter] Response:', res);
                    if (res && res.table !== undefined) {
                        $('#agents-table-body').html(res.table);
                        $('#pagination-links').html(res.pagination);
                        bindPagination();
                    } else {
                        console.error('[AgentFilter] Bad response format:', res);
                    }
                },
                error: function(xhr, status, err) {
                    console.error('[AgentFilter] AJAX error:', xhr.status, xhr.responseText);
                    alert('Filter error: ' + xhr.status + '. Check console.');
                },
                complete: function() {
                    $('#ajax-loader').hide();
                }
            });
        }

        // Text inputs — debounced 400ms
        $('#filter-email, #filter-name').on('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function(){ fetchAgents(1); }, 400);
        });

        // Date + status — instant
        $('#filter-date-from, #filter-date-to, #filter-status').on('change', function() {
            fetchAgents(1);
        });

        // Reset button
        $('#reset-btn').on('click', function() {
            $('#filter-email, #filter-name, #filter-date-from, #filter-date-to').val('');
            $('#filter-status').val('');
            fetchAgents(1);
        });

        // Pagination hijack
        function bindPagination() {
            $('#pagination-links a').off('click').on('click', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                if (!href || href === '#') return;
                try {
                    var page = new URL(href, window.location.origin).searchParams.get('page') || 1;
                    fetchAgents(page);
                } catch(err) {
                    console.error('[AgentFilter] Pagination parse error:', err);
                }
            });
        }

        bindPagination();
        $('#export-btn').on('click', function(e) {
    e.preventDefault();
    var params = getFilters();
    delete params.ajax;
    window.location.href = '{{ route("join-agent.export") }}' + '?' + $.param(params);
});
        console.log('[AgentFilter] ✅ Initialized on URL:', AJAX_URL);
    });

})();
</script>

@endsection