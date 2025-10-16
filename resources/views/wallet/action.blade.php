
<?php
    $auth_user= authSession();
?>
{{ html()->form('DELETE', route('wallet.destroy', $wallet->id))->attribute('data--submit', 'wallet' . $wallet->id)->open() }}
@if(auth()->user()->hasAnyRole(['admin','demo_admin']))
<div class="d-flex justify-content-end align-items-center">
        <a class="me-2" href="{{ route('wallet.create',['id' => $wallet->id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.wallet') ]) }}"><i class="fas fa-pen text-secondary"></i></a>
        <a class="me-2" href="{{ route('wallet.destroy', $wallet->id) }}" data--submit="wallet{{$wallet->id}}"
            data--confirmation='true'
            data--ajax="true"
            data-datatable="reload"
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.wallet') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.wallet') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    </div>
@endif
{{ html()->form()->close() }}
