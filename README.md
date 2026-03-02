# NEW Release (Available Soon...)! Version-Agnostic.
Compatible with multiple versions of 6amMart.
Please report compatibility with v2.9 and below!
# Warning!
If you have installed old version of 6amMart Modification from me before, please remove the modifications or overwrite the 5 modified files with 6amMart original files.
If you don't, you will send double notifications.
# Installation
1. Upload and extract the zip file on to 6amMart root folder.
2. You will see 'AwgCloud' folder created inside /Module folder.
3. Login as admin to 6amMart.
4. Go to System Management Settings -> Addon Activation -> Awg Cloud
5. If you see AWG Cloud [Installed : Not Activated], please refresh the page!
6. Click View -> enter AWG Cloud Token -> enter Test phone number -> click Send Test Message button.
7. Send Test Message -> OK -> click Slider/Checkbox to enable the module.
8. SAVE!
# How to use
Settings for OTP and Order Notifications are integrated with 6amMart Business Management Settings.
# Below is tutorial for older version, will be removed when new release is up and ready.
=========================================================================
# 6amMart-awgCloud-mod (OLD VERSION)
Modification files to 6amMart for adding whatsapp notifications and Whatsapp OTP
# PLEASE BACKUP THESE 5 ORIGINAL FILES
1. /app/CentralLogics/Helpers.php
2. /app/CentralLogics/SMS_module.php
3. /app/Http/Controllers/Admin/BusinessSettingsController.php
4. /app/Http/Controllers/Admin/SMSModuleController.php
5. /resources/views/admin-views/business-settings/fcm-config.blade.php
# Installation
1. Download the zip file, Upload it to root of 6amMart site, Extract.
2. Clear cache (delete all files inside /storage/framework/views/ folder)
3. Go to (SYSTEM MANAGEMENT) menu "3rd party & configurations" >> "Firebase Notification" >> "Firebase Configuration"
4. Fill TOKEN (get it from arrocy.com)
5. Click "Submit" to save the settings
# ATTENTION!!!!
In order to show the MODIFIED "Notification Settings" page, You will need to clear cache.
If 6amMart does not have clear-cache button, then do this:
- open file manager, browse to /storage/framework/views/ folder
- delete all files in there
# How to use
1. Go to (SYSTEM MANAGEMENT) menu "3rd party & configurations" >> "Firebase Notification" >> "Firebase Configuration"
2. Fill TOKEN (get it from arrocy.com)
3. Click "Submit" to save the settings
# How to custom the message
1. Go to (SYSTEM MANAGEMENT) menu "3rd party & configurations" >> "Firebase Notification" >> "Push Notification"
2. Edit the default messages
