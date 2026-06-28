@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All Orders') }}</h5>
                </div>

                @canany(['delete_order', 'export_order'])
                    <div class="dropdown mb-2 mb-md-0">
                        <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @can('delete_order')
                                <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal">{{ translate('Delete selection') }}</a>
                            @endcan
                            @can('export_order')
                                <a class="dropdown-item" href="javascript:void(0)" onclick="order_bulk_export()">{{ translate('Export') }}</a>
                            @endcan
                            @if(auth()->user()->can('unpaid_order_payment_notification_send') && $unpaid_order_payment_notification->status == 1 && Route::currentRouteName() == 'unpaid_orders.index')
                                <a class="dropdown-item" href="javascript:void(0)" onclick="bulk_unpaid_order_payment_notification()">{{ translate('Unpaid Order Payment Notification') }}</a>
                            @endif
                        </div>
                    </div>
                @endcan
                @if(Route::currentRouteName() == 'offline_payment_orders.index')
                    <div class="col-lg-2 ml-auto">
                        <select class="form-control aiz-selectpicker" name="order_type" id="order_type">
                            <option value="">{{ translate('Filter by Order Type') }}</option>
                            <option value="inhouse_orders" @if ($order_type == 'inhouse_orders') selected @endif>{{ translate('Inhouse Orders') }}</option>
                            <option value="seller_orders" @if ($order_type == 'seller_orders') selected @endif>{{ translate('Seller Orders') }}</option>
                        </select>
                    </div>
                @endif

                <div class="col-lg-2 ml-auto">
                    <select class="form-control aiz-selectpicker" name="delivery_status" id="delivery_status">
                        <option value="">{{ translate('Filter by Delivery Status') }}</option>
                        <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{ translate('Pending') }}
                        </option>
                        <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                            {{ translate('Confirmed') }}</option>
                        <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                            {{ translate('Picked Up') }}</option>
                        <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                            {{ translate('On The Way') }}</option>
                        <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                            {{ translate('Delivered') }}</option>
                        <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                            {{ translate('Cancel') }}</option>
                    </select>
                </div>
                @if(Route::currentRouteName() != 'unpaid_orders.index')
                    <div class="col-lg-2 ml-auto">
                        <select class="form-control aiz-selectpicker" name="payment_status" id="payment_status">
                            <option value="">{{ translate('Filter by Payment Status') }}</option>
                            <option value="paid"
                                @isset($payment_status) @if ($payment_status == 'paid') selected @endif @endisset>
                                {{ translate('Paid') }}</option>
                            <option value="unpaid"
                                @isset($payment_status) @if ($payment_status == 'unpaid') selected @endif @endisset>
                                {{ translate('Unpaid') }}</option>
                        </select>
                    </div>
                @endif
                <div class="col-lg-1">
                    <div class="form-group mb-0">
                        <input type="text" class="aiz-date-range form-control" value="{{ $date }}"
                            name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y"
                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            @if (auth()->user()->can('delete_order') || auth()->user()->can('export_order'))
                                <th>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-all">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </th>
                            @else
                                <th data-breakpoints="lg">#</th>
                            @endif

                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="md">{{ translate('Num. of Products') }}</th>
                            <th data-breakpoints="md">{{ translate('Customer') }}</th>
                            <th data-breakpoints="md">{{ translate('Seller') }}</th>
                            <th data-breakpoints="md">{{ translate('Amount') }}</th>
                            <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
                            <th data-breakpoints="md">{{ translate('Payment method') }}</th>
                            <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                            <th data-breakpoints="md">{{ translate('Member') }}</th>
                                <th>{{ translate('Refund') }}</th>
                            <th class="text-right" width="15%">{{ translate('options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                @if (auth()->user()->can('delete_order') || auth()->user()->can('export_order'))
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="{{ $order->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                @endif
                                <td>
                                    {{ $order->code }}
                                    @if ($order->viewed == 0)
                                        <span class="badge badge-inline badge-info">{{ translate('New') }}</span>
                                    @endif
                                    <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>
                                </td>
                                <td>
                                    {{ count($order->orderDetails) }}
                                </td>
                                <td>
                                    @if ($order->user != null)
                                        {{ $order->user->name }}
                                    @else
                                        Guest ({{ $order->guest_id }})
                                    @endif
                                </td>
                                <td>
                                    @if ($order->shop)
                                        {{ $order->shop->name }}
                                    @else
                                        {{ translate('Inhouse Order') }}
                                    @endif
                                </td>
                                <td>
                                    {{ single_price($order->grand_total) }}
                                </td>
                                <td>
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }} <br>
                                    @if (shiprocket_is_enabled())
                                    @if ($order->shipping_method == 'shiprocket')
                                        <span class="fw-bold">{{ translate('Shiprocket Status') }}:</span> {{ ucfirst(translate(str_replace('_', ' ', $order->shiprocket_status))) }}
                                    @endif
                                    @endif
                                </td>
                                <td>
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                                </td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                    @endif
                                </td>
                                
                                
                                
                                <td>
                                @php
                                    $isAgentMember = false;
                                    if ($order->user) {
                                        $isAgentMember = \App\Models\AgentJoin::where('user_id', $order->user->id)
                                                            ->where('payment_status', 'success')
                                                            ->exists();
                                    }
                                @endphp
                                @if($isAgentMember)
                                    <span class="badge badge-inline badge-success">{{ translate('Yes') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('No') }}</span>
                                @endif
                            </td>
                                                            
                                
                                    <td>
                                        @if (count($order->refund_requests) > 0)
                                            {{ count($order->refund_requests) }} {{ translate('Refund') }}
                                        @else
                                            {{ translate('No Refund') }}
                                        @endif
                                    </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            href="{{ route('admin.invoice.thermal_printer', $order->id) }}" target="_blank"
                                            title="{{ translate('Thermal Printer') }}">
                                            <i class="las la-print"></i>
                                        </a>
                                    @can('view_order_details')
                                        @php
                                            $order_detail_route = route('all_orders.show', encrypt($order->id));
                                            if (Route::currentRouteName() == 'seller_orders.index') {
                                                $order_detail_route = route('seller_orders.show', encrypt($order->id));
                                            } elseif (Route::currentRouteName() == 'pick_up_point.index') {
                                                $order_detail_route = route('pick_up_point.order_show', encrypt($order->id));
                                            }
                                            if (Route::currentRouteName() == 'inhouse_orders.index') {
                                                $order_detail_route = route('inhouse_orders.show', encrypt($order->id));
                                            }
                                        @endphp
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ $order_detail_route }}" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                    @endcan
                                    <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                        href="{{ route('invoice.download', $order->id) }}"
                                        title="{{ translate('Download Invoice') }}">
                                        <i class="las la-download"></i>
                                    </a>
                                    @if (shiprocket_is_enabled() && empty($order->shiprocket_shipment_id))
                                        <a href="javascript:void(0);"
                                            class="btn btn-soft-info btn-icon btn-circle btn-sm create-shiprocket-shipment"
                                            data-order-id="{{ $order->id }}"
                                            title="{{ translate('Create Shipment') }}">
                                            <i class="las la-shipping-fast"></i>
                                        </a>
                                    @endif
                                    
                                    @if(auth()->user()->can('unpaid_order_payment_notification_send') && $order->payment_status == 'unpaid' && $unpaid_order_payment_notification->status == 1)
                                        <a class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                            href="javascript:void();" onclick="unpaid_order_payment_notification('{{ $order->id }}');"
                                            title="{{ translate('Unpaid Order Payment Notification') }}">
                                            <i class="las la-bell"></i>
                                        </a>
                                    @endif
                                    @can('delete_order')
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('orders.destroy', $order->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="aiz-pagination">
                    {{ $orders->appends(request()->input())->links() }}
                </div>

            </div>
        </form>
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')

    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')

    {{-- Bulk Unpaid Order Payment Notification --}}
    <div id="complete_unpaid_order_payment" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content pb-2rem px-2rem">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <form class="form-horizontal" action="{{ route('unpaid_order_payment_notification') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <input type="hidden" name="order_ids" value="" id="order_ids">
                        <p class="mt-2 mb-2 fs-16 fw-700">{{ translate('Are you sure to send notification for the selected orders?') }}</p>
                        <button type="submit" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px">{{ translate('Send Notification') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (shiprocket_is_enabled())
    <div id="shiprocket-shipment-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">{{ translate('Create Shiprocket Shipment') }}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="shiprocket_order_id" value="">
                    <div id="shiprocket-shipment-options">
                        <p class="text-muted mb-0">{{ translate('Loading...') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="button" class="btn btn-primary btn-styled btn-base-1" id="confirm-shiprocket-shipment">{{ translate('Create Shipment') }}</button>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function sort_orders(el){
            $('#sort_orders').submit();
        }

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', 'Selected items deleted successfully');
                        location.reload();
                    }
                }
            });
        }
        
        function order_bulk_export (){
            var url = '{{route('order-bulk-export')}}';
            $("#sort_orders").attr("action", url);
            $('#sort_orders').submit();
            $("#sort_orders").attr("action", '');
        }

        // Unpaid Order Payment Notification
        function unpaid_order_payment_notification(order_id){
            var orderIds = [];
            orderIds.push(order_id);
            $('#order_ids').val(orderIds);
            $('#complete_unpaid_order_payment').modal('show', {backdrop: 'static'});
        }

        // Unpaid Order Payment Notification
        function bulk_unpaid_order_payment_notification(){
            var orderIds = [];
            $(".check-one[name='id[]']:checked").each(function() {
                orderIds.push($(this).val());
            });
            if(orderIds.length > 0){
                $('#order_ids').val(orderIds);
                $('#complete_unpaid_order_payment').modal('show', {backdrop: 'static'});
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Please Select Order first.') }}');
            }
        }

        @if (shiprocket_is_enabled())
        const defaultBoxDimensions = {
            length: {{ (float) config('services.shiprocket.default_length', 10) }},
            breadth: {{ (float) config('services.shiprocket.default_breadth', 10) }},
            height: {{ (float) config('services.shiprocket.default_height', 10) }},
        };

        function loadShiprocketShipmentOptions() {
            $('#shiprocket-shipment-options').html('<p class="text-muted mb-0">{{ translate("Loading...") }}</p>');

            $.when(
                $.get("{{ route('shiprocket.pickup_locations') }}"),
                $.ajax({
                    url: "{{ route('box.sizes.list') }}",
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': AIZ.data.csrf },
                    data: { user_id: {{ Auth::id() }}, shipping_system: 'shiprocket' }
                })
            ).done(function(pickupResponse, boxResponse) {
                let html = '';
                const pickupResult = pickupResponse[0] || {};
                const pickupAddresses = pickupResult.success ? (pickupResult.data || []) : [];

                html += `<label class="fw-700 d-block mb-2">{{ translate('Pickup Location') }}</label>`;
                if (pickupAddresses.length > 0) {
                    let defaultPickupIndex = pickupAddresses.findIndex(addr => addr.is_primary_location);
                    if (defaultPickupIndex < 0) {
                        defaultPickupIndex = 0;
                    }

                    pickupAddresses.forEach((addr, index) => {
                        const label = [
                            addr.pickup_location,
                            addr.city,
                            addr.pin_code ? `(${addr.pin_code})` : ''
                        ].filter(Boolean).join(' - ');
                        const checked = index === defaultPickupIndex ? 'checked' : '';

                        html += `
                            <div class="border p-3 mb-3 rounded">
                                <div class="form-check">
                                    <input class="magic-radio" type="radio" name="list_pickup_location" value="${addr.pickup_location}" id="list_addr_${index}" ${checked}>
                                    <label class="form-check-label" for="list_addr_${index}">
                                        ${label}
                                        ${addr.address ? `<div class="text-muted fs-12">${addr.address}</div>` : ''}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html += `<p class="text-muted mb-4">${pickupResult.message || '{{ translate('No pickup address found.') }}'}</p>`;
                }

                const boxSizes = boxResponse[0] || [];
                html += `<label class="fw-700 d-block mb-2">{{ translate('Box Size (Length × Breadth × Height)') }}</label>`;
                if (boxSizes.length > 0) {
                    boxSizes.forEach((box, index) => {
                        const dims = `${box.length} × ${box.breadth} × ${box.height} cm`;
                        html += `
                            <div class="border p-3 mb-3 rounded">
                                <div class="form-check">
                                    <input class="magic-radio shiprocket-box-option" type="radio" name="list_box_size_id" value="${box.id}" id="list_box_${box.id}" data-length="${box.length}" data-breadth="${box.breadth}" data-height="${box.height}" ${index === 0 ? 'checked' : ''}>
                                    <label class="form-check-label" for="list_box_${box.id}">
                                        ${dims}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    const dims = `${defaultBoxDimensions.length} × ${defaultBoxDimensions.breadth} × ${defaultBoxDimensions.height} cm`;
                    html += `
                        <div class="border p-3 mb-3 rounded">
                            <div class="form-check">
                                <input class="magic-radio shiprocket-box-option" type="radio" name="list_box_size_id" value="" id="list_box_default" data-length="${defaultBoxDimensions.length}" data-breadth="${defaultBoxDimensions.breadth}" data-height="${defaultBoxDimensions.height}" checked>
                                <label class="form-check-label" for="list_box_default">
                                    ${dims} ({{ translate('Default') }})
                                </label>
                            </div>
                        </div>
                    `;
                }

                $('#shiprocket-shipment-options').html(html);
            }).fail(function(xhr) {
                const message = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : '{{ translate("Failed to load shipping information.") }}';
                $('#shiprocket-shipment-options').html('<p class="text-danger mb-0">' + message + '</p>');
            });
        }

        $(document).on('click', '.create-shiprocket-shipment', function() {
            const orderId = $(this).data('order-id');
            $('#shiprocket_order_id').val(orderId);
            loadShiprocketShipmentOptions();
            $('#shiprocket-shipment-modal').modal('show');
        });

        $('#confirm-shiprocket-shipment').on('click', function() {
            const orderId = $('#shiprocket_order_id').val();
            const pickupLocation = $('input[name="list_pickup_location"]:checked').val();
            const $selectedBox = $('.shiprocket-box-option:checked');

            if (!pickupLocation) {
                AIZ.plugins.notify('warning', '{{ translate("Please select a pickup location.") }}');
                return;
            }

            if (!$selectedBox.length) {
                AIZ.plugins.notify('warning', '{{ translate("Please select box size.") }}');
                return;
            }

            const postData = {
                _token: AIZ.data.csrf,
                order_id: orderId,
                pickup_location: pickupLocation,
                length: $selectedBox.data('length'),
                breadth: $selectedBox.data('breadth'),
                height: $selectedBox.data('height'),
            };

            const boxId = $selectedBox.val();
            if (boxId) {
                postData.shipping_box_size_id = boxId;
            }

            const $btn = $(this);
            $btn.prop('disabled', true);

            $.post("{{ route('orders.confirm_shiprocket_info') }}", postData, function(response) {
                if (response.success) {
                    AIZ.plugins.notify('success', response.message);
                    $('#shiprocket-shipment-modal').modal('hide');
                    location.reload();
                } else {
                    AIZ.plugins.notify('danger', response.message);
                }
            }).fail(function(xhr) {
                const message = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : '{{ translate("Failed to create shipment.") }}';
                AIZ.plugins.notify('danger', message);
            }).always(function() {
                $btn.prop('disabled', false);
            });
        });
        @endif
    </script>
@endsection
