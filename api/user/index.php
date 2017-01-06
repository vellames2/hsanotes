<?php

/**
 * This is the dispatcher of user
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */

include "../autoload.php";

// Creating response object
$response = new Response();

// Verb variable
$verb = strtoupper($_SERVER['REQUEST_METHOD']);

// Setting the form to catch the endpoint params
$endPointParam = null;
switch ($verb){
    case "POST" || "PUT" || "PATCH":
        $endPointParam = json_decode(file_get_contents("php://input"), true);
        break;
    case "GET" || "DELETE":
        $endPointParam = $_GET;
        break;
    default:
        $response->setStatus(ResponseStatus::FAILED_STATUS);
        $response->setMessage("Unsupported verb type: '{$verb}'");
        die(json_encode($response, JSON_UNESCAPED_UNICODE));
}

// Verify verb to call the correct method
$class = new UserController();
switch ($verb) {
    case "GET":

        break;
    case "POST":
        echo json_encode($class->postRequisition($endPointParam), JSON_UNESCAPED_UNICODE);
        break;
    case "PUT" || "PATCH":

        break;
    case "DELETE":

        break;
}