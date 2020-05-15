<!-- Sidebar -->
<!-- Classe toggled to set the closed sidebar -->
<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard.index') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-project-diagram thirema-logo-color-light"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            <span class="thirema-logo-special-font thirema-logo-color-blue">r</span>
            <span class="thirema-logo-special-font thirema-logo-color-light">iot</span>
        </div>
        @can(['isAdmin'])
        <div class="thirema-logo-subtext d-none d-md-block">
            admin
        </div>
        @endcan
        @can(['isMod'])
            <div class="thirema-logo-subtext thirema-logo-subtext-mod d-none d-md-block">
                mod
            </div>
        @endcan
    </a>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Generale
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>


    <li class="nav-item">
        <a class="nav-link" href="{{ route('settings.edit') }}">
            <i class="fas fa-fw fa-cog"></i>
            <span>Impostazioni</span>
        </a>
    </li>
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Dati
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('devices.index') }}">
            <i class="fas fa-fw fa-microchip text-light"></i>
            <span>Dispositivi e sensori</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('views.index') }}">
            <i class="fas fa-fw fa-chart-bar text-warning"> </i>
            <span>Pagine view</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('alerts.index') }}">
            <i class="fas fa-fw fa-bell text-light"> </i>
            <span>Alerts</span>
        </a>
    </li>

    @can('isMod')
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Moderatore
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users text-success"></i> <span>Gestione utenti</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('logs.index') }}"><i class="fas fa-list-alt text-success"></i> <span>Logs</span></a>
    </li>

    @endcan


    @can('isAdmin')

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Admin
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-fw fa-users text-danger"></i> <span>Gestione utenti</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('entities.index') }}"><i class="far fa-fw fa-building text-danger"></i> <span>Gestione enti</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('gateways.index') }}"><i class="fas fa-fw fa-dungeon text-danger"></i> <span>Gestione gateways</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logs.index') }}"><i class="fas fa-fw fa-list-alt text-danger"></i> <span>Logs</span></a>
    </li>

    @endcan


    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->

