<?php
//定义图片保存目录，需给777权限
define("IMG_PATH","/home/ljlia/www/img/");

//获取页面内容
$url = "http://699pic.com/best.html";

//1.该函数会将获取到的内容放在一个字符串中
//$str = getResources($url);

//2.使用curl
$str = curlResources($url);

//匹配出img标签中的 src属性的信息;这里换成data-original属性,是因为该页面使用了延时加载机制
preg_match_all("|<img[^>]+data-original=['\" ]?([^ '\"?]+)['\" >]|U",$str,$array,PREG_SET_ORDER);

//因上一步中 我们选择了PREG_SET_ORDER排序,故$value[1]中便为我们所要下载图片的路径
$k = 0;
foreach ($array as $key=>$value)
{
    $res = downLoadImg($value[1]);
    if($res)
    {
        $k++;
    }
}
echo '成功抓取的图片张数为'.$k;

/**
 * 获取图片内容
 */
function getResources($url)
{
    $str = file_get_contents($url);
    if(empty($str)) exit('抓取页面失败！');
    return $str;
}

/**
 * 单张图片下载
 */
function downLoadImg($imgUrl)
{
    $imgInfo = pathinfo($imgUrl);
    $imgPath = IMG_PATH.$imgInfo['basename'];
    if(file_exists($imgPath))
    {
        return false;
    }
    $imgData = getResources($imgUrl);
    $imgRes = file_put_contents($imgPath, $imgData);
    if(empty($imgRes))
    {
        exit('生成图片失败！'.$imgPath);
    }else
    {
        return true;
    }
}

/**
 * 使用curl获取远程图片
 */
function curlResources($url)
{
    $curl = curl_init(); //初始化curl
    curl_setopt($curl, CURLOPT_URL, $url); //请求路径
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//禁止服务器端进行证书认证
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);//不检查证书
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//显示输出结果
    curl_setopt($curl, CURLOPT_POST, true);//post传输数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, '');//传递参数
    $responseText = curl_exec($curl);//执行并返回结果
    //var_dump(curl_error($curl));exit;//查看异常
    curl_close($curl);//关闭会话
    if(empty($responseText)) exit('抓取页面错误');
    return $responseText;
}
?>