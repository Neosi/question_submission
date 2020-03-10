<?php
require_once "../config.php";
include_once "./api.php";

use \Tsugi\Util\LTI;
use \Tsugi\Util\PDOX;
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
use \Tsugi\UI\SettingsForm;
use \Tsugi\UI\Output;

$LAUNCH = LTIX::requireData();
$p = $CFG->dbprefix;

//Initial fetch
refresh();

//API Calls
if (isset($_POST["question"])) {
    addQuestion();
    refresh();
}

if (isset($_POST["upvoter_id"])) {
    upvote($_POST['upvoter_id'], $_POST['question_id']);
    refresh();
}

if (isset($_POST["remove_id"])) {
    removeQuestion();
    refresh();
}
?>

<script type="text/javascript">
    // Set page refresh on timer
    // setInterval(function() {
    //   location = ''
    // }, 20000)
</script>
<link rel="stylesheet" type="text/css" href="style.css" />
<link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<div class='input-outer'></div>
<div class='background'></div>
<form action="index.php" method="post">
    <input class='input' type="text" name="question"> </input>
    <p>
        <label class="checkbox">
            <input class="checkbox" type="checkbox" name="anon" />
            <span>Anon</span>
        </label>
    </p>
    <button class="btn waves-effect waves-light button red" type="submit" name="action">Ask
        <i class="material-icons right">send</i>
    </button>
</form>
<ul class="collection container list-layout">

    <?php
    // Mapping the fetched questions to the view

    global $rows, $countdict;

    if ($rows) {
        foreach ($rows as $row) {
            $id = $row['id'];
            $question = $row['question_text'];
            $date = $row['date_created'];
            $anon = $row['anonymous'];
            $user = $row["quser_id"];
            $username = "Anonymous";
            if ($anon != 1) {
                $sql = "SELECT * FROM {$p}lti_user WHERE user_id = $user";
                $result = $PDOX->allRowsDie($sql);
                $username = $result[0]['displayname'];
            }

            if ($user == $USER->id) {
                $actions = "<form action='index.php' method='post'>
                                        <input type='hidden' value=$id name='remove_id'>
                                        <button type='submit' class='secondary-content btn red'><i class='material-icons right'>clear</i></button>
                                    </form>";
            } else {
                $actions = "";
            }
            

            $sql = "SELECT COUNT(*) FROM {$p}qs_vote WHERE question_id = $id";
            $result = $PDOX->rowDie($sql);
            $count = $result['COUNT(*)'];
            
            echo "
                    <li class='collection-item avatar'>
                        <form action='index.php' method='post'>
                            <input type='hidden' value=$USER->id name='upvoter_id'>
                            <input type='hidden' value=$id name='question_id'>
                            <button type='submit' class='circle red button-color'>
                                <p>+
                                <br>
                                $count</p>
                            </button>
                        </form>
                        <span class='title'>$username</span>
                        <p>$question</p>
                        $actions
                    </li>
                    ";
        }
    } else {
        echo "<div class='grid-left'>No questions yet!</div>";
    }
    ?>
    <?php
    //global $rows; 
    //echo json_encode($rows);
    global $countdict;


    foreach ($countdict as $key => $value) {
        echo "Key: $key; Value: $value\n";
    }


    ?>

    <ul class="pagination pagination-color">
        <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
        <li class="active"><a href="#!">1</a></li>
        <li class="waves-effect"><a href="#!">2</a></li>
        <li class="waves-effect"><a href="#!">3</a></li>
        <li class="waves-effect"><a href="#!">4</a></li>
        <li class="waves-effect"><a href="#!">5</a></li>
        <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
    </ul>
</ul>
<script type="text/javascript" src="js/materialize.min.js"></script>
</div>