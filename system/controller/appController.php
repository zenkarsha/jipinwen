<?php

class generate extends View
{
  function __construct($url)
  {
    parent::__construct();

    if(isset($_POST['parent'])) {
      @$author = $_POST['author'];
      @$text = $_POST['text'];
      @$parent = $_POST['parent'];
      @$comment_type = intval($_POST['comment_type']);
      $type = 2;

      $data = $this->handle('readItem',$parent);
      @$title = $data['title'];
    }
    else {
      @$author = $_POST['author'];
      @$title = $_POST['title'];
      @$text = $_POST['text'];
      $type = intval($_POST['type']);
      $parent = '';
      $comment_type = 0;
    }
    $this->createImage($author, $title, $text, $type, $parent, $comment_type);
  }
  private function createImage($author, $title, $text, $type, $parent, $comment_type)
  {
    $fontsize1 = 22;
    $fontsize2 = $this->config['app']['fontsize'];
    $font = './font/PMingLiU-ptt-1162216.ttf';

    $width = $this->config['app']['width'];
    $height = $this->config['app']['height'];
    $image = imagecreatetruecolor($width, $height);
    $background = imagecolorallocate($image, 0, 0, 0);
    imagefilledrectangle($image, 0, 0, $width, $height, $background);

    //color
    $fontcolor = imagecolorallocate($image, 255, 255, 255);
    $pttblue = imagecolorallocate($image, 1, 0, 127);
    $pttgray = imagecolorallocate($image, 128, 128, 128);

    //text
    if($author == null) $author = 'neihusnape（示上ㄦ）';
    if($title == null) $title = '如果割闌尾投票率過半的話';
    if($parent !== '') $title = 'R: '.$title;
    else $title = '[祭品] '.$title;
    $time = date('D M j H:i:s Y');
    if($text == null) $text = '只要割闌尾投票率過半
我就吃垮義美@';

    $init_author = htmlEncode($author);
    $init_title = htmlEncode($title);
    $init_text = htmlEncode($text);

    $text = replaceByArray($text,$this->config['app']['replace']);
    $author = replaceByArray($author,$this->config['app']['replace']);
    $title = replaceByArray($title,$this->config['app']['replace']);
    $text = autoWrap($text, 14);
    if(count(explode("\n", $text)) < 4) $text = '
'.$text;
    $text = limitLine($text, 5);

    //draw
    imagefilledrectangle($image, 0, 0, $this->config['app']['width'], 106, $pttblue);
    imagefilledrectangle($image, 0, 0, 100, 106, $pttgray);
    imagettftextbold($image, $fontsize1, 0, 21, 30, $pttblue, $font, '作者');
    imagettftextbold($image, $fontsize1, 0, 21, 64, $pttblue, $font, '標題');
    imagettftextbold($image, $fontsize1, 0, 21, 98, $pttblue, $font, '時間');
    imagettftextbold($image, $fontsize1, 0, 118, 30, $pttgray, $font, $author);
    imagettftextbold($image, $fontsize1, 0, 118, 64, $pttgray, $font, $title);
    imagettftextbold($image, $fontsize1, 0, 118, 98, $pttgray, $font, $time);
    imagettftext($image, $fontsize2, 0, 14, 160, $fontcolor, $font, $text);

    switch ($type)
    {
      case 1:
        ob_start();
        imagepng($image,null,9,null);
        $image = ob_get_contents();
        ob_end_clean();
        @imagedestroy($image);
        // print '<img src="data:image/png;base64,'.base64_encode($image).'"/>';
        print '<div style="background: url(data:image/png;base64,'.base64_encode($image).');background-size: contain;"/></div>';
        break;

      case 2:
        header('Content-Type: image/png');
        $url = base62(strrev(time())).genRandomString(2);
        $save = "./i/".$url.".png";
        imagepng($image,$save,9,null);
        $ip = getUserIP();
        $device = $_SERVER['HTTP_USER_AGENT'];
        $this->handle('insertContract',$url,$init_author,$init_title,$init_text,$ip,$device,time(),$parent,$comment_type);
        if($parent !== '') $url = $this->config['site']['path'].$parent;
        else $url = $this->config['site']['path'].$url;
        header("Location: $url");
        break;

      case 3:
      default:
        header('Content-Type: image/png');
        header("Content-Transfer-Encoding: binary");
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.$this->config['app']['filename']);
        imagepng($image, null, 9, null);
        @imagedestroy($image);
        break;
    }
  }
}
