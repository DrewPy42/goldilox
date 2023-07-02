<?php

namespace services;
include __DIR__ . "/../autoload.php";

use gateways\SeriesGateway;

class SeriesService
{
    private SeriesGateway $gateway;

    public function __construct()
    {
        $this->gateway = new SeriesGateway();
    }
}