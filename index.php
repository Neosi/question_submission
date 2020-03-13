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
    <ul class="collection raised container">
        <?php include "list.php";?>
    </ul>
</div>
</div>
<script type="text/javascript" src="js/materialize.min.js"></script>