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
    $anon = 0;
    if (isset($_POST["anon"])){
        $anon = 1;
        echo "WAS ANON";
    }
    $sql =
        "INSERT INTO {$p}qs_question (id, module_id, user_id, question_text, date_created, anonymous) 
    VALUES (NULL, '0', '$USER->id', '$question', '2020-03-06', '$anon')";
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
    global $rows;
    if ($rows) {
        foreach ($rows as $row) {
            $id = $row['id'];
            $question = $row['question_text'];
            $date = $row['date_created'];
            $anon = $row['anonymous'];
            $user = $row["user_id"];
            $username = "Anonymous";
            if ($anon != 1){
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
            echo "
                    <li class='collection-item avatar'>
                        <button class='circle red button-color'>
                            <p>+
                            <br>
                            $anon</p>
                        </button>

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