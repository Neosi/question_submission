<?php
require_once "../config.php";

use \Tsugi\Util\LTI;
use \Tsugi\Util\PDOX;
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\SettingsForm;
use \Tsugi\UI\Output;

//=========================================================
//======Fetching required data for Tsugi to function=======
//=========================================================

$LAUNCH = LTIX::requireData();
$p = $CFG->dbprefix;

//=========================================================
//======Fetching all questions=============================
//=========================================================

$sql = "SELECT * FROM {$p}qs_question ";
$rows = $PDOX->allRowsDie($sql);

//=========================================================
//=====If there is a question posted add it to the db======
//=========================================================

if (isset($_POST["question"])) {
    $question = $_POST["question"];
    $sql =
        "INSERT INTO {$p}qs_question (id, module_id, user_id, question_text, date_created) 
    VALUES (NULL, '0', '$USER->id', '$question', '2020-03-06')";
    $result = $PDOX->queryDie($sql);

    //Fetch the created row and push to current array
    $user_id = $USER->id;
    $sql = "SELECT * FROM {$p}qs_question WHERE user_id = $user_id AND question_text='$question'";
    $row = $PDOX->rowDie($sql);
    array_push($rows, $row);
}

//================================================================
//=====If there is a removal request remove question from db======
//================================================================

if (isset($_POST["remove_id"])) {
    $id = $_POST["remove_id"];
    $sql = "DELETE FROM {$p}qs_question WHERE id = $id";
    $result = $PDOX->queryDie($sql);

    $sql = "SELECT * FROM {$p}qs_question ";
    $rows = $PDOX->allRowsDie($sql);
}
?>
<script type="text/javascript">
    // setInterval(function() {
    //   location = ''
    // }, 20000)
</script>
<link rel="stylesheet" type="text/css" href="style.css"/>
<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <div class='input-outer'></div>
    <form action="index.php" method="post">
        <input class='input' type="text" name="question"> </input>
        <input type='checkbox' class='checkbox' name="anon"></input>
        <p class='checkbox-text'>Anonymous</p>
        <a type='submit' class='waves-effect waves-light btn'>button</a>
    </form>
    <div class='qlist'>
        <div class='grid'>
            <?php
            // Mapping the fetched questions to the view
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
                    if ($user == $USER->id) {
                        $actions = "<form action='index.php' method='post'>
                                        <input type='hidden' value=$id name='remove_id'>
                                        <a type='submit' class='waves-effect waves-light btn'>Remove</a>
                                    </form>";
                    } else {
                        $actions = "";
                    }

                    echo "  
                            <div class='grid-left'>$username</div>
                            <div class='grid-center'>$question</div>
                            <div class='grid-center'>$date</div>
                            <div class='grid-right'>$actions</div>
                        ";
                }
            } else {
                echo "<div class='grid-left'>No questions yet!</div>";
            }
            ?>
        </div>
    </div>
    <script type="text/javascript" src="js/materialize.min.js"></script>
</div>