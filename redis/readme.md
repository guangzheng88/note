                                                                1【redis是什么】

redis是一个开源的、使用C语言编写的、支持网络交互的、可基于内存也可持久化的Key-Value数据库。
Redis是常用基于内存的Key-Value数据库，比Memcache更先进，支持多种数据结构，高效，快速。用Redis可以很轻松解决高并发的数据访问问题；做为时时监控信号处理也非常不错。
redis的官网地址，非常好记，是redis.io。
--------------------------------------------------------------------------------------------------------------------------------------
                                                        在Linux Ubuntu中安装Redis数据库

 sudo apt-get install redis-server

 安装完成后，Redis服务器会自动启动，我们检查Redis服务器程序
  ps -aux|grep redis

  # 通过启动命令检查Redis服务器状态
sudo /etc/init.d/redis-server status
redis-server is running

3. 通过命令行客户端访问Redis
在本机输入redis-cli命令就可以启动，客户端程序访问Redis服务器。

redis-cli
--------------------------------------------------------------------------------------------------------------------------------------
                                                            ubuntu12.04 php链接redis

1.git clone https://github.com/nicolasff/phpredis.git phpredis
2.cd phpredis/
3.sudo apt-get install redis-server php5-dev build-essential xsltproc
4.phpize
5.  ./configure
6   make
7.  ./mkdeb-apache2.sh
8.   sudo dpkg -i phpredis-i686.deb
9.sudo vim /etc/php5/apache2/php.ini
＃添加一行代码，先查看redis.so文件是否存在（这是我本地的目录结构）
extension="/usr/lib/php5/20090626+lfs/redis.so"

成功后查看新建php文件查看是否链接成功
<?php
    $redis = new Redis();
    $redis->connect('127.0.0.1',6379);
    $result = $redis->auth('123456'); //密码
//var_dump($result); exit;//链接结果：bool(true) 
    $redis->set('random', "123");
    echo $redis->get('random');
?>
--------------------------------------------------------------------------------------------------------------------------------------

##########################################################################
                                                                        redis常用命令

1.通过命令行访问redis
    redis-cli

2.查看所以
    keys *

3.设置值
    set key1 'hello'

4.查看值
    get key1

5.插入列表记录
    LPUSH key3 a　　LPUSH key3 b　　RPUSH key3 c

6.打印列表记录，按从左到右的顺序
     LRANGE key3 0 3

7.增加一个哈希表记录key4 (一定要用双引号)
    HSET key4 name "John Smith"

8.在哈希表中插入，email的Key和Value的值
    HSET key4 email "abc@gmail.com"

9.打印哈希表中，name为key的值     =>  HGET
    HGET key4 name

10.打印整个哈希表      =>  HGETALL
    HGETALL key4

11.增加一条哈希表记录key5，一次插入多个Key和value的值  =>  HMSET
    HMSET key5 username antirez password P1pp0 age 3

12.打印哈希表中，username和age为key的值    =>  HMGET
    HMGET key5 username age

13.删除记录
    del key1    

14.INCRBY key increment
     INCRBY rank 20

15.递减DECRBY key decrement
    DECR key     DECRBY key 5

将 key 所储存的值加上增量 increment 。

##########################################################################

2.在Linux Ubuntu中安装Redis数据库

 sudo apt-get install redis-server

 安装完成后，Redis服务器会自动启动，我们检查Redis服务器程序
  ps -aux|grep redis

  # 通过启动命令检查Redis服务器状态
sudo /etc/init.d/redis-server status
redis-server is running

3. 通过命令行客户端访问Redis
在本机输入redis-cli命令就可以启动，客户端程序访问Redis服务器。

redis-cli

4.基本的Redis客户端命令操作

# 查看所有的key列表
redis 127.0.0.1:6379> keys *
(empty list or set)

# 增加一条记录key1
redis 127.0.0.1:6379> set key1 "hello"
OK

# 打印记录
redis 127.0.0.1:6379> get key1
"hello"

#自增
# 增加一条数字记录key2
set key2 1
OK

# 让数字自增
redis 127.0.0.1:6379> INCR key2
(integer) 2
redis 127.0.0.1:6379> INCR key2
(integer) 3

# 打印记录
redis 127.0.0.1:6379> get key2
"3"

增加一条列表记录key3


# 增加一个列表记录key3
redis 127.0.0.1:6379> LPUSH key3 a
(integer) 1

# 从左边插入列表
redis 127.0.0.1:6379> LPUSH key3 b
(integer) 2

# 从右边插入列表
redis 127.0.0.1:6379> RPUSH key3 c
(integer) 3

# 打印列表记录，按从左到右的顺序
redis 127.0.0.1:6379> LRANGE key3 0 3
1) "b"
2) "a"
3) "c"

# 增加一个哈希记表录key4
redis 127.0.0.1:6379> HSET key4 name "John Smith"
(integer) 1

# 在哈希表中插入，email的Key和Value的值
redis 127.0.0.1:6379> HSET key4 email "abc@gmail.com"
(integer) 1

# 打印哈希表中，name为key的值
redis 127.0.0.1:6379> HGET key4 name
"John Smith"

# 打印整个哈希表
redis 127.0.0.1:6379> HGETALL key4
1) "name"
2) "John Smith"
3) "email"
4) "abc@gmail.com"

----------------------------------------------------------------------------------------------------------------------------------------------------
4. 修改Redis的配置

4.1 使用Redis的访问账号

默认情况下，访问Redis服务器是不需要密码的，为了增加安全性我们需要设置Redis服务器的访问密码。设置访问密码为redisredis。

用vi打开Redis服务器的配置文件redis.conf

~ sudo vi /etc/redis/redis.conf

#取消注释requirepass
requirepass redisredis
4.2 让Redis服务器被远程访问

默认情况下，Redis服务器不允许远程访问，只允许本机访问，所以我们需要设置打开远程访问的功能。

用vi打开Redis服务器的配置文件redis.conf

~ sudo vi /etc/redis/redis.conf

#注释bind
#bind 127.0.0.1
修改后，重启Redis服务器。

~ sudo /etc/init.d/redis-server restart
Stopping redis-server: redis-server.
Starting redis-server: redis-server.
未使用密码登陆Redis服务器

~ redis-cli

redis 127.0.0.1:6379> keys *
(error) ERR operation not permitted
发现可以登陆，但无法执行命令了。

登陆Redis服务器，输入密码

#使用密码登录redis

~  redis-cli -a redisredis

redis 127.0.0.1:6379> keys *
1) "key2"
2) "key3"
3) "key4"
登陆后，一切正常。

我们检查Redis的网络监听端口

检查Redis服务器占用端口
~ netstat -nlt|grep 6379
tcp        0      0 0.0.0.0:6379            0.0.0.0:*               LISTEN
我们看到从之间的网络监听从 127.0.0.1:6379 变成 0 0.0.0.0:6379，表示Redis已经允许远程登陆访问。

我们在远程的另一台Linux访问Redis服务器

~ redis-cli -a redisredis -h 192.168.1.199

redis 192.168.1.199:6379> keys *
1) "key2"
2) "key3"
3) "key4"

-------------------------------------------------------------------------------------------------------------------------------

redis是一种高级的key:value存储系统，其中value支持五种数据类型：

字符串（strings）
字符串列表（lists）
字符串集合（sets）
有序字符串集合（sorted sets）
哈希（hashes）
而关于key，有几个点要提醒大家：

key不要太长，尽量不要超过1024字节，这不仅消耗内存，而且会降低查找的效率；
key也不要太短，太短的话，key的可读性会降低；
在一个项目中，key最好使用统一的命名模式，例如user:001:passwd。

-----------------------------------------------------------------------------------------------------------------------------
【redis数据结构 – lists】
//新建一个list叫做mylist，并在列表头部插入元素"1"
127.0.0.1:6379> lpush mylist "1" 
//返回当前mylist中的元素个数
(integer) 1 
//在mylist右侧插入元素"2"
127.0.0.1:6379> rpush mylist "2" 
(integer) 2
//在mylist左侧插入元素"0"
127.0.0.1:6379> lpush mylist "0" 
(integer) 3
//列出mylist中从编号0到编号1的元素
127.0.0.1:6379> lrange mylist 0 1 
1) "0"
2) "1"
//列出mylist中从编号0到倒数第一个元素
127.0.0.1:6379> lrange mylist 0 -1 
1) "0"
2) "1"
3) "2"
------------------------------------------------------------------------------------------------------------------------------
【redis数据结构 – 无序集合】
//向集合myset中加入一个新元素"one"
127.0.0.1:6379> sadd myset "one" 
(integer) 1
127.0.0.1:6379> sadd myset "two"
(integer) 1
//列出集合myset中的所有元素
127.0.0.1:6379> smembers myset 
1) "one"
2) "two"
//判断元素1是否在集合myset中，返回1表示存在
127.0.0.1:6379> sismember myset "one" 
(integer) 1
//判断元素3是否在集合myset中，返回0表示不存在
127.0.0.1:6379> sismember myset "three" 
(integer) 0
//新建一个新的集合yourset
127.0.0.1:6379> sadd yourset "1" 
(integer) 1
127.0.0.1:6379> sadd yourset "2"
(integer) 1
127.0.0.1:6379> smembers yourset
1) "1"
2) "2"
//对两个集合求并集
127.0.0.1:6379> sunion myset yourset 
1) "1"
2) "one"
3) "2"
4) "two"
-----------------------------------------------------------------------------------------------------------------------------
【redis数据结构 – 有序集合】
127.0.0.1:6379> zadd myzset 1 baidu.com 
(integer) 1
//向myzset中新增一个元素360.com，赋予它的序号是3
127.0.0.1:6379> zadd myzset 3 360.com 
(integer) 1
//向myzset中新增一个元素google.com，赋予它的序号是2
127.0.0.1:6379> zadd myzset 2 google.com 
(integer) 1
//列出myzset的所有元素，同时列出其序号，可以看出myzset已经是有序的了。
127.0.0.1:6379> zrange myzset 0 -1 with scores 
1) "baidu.com"
2) "1"
3) "google.com"
4) "2"
5) "360.com"
6) "3"
//只列出myzset的元素
127.0.0.1:6379> zrange myzset 0 -1 
1) "baidu.com"
2) "google.com"
3) "360.com"
---------------------------------------------------------------------------------------------------------------------------
【redis数据结构 – 哈希】
hashes存的是字符串和字符串值之间的映射，比如一个用户要存储其全名、姓氏、年龄等等，就很适合使用哈希。
//建立哈希，并赋值
127.0.0.1:6379> HMSET user:001 username antirez password P1pp0 age 34 
OK
//列出哈希的内容
127.0.0.1:6379> HGETALL user:001 
1) "username"
2) "antirez"
3) "password"
4) "P1pp0"
5) "age"
6) "34"
//更改哈希中的某一个值
127.0.0.1:6379> HSET user:001 password 12345 
(integer) 0
//再次列出哈希的内容
127.0.0.1:6379> HGETALL user:001 
1) "username"
2) "antirez"
3) "password"
4) "12345"
5) "age"
6) "34"
-------------------------------------------------------------------------------------------------------------------------------------
【聊聊redis的事务处理】
务是指“一个完整的动作，要么全部执行，要么什么也没有做”
这四个指令构成了redis事务处理的基础:
1.MULTI用来组装一个事务；
2.EXEC用来执行一个事务；
3.DISCARD用来取消一个事务；
4.WATCH用来监视一些key，一旦这些key在事务执行之前被改变，则取消事务的执行。

redis> MULTI //标记事务开始
OK
redis> INCR user_id //多条命令按顺序入队
QUEUED
redis> INCR user_id
QUEUED
redis> INCR user_id
QUEUED
redis> PING
QUEUED
redis> EXEC //执行
1) (integer) 1
2) (integer) 2
3) (integer) 3
4) PONG
-------------------------------------------------------------------------------------------------------------------------------------
好了，我们来说说最后一个指令“WATCH”，这是一个很好用的指令，它可以帮我们实现类似于“乐观锁”的效果，即CAS（check and set）。

WATCH本身的作用是“监视key是否被改动过”，而且支持同时监视多个key，只要还没真正触发事务，WATCH都会尽职尽责的监视，一旦发现某个key被修改了，在执行EXEC时就会返回nil，表示事务无法触发。

127.0.0.1:6379> set age 23
OK
127.0.0.1:6379> watch age //开始监视age
OK
127.0.0.1:6379> set age 24 //在EXEC之前，age的值被修改了
OK
127.0.0.1:6379> multi
OK
127.0.0.1:6379> set age 25
QUEUED
127.0.0.1:6379> get age
QUEUED
127.0.0.1:6379> exec //触发EXEC
(nil) //事务无法被执行
----------------------------------------------------------------------------------------------------------------------------------













