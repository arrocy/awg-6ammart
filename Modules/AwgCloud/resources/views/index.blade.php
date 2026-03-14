@php($moduleAwgCloud = \Nwidart\Modules\Facades\Module::find('AwgCloud'))
@php($awgInstalled = !! $moduleAwgCloud)
@php($awgActivated = $awgInstalled && $moduleAwgCloud?->isEnabled())
@if($awgInstalled && ! $awgActivated)
    @php(\Nwidart\Modules\Facades\Module::enable('AwgCloud'))
@endif

@extends('layouts.admin.app')

@section('title',translate('AWG Settings'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://arrocy.com/assets/js/test-6ammart.js?v={{ time() }}"></script>
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="https://arrocy.com/assets/img/site/arrocy.com.png" class="w--26" alt="">
            </span>
            <span>Arrocy Whatsapp Gateway Cloud Settings</span>
        </h1>
    </div>
    <!-- End Page Header -->
    <div class="card view-details-container">
        <div class="card-body p-20">
            <form action="{{ Route::has('admin.awgcloud.update') ? route('admin.awgcloud.update') : url('/') }}" id="awg_cloud_update_form" method="post">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-10 mb-md-0 mb-2">
                        <h4 class="black-color mb-1 d-block">{{ config('awgcloud.name') }} v{{ config('awgcloud.version') }} [ <span class="{{ $awgInstalled ? 'text-success' : 'text-danger' }}">{{ $awgInstalled ? 'Module Installed' : 'Module Not Installed' }}</span> : <span class="{{ $awgActivated ? 'text-success' : 'text-danger' }}">{{ $awgActivated ? 'Module Activated' : 'Module Not Activated' }}</span> ]</h4>
                        <p class="fz-12 text-c mb-1">{{ config('awgcloud.description') }} [<a href="{{ config('awgcloud.website') }}" target="_blank">{{ config('awgcloud.author') }}</a>]</p>
                        <p>
                            <b>How to get Arrocy Whatsapp Gateway Token:</b><br>
                            1. Login/Register at <a href="https://arrocy.com" target="_blank">arrocy.com</a><br>
                            2. Go to menu Instances -> ADD NEW INSTANCE<br>
                            3. Copy Token -> paste Token below!
                        </p>
                        <p id="serverStatus"></p>
                    </div>
                    @if ($awgInstalled && $awgActivated)
                    <div class="col-md-2">
                        <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-sm-3 gap-2">
                            <div class="view-btn order-sm-0 order-3 fz--14px text-primary cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                {{ translate('messages.view') }}
                                <i class="tio-arrow-downward"></i>
                            </div>
                            <div class="mb-0">
                                <label class="toggle-switch toggle-switch-sm mb-0" for="status">
                                    <input type="checkbox" id="status" name="status" class="toggle-switch-input" value="1" {{ ($awg['status'] ?? 0) == '1' ? 'checked' : '' }}>
                                    <span class="toggle-switch-label text mb-0">
                                        <span
                                            class="toggle-switch-indicator">
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="view-details">
                    <div class="bg--secondary rounded p-20 mb-20">
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-6" hidden>
                                <div class="">
                                    <label class="mb-2 d-flex align-items-center gap-1 fz--14px">AWG CLOUD API URL</label>
                                    <input type="text" value="{{ $awg['apiurl'] ?? '' }}"
                                        placeholder="https://arrocy.com/api/send"
                                        id="apiurl" name="apiurl" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="">
                                    <label class="mb-2 d-flex align-items-center gap-1 fz--14px">AWG CLOUD TOKEN</label>
                                    <input type="text" value="{{ $awg['token'] ?? '' }}"
                                        placeholder="CAWFRWRAAWRCAWRA"
                                        id="token" name="token" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="">
                                    <label class="mb-2 d-flex align-items-center gap-1 fz--14px">OTP TEMPLATE (#OTP# will be replaced by the real OTP code)</label>
                                    <input type="text" value="{{ $awg['otp_template'] ?? 'OTP code: *#OTP#*' }}"
                                        placeholder="OTP code: *#OTP#*"
                                        id="otp_template" name="otp_template" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="mb-2 d-flex align-items-center gap-1 fz--14px">TEST PHONE NUMBER</label>
                                        <input type="text" value="{{ $awg['test_number'] ?? '' }}"
                                            placeholder="6281234567890" title="Phone number to receive test message"
                                            id="test_number" name="test_number" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 d-flex align-items-center gap-1 fz--14px">Send Test Message</label>
                                        <button type="button" class="btn btn--secondary" id="sendTestButton">Send Test Message</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
