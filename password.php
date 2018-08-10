<?php

require __DIR__ . "/vendor/autoload.php";

use thiagoalessio\TesseractOCR\TesseractOCR;

$baseUrl = "http://vpnbook.com";
$html = Pharse::file_get_dom($baseUrl . "/");

foreach($html("li#pptpvpn>ul>li>strong>img") as $e) {
  if(preg_match("/^(password\.php.*)$/", $e->src, $matches)) {
    $imageUrl = $baseUrl . "/" . $matches[1];
    $fileName = tempnam(sys_get_temp_dir(), "vpnbook");
    file_put_contents($fileName, fopen($imageUrl, "r"));
    $ocr = new TesseractOCR();
    $ocr->config("tessedit_char_whitelist", "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
    $ocr->image($fileName);
    $password = $ocr->run();
    unlink($fileName);
    echo $password;
    break;
  }
}
