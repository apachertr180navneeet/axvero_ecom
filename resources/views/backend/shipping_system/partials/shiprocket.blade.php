<form class="form-horizontal" action="{{ route('shiprocket_settings.update') }}" method="POST">
    @csrf
    <div class="alert alert-info">
        {{ translate('Enable mock mode to test locally without Shiprocket API credentials. Disable mock mode and add client credentials when ready for live shipments.') }}
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label class="col-from-label">{{ translate('Shiprocket Email') }}</label>
        </div>
        <div class="col-md-8">
            <input type="email" class="form-control" name="SHIPROCKET_EMAIL"
                value="{{ env('SHIPROCKET_EMAIL') }}" placeholder="{{ translate('Shiprocket API email') }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label class="col-from-label">{{ translate('Shiprocket Password') }}</label>
        </div>
        <div class="col-md-8">
            <input type="password" class="form-control" name="SHIPROCKET_PASSWORD"
                value="{{ env('SHIPROCKET_PASSWORD') }}" placeholder="{{ translate('Shiprocket API password') }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label class="col-from-label">{{ translate('Default Weight (kg)') }}</label>
        </div>
        <div class="col-md-8">
            <input type="number" step="0.01" min="0.01" class="form-control" name="SHIPROCKET_DEFAULT_WEIGHT"
                value="{{ env('SHIPROCKET_DEFAULT_WEIGHT', 0.5) }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label class="col-from-label">{{ translate('Mock Mode') }}</label>
        </div>
        <div class="col-md-8">
            <label class="aiz-switch aiz-switch-success mb-0">
                <input type="checkbox" name="SHIPROCKET_MOCK_MODE" value="1"
                    @if (filter_var(env('SHIPROCKET_MOCK_MODE', true), FILTER_VALIDATE_BOOLEAN)) checked @endif>
                <span class="slider round"></span>
            </label>
            <small class="text-muted d-block mt-2">
                {{ translate('When enabled, no real Shiprocket orders are placed.') }}
            </small>
        </div>
    </div>
    <div class="form-group mb-0 text-right">
        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
    </div>
</form>
