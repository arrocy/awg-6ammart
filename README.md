# NEW Release! Version-Agnostic.
This is a real Laravel-Module, not file modifications like before.
Compatible with multiple versions of 6amMart. Tested with 6amMart v3.x.
Please report compatibility with v2.9 and older!
# Versioning number
Since this is a new Module, Version will start from v1.
New version number does not reflect on the 6amMart version.
If you update 6amMart, you need to re-upload just 1 file: /resources/views/admin-views/addon-activation/index.blade.php
This page is the Awg Cloud Module settings.
Alternate Awg Cloud Module settings page is https://6ammart-domain.com/admin/awgcloud
# Warning!
If you have installed old version of 6amMart Modification from me before, please remove the modifications or overwrite the 5 modified files with 6amMart original files.
If you don't, you will send double notifications.
# Installation
1. Upload and extract the zip file on to 6amMart root folder.
2. You will see 'AwgCloud' folder created inside /Module folder.
3. Login as admin to 6amMart.
4. Go to System Management Settings -> Addon Activation -> Awg Cloud
5. If you see AWG Cloud [Module Installed : Module Not Activated], please refresh the page!
6. Click View -> enter AWG Cloud Token -> enter Test phone number -> click Send Test Message button.
7. Send Test Message -> OK -> click Slider/Checkbox to enable the module.
8. SAVE!
# How to use
Settings for OTP and Order Notifications are integrated with 6amMart Business Management Settings.
=========================================================================
# IF YOU INSTALLED OLD VERSION (v2.9-V3.5), REPLACE 5 MODIFIED FILES WITH ORIGINAL FILES
1. /app/CentralLogics/Helpers.php
2. /app/CentralLogics/SMS_module.php
3. /app/Http/Controllers/Admin/BusinessSettingsController.php
4. /app/Http/Controllers/Admin/SMSModuleController.php
5. /resources/views/admin-views/business-settings/fcm-config.blade.php
