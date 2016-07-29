<?php
$class = new Excel();
if($_GET['act'] == 'out'){
    //导出excel
    $res = $class->newsExportExcel();
}else{
    //导入excel
    $res = $class->newsEnterExcel();
}
/**
 * 导出excel类
 */
class Excel
{
    public function __construct()
    {
        //加载PHPExcel
        include_once('./PHPExcel/PHPExcel.php');
        include_once('./PHPExcel/PHPExcel/IOFactory.php');
        include_once('./PHPExcel/PHPExcel/Reader/Excel5.php');
    }

    /**
     * 导出文章excel
     */
    public function newsExportExcel()
    {
        $excel = new PHPExcel();
        //excel表格式
        $letter = range('A','E');
        //表头
        $tableHeader = array('ID','标题','栏目','作者','发布时间');
        for($i = 0;$i < count($tableHeader);$i++) 
        {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableHeader[$i]");
        }
        //表格数据
       $info = unserialize($_POST['content']);
        foreach ($info as $val)
        {
            $data[] = array($val['id'],$val['title'],$val['category'],$val['author'],$val['time']);
        }
        for ($i = 2;$i <= count($data) + 1;$i++) 
        {
            $j = 0;
            foreach ($data[$i - 2] as $key=>$value) 
            {
                $excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
                $j++;
            }
        }
        //创建excel输入对象
        $write = new PHPExcel_Writer_Excel5($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="文章列表.xls"');//文件名称
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }    

    /**
     * 导入文章excel
     */
    public function newsEnterExcel()
    {
        if(!empty($_FILES['excel']['name']))
        {
            $tmp_file = $_FILES['excel']['tmp_name'];
            $file_types = explode ( ".", $_FILES ['excel'] ['name'] );
            $file_type = $file_types [count ( $file_types ) - 1];
             /*判别是不是.xls文件，判别是不是excel文件*/
             if (strtolower ( $file_type ) != "xls")              
            {
                  exit( '不是Excel文件，重新上传' );
            }
            /*设置上传路径*/
            $savePath = './PHPExcel/upload/';
            /*以时间来命名上传的文件*/
            $str = date('YmdHis'); 
            $file_name = $str . "." . $file_type;
             /*是否上传成功*/
            if (! copy ( $tmp_file, $savePath . $file_name )) 
            {
                exit( '上传失败' );
            }
            $filename =   $savePath . $file_name;
            if($file_type=="xlsx"){  
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');  
            }else{  
                $objReader = PHPExcel_IOFactory::createReader('Excel5');  
            }
            $objPHPExcel = $objReader->load($filename);//指定的文件
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();//取得总行数
            $highestColumn = $sheet->getHighestColumn();//取得总列数
            $array = range('A',$highestColumn);
            $k = 0;
            //循环读取excel文件，读取一条插入一条
            for ($j=2;$j<=$highestRow;$j++)
            {
                $data['title'] = $sheet->getCellByColumnAndRow(1, $j)->getValue(); 
                $data['category'] = $sheet->getCellByColumnAndRow(2, $j)->getValue(); 
                $data['author'] = $sheet->getCellByColumnAndRow(3, $j)->getValue(); 
                $data['create_time'] = $sheet->getCellByColumnAndRow(4, $j)->getValue();
var_dump($data);exit;                
                //插入数据库
                //$res = $this->db->insert('article',$data);
                if(true == $res){
                    $k++;
                }
            }
            echo '共有'.$k.'条数据插入成功！';  
            exit;
        }else
        {
            exit('上传文件不能为空！');
        }
        
    }
        
}
?>
