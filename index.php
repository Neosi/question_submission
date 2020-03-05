<?php
require_once "../config.php";

use \Tsugi\Util\LTI;
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\SettingsForm;
use \Tsugi\UI\Output;

$LAUNCH = LTIX::requireData();
?>

<link rel="stylesheet" type="text/css" href="style.css">
<p><?php $OUTPUT->welcomeUserCourse(); ?></p>
<div class="body">
    <div class='input-outer'>
    </div> <input class='input'> </input>
    <input type='checkbox' class='checkbox'></input>
    <p class='checkbox-text'>Anonymous</p>
    <button type='button' class='button'>Ask!</button>
    <div class='qlist'>
    </div>
</div>