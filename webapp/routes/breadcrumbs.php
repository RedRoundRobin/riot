<?php

// Auth
Breadcrumbs::for('login', function ($trail) {
    $trail->push('Accesso', route('login'));
});
Breadcrumbs::for('tfaLogin', function ($trail) {
    $trail->parent('login');
    $trail->push('Autenticazione a due fattori', route('tfaLogin'));
});

// Home
Breadcrumbs::for('dashboard.index', function ($trail) {
    $trail->push('Home', route('dashboard.index'));
});
Breadcrumbs::for('dashboard.coffee', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Errore 418', route('dashboard.index'));
});


// Impostazioni
Breadcrumbs::for('settings.edit', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Impostazioni account', route('settings.edit'));
});

// Dispositivi
Breadcrumbs::for('devices.index', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Dispositivi', route('devices.index'));
});
Breadcrumbs::for('devices.show', function ($trail, $deviceId) {
    $trail->parent('devices.index');
    $trail->push('Dispositivo #' . $deviceId, route('devices.show', ['deviceId' => $deviceId]));
});
Breadcrumbs::for('devices.create', function ($trail) {
    $trail->parent('devices.index');
    $trail->push('Aggiunta dispositivo', route('devices.create'));
});
Breadcrumbs::for('devices.edit', function ($trail, $deviceId) {
    $trail->parent('devices.index');
    $trail->push('Modifica dispositivo #' . $deviceId, route('devices.edit', ['deviceId' => $deviceId]));
});

// Sensori
Breadcrumbs::for('sensors.show', function ($trail, $deviceId, $sensorId) {
    $trail->parent('devices.show', $deviceId);
    $trail->push('Sensore @' . $sensorId, route('sensors.show', ['deviceId' => $deviceId, 'sensorId' => $sensorId]));
});

// Alerts
Breadcrumbs::for('alerts.index', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Alerts', route('alerts.index'));
});
Breadcrumbs::for('alerts.create', function ($trail) {
    $trail->parent('alerts.index');
    $trail->push('Creazione alert', route('alerts.create'));
});
Breadcrumbs::for('alerts.edit', function ($trail, $alertId) {
    $trail->parent('alerts.index');
    $trail->push('Modifica alert #', route('alerts.edit', ['alertId' => $alertId]));
});

// Utenti
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Gestione Utenti', route('users.index'));
});
Breadcrumbs::for('users.show', function ($trail, $userId) {
    $trail->parent('users.index');
    $trail->push('Utente #' . $userId, route('users.show', ['userId' => $userId]));
});
Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('Creazione utente', route('users.create'));
});
Breadcrumbs::for('users.edit', function ($trail, $userId) {
    $trail->parent('users.show', $userId);
    $trail->push('Modifica utente #' . $userId, route('users.edit', ['userId' => $userId]));
});

// Gateway
Breadcrumbs::for('gateways.index', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Gestione gateway', route('gateways.index'));
});
Breadcrumbs::for('gateways.show', function ($trail, $gatewayId) {
    $trail->parent('gateways.index');
    $trail->push('Gateway ' . $gatewayId, route('gateways.show', ['gatewayId' => $gatewayId]));
});
Breadcrumbs::for('gateways.create', function ($trail) {
    $trail->parent('gateways.index');
    $trail->push('Creazione gateway', route('gateways.create'));
});
Breadcrumbs::for('gateways.edit', function ($trail, $gatewayId) {
    $trail->parent('gateways.index');
    $trail->push('Modifica gateway #' . $gatewayId, route('gateways.edit', ['gatewayId' => $gatewayId]));
});

// Enti
Breadcrumbs::for('entities.index', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Gestione enti', route('entities.index'));
});

Breadcrumbs::for('entities.show', function ($trail, $entityId) {
    $trail->parent('entities.index');
    $trail->push('Ente #' . $entityId, route('entities.show', ['entityId' => $entityId]));
});

Breadcrumbs::for('entities.create', function ($trail) {
    $trail->parent('entities.index');
    $trail->push('Creazione ente', route('entities.create'));
});

Breadcrumbs::for('entities.edit', function ($trail, $entityId) {
    $trail->parent('entities.index');
    $trail->push('Modifica ente #' . $entityId, route('entities.edit', ['entityId' => $entityId]));
});

//Views
Breadcrumbs::for('views.index', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Pagine view', route('views.index'));
});
Breadcrumbs::for('views.show', function ($trail, $viewId) {
    $trail->parent('views.index');
    $trail->push('View #' . $viewId, route('views.show', ['viewId' => $viewId]));
});

//Logs
Breadcrumbs::for('logs.index', function ($trail) {
    $trail->parent('dashboard.index');
    $trail->push('Logs', route('logs.index'));
});
