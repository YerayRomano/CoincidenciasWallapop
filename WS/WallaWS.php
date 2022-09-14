<?php

require_once dirname(dirname(__FILE__)) . '/Utils/Curl.php';

class WallaWS {
    private $base_url;
    private $api_domain;
    private $version;
    private $usr;
    private $shuma = 0;
    public function __construct($base_url,$api_domain,$version,$usr) {
        $this->base_url = $base_url;
        $this->api_domain = $api_domain;
        $this->version = $version;
        $this->usr = $usr;
    }
    
    public function setUSR($usr) {
        $this->usr = $usr;
    }
    public function generateBaseUrl() {
        $url_base_pattern = $this->base_url;
        $domain = $this->api_domain;
        $version = $this->version;
        $usr = $this->usr;
        $final_url = $url_base_pattern;
        $final_url = str_replace('{domain}',$domain,$final_url);
        $final_url = str_replace('{version}',$version,$final_url);
        $final_url = str_replace('{usr}',$usr,$final_url);
        return $final_url;
    }
    private function getResponse($url) {
        $response = CurlUtil::sendRequest($url);
        if(!is_string($response)) return "invalid response";
        $response_json = json_decode($response,true);
        if(!is_array($response_json)) return "incorrect json format";
        return $response_json;
    }
    public function getReviews($url) {
        if($url == NULL || $url == '') return "empty url";
        $reviews = [];
        $shuma = 0;
        $shuma_old = 0;
        do {
            echo $url . $shuma;
            die;
            $response = $this->getResponse($url . $shuma);
            if(empty($response)) break;
            $shuma  += (count($response)-1);
            if(!is_array($response)) return "incorrect json format";
            
            foreach($response as $item) {
                if(!isset($item ["user"] ["id"])) continue;
                if(!isset($reviews [$item ["user"] ["id"]])) {
                    $reviews [$item ["user"] ["id"]] ["name"] = $item ["user"] ["micro_name"]; 
                    $reviews [$item ["user"] ["id"]] ["reviews"] = array();
                }
                $reviews [$item ["user"] ["id"]] ["reviews"] [] = ["item"=>$item ["item"] ["title"],"date"=>gmdate("M d Y H:i:s", $item ["review"] ["date"]),"scoring"=>$item ["review"] ["scoring"],"comments"=>$item ["review"] ["comments"], "type"=>$item ["type"]];
            }
            if($shuma == $shuma_old) break;
            $shuma_old = $shuma;
        } while (true);
        return $reviews;
    }
}