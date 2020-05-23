<?php
/**
 * Created by PhpStorm.
 * User: MUTHUI
 * Date: 21-May-20
 * Time: 7:22 AM
 */

$query = $repository->find_user_with_id($_SESSION['login_ID']);
//print_r($query);
$date_string =  date('l jS \of F Y h:i:s A');
$nav = <<<NAV
<nav>
    <div class="nav-wrapper p-2">
        <a href="#" class="">{$date_string}</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
           <li><a href="../admin/admin.php"><i class="fa fa-user-astronaut"></i> {$query['body']['user']['username']}</a></li>
            <li><a href="{$_SERVER['SERVER_NAME']}">site</a> </li>
            <li class=""><i class="" ></i></li>
            <li><a href="../logout.php"><i class="fa fa-power-off fa-1x"></i>log out</a></li>   
        </ul>
    </div>
</nav>
NAV;
echo $nav;



