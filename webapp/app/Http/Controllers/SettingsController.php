<?php
// @codingStandardsIgnoreFile

namespace App\Http\Controllers;

use App\Providers\AlertServiceProvider;
use App\Providers\DeviceServiceProvider;
use App\Providers\SensorServiceProvider;
use App\Providers\UserServiceProvider;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $alertsProvider;
    private $devicesProvider;
    private $sensorsProvider;

    public function __construct()
    {
        $this->middleware('auth');
        $this->alertsProvider = new AlertServiceProvider();
        $this->devicesProvider = new DeviceServiceProvider();
        $this->sensorsProvider = new SensorServiceProvider();
    }

    public function edit()
    {
        $user = Auth::user();
        $alerts = $this->alertsProvider->findAll() ?? [];
        $alertsWithSensors = [];
        $sensorsCache = [];
        $devicesCache = [];
        foreach ($alerts as $state => $alertsList) {
            foreach ($alertsList as $alert) {
                key_exists($alert->sensor, $sensorsCache) ? $sensor = $sensorsCache[$alert->sensor]
                    : $sensor = $this->sensorsProvider->findFromLogicalId($alert->sensor);

                key_exists($sensor->device, $devicesCache) ? $device = $devicesCache[$sensor->device]
                    : $device = $this->devicesProvider->find($sensor->device);
                $alertsWithSensors[$state][] = [
                    'alert' => $alert,
                    'sensor' => $sensor,
                    'device' => $device
                ];
            }
        }
        return view('settings.edit', compact(['user','alertsWithSensors']));
    }

    public function update()
    {
        $user = Auth::user();
        $data = request()->validate([
            'email' => 'email',
            'telegramName' => 'nullable|string|required_if:tfa,==,true',
            'tfa' => 'nullable|in:true',
            'password' => 'required_with:new_password',
            'new_password' => 'required_with:password|min:6',
            'confirm_password' => 'required_with:new_password|same:new_password'
        ]);

        if (key_exists('tfa', $data)) {
            $data['tfa'] = boolval($data['tfa']);
        } else {
            $data['tfa'] = false;
        }

        if ((key_exists('password', $data) && hash('sha512', $data['password']) == $user->getAuthPassword()) || !key_exists('password', $data)) {
            if (key_exists('tfa', $data)) {
                $data['tfa'] = boolval($data['tfa']);
            }
            if (key_exists('telegramName', $data)) {
                if ($data['telegramName'] != $user->getTelegramName()  || is_null($user->getChatId())) {
                    $data['tfa'] = false;
                }
            }
            $data = array_diff_assoc($data, $user->getAttributes());
            if (key_exists('new_password', $data)) {
                $data['password'] = $data['new_password'];
            }
            if (key_exists('password', $data)) {
                $data['password'] = hash('sha512', $data["password"]);
            }
            $service = new UserServiceProvider();
            return $service->update($user->getAuthIdentifier(), $data) ?
                redirect('/settings/edit')->withErrors(['GoodUpdate' => 'Impostazioni aggiornate con successo']) :
                redirect('/settings/edit')->withErrors(['NotUpdate' => 'Impostazioni non aggiornate']);
        }
        return redirect('/settings/edit')->withErrors(['password' => trans('passwords.oldNotValid')]);
    }

    public function updateAlerts()
    {
        $alerts = $this->alertsProvider->findAll();
        $data = request()->validate([
            'alerts.*' => 'required|numeric'
        ]);
        if (key_exists('alerts', $data)) {
            $data = $data['alerts'];
        }
        $enable = [];
        $disable = [];
        foreach ($alerts['enable'] as $a) {
            $enable[] = $a->alertId;
        }
        foreach ($alerts['disable'] as $a) {
            $disable[] = $a->alertId;
        }
        $toEnable = array_diff($data, $enable);
        $toDisable = array_diff($enable, $data);
        $check = true;
        foreach ($toEnable as $e) {
            !$this->alertsProvider->enable($e) ? $check = false : "";
        }
        foreach ($toDisable as $d) {
            !$this->alertsProvider->disable($d) ? $check = false : "";
        }
        return $check ?
            redirect('/settings/edit')->withErrors(['GoodUpdate' => 'Alerts aggiornate con successo']) :
            redirect('/settings/edit')->withErrors(['NotUpdate' => 'Alerts non aggiornate']);
    }
}
