<?php
declare(strict_types=1);

/*
 * SPMS test bootstrap.
 * This file only loads classes from the real project.
 * It does NOT change project files or database data.
 */

$projectRoot = dirname(__DIR__);

require_once $projectRoot . '/backend/core/Security.php';
require_once $projectRoot . '/backend/core/JWT.php';
require_once $projectRoot . '/backend/core/Database.php';
require_once $projectRoot . '/backend/core/Response.php';

require_once $projectRoot . '/backend/models/User.php';
require_once $projectRoot . '/backend/models/Medicine.php';
require_once $projectRoot . '/backend/models/Order.php';
require_once $projectRoot . '/backend/models/Supplier.php';

foreach (glob($projectRoot . '/backend/patterns/behavioral/*.php') as $file) {
    require_once $file;
}
foreach (glob($projectRoot . '/backend/patterns/creational/*.php') as $file) {
    require_once $file;
}
foreach (glob($projectRoot . '/backend/patterns/structural/*.php') as $file) {
    require_once $file;
}
