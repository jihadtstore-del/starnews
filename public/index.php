<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/../app/config/config.php';
$GLOBALS['config'] = $config;

require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/core/Controller.php';
require __DIR__ . '/../app/core/Auth.php';
require __DIR__ . '/../app/core/Router.php';

require __DIR__ . '/../app/models/User.php';
require __DIR__ . '/../app/models/Technician.php';
require __DIR__ . '/../app/models/Equipment.php';
require __DIR__ . '/../app/models/DailyIssue.php';
require __DIR__ . '/../app/models/IssueReturnLog.php';

require __DIR__ . '/../app/controllers/AuthController.php';
require __DIR__ . '/../app/controllers/DashboardController.php';
require __DIR__ . '/../app/controllers/TechnicianController.php';
require __DIR__ . '/../app/controllers/EquipmentController.php';
require __DIR__ . '/../app/controllers/AdminController.php';
require __DIR__ . '/../app/controllers/IssueController.php';
require __DIR__ . '/../app/controllers/AdminIssueController.php';
require __DIR__ . '/../app/controllers/ReportController.php';

$db = new Database($config['db']);

$routes = [
    'login' => [new AuthController($config, $db), 'showLogin'],
    'login-post' => [new AuthController($config, $db), 'login'],
    'logout' => [new AuthController($config, $db), 'logout'],
    'dashboard' => [new DashboardController($config, $db), 'index'],
    'admin-dashboard' => [new AdminController($config, $db), 'dashboard'],
    'issue-create' => [new IssueController($config, $db), 'createForm'],
    'issue-store' => [new IssueController($config, $db), 'store'],
    'issue-history' => [new IssueController($config, $db), 'history'],
    'technicians' => [new TechnicianController($config, $db), 'index'],
    'technician-store' => [new TechnicianController($config, $db), 'store'],
    'technician-delete' => [new TechnicianController($config, $db), 'delete'],
    'equipments' => [new EquipmentController($config, $db), 'index'],
    'equipment-store' => [new EquipmentController($config, $db), 'store'],
    'equipment-delete' => [new EquipmentController($config, $db), 'delete'],
    'admin-issues' => [new AdminIssueController($config, $db), 'index'],
    'issue-approve' => [new AdminIssueController($config, $db), 'approve'],
    'issue-reject' => [new AdminIssueController($config, $db), 'reject'],
    'issue-return' => [new AdminIssueController($config, $db), 'markReturned'],
    'reports' => [new ReportController($config, $db), 'index'],
    'reports-print' => [new ReportController($config, $db), 'printView'],
];

$route = $_GET['route'] ?? 'login';

$router = new Router($routes);
$router->dispatch($route);
