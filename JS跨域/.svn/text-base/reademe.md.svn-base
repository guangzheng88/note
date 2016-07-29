AJAX跨域请求

什么是跨域？
概念：只要协议、域名、端口有任何一个不同，都被当作是不同的域。

一、CORS的原理：
     CORS定义一种跨域访问的机制，可以让AJAX实现跨域访问。CORS 允许一个域上的网络应用向另一个域提交跨域 AJAX 请求。实现此功能非常简单，只需由服务器发送一个响应标头即可

     <?php
        header("Access-Control-Allow-Origin:*");
        echo 'AJAX跨域请求成功！';
    ?>

    * 号表示允许任何域向我们的服务端提交请求，也可以设置指定的域名，如域名 http://www.test2.com ，那么就允许来自这个域名的请求
优劣点：
    １．CORS是W3C中一项较新的方案，所以部分浏览器还没有对其进行支持或者完美支持
    ２．安全问题。CORS提供了一种跨域请求方案，但没有为安全访问提供足够的保障机制，如果你需要信息的绝对安全，不要依赖CORS当中的权限制度，应当使用更多其它的措施来保障，比如OAuth2。
 
自认为的cors使用场景：
    cors在移动终端支持的不错，可以考虑在移动端全面尝试；PC上有不兼容和没有完美支持，所以小心踩坑。
    jsonp是get形式，承载的信息量有限，所以信息量较大时CORS是不二选择；
    配合新的JSAPI(fileapi、xhr2等)一起使用，实现强大的新体验功能。

二、JSONP
1、什么是JSONP
　　JSONP(JSON with Padding)是JSON的一种“使用模式”，可用于解决主流浏览器的跨域数据访问的问题。其核心思想是利用JS标签里面的跨域特性进行跨域数据访问，在JS标签里面存在的是一个跨域的URL，实际执行的时候通过这个URL获得一段字符串，这段返回的字符串必须是一个合法的JS调用，通过EVAL这个字符串来完成对获得的数据的处理。
　　JSONP是一个非官方的协议，它允许在服务器端集成Script tags返回至客户端，通过javascript callback的形式实现跨域访问（这仅仅是JSONP简单的实现形式）

２．什么是json
JSON是一种基于文本的数据交换方式，或者叫做数据描述格式

JSON的优点：
1、基于纯文本，跨平台传递极其简单；
2、Javascript原生支持，后台语言几乎全部支持；
3、轻量级数据格式，占用字符数量极少，特别适合互联网传递；
4、可读性较强，虽然比不上XML那么一目了然，但在合理的依次缩进之后还是很容易识别的；
5、容易编写和解析，当然前提是你要知道数据结构；
JSON的缺点当然也有，但在作者看来实在是无关紧要的东西，所以不再单独说明。

JSON的格式或者叫规则：
    JSON能够以非常简单的方式来描述数据结构，XML能做的它都能做，因此在跨平台方面两者完全不分伯仲。
    1、JSON只有两种数据类型描述符，大括号{}和方括号[]，其余英文冒号:是映射符，英文逗号,是分隔符，英文双引号""是定义符。
    2、大括号{}用来描述一组“不同类型的无序键值对集合”（每个键值对可以理解为OOP的属性描述），方括号[]用来描述一组“相同类型的有序数据集合”（可对应OOP的数组）。
    3、上述两种集合中若有多个子项，则通过英文逗号,进行分隔。
    4、键值对以英文冒号:进行分隔，并且建议键名都加上英文双引号""，以便于不同语言的解析。
    5、JSON内部常用数据类型无非就是字符串、数字、布尔、日期、null 这么几个，字符串必须用双引号引起来，其余的都不用，日期类型比较特殊，这里就不展开讲述了，只是建议如果客户端没有按日期排序功能需求的话，那么把日期时间直接作为字符串传递就好，可以省去很多麻烦。

    $.ajax({
            type: "get",
            async: false,
            url: "http://mobile.my/index.php",
            dataType: "jsonp",
            jsonp: "callback",//传递给请求处理程序或页面的，标识jsonp回调函数名(一般为:callback)
            jsonpCallback: "GetData",//callback的function名称
            success: function (data) {
                alert(data.price);
                alert(data.code);
            },
            error: function () {
                alert('fail');
            }
        });

        请求url其实就是：http://mobile.my/index.php?callback=GetData&_=1468391435206". 
        在php页面需要返回回调函数方法名GetData和json数据，如下：
        echo "{GetData}({'code':'跨域成功！','price':99})";
jsop只支持get方式

三、使用HTML5的window.postMessage方法跨域

postMessage()方法允许来自不同源的脚本采用异步方式进行有限的通信，可以实现跨文本档、多窗口、跨域消息传递
这个功能实现也非常简单主要包括接受信息的”message”事件和发送消息的”postMessage”方法

postMessage(data,origin)方法接受两个参数

 1.data:要传递的数据，html5规范中提到该参数可以是JavaScript的任意基本类型或可复制的对象，然而并不是所有浏览器都做到了这点儿，部分浏览器只能处理字符串参数，所以我们在传递参数的时候需要使用JSON.stringify()方法对对象参数序列化，在低版本IE中引用json2.js可以实现类似效果。

2.origin：字符串参数，指明目标窗口的源，协议+主机+端口号[+URL]，URL会被忽略，所以可以不写，这个参数是为了安全考虑，postMessage()方法只会将message传递给指定窗口，当然如果愿意也可以建参数设置为"*"，这样可以传递给任意窗口，如果要指定和当前窗口同源的话设置为"/"。
window.onload=function(){
            window.frames[0].postMessage('getcolor','http://lslib.com');
        }
接收消息
window.addEventListener('message',function(e){
                if(e.source!=window.parent) return;
                var color=container.style.backgroundColor;
                window.parent.postMessage(color,'*');
            },false);

四、利用window.name 实现跨域