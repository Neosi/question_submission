<?php

global $LAUNCH;
$rows = API::refresh($LAUNCH->link->id); 

foreach ($rows as $row) {
    $id = $row['id'];
    $question = urldecode($row['question_text']);
    $date = $row['date_created'];
    $anon = $row['anonymous'];
    $user = $row["quser_id"];
    $username = "Anonymous";
    if ($anon != 1 || $LAUNCH->user->instructor) {
        $sql = "SELECT * FROM {$p}lti_user WHERE user_id = $user";
        $result = $PDOX->allRowsDie($sql);
        $username = $result[0]['displayname'];
    }          

    if ($user == $USER->id || $LAUNCH->user->instructor) {
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
                    <p class='question'>$question</p>
                    $actions
                </li>
                ";
}


if (!$rows) {
    echo "<li class='collection-item'>No questions yet!</li>";
}

