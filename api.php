<?php

class API
{
    public static function refresh($link_id)
    {
        global $PDOX, $p;
        $sql = "    SELECT q.id, q.upvotes, q.question_text, q.date_created, q.status, q.anonymous, q.user_id as quser_id
                    FROM {$p}qs_question AS q
                    WHERE q.link_id = $link_id
                    ORDER BY q.upvotes DESC";
        return $PDOX->allRowsDie($sql);
    }

    public static function addQuestion($question)
    {
        global $p, $PDOX, $USER, $link_id;
        $anon = 0;
        if (isset($_POST["anon"])) {
            $anon = 1;
        }
        $sql =
            "   INSERT INTO {$p}qs_question (id, module_id, user_id, question_text, date_created, anonymous, link_id) 
                VALUES (NULL, '0', '$USER->id', '$question', '2020-03-06', '$anon', $link_id)";
        $PDOX->queryDie($sql);
    }

    public static function removeQuestion($question)
    {
        global $p, $PDOX;
        $sql = "DELETE FROM {$p}qs_question WHERE id = $question";
        $PDOX->queryDie($sql);
    }

    public static function upvote($user, $question)
    {
        global $p, $PDOX;

        $question = $_POST["question_id"];
        $user = $_POST["upvoter_id"];

        $sql = "SELECT * FROM {$p}qs_vote WHERE question_id = $question AND user_id = $user";
        $result = $PDOX->allRowsDie($sql);

        if ($result) {
            $sql = "DELETE FROM {$p}qs_vote WHERE question_id = $question AND user_id = $user";
            $result = $PDOX->queryDie($sql);
            $sql = "UPDATE {$p}qs_question SET upvotes = upvotes - 1 WHERE id = $question";
            $result = $PDOX->queryDie($sql);
        } else {
            $sql = "INSERT INTO {$p}qs_vote (user_id, question_id) VALUES ('$user', '$question')";
            $result = $PDOX->queryDie($sql);
            $sql = "UPDATE {$p}qs_question SET upvotes = upvotes + 1 WHERE id = $question";
            $result = $PDOX->queryDie($sql);
        }
    }
}