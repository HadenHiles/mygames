<?php
function unproxifyUrl($url) {
    $unproxifiedUrl = '';
    if(!empty($url)) {
        preg_match('/.*\?q=([^&]*)&?.*/i', $url, $url_match);
        if(sizeof($url_match) > 1) {
            $unproxifiedUrl = base64_url_decode($url_match[1]);
        }
    }
    return $unproxifiedUrl;
}
function base64_url_decode($input){
    return base64_decode(str_pad(strtr($input, '-_', '+/'), strlen($input) % 4, '=', STR_PAD_RIGHT));
}