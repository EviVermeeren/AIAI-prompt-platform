<?php

spl_autoload_register(function ($class) { // autoload classes
    include_once("../classes/" . $class . ".php"); // include class
});

session_start(); // start session