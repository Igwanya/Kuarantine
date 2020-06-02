<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 12-Apr-20
 * Time: 10:35 PM
 */
namespace Src;

use PDO;

require_once __DIR__ . '../../../vendor/autoload.php';

date_default_timezone_set('Africa/Nairobi');

static $state = 1;

$password_reset_email_error = array(
    "error" => ""
);

$password_reset_page_success = <<<HTML
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="felixmuthui32@gmail.com">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="../res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
    <link href="../res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
    <link href="../res/css/main.css?lastModified=Sun, 05 Apr 2020 08:32:00 GMT" rel="stylesheet"  media="screen,projection">
    <title>Email sent</title>
</head>
<body>
<nav class="white" id="passwordResetNav">
    <div class="nav-wrapper">
        <ul id="nav-mobile" class="left">
            <li>
                <a href="../login.php" >
                    <i id="passwordResetLoginPageIcon" class="material-icons">chevron_left</i>
            </a></li>
            <li id="passwordResetNavBack" >
                Go Back
            </li>
        </ul>
    </div>
</nav>
<section id="resetSection">
    <div class="">
        <div class="row">
            <div class="col s4"></div>
            <div class="col s4">
                <h6>Password reset email sent.</h6>
            </div>
            <div class="col s4"></div>
        </div>
    </div>
</section>
<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/vendor/fontawesome/js/all.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script> 
</body>
</html>
HTML;

/**
 * @param $conn
 * @param string $email
 * @param string $expiry_date
 */
function insert_expiry_date_to_db($conn, $email, $expiry_date){
    $update_query = "UPDATE users SET expiry_date=? WHERE email=?";
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $conn->prepare($update_query))) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
    /* Prepared statement, stage 2: bind and execute */
    if (!$stmt->bind_param("ss", $expiry_date, $email)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
}

/**
 * Send the password reset email to the user
 * @param $email
 * @param $key
 * @return bool
 */
function send_password_reset_email($email, $key)  : bool
{
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $subject = "Dear user,";
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $html_message = "
<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\">
    <meta charset=\"UTF-8\"/>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
    <meta name=\"description\" content=\"\">
    <meta name=\"author\" content=\"felixmuthui32@gmail.com\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"msapplication-tap-highlight\" content=\"no\">
    <title>Reset password</title>
</head>
<body>
<p>Please click the following link to reset your password.</p>
<p>This link will expire in 24hrs 
<a href=\"http://{$_SERVER['HTTP_HOST']}{$uri}/change_password?key={$key}&email={$email}&action=reset\" target='_blank' ></a>
.</p>
<p>
If you did not request the link then no action is required.<br/>Thank you.
</p>
</body>
</html>
";
    return mail($email, $subject, $html_message, $headers);
}

/**
 * Captures the inputs from the page
 */
/**
 * @param $password_reset_email_error
 * @param $password_reset_page_success
 * @return mixed
 */
function init($password_reset_email_error)
{
    $password_reset_email = filter_input(INPUT_POST, 'passwordResetEmail');
    if (!empty($password_reset_email)) {
        $password_reset_email = filter_var($password_reset_email, FILTER_SANITIZE_EMAIL);
        $password_reset_email = filter_var($password_reset_email, FILTER_VALIDATE_EMAIL);
        $db = new database\DatabaseConnection();
        $conn = $db->get_db_connection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email LIKE ?");
        if (!($stmt)) {
            trigger_error("Prepare failed: (" . $conn->errno . ") " . $conn->error, E_USER_ERROR);
        }
        if (!$stmt->bind_param('s', $password_reset_email)) {
            trigger_error("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error, E_ERROR);
        }
        if (!$stmt->execute()) {
            trigger_error("Execute failed: (" . $stmt->errno . ") " . $stmt->error, E_CORE_ERROR);
        }
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        if ($row['email'] != null) { // The email exists
            $password_reset_email_error["error"] = "";
            $exp_date_format = mktime(
                date("H"), date("i"), date("s"), date("m"),
                date("d") + 1, date("Y")
            );
            $expiry_date = date("Y-m-d H:i:s", $exp_date_format);
            $reset_key = $row['password_hash'];
            insert_expiry_date_to_db($conn, $password_reset_email, $expiry_date);
            if (send_password_reset_email($password_reset_email, $reset_key)) {
               global $state; // Change the state of the process to display success
                $state = 2;
            }


        } else {   // The email does not exist
            $password_reset_email_error["error"] = "Invalid email address";
        }


    } else {
        $password_reset_email_error["error"] = "";
    }
    return $password_reset_email_error;
}

/**
 * Initialise the process
 */
$password_reset_email_error = init($password_reset_email_error);

$password_reset_page = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="felixmuthui32@gmail.com">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="../res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
    <link href="../res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
    <link href="../res/css/main.css?lastModified=Sun, 05 Apr 2020 08:32:00 GMT" rel="stylesheet"  media="screen,projection">
    <title>Reset password</title>
</head>
<body>
<nav class="white" id="passwordResetNav">
    <div class="nav-wrapper">
        <ul id="nav-mobile" class="left">
            <li>
                <a href="../login.php" >
                    <i id="passwordResetLoginPageIcon" class="material-icons">chevron_left</i>
            </a></li>
            <li id="passwordResetNavBack" >
                Go Back
            </li>
        </ul>
    </div>
</nav>
<section id="resetSection">
    <div class="">
        <div class="row">
            <div class="col s4"></div>
            <div class="col s4">
                <form action="password_reset.php" method="post" class="" id="passwordResetForm">
                    <fieldset>
                        <legend>Password reset</legend>
                            <div class="input-field">
                                <input id="inputPasswordResetEmail" name="passwordResetEmail" type="email" class="validate">
                                <label for="inputPasswordResetEmail" id="labelPasswordResetEmail">Email</label>
                                 <p id="passwordResetEmailHelper">The email to send the reset link</p>
                                <span class="helper-text" id="passwordResetEmailHelper" data-error="wrong" data-success="">{$password_reset_email_error["error"]}</span>
                            </div>
                            <div class="input-field">
                                <input id="passwordResetBtn" type="submit" value="Submit" class="btn right right-align" />
                            </div>
                    </fieldset>
                </form>
            </div>
            <div class="col s4"></div>
        </div>
    </div>
</section>
<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/vendor/fontawesome/js/all.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script> 
</body>
</html>
HTML;

switch ($state)
{
    case 1:
        echo $password_reset_page;
        break;
    case 2:
        echo $password_reset_page_success;
}

