<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 26-Apr-20
 * Time: 11:16 PM
 */

namespace Src;

use Src\database\DatabaseConnection;
use Src\models\User;

require_once __DIR__ . '../../vendor/autoload.php';

 $user_bucket = array(
     "id"           => 0,
     "username"     => "",
     "email"        => "",
     "first_name"   => "",
     "last_name"    => "",
     "is_admin"     => "",
     "created"      => "",
     "last_updated" => ""
 );
$user = new User(
    $user_bucket['id'],
    $user_bucket['username'],
    $user_bucket['email'],
    $user_bucket['first_name'],
    $user_bucket['last_name'],
    $user_bucket['is_admin'],
    $user_bucket['created'],
    $user_bucket['last_updated']
);

date_default_timezone_set('Africa/Nairobi');
static $process_state = 0;

$action = filter_input(INPUT_GET, 'action');
if (!empty($action)) {
    $reset_key = filter_input(INPUT_GET, 'key');
    $reset_email = filter_input(INPUT_GET, 'email');
    $query = "SELECT * FROM users WHERE email LIKE ?";
    $db = new DatabaseConnection();
    $conn = $db->get_db_connection();
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $conn->prepare($query))) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
    /* Prepared statement, stage 2: bind and execute */
    if (!$stmt->bind_param("s", $reset_email)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    // Case 0: Invalid link  used the url inputs are not correct
    if ($row == null)
    {
        global $process_state;
        $process_state = 0;
    }
    // case 2: The url contains the ?key=password_hash&email=email&action=reset
    $current_date = date("Y-m-d H:i:s");
    if ($row['expiry_date'] >= $current_date && $row['password_hash'] == $reset_key){
        global $process_state;
        global $user_bucket;
        $user_bucket['id']           =  $row['id'];
        $user_bucket['username']     =  $row['username'];
        $user_bucket['email']        =  $row['email'];
        $user_bucket['first_name']   =  $row['first_name'];
        $user_bucket['last_name']    =  $row['last_name'];
        $user_bucket['is_admin']     =  $row['is_admin'];
        $user_bucket['created']      =  $row['created'];
        $user_bucket['last_updated'] =  $row['last_updated'];
        $process_state = 1;
    }
    else{  // case 3: The link expired.
        global $process_state;
        $process_state = 3;
    }
}

$reset_password = filter_input(INPUT_POST, 'password');
if (!empty($reset_password)){
    $query = "UPDATE users SET password_hash=? email LIKE ?";
    $db = new DatabaseConnection();
    $conn = $db->get_db_connection();
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $conn->prepare($query))) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
    /* Prepared statement, stage 2: bind and execute */
    global $user;
    $user->set_password_hash($reset_password);

    if (!$stmt->bind_param("ss",$user->get_password_hash() ,$reset_email)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

}

// TODO:: Customise the page
$link_expired_page =<<<HTML
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
	<link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
	<link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
	<link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>Change Password</title>
</head>
<body>
<div class="row">
	<div class="col s4"></div>
	<div class="col s4">
		<p>
		<h6>Link expired</h6>
</p>
	</div>
	<div class="col s4"></div>
</div>
<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/vendor/fontawesome/js/all.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script> 
</body>
</html>
HTML;

$change_password_page =<<<HRML
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
	<link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
	<link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
	<link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>Change Password</title>
</head>
<body>
<div class="row">
	<div class="col s4"></div>
	<div class="col s4">
		<form action="change_password.php" method="POST" class="form" id="passwordChangeForm">
			<fieldset id="passwordChangeFormFieldset">
				<legend id="passwordChangeFormLegend">New Password Request</legend>
				<div class="input-field">
					<input id="inputNewPassword" type="password" class="validate">
					<label for="inputNewPassword" id="labelInputNewPassword" >New password</label>
					<span class="helper-text" id="passwordChangeFormPNewasswordHelper" data-error="wrong" data-success="right"></span>
				</div>
				<div class="input-field">
					<input id="inputPassword" type="password" name="password" class="validate">
					<label for="inputPassword" id="labelInputPassword" >Confirm password</label>
					<span class="helper-text" id="passwordChangeFormPasswordHelper" data-error="wrong" 
					data-success="right"></span>
				</div>
				<div class="input-field right-align">
					<input type="submit" id="inputSubmit" class="btn" />
				</div>
				<div>
			</fieldset>   
		</form>
	</div>
	<div class="col s4"></div>
</div>
<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/vendor/fontawesome/js/all.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script> 
</body>
</html>
HRML;

// TODO:: Customise the page
$change_password_page_success_page =<<<HTML
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
	<link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
	<link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
	<link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>Change Password</title>
</head>
<body>
<div class="row">
	<div class="col s4"></div>
	<div class="col s4">
		<p>
		<h6>Change Password success</h6>
</p>
	</div>
	<div class="col s4"></div>
</div>
<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/vendor/fontawesome/js/all.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script> 
</body>
</html>
HTML;

// TODO:: Customise the page 
$invalid_link_expired_page =<<<HTML
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
	<link href="res/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"  media="screen,projection">
	<link href="res/vendor/materialize/css/materialize.css" rel="stylesheet"  media="screen,projection">
	<link href="res/css/main.css" rel="stylesheet"  media="screen,projection">
    <title>Invalid Link</title>
</head>
<body>
<div class="row">
	<div class="col s4"></div>
	<div class="col s4">
	<h6>Invalid link</h6>
		<p>
		 The link is invalid/expired. Either you did not copy the correct link from the email 
		 or you have already used the key in which case it is deactivated	
</p>
	</div>
	<div class="col s4"></div>
</div>
<script type="text/javascript" src="../res/vendor/jquery-3.4.1.js"></script>
<script type="text/javascript" src="../res/vendor/popper.min.js"></script>
<script type="text/javascript" src="../res/vendor/jquery.mobile-1.4.5.js"></script>
<script type="text/javascript" src="../res/vendor/materialize/js/materialize.js"></script>
<script type="text/javascript" src="../res/vendor/fontawesome/js/all.js"></script>
<script type="text/javascript" src="../res/js/init.js"></script> 
</body>
</html>
HTML;

switch ($process_state)
{
    case 0:
       echo $invalid_link_expired_page;
        break;
    case 1:
        echo $change_password_page;
        break;
    case 2:
        echo $change_password_page_success_page;
        break;
    case 3:
        echo $link_expired_page;
        break;
}

