<?php
require_once "../config.php";
include_once "./api.php";

use \Tsugi\Util\LTI;
use \Tsugi\Util\PDOX;
use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;
use \Tsugi\Core\Link;
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

<link rel="stylesheet" type="text/css" href="style.css" />
<link id='style-set' rel="stylesheet" type="text/css" href="light.css" />
<link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<div class='background'></div>
<div class='encap'>
    <div class='container evspace'>
        <div class="col s12 m7">
            <div class="card horizontal">
                <div class="card-content fill-available">
                    <form action="index.php" method="post">
                        <input type="text" name="question"> </input>
                        <label>
                            <input type="checkbox" name="anon" />
                            <span>Anon</span>
                        </label>
                        <button class="btn waves-effect waves-light button red" type="submit" name="action">Ask
                            <i class="material-icons right">send</i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <ul class="collection raised container">

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
                                        <button type='submit' class='secondary-content btn red'><i class='material-icons'>clear</i></button>
                                    </form>";
                } else {
                    $actions = "";
                }


                $sql = "SELECT COUNT(*) FROM {$p}qs_vote WHERE question_id = $id";
                $result = $PDOX->rowDie($sql);
                $count = $result['COUNT(*)'];

                $upvotes = $row['upvotes'];

                echo "
                    <li class='collection-item avatar'>
                        <form action='index.php' method='post'>
                            <input type='hidden' value=$USER->id name='upvoter_id'>
                            <input type='hidden' value=$id name='question_id'>
                            <button type='submit' class='circle red button-color'>
                                <p>+
                                <br>
                                $upvotes</p>
                            </button>
                        </form>
                        <span class='title'>$username</span>
                        <p>$question</p>
                        $actions
                    </li>
                    ";
            }
        } else {
            echo "<li class='collection-item'>No questions yet!</li>";
        }
        ?>
        <?php
        //global $rows; 
        //echo json_encode($rows);
        ?>
    </ul>
</div>
</div>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript">
    // Set page refresh on timer
    // setInterval(function() {
    //   location = ''
    // }, 20000)
    function toggle() {
        var a = document.getElementById("style-set");
        a.x = 'dark' == a.x ? 'light' : 'dark';
        a.href = a.x + '.css';
    }
</script>