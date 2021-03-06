linux的定时任务　crontab
        1．命令格式：
        crontab [-u user] file
        crontab [-u user] [ -e | -l | -r ]

        2．命令功能：
        通过crontab 命令，我们可以在固定的间隔时间执行指定的系统指令或 shell script脚本。时间间隔的单位可以是分钟、小时、日、月、周及以上的任意组合。这个命令非常设合周期性的日志分析或数据备份等工作。

        3．命令参数：
        -u user：用来设定某个用户的crontab服务，例如，“-u ixdba”表示设定ixdba用户的crontab服务，此参数一般有root用户来运行。
        file：file是命令文件的名字,表示将file做为crontab的任务列表文件并载入crontab。如果在命令行中没有指定这个文件，crontab命令将接受标准输入（键盘）上键入的命令，并将它们载入crontab。
        -e：编辑某个用户的crontab文件内容。如果不指定用户，则表示编辑当前用户的crontab文件。
        -l：显示某个用户的crontab文件内容，如果不指定用户，则表示显示当前用户的crontab文件内容。
        -r：从/var/spool/cron目录中删除某个用户的crontab文件，如果不指定用户，则默认删除当前用户的crontab文件。
        -i：在删除用户的crontab文件时给确认提示。

        crontab文件的含义：
        用户所建立的crontab文件中，每一行都代表一项任务，每行的每个字段代表一项设置，它的格式共分为六个字段，前五段是时间设定段，第六段是要执行的命令段，格式如下：
        minute   hour   day   month   week   command
        其中：
        minute： 表示分钟，可以是从0到59之间的任何整数。
        hour：表示小时，可以是从0到23之间的任何整数。
        day：表示日期，可以是从1到31之间的任何整数。
        month：表示月份，可以是从1到12之间的任何整数。
        week：表示星期几，可以是从0到7之间的任何整数，这里的0或7代表星期日。
        command：要执行的命令，可以是系统命令，也可以是自己编写的脚本文件

        星号（*）：代表所有可能的值，例如month字段如果是星号，则表示在满足其它字段的制约条件后每月都执行该命令操作。
        逗号（,）：可以用逗号隔开的值指定一个列表范围，例如，“1,2,5,7,8,9”
        中杠（-）：可以用整数之间的中杠表示一个整数范围，例如“2-6”表示“2,3,4,5,6”
        正斜线（/）：可以用正斜线指定时间的间隔频率，例如“0-23/2”表示每两小时执行一次。同时正斜线可以和星号一起使用，例如*/10，如果用在minute字段，表示每十分钟执行一次。
        在上午8点到11点的第3和第15分钟执行命令   3,15 8-11 * * * command
        每晚的21:30重启smb    30 21 * * * /etc/init.d/smb restart    
        每小时执行/etc/cron.hourly目录内的脚本命令   01   *   *   *   *     root run-parts /etc/cron.hourly

        使用注意事项
        1.注意环境变量问题
        2.注意清理系统用户的邮件日志
            忽略日志输出：0 */3 * * * /usr/local/apache2/apachectl restart >/dev/null 2>&1
        3.系统级任务调度与用户级任务调度
        4. 其他注意事项
            新创建的cron job，不会马上执行，至少要过2分钟才执行。如果重启cron则马上执行。
            当crontab突然失效时，可以尝试/etc/init.d/crond restart解决问题。或者查看日志看某个job有没有执行/报错tail -f /var/log/cron。
            千万别乱运行crontab -r。它从Crontab目录（/var/spool/cron）中删除用户的Crontab文件。删除了该用户的所有crontab都没了。
            在crontab中%是有特殊含义的，表示换行的意思。如果要用的话必须进行转义\%，如经常用的date ‘+%Y%m%d’在crontab里是不会执行的，应该换成date ‘+\%Y\%m\%d’。


.linux下的权限解释：
    r：对该文件读的权限；如标记为-就是不可读。
    w：对该文件拥有写权限；如果标记为-就是不可写。
    x：该文件拥有执行权限，如果是目录则拥有检索（查看）权限。
    -：无某（读，写，执行，setuid等）权限

    r-- 4   -w- 2　　--x 1　　rw- 6    r-x 5        -wx 3　 rwx 7        --- 0
    
    实际上这里是10位：-rw-r--r--
    使用ls的-l选项可以较完整的形式显示文件的类型（第一位）和权限（后九位）。其中第一位文件类型的可变字符为：

    - 普通文件
    b 特殊块文件（存储在/dev）。
    c 特殊字符文件（存储在/dev）。
    d 目录。
    l 软链接。
    p FIFO（管道文件）。
    s Socket（套接口文件）。
    w Whiteout。　

    剩下的九位，每三位一个部分，一共三个权限部分。分别为：

    文件所有者权限 “owner”
    文件属组权限      “group”
    其他人权限（既不是该文件的所有者也不是属组）　“other”