<?php

namespace Modules\AwgCloud\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BusinessSetting;
use App\Models\DeliveryMan;
use App\Models\Store;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

class AwgCloudController extends Controller
{
    public function index()
    {
        $bSetting = BusinessSetting::where('key', 'awg_cloud')->first();

        if (empty($bSetting)) {
            $awg = ['status' => '0','apiurl' => '','token' => '','otp_template' => 'OTP code: *#OTP#*','test_number' => ''];
            BusinessSetting::insert(['key' => 'awg_cloud', 'value' => json_encode($awg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
        } else {
            $awg = json_decode($bSetting->value, true);
        }

        $module_type = 'settings';
        return view('awgcloud::index', compact('awg', 'module_type'));
    }

    public function update(Request $request) {
        $input = $request->json()->all() ?: $request->all();
        $input['status'] ??= '0';
        $newStatus = $input['status'];

        $bSetting = BusinessSetting::where('key', 'awg_cloud')->first();

        if (empty($bSetting)) {
            $oldStatus = '0';
            $newValue = [
                'status' => $newStatus,
                'apiurl' => $input['apiurl'] ?? '',
                'token' => $input['token'] ?? '',
                'otp_template' => $input['otp_template'] ?? 'OTP code: *#OTP#*',
                'test_number' => $input['test_number'] ?? '',
            ];
            
            BusinessSetting::insert(['key' => 'awg_cloud', 'value' => json_encode($newValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

            $this->updateMainCode($newStatus, $oldStatus);
        } else {
            $awg = json_decode($bSetting->value, true);
            $oldStatus = $awg['status'] ?? '0';

            $fields = array_keys($awg);
            $updates = array_intersect_key($input, array_flip($fields));

            foreach ($updates as $key => $value) {
                if (isset($value) && $awg[$key] !== $value) $awg[$key] = $value;
            }

            $bSetting->value = json_encode($awg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $bSetting->save();

            $this->updateMainCode($newStatus, $oldStatus);
        }

        Toastr::success(translate('messages.settings_updated'));
        return back();
    }

    private function updateMainCode($newStatus, $oldStatus) {
        if ($newStatus == $oldStatus) return;

        if ($newStatus == '0') {
            $this->removeAwgCode();
        } else if ($newStatus == '1') {
            $this->addAwgCode();
        }
    }

    private function removeAwgCode() {
        $pathFiles = [app_path('Traits/NotificationTrait.php'), app_path('CentralLogics/Helpers.php'), app_path('CentralLogics/SMS_module.php'), app_path('Traits/SmsGateway.php')];
        foreach ($pathFiles as $pathFile) {
            $content = $this->getFileContent($pathFile);
            $lines = explode("\n", $content);
            $needle = "AwgCloud";
            $filtered = array_filter($lines, function ($line) use ($needle) {
                return !Str::contains($line, $needle);
            });
            $newFileString = implode("\n", $filtered);
            File::put($pathFile, $newFileString);
        }
    }

    private function addAwgCode() {
        $pathFiles = [app_path('Traits/NotificationTrait.php'), app_path('CentralLogics/Helpers.php')];
        foreach ($pathFiles as $pathFile) {
            $content = $this->getFileContent($pathFile);
            $search = "\$config = self::get_business_settings('push_notification_service_file_content');";
            $replace = <<<CODE
if (\Nwidart\Modules\Facades\Module::find('AwgCloud')?->isEnabled()) \Modules\AwgCloud\Http\Controllers\AwgCloudController::sendNotification(\$data);
        \$config = self::get_business_settings('push_notification_service_file_content');
CODE;
            // Replace only the first occurrence
            if (!Str::contains($content, $replace)) {
                $newFileString = preg_replace('/' . preg_quote($search, '/') . '/', $replace, $content, 1);
                File::put($pathFile, $newFileString);
            }
        }

        $pathSmsModules = [app_path('CentralLogics/SMS_module.php'), app_path('Traits/SmsGateway.php')];
        foreach ($pathSmsModules as $pathFile) {
            $content = $this->getFileContent($pathFile);
            $search = "\$config = self::get_settings('twilio');";
            $replace = <<<CODE
if (\Nwidart\Modules\Facades\Module::find('AwgCloud')?->isEnabled()) { \$response = \Modules\AwgCloud\Http\Controllers\AwgCloudController::sendOTP(\$receiver, \$otp); if (\$response === 'success') return \$response; }
        \$config = self::get_settings('twilio');
CODE;
            // Replace only the first occurrence
            if (!Str::contains($content, $replace)) {
                $newFileString = preg_replace('/' . preg_quote($search, '/') . '/', $replace, $content, 1);
                File::put($pathFile, $newFileString);
            }
        }
    }

    private function getFileContent($path) {
        if (Str::endsWith($path, ['Helpers.php','SMS_module.php'])) {
            if (!File::exists($path)) {
                $exploded = explode('/', $path);
                $filename = strtolower(end($exploded));
                $path = Str::replace(['Helpers.php','SMS_module.php'], $filename, $path);
            }
        }

        return File::get($path);
    }

    public static function sendOtp($receiver, $otp) {
        $bSetting = BusinessSetting::where('key', 'awg_cloud')->first();
        $awg = $bSetting ? json_decode($bSetting->value, true) : [];
        if (empty($awg)) {
            return 'AWG settings not found';
        } else if ($awg['status'] == 1) {
            $apiurl = $awg['apiurl'] ?? 'https://arrocy.com/api/send';
            $token = $awg['token'] ?? null;
            $msgtext = Str::replace("#OTP#", $otp, $awg['otp_template']);
            if (empty($token)) return 'AWG token not found';

            $payload = ['receiver' => $receiver, 'token' => $token, 'msgtext' => $msgtext];

            $response = Http::withOptions(['verify' => false])->withHeaders(['Content-Type' => 'application/json'])->post($apiurl, $payload);
            $res = json_decode($response, true);
            $msgid = $res['id'] ?? $res['key']['id'] ?? $msg[0]['id'] ?? null;
            if (empty($res['error']) && $msgid) {
                return 'success';
            } else {
                return 'error';
            }
        }
    }

    public static function sendNotification($data)
    {
        $bSetting = BusinessSetting::where('key', 'awg_cloud')->first();
        $awg = $bSetting ? json_decode($bSetting->value, true) : [];
        if (empty($awg)) {
            return response()->json(['error' => 'NO-SETTINGS', 'message' => 'AWG settings not found'], 404);
        } else if ($awg['status'] == 1) {
            $apiurl = $awg['apiurl'] ?? 'https://arrocy.com/api/send';
            $token = $awg['token'] ?? null;
            if (empty($token)) return response()->json(['error' => 'NO-TOKEN', 'message' => 'AWG token not found'], 404);
            $data['token'] = $token;
            $payload = self::processData($data);
            if (isset($payload['error'])) return response()->json($payload, 404);

            return Http::withOptions(['verify' => false])->withHeaders(['Content-Type' => 'application/json'])->post($apiurl, $payload);
        }
    }

    private static function processData($data)
    {
        $token = $data['token'];
        $msg_topic = $data['message']['topic'] ?? null;
        $msg_token = $data['message']['token'] ?? null;
        $msg_data = $data['message']['data'];
        $payload = [];

        if (isset($msg_topic)) {
            if ($msg_topic === 'admin_message') {
                $admin_phone = Admin::first()->phone;
                if (empty($admin_phone)) return ['error' => 'ADMIN-PHONE-NOT-FOUND', 'message' => 'Admin phone number not found'];
                $payload['receiver'] = $admin_phone;
            } else if (Str::startsWith($msg_topic, 'store_panel_')) {
                $store_id = (int) Str::after($msg_topic, 'store_panel_');
                $store_phone = Store::find($store_id)->phone ?? null;
                if (empty($store_phone)) return ['error' => 'STORE-PHONE-NOT-FOUND', 'message' => 'Store phone number not found'];
                $payload['receiver'] = 'store_' . $store_id;
            } else if (Str::startsWith($msg_topic, 'restaurant_dm_')) {
                $store_id = (int) Str::after($msg_topic, 'restaurant_dm_');
                $store_phone = Store::find($store_id)->phone ?? null;
                if (empty($store_phone)) return ['error' => 'STORE-PHONE-NOT-FOUND', 'message' => 'Store phone number not found'];
                $payload['receiver'] = 'store_' . $store_id;
            } else if (Str::startsWith($msg_topic, 'delivery_man_')) {
                $zone_id = (int) Str::after($msg_topic, 'delivery_man_');
                $delivery_men = DeliveryMan::where('zone_id', $zone_id)->get();
                if ($delivery_men->count() == 0) return ['error' => 'DELIVERY-MAN-NOT-FOUND', 'message' => 'No delivery man found for the specified zone'];
                $delivery_men_phones = $delivery_men->pluck('phone')->filter()->toArray();
                $payload['receiver'] = implode(',', $delivery_men_phones);
            } else {
                return ['error' => 'INVALID-TOPIC', 'message' => 'Invalid message topic'];
            }
        } else if (isset($msg_token)) {
            $user = User::where('cm_firebase_token', $msg_token)->first();
            if (empty($user)) return ['error' => 'USER-NOT-FOUND', 'message' => 'User not found for the provided token'];
            $payload['receiver'] = $user->phone;
        } else {
            return ['error' => 'NO-RECEIVER', 'message' => 'No receiver information found in the message'];
        }

        $title = $msg_data['title'] ? "*{$msg_data['title']}*" . "\n" : '';
        $body = $msg_data['body'] ?? '';
        $msgtext = $title . $body;

        $payload['token'] = $token;
        $payload['msgtext'] = $msgtext;
        if (isset($msg_data['image'])) $payload['mediaurl'] = $msg_data['image'];

        return $payload;
    }
}
