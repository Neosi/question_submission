<?php
// https://github.com/tsugiproject/trophy
require_once "../config.php";
require_once "./api.php";
//use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;
use \Tsugi\Util\U;

// Handle all forms of launch
$LTI = LTIX::requireData();

$question = U::get($_POST, 'question');
if (is_string($question) && strlen($question) > 0) {
    $anon = 0;
    
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
    //return;
}

//API Calls
if (isset($_POST["upvoter_id"])) {
    API::upvote($_POST['upvoter_id'], $_POST['question_id']);
} else if (isset($_POST["remove_id"])) {
    API::removeQuestion($_POST["remove_id"]);
}

// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
?>
<link rel="stylesheet" type="text/css" href="style.css" />
<link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<?php $OUTPUT->bodyStart();?>
<div class='background'></div>
<div class='container encap'>
    <div class='evspace'>
        <div class="col s12 m7">
            <div class="card horizontal">
                <div class="card-content fill-available">
                    <form action="message.php">
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
    <ul id="questions" class="collection raised container">
        <span class="fa fa-spinner fa-pulse"></span>
    </ul>
</div>
</div>
<script type="text/javascript" src="js/materialize.min.js"></script>

<?php
$OUTPUT->footerStart();
?>

<script>
    var _SIMPLECHAT_LAST_MICRO_TIME = 0;
    var _SIMPLECHAT_TIMER = false;

    // https://stackoverflow.com/questions/18749591/encode-html-entities-in-javascript
    if (typeof htmlentities != 'function') {
        function htmlentities(raw) {
            var span = document.createElement("span");
            span.textContent = raw;
            return span.innerHTML;
        }
    }

    setInterval(function() {
        doPoll();
    }, 5000);

    function handleMessages(data) {
        console.log(data);
        $('#questions').empty();
        data.forEach(item => {
            item.displayname = item.anonymous == 1 ? '' : item.displayname;


            var html = `<li class='collection-item avatar'>
                    <form action='index.php' method='post'>
                        <input type='hidden' value=${item.quser_id} name='upvoter_id'>
                        <input type='hidden' value=${item.id} name='question_id'>
                        <button type='submit' class='circle red button-color'>
                            <p>+
                            <br>
                            $upvotes</p>
                        </button>
                    </form>
                    <span class='title'>${item.displayname}</span>
                    <p class='question'>${item.question_text}</p>
                    $actions
                </li>`;
            $('#questions').prepend(html);
        })
    }

    function doPoll() {
        var messageurl = addSession('message.php?since=' + _SIMPLECHAT_LAST_MICRO_TIME);
        $.getJSON(messageurl, function(data) {
            handleMessages(data);
        });
    }

    // Make sure JSON requests are not cached
    $(document).ready(function() {
        $.ajaxSetup({
            cache: false
        });
        $("time.timeago").timeago();
        doPoll();
    });

$( "#messageForm" ).submit(function( event ) {
    // Stop form from submitting normally
    event.preventDefault();

    // Get some values from elements on the page:
    var $form = $( this ),
    question = $form.find( "input[name='question']" ).val(),
    question = $form.find( "input[name='question']" ).val(),
    question = $form.find( "input[name='question']" ).val(),
    question = $form.find( "input[name='question']" ).val(),
    session = $form.find( "input[name='PHPSESSID']" ).val(),
    url = $form.attr( "action" );

    $form.find( "input[name='message']" ).val('');
    if ( term.len < 1 ) {
    return;
    }

    if ( _SIMPLECHAT_TIMER ) clearTimeout(_SIMPLECHAT_TIMER);
    _SIMPLECHAT_TIMER = false;

    // Send the data using post
    var posting = $.post( url,
    { message: term, PHPSESSID: session, since: _SIMPLECHAT_LAST_MICRO_TIME } );

    // Put the results in a div
    posting.done(function( data ) {
    // Notiy our pals with a web socket is we have one.
    if ( _SIMPLECHAT_SOCKET && _SIMPLECHAT_SOCKET.readyState == 1 ) {
        console.log('Sending notification'); 
        _SIMPLECHAT_SOCKET.send('Update');
    }
    doPoll();
    $("#spinner").hide();
    });

    });
</script>


<?php
$OUTPUT->footerEnd();
