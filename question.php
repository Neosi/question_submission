<?php
require_once "../config.php";

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;

$max_seconds = 60000;
$present_max = 60;

//$LTI = LTIX::requireData();

$data = "hello";
header('Content-Type: application/json');
echo json_encode($data);
