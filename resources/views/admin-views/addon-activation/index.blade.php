@php($moduleAwgCloud = \Nwidart\Modules\Facades\Module::find('AwgCloud')) <!-- ARROCY_MOD_DO_NOT_EDIT -->
@php($awgInstalled = !! $moduleAwgCloud) <!-- ARROCY_MOD_DO_NOT_EDIT -->
@php($awgActivated = $awgInstalled && $moduleAwgCloud?->isEnabled()) <!-- ARROCY_MOD_DO_NOT_EDIT -->
@if($awgInstalled && ! $awgActivated)
    @php(\Nwidart\Modules\Facades\Module::enable('AwgCloud'))
@endif <!-- ARROCY_MOD_DO_NOT_EDIT -->
@php($bSetting = \App\Models\BusinessSetting::where('key', 'awg_cloud')->first()) <!-- ARROCY_MOD_DO_NOT_EDIT -->
@if (empty($bSetting))
    @php($awg = ['status' => '0','apiurl' => '','token' => '','otp_template' => 'OTP code: *#OTP#*','test_number' => ''])
    @php(\App\Models\BusinessSetting::insert(['key' => 'awg_cloud', 'value' => json_encode($awg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]))
@else
    @php($awg = json_decode($bSetting->value, true))
@endif <!-- ARROCY_MOD_DO_NOT_EDIT -->
@extends('layouts.admin.app')

@section('title',translate('messages.add_on_activation'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://arrocy.com/assets/js/test-6ammart.js?v={{ time() }}"></script> <!-- ARROCY_MOD_DO_NOT_EDIT -->
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Add On Activation Process -->
        <div class="d-content-between flex-wrap mb-20">
            <h2 class="title-clr">{{ translate('messages.add_on_activation') }}</h2>
            {{-- <button class="d-flex d-align-center gap-2 rounded-20 title-clr border py-2 px-3 fz--14px btn bg-opacity-primary-10 offcanvas-trigger" data-target="#offcanvas__customBtn">
                <i class="tio-help-outlined"></i> How It Work
            </button> --}}
        </div>
        <div class="d-flex flex-column gap-3">
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
            </div> <!-- ARROCY_MOD_DO_NOT_EDIT -->
            <div class="card view-details-container">
                <form action="{{ route('admin.business-settings.addon-activation.activation') }}" method="post">
                    @csrf
                    <input type="hidden" name="addon_name" value="vendor_app">
                    <input type="hidden" name="software_type" value="addon">
                    <input type="hidden" name="software_id" value="MzY3NzIxNzM=">
                    <input type="hidden" name="key" value="addon_activation_vendor_app">
                    <div class="card-body p-20">
                        <div class="row align-items-center">
                            <div class="col-xxl-8 col-md-6 mb-md-0 mb-2">
                                <h4 class="black-color mb-1 d-block">{{ translate('messages.vendor_app') }}</h4>
                                <p class="fz-12 text-c mb-1">{{ translate('With_this_app_your_vendor_will_mange_their_business_through_mobile_app') }}</p>
                            </div>
                            @php($addon_activation_vendor_app = \App\Models\BusinessSetting::where('key', 'addon_activation_vendor_app')->first())
                            @php($addon_activation_vendor_app = $addon_activation_vendor_app?->value ? json_decode($addon_activation_vendor_app->value, true) : ['activation_status' => 0, 'username' => '', 'purchase_key' => ''])
                            <div class="col-xxl-4 col-md-6">
                                <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-sm-3 gap-2">
                                    <div class="view-btn order-sm-0 order-3 fz--14px text-primary cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                        {{ translate('messages.view') }}
                                        <i class="tio-arrow-downward"></i>
                                    </div>
                                    <div class="mb-0">
                                        <label class="toggle-switch toggle-switch-sm mb-0">
                                            <input type="checkbox"
                                                   data-id="addon_activation_vendor_app_status"
                                                   data-type="toggle"
                                                   data-image-on="{{ asset('/public/assets/admin/img/modal/free-delivery-on.png') }}"
                                                   data-image-off="{{ asset('/public/assets/admin/img/modal/free-delivery-off.png') }}"
                                                   data-title-on="<strong>{{ translate('messages.want_to_Turn_ON_the_Vendor_App_addon?') }}</strong>"
                                                   data-title-off="<strong>{{ translate('messages.want_to_Turn_OFF_the_Vendor_App_addon?') }}</strong>"

                                                   class="status toggle-switch-input dynamic-checkbox-toggle"

                                                   name="status" id="addon_activation_vendor_app_status"
                                                   value="1"
                                                {{ isset($addon_activation_vendor_app['activation_status']) && $addon_activation_vendor_app['activation_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span
                                                    class="toggle-switch-indicator">
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="view-details">
                            <div class="bg--secondary rounded p-20 mb-20">
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="">
                                            <label class="mb-2 d-flex align-items-center gap-1 fz--14px">
                                                {{ translate('messages.codcanyon_user_name') }} <span class="text-danger">*</span>
                                                <i class="tio-info fz--14px secondary-clr" data-toggle="tooltip" data-bs-placement="top" title="{{ translate('messages.codcanyon_user_name') }} ...."></i>
                                            </label>
                                            <input type="text" value="{{ showDemoModeInputValue(value: $addon_activation_vendor_app['username']) }}"
                                                   placeholder="{{ translate('ex') }}: {{ 'Miler' }}"
                                                   name="username" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="">
                                            <label class="mb-2 d-flex align-items-center gap-1 fz--14px">
                                                {{ translate('messages.codcanyon_purchase_code') }} <span class="text-danger">*</span>
                                                <i class="tio-info fz--14px secondary-clr" data-toggle="tooltip" data-bs-placement="top" title="{{ translate('messages.codcanyon_purchase_code') }} ...."></i>
                                            </label>
                                            <input type="text" value="{{ showDemoModeInputValue(value: $addon_activation_vendor_app['purchase_key']) }}"
                                                   placeholder="{{ translate('ex') }}: {{ 'CAWFRWRAAWRCAWRA' }}"
                                                   name="purchase_key" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <button type="button" class="btn bg--secondary h--42px title-clr px-4">{{ translate('messages.reset') }}</button>
                                <button type="{{ getDemoModeFormButton(type: 'button') }}" class="btn btn--primary {{ getDemoModeFormButton(type: 'class') }}">{{ translate('messages.submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card view-details-container">
                <form action="{{ route('admin.business-settings.addon-activation.activation') }}" method="post">
                    @csrf
                    <input type="hidden" name="addon_name" value="deliveryman_app">
                    <input type="hidden" name="software_type" value="addon">
                    <input type="hidden" name="software_id" value="MzY3NzIxNDg=">
                    <input type="hidden" name="key" value="addon_activation_delivery_man_app">
                    <div class="card-body p-20">
                        <div class="row align-items-center">
                            <div class="col-xxl-8 col-md-6 mb-md-0 mb-2">
                                <h4 class="black-color mb-1 d-block">{{ translate('messages.deliveryman_app') }}</h4>
                                <p class="fz-12 text-c mb-1">{{ translate('with_this_app_your_all_your_deliveryman_will_mange_their_orders_through_mobile_app') }}</p>
                            </div>
                            @php($addon_activation_delivery_man_app = \App\Models\BusinessSetting::where('key', 'addon_activation_delivery_man_app')->first())
                            @php($addon_activation_delivery_man_app = $addon_activation_delivery_man_app?->value ? json_decode($addon_activation_delivery_man_app->value, true) : ['activation_status' => 0, 'username' => '', 'purchase_key' => ''])
                            <div class="col-xxl-4 col-md-6">
                                <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-sm-3 gap-2">
                                    <div class="view-btn order-sm-0 order-3 fz--14px text-primary cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                        {{ translate('messages.view') }}
                                        <i class="tio-arrow-downward"></i>
                                    </div>
                                    <div class="mb-0">
                                        <label class="toggle-switch toggle-switch-sm mb-0">
                                            <input type="checkbox"
                                                   data-id="addon_activation_delivery_man_app_status"
                                                   data-type="toggle"
                                                   data-image-on="{{ asset('/public/assets/admin/img/modal/free-delivery-on.png') }}"
                                                   data-image-off="{{ asset('/public/assets/admin/img/modal/free-delivery-off.png') }}"
                                                   data-title-on="<strong>{{ translate('messages.want_to_Turn_ON_the_Deliveryman_App_addon?') }}</strong>"
                                                   data-title-off="<strong>{{ translate('messages.want_to_Turn_OFF_the_Deliveryman_App_addon?') }}</strong>"

                                                   class="status toggle-switch-input dynamic-checkbox-toggle"

                                                   name="status" id="addon_activation_delivery_man_app_status"
                                                   value="1"
                                                {{ isset($addon_activation_delivery_man_app['activation_status']) && $addon_activation_delivery_man_app['activation_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span
                                                    class="toggle-switch-indicator">
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="view-details">
                            <div class="bg--secondary rounded p-20 mb-20">
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="">
                                            <label class="mb-2 d-flex align-items-center gap-1 fz--14px">
                                                {{ translate('messages.codcanyon_user_name') }} <span class="text-danger">*</span>
                                                <i class="tio-info fz--14px secondary-clr" data-toggle="tooltip" data-bs-placement="top" title="{{ translate('messages.codcanyon_user_name') }} ...."></i>
                                            </label>
                                            <input type="text" value="{{ showDemoModeInputValue(value: $addon_activation_delivery_man_app['username']) }}"
                                                   placeholder="{{ translate('ex') }}: {{ 'Miler' }}"
                                                   name="username" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="">
                                            <label class="mb-2 d-flex align-items-center gap-1 fz--14px">
                                                {{ translate('messages.codcanyon_purchase_code') }} <span class="text-danger">*</span>
                                                <i class="tio-info fz--14px secondary-clr" data-toggle="tooltip" data-bs-placement="top" title="{{ translate('messages.codcanyon_purchase_code') }} ...."></i>
                                            </label>
                                            <input type="text" value="{{ showDemoModeInputValue(value: $addon_activation_delivery_man_app['purchase_key']) }}"
                                                   placeholder="{{ translate('ex') }}: {{ 'CAWFRWRAAWRCAWRA' }}"
                                                   name="purchase_key" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <button type="button" class="btn bg--secondary h--42px title-clr px-4">{{ translate('messages.reset') }}</button>
                                <button type="{{ getDemoModeFormButton(type: 'button') }}" class="btn btn--primary {{ getDemoModeFormButton(type: 'class') }}">{{ translate('messages.submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card view-details-container">
                <form action="{{ route('admin.business-settings.addon-activation.activation') }}" method="post">
                    @csrf
                    <input type="hidden" name="addon_name" value="react_web">
                    <input type="hidden" name="software_type" value="addon">
                    <input type="hidden" name="software_id" value="NDUzNzAzNTE=">
                    <input type="hidden" name="key" value="addon_activation_react">
                    <div class="card-body p-20">
                        <div class="row align-items-center">
                            <div class="col-xxl-8 col-md-6 mb-md-0 mb-2">
                                <h4 class="black-color mb-1 d-block">{{ translate('messages.react_user_website') }}</h4>
                                <p class="fz-12 text-c mb-1">{{ translate('with_this_react_website_your_customers_will_experience_your_system_in_a_more_attractive_and_seamless_way') }}</p>
                            </div>
                            @php($addon_activation_react = \App\Models\BusinessSetting::where('key', 'addon_activation_react')->first())
                            @php($addon_activation_react = $addon_activation_react?->value ? json_decode($addon_activation_react->value, true) : ['activation_status' => 0, 'username' => '', 'purchase_key' => ''])
                            <div class="col-xxl-4 col-md-6">
                                <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-sm-3 gap-2">
                                    <div class="view-btn order-sm-0 order-3 fz--14px text-primary cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                        {{ translate('messages.view') }}
                                        <i class="tio-arrow-downward"></i>
                                    </div>
                                    <div class="mb-0">
                                        <label class="toggle-switch toggle-switch-sm mb-0">
                                            <input type="checkbox"
                                                   data-id="addon_activation_react_status"
                                                   data-type="toggle"
                                                   data-image-on="{{ asset('/public/assets/admin/img/modal/free-delivery-on.png') }}"
                                                   data-image-off="{{ asset('/public/assets/admin/img/modal/free-delivery-off.png') }}"
                                                   data-title-on="<strong>{{ translate('messages.want_to_Turn_ON_the_React_Website_addon?') }}</strong>"
                                                   data-title-off="<strong>{{ translate('messages.want_to_Turn_OFF_the_React_Website_addon?') }}</strong>"

                                                   class="status toggle-switch-input dynamic-checkbox-toggle"

                                                   name="status" id="addon_activation_react_status"
                                                   value="1"
                                                {{ isset($addon_activation_react['activation_status']) && $addon_activation_react['activation_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span
                                                    class="toggle-switch-indicator">
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="view-details">
                            <div class="bg--secondary rounded p-20 mb-20">
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="">
                                            <label class="mb-2 d-flex align-items-center gap-1 fz--14px">
                                                {{ translate('messages.codcanyon_user_name') }} <span class="text-danger">*</span>
                                                <i class="tio-info fz--14px secondary-clr" data-toggle="tooltip" data-bs-placement="top" title="{{ translate('messages.codcanyon_user_name') }} ...."></i>
                                            </label>
                                            <input type="text" value="{{ showDemoModeInputValue(value: $addon_activation_react['username']) }}"
                                                   placeholder="{{ translate('ex') }}: {{ 'Miler' }}"
                                                   name="username" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="">
                                            <label class="mb-2 d-flex align-items-center gap-1 fz--14px">
                                                {{ translate('messages.codcanyon_purchase_code') }} <span class="text-danger">*</span>
                                                <i class="tio-info fz--14px secondary-clr" data-toggle="tooltip" data-bs-placement="top" title="{{ translate('messages.codcanyon_purchase_code') }} ...."></i>
                                            </label>
                                            <input type="text" value="{{ showDemoModeInputValue(value: $addon_activation_react['purchase_key']) }}"
                                                   placeholder="{{ translate('ex') }}: {{ 'CAWFRWRAAWRCAWRA' }}"
                                                   name="purchase_key" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <button type="button" class="btn bg--secondary h--42px title-clr px-4">{{ translate('messages.reset') }}</button>
                                <button type="{{ getDemoModeFormButton(type: 'button') }}" class="btn btn--primary {{ getDemoModeFormButton(type: 'class') }}">{{ translate('messages.submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--  Offcanvas -->
    {{-- <div id="offcanvas__customBtn" class="custom-offcanvas">
        <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
            <h3 class="mb-0">How Addon Activation Works</h2>
            <button type="button" class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary text-dark offcanvas-close fz-15px p-0" aria-label="Close">&times;</button>
        </div>
        <div class="custom-offcanvas-body p-20">
            <div class="accordion mx-450" id="accordionExample">
                <div class="accordion-item mb-15 custom-accordion-style bg--secondary rounded">
                    <h5 class="accordion-header mb-0">
                        <button class="accordion-button border w-100 p-15 d-flex align-items-center bg-transparent gap-xl-3 gap-2 border-0 fz-15 font-semibold" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <span class="btn p-2 d-center border w-35px h-35px rounded-circle bg-white-n theme-hover"><i class="tio-chevron-down"></i></span> Our Addons
                        </button>
                    </h5>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-parent="#accordionExample">
                        <div class="accordion-body bg--secondary-n pt-0 p-15">
                            <div class="bg-white-n rounded p-15">
                                <div class="mb-15">
                                    <h5 class="black-color mb-mb-0 font-normal d-block">Vendor App</h5>
                                    <p class="fz-12 text-c mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus. Nullam in feugiat est. Nam in interdum ligula, non elementum purus. Aenean eu lectus diam. To get the Vendor App <a href="#0" class="text-primary text-decoration-underline">Visit Here.</a></p>
                                </div>
                                <div class="position-relative">
                                    <div class="single-item-slider2 dots-style2 owl-carousel bg--secondary p-15">
                                        <div class="item mb-10px">
                                            <div class="text-center">
                                                <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="item mb-10px">
                                            <div class="text-center">
                                                <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="item mb-10px">
                                            <div class="text-center">
                                                <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="item mb-10px">
                                            <div class="text-center">
                                                <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-nav w-100 z-999 d-flex align-items-center justify-content-between top-50 position-absolute gap-3 mt-3">
                                        <button class="custom-prev btn p-2 bg-white-n d-center border min-w-25px h-35px rounded-circle theme-hover"><i class="tio-chevron-left"></i></button>
                                        <button class="custom-next btn p-2 bg-white-n d-center border min-w-25px h-35px rounded-circle theme-hover"><i class="tio-chevron-right"></i></button></button>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-nav d-flex align-items-center justify-content-center gap-3 mt-3">
                                <button class="custom-prev btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-left"></i></button>
                                <div class="slide-counter slide-counter2"></div>
                                <button class="custom-next btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-right"></i></button></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item mb-15 custom-accordion-style bg--secondary rounded">
                    <h5 class="accordion-header mb-0">
                        <button class="accordion-button border w-100 p-15 d-flex align-items-center bg-transparent gap-xl-3 gap-2 border-0 fz-15 font-semibold collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <span class="btn p-2 d-center border w-35px h-35px rounded-circle bg-white-n theme-hover"><i class="tio-chevron-down"></i></span> How To Active Addons
                        </button>
                    </h5>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-parent="#accordionExample">
                        <div class="accordion-body bg--secondary-n pt-0 p-15">
                            <div class="bg-white-n rounded p-15">
                                <div class="mb-15">
                                    <h5 class="black-color mb-mb-0 font-normal d-block">Vendor App</h5>
                                    <p class="fz-12 text-c mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus. Nullam in feugiat est. Nam in interdum ligula, non elementum purus. Aenean eu lectus diam. To get the Vendor App <a href="#0" class="text-primary text-decoration-underline">Visit Here.</a></p>
                                </div>
                                <div class="position-relative">
                                    <div class="single-item-slider2 dots-style2 owl-carousel bg--secondary p-15">
                                        <div class="item mb-10px">
                                            <div class="text-center">
                                                <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="item mb-10px">
                                            <div class="text-center">
                                                <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="item mb-10px">
                                            <div class="text-center">
                                                <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="item mb-10px">
                                            <div class="text-center">
                                                <img src="{{asset('/public/assets/admin/img/map-img.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-nav w-100 z-999 d-flex align-items-center justify-content-between top-50 position-absolute gap-3 mt-3">
                                        <button class="custom-prev btn p-2 bg-white-n d-center border min-w-25px h-35px rounded-circle theme-hover"><i class="tio-chevron-left"></i></button>
                                        <button class="custom-next btn p-2 bg-white-n d-center border min-w-25px h-35px rounded-circle theme-hover"><i class="tio-chevron-right"></i></button></button>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-nav d-flex align-items-center justify-content-center gap-3 mt-3">
                                <button class="custom-prev btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-left"></i></button>
                                <div class="slide-counter slide-counter2"></div>
                                <button class="custom-next btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-right"></i></button></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item custom-accordion-style bg--secondary rounded">
                    <h5 class="accordion-header mb-0">
                        <button class="accordion-button border w-100 p-15 d-flex align-items-center bg-transparent gap-xl-3 gap-2 border-0 fz-15 font-semibold collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <span class="btn p-2 d-center border w-35px h-35px rounded-circle bg-white-n theme-hover"><i class="tio-chevron-down"></i></span> Why You Need to Active The Addons
                        </button>
                    </h5>
                    <div id="collapseThree" class="accordion-collapse collapse" data-parent="#accordionExample">
                        <div class="accordion-body bg--secondary-n pt-0 p-15">
                            <div class="bg-white-n rounded p-15">
                                <div class="position-relative">
                                    <div class="single-item-slider2 dots-style2 owl-carousel">
                                        <div class="item mb-10px">
                                            <div class="text-start">
                                                <h5 class="black-color font-normal mb-15 d-block">Vendor App</h5>
                                                <ol class="p-0 ps-20 d-flex flex-column gap-2">
                                                    <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus.</li>
                                                    <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio.</li>
                                                    <li class="fz-12px">Laoreet pharetra auctor eget, fringilla nec lectus. Nullam.</li>
                                                </ol>
                                            </div>
                                        </div>
                                        <div class="item mb-10px">
                                            <div class="text-start">
                                                <h5 class="black-color font-normal mb-15 d-block">Vendor App</h5>
                                                <ol class="p-0 ps-20 d-flex flex-column gap-2">
                                                    <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus.</li>
                                                    <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio.</li>
                                                    <li class="fz-12px">Laoreet pharetra auctor eget, fringilla nec lectus. Nullam.</li>
                                                </ol>
                                            </div>
                                        </div>
                                        <div class="item mb-10px">
                                            <div class="text-start">
                                                <h5 class="black-color font-normal mb-15 d-block">Vendor App</h5>
                                                <ol class="p-0 ps-20 d-flex flex-column gap-2">
                                                    <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet pharetra auctor eget, fringilla nec lectus.</li>
                                                    <li class="fz-12px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio.</li>
                                                    <li class="fz-12px">Laoreet pharetra auctor eget, fringilla nec lectus. Nullam.</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-nav d-flex align-items-center justify-content-center gap-3 mt-3">
                                <button class="custom-prev btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-left"></i></button>
                                <div class="slide-counter slide-counter2"></div>
                                <button class="custom-next btn p-2 d-center border bg-white-n w-35px h-35px rounded theme-hover"><i class="tio-chevron-right"></i></button></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

@endsection

@push('script_2')

@endpush
