<?php

    // Logout
    session_start();
    
    $_SESSION['loggedin'] = false;
    $_SESSION['email'] = "";
    
    $redirectUrl = "http://ukyswe.com/signin/";
    echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");

?>