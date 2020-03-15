<?php
require_once "../config.php";
include_once "./api.php";

use \Tsugi\Core\LTIX;

$LAUNCH = LTIX::requireData();
$p = $CFG->dbprefix;
$link_id = $LAUNCH->link->id;

//API Calls
if (isset($_POST["question"])) {
    API::addQuestion($_POST["question"]);
} else if (isset($_POST["upvoter_id"])) {
    API::upvote($_POST['upvoter_id'], $_POST['question_id']);
} else if (isset($_POST["remove_id"])) {
    API::removeQuestion($_POST["remove_id"]);
}
$OUTPUT->header();
?>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link id='style-set' rel="stylesheet" type="text/css" href="light.css" />
<link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<?php $OUTPUT->bodyStart();?>
<div class='background'></div>
<div class='container encap'>
    <div class='evspace'>
        <div class="col s12 m7">
            <div class="card horizontal">
                <div class="card-content fill-available">
                    <form action="index.php" method="post">
                        <input type="text" name="question" id='questionarea'> </input>
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
    <form action="index.php">
        <button class="btn waves-effect waves-light button refresh red" type="submit" name="action">
            <i class="material-icons">cached</i>
        </button>
    </form>
    <ul class="collection raised">
        <?php include "list.php"; ?>
    </ul>
</div>
</div>
<?php $OUTPUT->footerStart();?>
<script type="text/javascript" src="js/materialize.min.js"></script>