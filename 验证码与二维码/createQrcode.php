<?php
/*生成使用码的二维码图片*/
//header("Content-type: image/png");
include './phpqrcode.php'; //加载生成二维码的类
// $useCode = 'http://www.baidu.com';
// //生成二维码图片
// $result = QRcode::png($useCode,false, 'L', 8.5,0);

/**
 * 生成带有logo的二维码
 */
$value = 'http://www.baidu.com'; //二维码内容   
$errorCorrectionLevel = 'L';//容错级别   
$matrixPointSize = 10;//生成图片大小   
//生成二维码图片   
QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 5);   
$logo = 'logo.jpg';//准备好的logo图片   
$QR = 'qrcode.png';//已经生成的原始二维码图 
if ($logo !== FALSE) {   
    $QR = imagecreatefromstring(file_get_contents($QR));   
    $logo = imagecreatefromstring(file_get_contents($logo));   
    $QR_width = imagesx($QR);//二维码图片宽度   
    $QR_height = imagesy($QR);//二维码图片高度   
    $logo_width = imagesx($logo);//logo图片宽度   
    $logo_height = imagesy($logo);//logo图片高度   
    $logo_qr_width = $QR_width / 5;   
    $scale = $logo_width/$logo_qr_width;   
    $logo_qr_height = $logo_height/$scale;   
    $from_width = ($QR_width - $logo_qr_width) / 2;   
    //重新组合图片并调整大小   
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
    $logo_qr_height, $logo_width, $logo_height);   
}   
//输出图片   
imagepng($QR, 'helloweixin.png');   
echo '<img src="helloweixin.png">'; 



/**
 * 参数$text表示生成二位的的信息文本；
 *参数$outfile表示是否输出二维码图片 文件，默认否；
 *参数$level表示容错率，也就是有被覆盖的区域还能识别，分别是 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，*15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）； 
 *参数$size表示生成图片大小，默认是3；参数$margin表示二维码周围边框空白区域间距值；
 *参数$saveandprint表示是否保存二维码并显示
 */
// public static function png($text, $outfile=false, $level=QR_ECLEVEL_L, $size=3, $margin=4, $saveandprint=false)    
// {   
//     $enc = QRencode::factory($level, $size, $margin);   
//     return $enc->encodePNG($text, $outfile, $saveandprint=false);   
// } 
?>