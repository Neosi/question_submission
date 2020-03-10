<?php
$countdict = array();
function refresh(){
    global $rows, $p, $PDOX, $countdict;
    $sql = "SELECT q.id, q.question_text, q.date_created, q.status, q.anonymous, q.user_id as quser_id
        FROM {$p}qs_question AS q";
    $rows = $PDOX->allRowsDie($sql);

    
}

function addQuestion(){
    global $p, $PDOX, $USER;
    $question = $_POST["question"];
    $anon = 0;
    if (isset($_POST["anon"])) {
        $anon = 1;
        echo "WAS ANON";
    }
    $sql =
        "INSERT INTO {$p}qs_question (id, module_id, user_id, question_text, date_created, anonymous) 
    VALUES (NULL, '0', '$USER->id', '$question', '2020-03-06', '$anon')";
    $result = $PDOX->queryDie($sql);
}

function removeQuestion(){
    global $p, $PDOX;
    $id = $_POST["remove_id"];
    $sql = "DELETE FROM {$p}qs_question WHERE id = $id";
    $result = $PDOX->queryDie($sql);
}

function upvote($user, $question){
    global $p, $PDOX;

    $question = $_POST["question_id"];
    $user = $_POST["upvoter_id"];
    $sql = "INSERT INTO {$p}qs_vote (user_id, question_id) VALUES ('$user', '$question')";
    $result = $PDOX->queryDie($sql);
}

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
