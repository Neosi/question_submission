<?php
require_once "../config.php";

use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;


$LTI = LTIX::requireData();

$path = U::rest_path();

$question = U::get($_POST, 'question');
if (is_string($question) && strlen($question) > 0) {
    $anon = 0;
    if (isset($_POST["anon"])) {
        $anon = 1;
    }
    $question = urlencode($question);
    $sql =
        "   INSERT INTO {$CFG->dbprefix}qs_question (id, module_id, user_id,   question_text, date_created, anonymous, link_id) 
            VALUES (:id, :module_id, :user_id, :question, :date, :anon, :link_id)";
    $values = array(
        ':id' => null,
        ':module_id' => '0',
        ':user_id' => $LTI->user->id,
        ':link_id' => $LTI->link->id,
        ':question' => $question,
        ':date' => '2020-03-06',
        ':anon' => $anon
    );

    $retval = $PDOX->queryDie($sql, $values);
    echo ("What the fricka dicka");
    return;
}




$sql = "SELECT q.id, u.displayname, q.upvotes, q.question_text, q.date_created, q.status, q.anonymous, q.user_id
    FROM {$CFG->dbprefix}qs_question AS q
    INNER JOIN {$CFG->dbprefix}lti_user AS u ON q.user_id = u.user_id
    WHERE q.link_id = :link_id
";

$values = array(
    ':link_id' => $LTI->link->id,
);

$rows = $PDOX->allRowsDie($sql, $values);

echo (json_encode($rows, JSON_PRETTY_PRINT));
