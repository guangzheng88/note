<?php
    for ($i=1;$i<=20;$i++)
    {
        $data[$i]['id'] = $i;
        $data[$i]['title'] = '标题'.$i;
        $data[$i]['category'] = '栏目'.$i;
        $data[$i]['author'] = '小编'.$i;
        $data[$i]['time'] = '2016-07-'.rand(10,31).' '.rand(10,23).':'.rand(10,59).':'.rand(10,59);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>导出excel</title>
</head>
<body>
<table style="width:100%;">
    <tr>
        <td>ID</td>
        <td>标题</td>
        <td>栏目</td>
        <td>作者</td>
        <td>发布时间</td>
    </tr>
    <?php foreach ($data as $val):?>
        <tr>
            <td><?php echo $val['id']?></td>
            <td><?php echo $val['title']?></td>
            <td><?php echo $val['category']?></td>
            <td><?php echo $val['author']?></td>
            <td><?php echo $val['time']?></td>
        </tr>
    <?php endforeach;?>
</table>
<form action="excel.php?act=in" method="post" style="float:left;"  enctype="multipart/form-data">
    <input  class="r_left" type="file" name="excel" style="width:150px;">
    <input type="submit" name="" value="导入EXCEL" style="margin-right:80px;">
</form>
<form action="excel.php?act=out" method="post" style="float:left;">
    <textarea style="display:none;" name="content"><?php echo serialize($data);?></textarea>
    <input type="submit" name="" value="导出EXCEL">
</form>

</body>
</html>