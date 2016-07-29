<?php
//第一种cors
//header("Access-Control-Allow-Origin:*");
//echo 'AJAX跨域请求成功！';

//第二种jsonp
$fun = $_GET['callback'];
echo "{GetData}({'code':'跨域成功！','price':99})";

//回调函数格式
// flightHandler({
//     "code": "CA1998",
//     "price": 1780,
//     "tickets": 5
// });
?>
