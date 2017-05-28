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
    $response = array();
    $response['error'] = false;
    $response['login'] = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="css/custom.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <form action="">
            <div class="container">
                <div class="intro-text">
                    <h2>Login</h2>
                    <p>Please enter the username and password below</p>
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
    echoResponse(200, $response);
});

/* *
 * URL: http://localhost/Monitor/v1/createPlace
 * Parameters: name, latitude, longitude, raidus, address, phone
 * Method: POST
 * */
$app->post('/createPlace', function () use ($app) {
    verifyRequiredParams(array('name', 'latitude', 'longitude','raidus','address','phone'));
    $response = array();
    $name = $app->request->post('name');
    $latitude = $app->request->post('latitude');
    $longitude = $app->request->post('longitude');
    $raidus = $app->request->post('raidus');
    $address = $app->request->post('address');
    $phone = $app->request->post('phone');
    $db = new DbOperation();
    $res = $db->createPlace($name, $latitude, $longitude, $raidus, $address, $phone);
    if ($res == 0) {
        $response["error"] = false;
        $response["message"] = "Place successfully registered";
        echoResponse(201, $response);
    } else if ($res == 1) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registereing";
        echoResponse(200, $response);
    }
});

/* *
 * URL: http://localhost/Monitor/v1/place
 * Parameters: none
 * Method: GET
 * */
$app->get('/place', function() use ($app){
    $db = new DbOperation();
    $result = $db->getPlace();
    $response = array();
    $response['error'] = false;
    $response['place'] = array();

    while($row = $result->fetch_assoc()){
        $temp = array();
        $temp['id'] = $row['id'];
        $temp['name'] = $row['name'];
        $temp['latitude'] = $row['latitude'];
        $temp['longitude'] = $row['longitude'];
        $temp['radius'] = $row['radius'];
        $temp['address'] = $row['address'];
        $temp['phone'] = $row['phone'];
        array_push($response['place'],$temp);
    }

    echoResponse(200,$response);
});

/* *
 * URL: http://localhost/Monitor/v1/createUser
 * Parameters: name, phone, deviceId, deviceBrand, deviceModel
 * Method: POST
 * */
$app->post('/createUser', function () use ($app) {
    verifyRequiredParams(array('name', 'phone', 'deviceId','deviceBrand','deviceModel','latitude','longitude'));
    $response = array();
    $name = $app->request->post('name');
    $phone = $app->request->post('phone');
    $deviceId = $app->request->post('deviceId');
    $deviceBrand = $app->request->post('deviceBrand');
    $deviceModel = $app->request->post('deviceModel');
    $latitude = $app->request->post('latitude');
    $longitude = $app->request->post('longitude');
    $db = new DbOperation();
    $res = $db->createUser($name, $phone, $deviceId, $deviceBrand, $deviceModel,$latitude,$longitude);
    if ($res != 0) {
        // $response["error"] = false;
        // $response["message"] = "You are successfully registered";
        // echoResponse(201, $response);
        $db = new DbOperation();
        $result = $db->getPlace();
        $response = array();
        $response['error'] = false;
		$response['userId'] = $res;
        $response['place'] = array();
		//$response['userId'] = $res['id'];
        while($row = $result->fetch_assoc()){
            $temp = array();
            $temp['id'] = $row['id'];
            $temp['name'] = $row['name'];
            $temp['latitude'] = $row['latitude'];
            $temp['longitude'] = $row['longitude'];
            $temp['radius'] = $row['radius'];
            $temp['address'] = $row['address'];
            $temp['phone'] = $row['phone'];
            array_push($response['place'],$temp);
        }
        echoResponse(200,$response);
    } else {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registereing";
        echoResponse(200, $response);
    }
});
/* *
 * URL: http://localhost/Monitor/v1/createUser
 * Parameters: name, phone, deviceId, deviceBrand, deviceModel
 * Method: POST
 * */
$app->post('/checkUserExist', function () use ($app) {
    verifyRequiredParams(array('phone'));
    $response = array();
    $phone = $app->request->post('phone');
    $db = new DbOperation();
    $result = $db->checkUserExist($phone);
	$response = array();
	$response['error'] = false;
	$response['user'] = array();
	//$response['userId'] = $res['id'];
	while($row = $result->fetch_assoc()){
		$temp = array();
		$temp['id'] = $row['id'];
		$temp['name'] = $row['name'];
		$temp['deviceId'] = $row['deviceId'];
		$temp['deviceBrand'] = $row['deviceBrand'];
		$temp['deviceModel'] = $row['deviceModel'];
		$temp['latitude'] = $row['latitude'];
		$temp['longitude'] = $row['longitude'];
		$temp['phone'] = $row['phone'];
		array_push($response['user'],$temp);
	}
	$result1 = $db->getPlace();
	$response['place'] = array();
	while($row = $result1->fetch_assoc()){
		$temp = array();
		$temp['id'] = $row['id'];
		$temp['name'] = $row['name'];
		$temp['latitude'] = $row['latitude'];
		$temp['longitude'] = $row['longitude'];
		$temp['radius'] = $row['radius'];
		$temp['address'] = $row['address'];
		$temp['phone'] = $row['phone'];
		array_push($response['place'],$temp);
	}
	echoResponse(200,$response);
});


/* *
 * URL: http://localhost/Monitor/v1/users
 * Parameters: none
 * Authorization: Put API Key in Request Header
 * Method: GET
 * */
$app->get('/users', function() use ($app){
    $db = new DbOperation();
    $result = $db->getAllUsers();
    $response = array();
    $response['error'] = false;
    $response['users'] = array();

    while($row = $result->fetch_assoc()){
        $temp = array();
        $temp['id'] = $row['id'];
        $temp['name'] = $row['name'];
        $temp['phone'] = $row['phone'];
        $temp['deviceId'] = $row['deviceId'];
        $temp['deviceBrand'] = $row['deviceBrand'];
        $temp['deviceModel'] = $row['deviceModel'];
        $temp['latitude'] = $row['latitude'];
        $temp['longitude'] = $row['longitude'];
        array_push($response['users'],$temp);
    }

    echoResponse(200,$response);
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

/* *
 * URL: http://localhost/Monitor/v1/updateToken
 * Parameters: name, phone
 * Method: POST
 * */
$app->post('/updateToken', function () use ($app) {
    verifyRequiredParams(array('name', 'token'));
    $response = array();
	$name = $app->request->post('name');
    $token = $app->request->post('token');
    $db = new DbOperation();
    $res = $db->updateToken($name, $token);
    if ($res == 0) {
        $response["error"] = false;
        $response["message"] = "Token updated successfully";
        echoResponse(201, $response);
    } else if ($res == 1) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while updating";
        echoResponse(200, $response);
    }
});

$app->run();