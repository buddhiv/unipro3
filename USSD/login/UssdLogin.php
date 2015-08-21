
<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 8/20/15
 * Time: 5:14 PM
 */

//namespace login;
include_once '../libs/ussd/MoUssdReceiver.php';
include_once '../libs/ussd/MtUssdSender.php';
include_once '../libs/log.php';

ini_set('error_log', 'ussd-app-error.log');

$receiver = new MoUssdReceiver(); // Create the Receiver object

$receiverSessionId = $receiver->getSessionId();
session_id($receiverSessionId); //Use received session id to create a unique session
session_start();

$content = $receiver->getMessage(); // get the message content
$address = $receiver->getAddress(); // get the sender's address
$requestId = $receiver->getRequestID(); // get the request ID
$applicationId = $receiver->getApplicationId(); // get application ID
$encoding = $receiver->getEncoding(); // get the encoding value
$version = $receiver->getVersion(); // get the version
$sessionId = $receiver->getSessionId(); // get the session ID;
$ussdOperation = $receiver->getUssdOperation(); // get the ussd operation

$category;

logFile("[ content=$content, address=$address, requestId=$requestId, applicationId=$applicationId, encoding=$encoding, version=$version, sessionId=$sessionId, ussdOperation=$ussdOperation ]");

//your logic goes here......
$responseMsg = array(
    "main" => "Welcome!
                    1.Register
                    2.Jobs
                    99.Exit",
    "register" => "Please select a category
                    1.Tech
                    2.Business
                    3.Entertainment
                    99.Back",
    "jobs"=> "You have received following jobs",
    "selected" =>"You selected
                        1.Confirm registration
                    99. Back",
    "registered" => "Thank you for register in our service
                      Now you will receive jobs in selected category "
);

logFile("Previous Menu is := " . $_SESSION['menu-Opt']); //Get previous menu number
if (($receiver->getUssdOperation()) == "mo-init") { //Send the main menu
    //loadUssdSender($sessionId, $responseMsg["main"]);
    send($sessionId, $responseMsg["main"],$address);
    if (!(isset($_SESSION['menu-Opt']))) {
        $_SESSION['menu-Opt'] = "main"; //Initialize main menu
    }

}
if (($receiver->getUssdOperation()) == "mo-cont") {
    $menuName = null;

    switch ($_SESSION['menu-Opt']) {
        case "main":
            switch ($receiver->getMessage()) {
                case "1":
                    $menuName = "register";
                    break;
                case "2":
                    $menuName = "jobs";
                    break;
                case "3":
                    $menuName = "careers";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
            $_SESSION['menu-Opt'] = $menuName; //Assign session menu name
            break;
        case "register":
            $_SESSION['menu-Opt'] = "register-hist"; //Set to company menu back
            switch ($receiver->getMessage()) {
                case "1":
                    $_SESSION['cat']="1";
                    break;
                case "2":
                    $_SESSION['cat']="2";
                    break;
                case "3":
                    $_SESSION['cat']="3";
                    break;
                case "999":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
            $menuName = "selected";
            $_SESSION['menu-Opt'] = $menuName;
            break;

        case "selected":
            switch ($receiver->getMessage()) {
                case "1":
                    $menuName = "registered";
                    //add to database
                    //_SESSION['cat']
                    //$address
                    break;
                case "99":
                    $menuName = "register";
            }
        case "register-hist" || "products-hist" || "careers-hist":
            switch ($_SESSION['menu-Opt']) { //Execute menu back sessions
                case "register-hist":
                    $menuName = "register";
                    break;
                case "products-hist":
                    $menuName = "products";
                    break;
                case "careers-hist":
                    $menuName = "careers";
                    break;
                case "selected-hist":
                    $menuName = "selected";
            }
            $_SESSION['menu-Opt'] = $menuName; //Assign previous session menu name
            break;
    }

    if ($receiver->getMessage() == "000") {
        $responseExitMsg = "Exit Program!";
        $response = loadUssdSender($sessionId, $responseExitMsg);
        session_destroy();
    }

    if ($receiver->getMessage() == "exit") {
        $responseExitMsg = "Exit Program!";
        $response = loadUssdSender($sessionId, $responseExitMsg);
        session_destroy();
    }
    else {
        logFile("Selected response message := " . $responseMsg[$menuName]);
        logFile("cat".$_SESSION['cat']);
        //$response = loadUssdSender($sessionId, $responseMsg[$menuName]);
        $response = send($sessionId, $responseMsg[$menuName],$address);
    }

}
/*
    Get the session id and Response message as parameter
    Create sender object and send ussd with appropriate parameters
**/
function send($sessionId,$responseMessage,$destinationAddress){
    $password = "password";
    if ($responseMessage == "000") {
        $ussdOperation = "mt-fin";
    } else {
        $ussdOperation = "mt-cont";
    }
    $chargingAmount = "5";
    $applicationId = "APP_000001";
    $encoding = "440";
    $version = "1.0";

    try {
        // Create the sender object server url

//        $sender = new MtUssdSender("http://localhost:7000/ussd/send/");   // Application ussd-mt sending http url
        $sender = new MtUssdSender("https://localhost:7443/ussd/send/"); // Application ussd-mt sending https url
        $response = $sender->ussd($applicationId, $password, $version, $responseMessage,
            $sessionId, $ussdOperation, $destinationAddress, $encoding, $chargingAmount);
        return $response;
    } catch (UssdException $ex) {
        //throws when failed sending or receiving the ussd
        error_log("USSD ERROR: {$ex->getStatusCode()} | {$ex->getStatusMessage()}");
        return null;
    }
}

function loadUssdSender($sessionId, $responseMessage)
{
    $password = "password";
    $destinationAddress = "tel:94771122336";
    if ($responseMessage == "000") {
        $ussdOperation = "mt-fin";
    } else {
        $ussdOperation = "mt-cont";
    }
    $chargingAmount = "5";
    $applicationId = "APP_000001";
    $encoding = "440";
    $version = "1.0";

    try {
        // Create the sender object server url

//        $sender = new MtUssdSender("http://localhost:7000/ussd/send/");   // Application ussd-mt sending http url
        $sender = new MtUssdSender("https://localhost:7443/ussd/send/"); // Application ussd-mt sending https url
        $response = $sender->ussd($applicationId, $password, $version, $responseMessage,
            $sessionId, $ussdOperation, $destinationAddress, $encoding, $chargingAmount);
        return $response;
    } catch (UssdException $ex) {
        //throws when failed sending or receiving the ussd
        error_log("USSD ERROR: {$ex->getStatusCode()} | {$ex->getStatusMessage()}");
        return null;

    }
}



?>

