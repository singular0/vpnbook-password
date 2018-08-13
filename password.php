<?php

require __DIR__ . "/vendor/autoload.php";

class Gocr extends Shinbuntu\Gocr\Gocr {
  protected $gocrBinPath = 'gocr';
}

$baseUrl = "http://vpnbook.com";
$html = Pharse::file_get_dom($baseUrl . "/");

foreach($html("li#pptpvpn>ul>li>strong>img") as $e) {
  if(preg_match("/^(password\.php.*)$/", $e->src, $matches)) {
    $imageUrl = $baseUrl . "/" . $matches[1];
    $fileName = tempnam(sys_get_temp_dir(), "vpnbook") . ".png";
    file_put_contents($fileName, fopen($imageUrl, "r"));
    $ocr = new Gocr($fileName);
    $password = $ocr->recognize();
    unlink($fileName);
    echo $password;
    break;
  }
}
