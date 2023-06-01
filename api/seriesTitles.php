<?php
namespace api;
include __DIR__ . "/../core/autoload.php";

use gateways\SeriesGateway;

$gateway = new SeriesGateway();

$requestMethod = $_SERVER["REQUEST_METHOD"];
$queryString = $_SERVER["REQUEST_URI"];
// cut off "?" query params from uri so routing is easier
if(strpos($queryString, "?")) $queryString = strstr($queryString, "?", true);
// decode and save body
$requestBody = json_decode(file_get_contents("php://input"), true);

$get = function(string $id) use ($gateway) {
    $results = $gateway->get($id);
    $count = count($results);
    JSONResponse::respondAndExitStatic(200, $results, "$count records found", null, $count);
};

$getAll = function() use ($gateway) {
    // get and return records
    $results = $gateway->getAll();
    $count = count($results);
    JSONResponse::respondAndExitStatic(200, $results, "$count records found");
};
