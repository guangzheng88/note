<!DOCTYPE html>
<html>
<head>
    <title>文件上传</title>
</head>
<body>
    <form name="upload" method="post" action="?action=save" enctype="multipart/form-data" style="margin:0">
       <input type="file" name="attach[]" />
       <input type="file" name="attach[]" />
       <input type="submit" name="submit" value="上 传" />
    </form>
</body>
</html>

<?php
    include './upload.php';
    if ($_GET['action'] == 'save') 
    {
        $up = new upload('attach');

        //dirname() 函数返回路径中的目录部分。__FILE__ PHP魔术常量,返回当前执行PHP脚本的完整路径和文件名,包含一个绝对路径
        $up->set_dir(dirname(__FILE__).'/upload/','{y}/{m}');//设置保存路径
        $up->set_thumb(100,80);//生成缩略图宽高
        $up->set_watermark(dirname(__FILE__).'/logo.png',1,80);//水印
        $fs = $up->execute();
       
        var_dump($fs);
    }
?>