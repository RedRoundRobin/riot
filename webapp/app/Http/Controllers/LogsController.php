<?php

namespace App\Http\Controllers;

use App\Providers\LogsServiceProvider;
use App\Providers\UserServiceProvider;

class LogsController extends Controller
{
    private $logsProvider;
    private $userProvider;

    public function __construct()
    {
        $this->middleware('auth');
        $this->logsProvider = new LogsServiceProvider();
        $this->userProvider = new UserServiceProvider();
    }

    public function index()
    {
        $list = $this->logsProvider->findAll();
        $users = [];
        foreach ($list as $l) {
            $l->time = date("d/m/Y - H:i:s", strtotime($l->time));
            if (key_exists($l->userId, $users)) {
                $u = $users[$l->userId];
            } else {
                $users[$l->userId] = $this->userProvider->retrieveById($l->userId);
                $u = $users[$l->userId];
            }
            $logs[] = ["user" => $u, "log" => $l];
        }
        return view('logs.index', compact('logs'));
    }
}
