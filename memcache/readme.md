------------------------------------------------------------------------------------------------------------------------------------
Memcached 是一个高性能的分布式内存对象缓存系统，用于动态Web应用以减轻数据库负载。它通过在内存中缓存数据和对象来减少读取数据库的次数，从而提高动态、数据库驱动网站的速度。Memcached基于一个存储键/值对的hashmap。其守护进程（daemon ）是用C写的，但是客户端可以用任何语言来编写，并通过memcached协议与守护进程通信。

------------------------------------------------------------------------------------------------------------------------------------
ubuntu下安装memcached
sudo apt-get install memcached 

安装Memcache客户端（PHP5为示例）
sudo apt-get install php5-memcache

安装完以后我们需要在php.ini里进行简单的配置,打开/etc/php5/apache2/php.ini文件在末尾添加如下内容：

[Memcache]
 
; 是否在遇到错误时透明地向其他服务器进行故障转移。
memcache.allow_failover = On
 
; 接受和发送数据时最多尝试多少个服务器，只在打开memcache.allow_failover时有效。
memcache.max_failover_attempts = 20
 
;数据将按照此值设定的块大小进行转移。此值越小所需的额外网络传输越多。
; 如果发现无法解释的速度降低，可以尝试将此值增加到32768。
memcache.chunk_size = 8192
 
; 连接到memcached服务器时使用的默认TCP端口。
memcache.default_port = 11111
 
; 控制将key映射到server的策略。默认值”standard”表示使用先前版本的老hash策略。
; 设为”consistent”可以允许在连接池中添加/删除服务器时不必重新计算key与server之间的映射关系。
memcache.hash_strategy = “standard”

; 控制将key映射到server的散列函数。默认值”crc32″使用CRC32算法，而”fnv”则表示使用FNV-1a算法。
; FNV-1a比CRC32速度稍低，但是散列效果更好。
memcache.hash_function = “crc32″

最后，保存php.ini,执行sudo/etc/init.d/apache2 restart重启Apache。
---------------------------------------------------------------------------------------------------------------------------------
启动
#memcached -d -m 128 -p 11111 -u root
memcached -d -u root -l 127.0.0.1 -p11222 -u 11222
查看是否启用
ps -ef|grep memcached

这里需要说明一下memcached服务的启动参数：
-p 监听的端口
-l 连接的IP地址, 默认是本机
-d start 启动memcached服务
-d restart 重起memcached服务
-d stop|shutdown 关闭正在运行的memcached服务
-d install 安装memcached服务
-d uninstall 卸载memcached服务
-u 以的身份运行 (仅在以root运行的时候有效)
-m 最大内存使用，单位MB。默认64MB
-M 内存耗尽时返回错误，而不是删除项
-c 最大同时连接数，默认是1024
-f 块大小增长因子，默认是1.25-n 最小分配空间，key+value+flags默认是48
-h 显示帮助
--------------------------------------------------------------------------------------------------------------------------------------------------
php代码测试
<?php
    $mem = new Memcache; //创建Memcache对象
    $mem->connect('127.0.0.1', 11222); //连接Memcache服务器
    $val = '这是一个Memcache的测试.';
    $mem->set('key', $val, 0, 120); //增加插入一条缓存，缓存时间为120s
    if(($k = $mem->get('key')))
    { //判断是否获取到指定的key
        echo 'fromcache:'.$k;
    } else {
        echo 'normal'; //这里我们在实际使用中就需要替换成查询数据库并创建缓存.
    }
?>
-----------------------------------------------------------------------------------------------------------------------------------------------------
php5-memcache扩展提供的方法

Memcache::connect                 — 连接memcache服务器
    bool Memcache::connect ( string $host [, int $port [, int $timeout ]] )
    $memcache->connect(‘memcache_host‘, 11211);

Memcache::set                       — 添加一个值，如果已经存在，则覆写
    bool Memcache::set ( string $key , mixed $var [, int $flag [, int $expire ]] )
    memcache_set($memcache_obj, ‘var_key‘, ‘some variable‘, 0, 30);

Memcache::get                       — 获取一个key值
    $var = $memcache_obj->get(Array(‘some_key‘, ‘second_key‘));

Memcache::add                       — 添加一个值，如果已经存在，则返回false
    bool Memcache::add ( string $key , mixed $var [, int $flag [, int $expire ]] )
    $memcache_obj->add(‘var_key‘, ‘test variable‘, FALSE, 30);
Memcache::addServer             — 添加一个可供使用的服务器地址


Memcache::delete                    — 删除一个key值
    bool Memcache::delete ( string $key [, int $timeout ] )
    $memcache_obj->delete(‘key_to_delete‘, 10);//10秒内删除

Memcache::flush                     — 清除所有缓存的数据
    $memcache_obj->flush();

Memcache::replace                   — R对一个已有的key进行覆写操作
    bool Memcache::replace ( string $key , mixed $var [, int $flag [, int $expire ]] )
    $memcache_obj->replace("test_key", "some variable", FALSE, 30);

Memcache::close                     — 关闭一个Memcache对象
    $memcache_obj->close();

Memcache::getExtendedStats  　— 获取进程池中所有进程的运行系统统计
Memcache::getServerStatus     　— 获取运行服务器的参数
Memcache::getStats                    — 返回服务器的一些运行统计信息
Memcache::getVersion                — 返回运行的Memcache的版本信息
Memcache::increment                 — 对保存的某个key中的值进行加法操作
Memcache::pconnect                   — 创建一个Memcache的持久连接对象
Memcache::setCompressThreshold — 对大于某一大小的数据进行压缩
Memcache::setServerParams       — 在运行时修改服务器的参数
memcache_debug                        — 控制调试功能
Memcache::decrement                — 对保存的某个key中的值进行减法操作


