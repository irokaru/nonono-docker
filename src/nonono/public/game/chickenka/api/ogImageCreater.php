<?php
function ogImageCreater($param = null)
{
  $stageNum = substr($param, 0, 1);
  $score = substr($param, 1);

  $image = imagecreatetruecolor(960, 502);
  $bcolor = imagecolorallocate($image, 200, 200, 200);
  imagefill($image, 0, 0, $bcolor);

  $baseURL = "./assets/thumbnail.png";
  $base = imagecreatefrompng($baseURL);
  $base_info = getimagesize($baseURL);
  imagecopy($image, $base, 0, 0, 0, 0, $base_info[0], $base_info[1]);

  if($param == null || $stageNum > 4) {  // 中身が空っぽだったら普通のやつを返す
    return "https://nononotyaya.net/game/chickenka/assets/thumbnail.png";
  }

  // URL生成
  $saveURL = "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
  $saveURL = substr($saveURL, 0, strcspn($saveURL,'?'));
  $saveURL .= "score/".$param.".png";

  // すでに生成済みのファイルだったらあとの処理は止めておけ
  if(is_file("./score/".$param.".png")) return $saveURL;

  $stageName = ["トリノ平原", "コッコさばく", "ドゥルドゥ山", "チキチキビーチ"];

  $fontURL = "./assets/CP_Revenge.ttf";
  $fontCont = imagecolorallocate($image, 255, 255, 255);
  $fontEdge = imagecolorallocate($image, 153, 129, 109);
  $fontSize = 36;
  $text = $stageName[$stageNum]."\r\n スコア ".intval($score)."\r\n  だったよ！";
  imagettftext($image, $fontSize, 0, 79, 309, $fontEdge, $fontURL, $text);
  imagettftext($image, $fontSize, 0, 81, 309, $fontEdge, $fontURL, $text);
  imagettftext($image, $fontSize, 0, 83, 309, $fontEdge, $fontURL, $text);

  imagettftext($image, $fontSize, 0, 79, 311, $fontEdge, $fontURL, $text);
  imagettftext($image, $fontSize, 0, 83, 311, $fontEdge, $fontURL, $text);

  imagettftext($image, $fontSize, 0, 79, 313, $fontEdge, $fontURL, $text);
  imagettftext($image, $fontSize, 0, 81, 313, $fontEdge, $fontURL, $text);
  imagettftext($image, $fontSize, 0, 83, 313, $fontEdge, $fontURL, $text);

  imagettftext($image, $fontSize, 0, 81, 311, $fontCont, $fontURL, $text);


  imagepng($image, "./score/".$param.".png");
  imagedestroy($image);

  return $saveURL;

}
?>