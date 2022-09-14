<?php
    //comienzo
    require dirname(__FILE__) . '/WS/WallaWS.php';
    
    $base_url = "https://{domain}/api/v3/users/{usr}/reviews?init=";
    $api_domain = "api.wallapop.com";
    $version = "v3";
    $usr = '08z82exy8oz3';
    
    $WS = new WallaWS($base_url,$api_domain,$version,$usr);
    $base_url = $WS->generateBaseUrl();
    $reviews_trusted = $WS->getReviews($base_url);
    
    $usr = 'v9jdqnkol6ko';
    $WS->setUSR($usr);
    $base_url = $WS->generateBaseUrl();
    $reviews_reviewed = $WS->getReviews($base_url);
    
    $list = array_intersect_key($reviews_trusted,$reviews_reviewed);
    var_dump($list);