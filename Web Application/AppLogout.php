<?php
    // http://stackoverflow.com/questions/8723999/how-to-perform-logging-out-in-iphone-application
    session_start();

    $response = array();

    session_unset();
    session_destroy();

    $response["code"] = "0";
    $response["message"] = "Logout successful";
    echo json_encode($response);
    

    /*
        HealthKit
        https://www.natashatherobot.com/healthkit-getting-fitness-data/
        http://www.appcoda.com/healthkit-introduction/
        https://www.raywenderlich.com/86336/ios-8-healthkit-swift-getting-started

    */
?>