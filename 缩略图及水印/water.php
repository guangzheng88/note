<?php
$water = new Water();
//生成文字水印示例
//$water->waterText('a.jpg','2016-07-21','',10,-3,120,130,array(255,0,0));

//生成图片水印示例
$water->waterImg('b.jpg','a.jpg');

/**
 * 添加水印
 */
class Water
{
    /**
     * 添加文字水印
     *@param [string] dst_path 图片源文件
     *@param [string] text 需添加的文字
     *@param [float] size 字体大小
     *@param [float] angle 字体倾斜角度
     *@param [int] x x起点
     *@param [int] y y起点
     *@param [array] textColor 字体颜色 array(red,green,blue)
     */
    public function waterText($dst_path,$text,$newFile='',$size=13,$angle=0, $x=20, $y=20, $textColor=array(0x00,0x00,0x00))
    {
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));

        //打上文字
        $font = './Elephant.ttf';//字体
        $black = imagecolorallocate($dst, $textColor[0], $textColor[1], $textColor[2]);//字体颜色
        imagefttext($dst, $size, $angle, $x, $y, $black, $font, $text);

        //输出图片
        list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        switch ($dst_type) {
            case 1://GIF
            header('Content-Type: image/gif');
            imagegif($dst);
            break;
            case 2://JPG
            header('Content-Type: image/jpeg');
            $newFile = empty($newFile) ? '' : $newFile.'.jpg';
            $res = imagejpeg($dst,$newFile);
            break;
            case 3://PNG
            header('Content-Type: image/png');
            imagepng($dst);
            break;
            default:
            break;
        }
        imagedestroy($dst);
    }

    /**
     * 生成图片水印
     *@param [string] dst_path 源图
     *@param [string] src_path logo图
     */
    public function waterImg($dst_path,$src_path)
    {
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $src = imagecreatefromstring(file_get_contents($src_path));

        //获取水印图片的宽高
        list($src_w, $src_h) = getimagesize($src_path);

        //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
        imagecopymerge($dst, $src, 10, 10, 0, 0, $src_w, $src_h, 50);
        //如果水印图片本身带透明色，则使用imagecopy方法
        //imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);

        //输出图片
        list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        switch ($dst_type) {
            case 1://GIF
            header('Content-Type: image/gif');
            imagegif($dst);
            break;
            case 2://JPG
            header('Content-Type: image/jpeg');
            imagejpeg($dst);
            break;
            case 3://PNG
            header('Content-Type: image/png');
            imagepng($dst);
            break;
            default:
            break;
        }

        imagedestroy($dst);
        imagedestroy($src);
    }
}
?>