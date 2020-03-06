<?php
require_once "../config.php";

use \Tsugi\Util\LTI;
use \Tsugi\Util\PDOX;
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\SettingsForm;
use \Tsugi\UI\Output;

$LAUNCH = LTIX::requireData();
$p = $CFG->dbprefix;
$sql = "SELECT * FROM {$p}qs_question ";
$rows = $PDOX->allRowsDie($sql);

if (isset($_POST["question"])) {
    echo "POST IS SET";
    $question = $_POST["question"];
    $sql =
        "INSERT INTO {$p}qs_question (id, module_id, user_id, question_text, date_created) 
    VALUES (NULL, '0', '$USER->id', '$question', CURDATE())";
    $result = $PDOX->queryDie($sql);
}
?>

<link rel="stylesheet" type="text/css" href="style.css">
<div class="body">
    <div class='input-outer'></div>

    <form action="index.php" method="post">

        <input class='input' type="text" name="question"> </input>
        <input type='checkbox' class='checkbox'></input>
        <p class='checkbox-text'>Anonymous</p>
        <button type='submit' class='button'>Ask</button>
    </form>
    <div class='qlist'>
        <div class='grid'>
            <?php
            global $rows;

            if ($rows) {
                foreach ($rows as $row) {
                    $id = $row['id'];
                    $question = $row['question_text'];
                    $date = $row['date_created'];
                    $user = $row["user_id"];
                    $sql = "SELECT * FROM {$p}lti_user WHERE user_id = $user";
                    $result = $PDOX->allRowsDie($sql);
                    $username = $result[0]['displayname'];

                    echo "  
                            <div class='grid-left'>$username</div>
                            <div class='grid-center'>$question</div>
                            <div class='grid-right'>$date</div>
                        ";
                }
            } else {
                echo "No questions yet!";
            }
            ?>
        </div>
    </div>
</div>