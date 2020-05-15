<?php

namespace App\Http\Controllers;

use App\Providers\EntityServiceProvider;
use App\Providers\StatsServiceProvider;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $statsProvider;
    private $entityProvider;

    public function __construct()
    {
        $this->middleware('auth');
        $this->statsProvider = new StatsServiceProvider();
        $this->entityProvider = new EntityServiceProvider();
    }
    public function index()
    {
        $user = Auth::user();
        $stats = $this->statsProvider->stats();
        $entity = null;
        if ($user->getRole() != 'Amministratore') {
            $entity = $this->entityProvider->findFromUser($user->getAuthIdentifier());
        }
        return view('dashboard.index', compact([
            'user', 'entity', 'stats'
        ]));
    }
    public function coffee()
    {
        return view('dashboard.coffee');
    }
}
