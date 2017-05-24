<?php

//including the required files
require_once '../include/DbOperation.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/* *
 * URL: http://localhost/DemoApp/v1/createUser
 * Parameters: name, phone, deviceId, deviceBrand, deviceModel
 * Method: POST
 * */
$app->post('/createUser', function () use ($app) {
    verifyRequiredParams(array('name', 'phone', 'password'));
    $response = array();
    $name = $app->request->post('name');
    $phone = $app->request->post('phone');
    $password = $app->request->post('password');
    $db = new DbOperation();
    $res = $db->createUser($name, $phone, $password);
    if ($res == 1) {
        $response["error"] = true;
        $response["message"] = "Oops! An error occurred while registereing";
        echoResponse(200, $response);
       
    } else {
        $db = new DbOperation();
        $response = array();
        $response['error'] = false;
        $response['id']= $res;
        echoResponse(200,$response);
    }
});


/* *
 * URL: http://localhost/Monitor/v1/users
 * Parameters: none
 * Authorization: Put API Key in Request Header
 * Method: POST
 * */
$app->get('/loginUser', function() use ($app){
    verifyRequiredParams(array('phone', 'password'));
    $phone = $app->request->get('phone');
    $password = $app->request->get('password');
    $db = new DbOperation();
    $result = $db->getUserById($phone,$password);
    $response = array();
    $response['error'] = false;
    $response['users'] = array();

    while($row = $result->fetch_assoc()){
        $temp = array();
        $temp['id'] = $row['id'];
        $temp['name'] = $row['name'];
        $temp['phone'] = $row['phone'];
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

$app->run();