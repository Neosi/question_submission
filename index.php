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

if (isset($_POST["question"])){
    echo "POST IS SET";
    $question = $_POST["question"];
    $sql = 
    "INSERT INTO {$p}qs_question (id, module_id, user_id, question_text, date_created) 
    VALUES (NULL, '0', '3', '$question', '2020-03-04')";
    $result = $PDOX->queryDie($sql);
}
?>

<link rel="stylesheet" type="text/css" href="style.css">
<p><?php $OUTPUT->welcomeUserCourse(); ?></p>
<div class="body">
    <div class='input-outer'></div>
    
    <form action="index.php" method="post">

     <input class='input' type="text" name="question"> </input>
        <input type='checkbox' class='checkbox'></input>
        <p class='checkbox-text'>Anonymous</p>
        <button type='submit' class='button'>Ask</button>
    </form>
    <div class='qlist'>
    <table class="table" style="width:100%">
  <tr>
    <th>ID</th>
    <th>User</th>
    <th>Question</th>
  </tr>
  
        <?php
        global $rows;
        
        if ($rows) {
            foreach ($rows as $row) {
                $id = $row['id']; 
                $question = $row['question_text']; 
                $user = $row["user_id"];
                $sql = "SELECT * FROM {$p}lti_user WHERE user_id = $user";
                $result = $PDOX->allRowsDie($sql);
                $username = $result[0]['displayname'];
                echo "<tr>
                        <td>$id</td>
                        <td>$username</td>
                        <td>$question</td>
                    </tr>";
            }
        } else {
            echo "No questions yet!";
        }
        echo "</table>"
        ?>
    </div>
</div>