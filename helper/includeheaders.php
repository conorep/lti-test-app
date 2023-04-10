<?php
    if (empty(session_id()))
    {
        session_start(['cookie_lifetime' => 86400]);
    } else
    {
        session_start();
    }
    header("Accept: *");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Access-Control-Max-Age: 1000');
