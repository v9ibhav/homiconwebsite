{{ html()->form('POST', route('serviceConfig'))
    ->attribute('enctype', 'multipart/form-data')
    ->attribute('data-toggle', 'validator')
    ->id('myForm')
    ->open() }}

{{ html()->hidden('id', $serviceconfig->id ?? null)->class('form-control')->placeholder('id') }}
{{ html()->hidden('type')->value($page)->class('form-control')->placeholder('id') }}

<div class="form-group">
    <div class="form-control d-flex align-items-center justify-content-between">
        <label for="advance_payment" class="mb-0">{{ __('messages.enable_advanced_payment') }}</label>
        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="advance_payment" id="advance_payment" {{ !empty($serviceconfig->advance_payment) ? 'checked' : '' }}>
            <label class="custom-control-label" for="advance_payment"></label>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="form-control d-flex align-items-center justify-content-between">
        <label for="slot_service" class="mb-0">{{ __('messages.enable_slot_Service') }}</label>
        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="slot_service" id="slot_service" {{ !empty($serviceconfig->slot_service) ? 'checked' : '' }}>
            <label class="custom-control-label" for="slot_service"></label>
        </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="form-control d-flex align-items-center justify-content-between">
        <label for="digital_services">{{ __('messages.enable_digital_services') }}</label>
        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="digital_services" id="digital_services" {{ !empty($serviceconfig->digital_services) ? 'checked' : '' }}>
            <label class="custom-control-label" for="digital_services"></label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-control d-flex align-items-center justify-content-between">
        <label for="service_packages">{{ __('messages.enable_service_packages') }}</label>
        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="service_packages" id="service_packages" {{ !empty($serviceconfig->service_packages) ? 'checked' : '' }}>
            <label class="custom-control-label" for="service_packages"></label>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="form-control d-flex align-items-center justify-content-between">
        <label for="service_addons">{{ __('messages.enable_service_addons') }}</label>
        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="service_addons" id="service_addons" {{ !empty($serviceconfig->service_addons) ? 'checked' : '' }}>
            <label class="custom-control-label" for="service_addons"></label>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="form-control d-flex align-items-center justify-content-between">
        <label for="post_services">{{ __('messages.enable_post_services') }}</label>
        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="post_services" id="post_services" {{ !empty($serviceconfig->post_services) ? 'checked' : '' }}>
            <label class="custom-control-label" for="post_services"></label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-control d-flex align-items-center justify-content-between">
        <label for="enable_global_advance_payment" class="mb-0">{{ __('messages.global_advance_payment') }}</label>
        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="global_advance_payment" id="global_advance_payment"
                   {{ !empty($serviceconfig->global_advance_payment) ? 'checked' : '' }}>
            <label class="custom-control-label" for="global_advance_payment"></label>
        </div>
    </div>
</div>

<div class="form-padding-box mb-3 d-none" id="advance_payment_section">
    <div class="row">
        <div class="form-group col-sm-6 mb-0" id="key">
            {{ html()->label(trans('messages.advance_paynment_percantage') . '(%) <span class="text-danger">*</span>', 'advance_paynment_percantage')->class('form-control-label') }}
            {{ html()->number('advance_paynment_percantage')
                ->class('form-control')
                ->id('advance_paynment_percantage')
                ->value(!empty($serviceconfig->advance_paynment_percantage) ? $serviceconfig->advance_paynment_percantage : '')
                ->attribute('min', 1)
                ->attribute('max', 99)
                ->attribute('step', '0.1')
                ->placeholder(__('messages.advance_paynment_percantage'))
            }}
            <small class="help-block with-errors text-danger"></small>
        </div>
    </div>
</div>


<div class="form-group">
    <div class="form-control d-flex align-items-center justify-content-between">
        <label for="enable_cancellation_charge" class="mb-0">{{ __('messages.cancellation_charge') }}</label>
        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="cancellation_charge" id="cancellation_charge"
                   {{ !empty($serviceconfig->cancellation_charge) ? 'checked' : '' }}>
            <label class="custom-control-label" for="cancellation_charge"></label>
        </div>
    </div>
</div>

<div class="form-padding-box mb-3 d-none" id="cancellation_charge_section">
    <div class="row">
        <div class="form-group col-sm-6 mb-0" >
            {{ html()->label(trans('messages.cancellation_charge_amount') . ' (%) <span class="text-danger">*</span>' , 'cancellation_charge_amount')->class('form-control-label') }}
            {{ html()->number('cancellation_charge_amount')
                ->class('form-control')
                ->id('cancellation_charge_amount')
                ->value(!empty($serviceconfig->cancellation_charge_amount) ? $serviceconfig->cancellation_charge_amount : '')
                ->attribute('min', 1)
                ->attribute('max', 99)
                ->attribute('step', '0.1')
                ->placeholder(__('messages.cancellation_charge_amount'))
            }}
            <small class="help-block with-errors text-danger"></small>
        </div>

        <div class="form-group col-sm-6 mb-0">
            {{ html()->label(trans('messages.cancellation_charge_hours') . ' <span class="text-danger">*</span>', 'cancellation_charge_hours')->class('form-control-label') }}
            {{ html()->number('cancellation_charge_hours')
                ->class('form-control')
                ->id('cancellation_charge_hours')
                ->value(!empty($serviceconfig->cancellation_charge_hours) ? $serviceconfig->cancellation_charge_hours : '')
                ->attribute('min', 1)
                ->attribute('max', 99)
                ->placeholder(__('messages.cancellation_charge_hours'))
            }}
            <small class="help-block with-errors text-danger"></small>
        </div>
    </div>
</div>


{{ html()->submit(__('messages.save'))->class('btn btn-md btn-primary float-md-end') }}
{{ html()->form()->close() }}

<script>

$(document).ready(function () {
    const advancePaymentPercentage = document.getElementById('advance_paynment_percantage');

    let enableGlobalAdvancePayment = $("input[name='global_advance_payment']").prop('checked');
    advancePaymentSetting(enableGlobalAdvancePayment);

    $('#global_advance_payment').change(function() {
        let value = $(this).prop('checked');
        advancePaymentSetting(value);
    });

    function advancePaymentSetting(value) {
        if (value) {
            $('#advance_payment_section').removeClass('d-none');
            advancePaymentPercentage.setAttribute('required', 'required');
        } else {
            $('#advance_payment_section').addClass('d-none');
            advancePaymentPercentage.removeAttribute('required');
        }
    }



 const CancellationChargeAmount = document.getElementById('cancellation_charge_amount');
 const CancellationChargeHours = document.getElementById('cancellation_charge_hours');

let enableCancellationCharge = $("input[name='cancellation_charge']").prop('checked');
CancellationChargeSetting(enableCancellationCharge);

$('#cancellation_charge').change(function() {
    let value = $(this).prop('checked');
    CancellationChargeSetting(value);
});

function CancellationChargeSetting(value) {
    if (value) {
        $('#cancellation_charge_section').removeClass('d-none');
        CancellationChargeAmount.setAttribute('required', 'required');
        CancellationChargeHours.setAttribute('required', 'required');
    } else {
        $('#cancellation_charge_section').addClass('d-none');
        CancellationChargeAmount.removeAttribute('required');
        CancellationChargeHours.removeAttribute('required');
    }
}
});

</script>
