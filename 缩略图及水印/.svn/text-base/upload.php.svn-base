<?php
   /**
    * 文件上传处理类
    */
    class upload {
        protected $dir; //附件存放物理目录
        protected $time; //自定义文件上传时间
        protected $allow_types; //允许上传附件类型
        protected $field; //上传控件名称
        protected $maxsize; //最大允许文件大小，单位为KB
        protected $thumb_width; //缩略图宽度
        protected $thumb_height; //缩略图高度
        protected $watermark_file; //水印图片地址
        protected $watermark_pos; //水印位置
        protected $watermark_trans;//水印透明度

        /**
         * @description 构造函数
         *@param $field 上传控件名称
         *@param $maxsize 允许大小 默认5M
         *@param $types 允许上传的文件类型
         *@param $time 自定义上传时间戳
         */
        function __construct($field = 'filed', $maxsize = 5, $types = 'jpg|png|jpeg|gif', $time = '') 
        {
            $this->allow_types = explode('|',$types);
            $this->maxsize = $maxsize * 1024 * 1024;
            $this->field = $field;
            $this->time = $time ? $time : time();
        }

         /**
         * @description 执行文件上传
         *@param $file : 水印图片
         *@param $pos : 水印位置
         *@param $trans : 水印透明度
         *@return 返回一个包含上传成功或失败的文件信息数组
         *@return 其中：name 为文件名，上传成功时是上传到服务器上的文件名，上传失败则是本地的文件名
         *@return dir 为服务器上存放该附件的物理路径，上传失败不存在该值
         *@return size 为附件大小，上传失败不存在该值
         *@return flag 为状态标识，1表示成功，-1表示文件类型不允许，-2表示文件大小超出
         *@return thumb 缩略图文件名
         */
        public function execute() 
        {
            $files = array(); //成功上传的文件信息
            $field = $this->field;
            $keys = array_keys($_FILES[$field]['name']);
            foreach ($keys as $key) 
            {
                if (!$_FILES[$field]['name'][$key]) continue;
                $fileext = $this->fileext($_FILES[$field]['name'][$key]); //获取文件扩展名
                $filename = $this->time.mt_rand(100,999).'.'.$fileext; //生成文件名
                $filedir = $this->dir; //附件实际存放目录
                $filesize = $_FILES[$field]['size'][$key]; //文件大小
                
                //文件类型不允许
                if (!in_array($fileext,$this->allow_types)) {
                    $files[$key]['name'] = $_FILES[$field]['name'][$key];
                    $files[$key]['flag'] = -1;
                    continue;
                }
                //文件大小超出
                if ($filesize > $this->maxsize) {
                    $files[$key]['name'] = $_FILES[$field]['name'][$key];
                    $files[$key]['flag'] = -2;
                    continue;
                }
                $files[$key]['name'] = $filename;
                $files[$key]['dir'] = $filedir;
                $files[$key]['size'] = $filesize;
                //保存上传文件并删除临时文件
                if (is_uploaded_file($_FILES[$field]['tmp_name'][$key])) {
                    move_uploaded_file($_FILES[$field]['tmp_name'][$key],$filedir.$filename);
                    @unlink($_FILES[$field]['tmp_name'][$key]);
                    $files[$key]['flag'] = 1;
                    //对图片进行加水印和生成缩略图
                    if (in_array($fileext,array('jpg','png','gif','jpeg'))) {
                        if ($this->thumb_width) {
                            if ($this->create_thumb($filedir.$filename,$filedir.'thumb_'.$filename)) {
                                $files[$key]['thumb'] = 'thumb_'.$filename; //缩略图文件名
                            }
                        }
                        //添加水印
                        $this->create_watermark($filedir.$filename);
                    }
                }
            }
            return $files;
        }

        /**
         * @description 设置并创建文件具体存放的目录
         *@param $basedir : 基目录，必须为物理路径
         *@param $filedir : 自定义子目录，可用参数{y}、{m}、{d}
         */
        public function set_dir($basedir,$filedir = '') 
        {
            $dir = $basedir;
            !is_dir($dir) && @mkdir($dir,0777);
            if (!empty($filedir)) {
                $filedir = str_replace(array('{y}','{m}','{y}'),array(date('Y',$this->time),date('m',$this->time),date('d',$this->time)),strtolower($filedir));
                $dirs = explode('/',$filedir);
                foreach ($dirs as $d) 
                {
                    !empty($d) && $dir .= $d.'/';
                    !is_dir($dir) && @mkdir($dir,0777);
                }
            }
            $this->dir = $dir;
        }

        /**
         * @description 图片缩略图设置，如果不生成缩略图则不用设置
         *@param $width : 缩略图宽度
         *@param $height : 缩略图高度
         */
        public function set_thumb ($width = 0, $height = 0) 
        {
            $this->thumb_width = $width;
            $this->thumb_height = $height;
        }

        /**
         * @description 图片水印设置，如果不生成添加水印则不用设置
         *@param $file : 水印图片
         *@param $pos : 水印位置case 1 : //顶部居左 case 2 : //顶部居中 case 3 : //顶部居右 case 4 : //底部居左 case 5 : //底部居中 case 6 : //底部居右 default 随机
         *@param $trans : 水印透明度
         */
        public function set_watermark ($file, $pos = 6, $trans = 80) {
            $this->watermark_file = $file;
            $this->watermark_pos = $pos;
            $this->watermark_trans = $trans;
        }

       
        /**
         *@description 创建缩略图,以相同的扩展名生成缩略图
         *@param $aspx_file : 来源图像路径
         *@param $thumb_file : 缩略图路径
         */
        protected function create_thumb ($aspx_file,$thumb_file) 
        {
            $t_width = $this->thumb_width;
            $t_height = $this->thumb_height;
            if (!file_exists($aspx_file)) return false;
            $aspx_info = getImageSize($aspx_file);
            //如果来源图像小于或等于缩略图则拷贝源图像作为缩略图
            if ($aspx_info[0] <= $t_width && $aspx_info[1] <= $t_height) {
                if (!copy($aspx_file,$thumb_file)) {
                    return false;
                }
                return true;
            }
            //按比例计算缩略图大小
            if ($aspx_info[0] - $t_width > $aspx_info[1] - $t_height) {
                $t_height = ($t_width / $aspx_info[0]) * $aspx_info[1];
            } else {
                $t_width = ($t_height / $aspx_info[1]) * $aspx_info[0];
            }
            //取得文件扩展名
            $fileext = $this->fileext($aspx_file);
            switch ($fileext) {
                case 'jpg' :
                    $aspx_img = ImageCreateFromJPEG($aspx_file); break;
                case 'png' :
                    $aspx_img = ImageCreateFromPNG($aspx_file); break;
                case 'gif' :
                    $aspx_img = ImageCreateFromGIF($aspx_file); break;
                case 'jpeg' :
                    $aspx_img = ImageCreateFromJPEG($aspx_file); break;
            }
            //创建一个真彩色的缩略图像
            $thumb_img = @ImageCreateTrueColor($t_width,$t_height);
            //ImageCopyResampled函数拷贝的图像平滑度较好，优先考虑
            if (function_exists('imagecopyresampled')) {
                @ImageCopyResampled($thumb_img,$aspx_img,0,0,0,0,$t_width,$t_height,$aspx_info[0],$aspx_info[1]);
            } else {
                @ImageCopyResized($thumb_img,$aspx_img,0,0,0,0,$t_width,$t_height,$aspx_info[0],$aspx_info[1]);
            }
            //生成缩略图
            switch ($fileext) {
                case 'jpg' :
                    ImageJPEG($thumb_img,$thumb_file); break;
                case 'jpeg' :
                    ImageJPEG($thumb_img,$thumb_file); break;
                case 'gif' :
                    ImageGIF($thumb_img,$thumb_file); break;
                case 'png' :
                    ImagePNG($thumb_img,$thumb_file); break;
            }
            //销毁临时图像
            @ImageDestroy($aspx_img);
            @ImageDestroy($thumb_img);
            return true;
        }
        
        /**
         * @description 为图片添加水印
         * @param $file : 要添加水印的文件
         */
        protected function create_watermark ($file) 
        {
            //文件不存在则返回
            if (!file_exists($this->watermark_file) || !file_exists($file)) return;
            if (!function_exists('getImageSize')) return;
            
            //检查GD支持的文件类型
            $gd_allow_types = array();
            if (function_exists('ImageCreateFromGIF')) $gd_allow_types['image/gif'] = 'ImageCreateFromGIF';
            if (function_exists('ImageCreateFromPNG')) $gd_allow_types['image/png'] = 'ImageCreateFromPNG';
            if (function_exists('ImageCreateFromJPEG')) $gd_allow_types['image/jpeg'] = 'ImageCreateFromJPEG';
            //获取文件信息
            $fileinfo = getImageSize($file);
            $wminfo = getImageSize($this->watermark_file);
            if ($fileinfo[0] < $wminfo[0] || $fileinfo[1] < $wminfo[1]) return;
            if (array_key_exists($fileinfo['mime'],$gd_allow_types)) {
                if (array_key_exists($wminfo['mime'],$gd_allow_types)) {
                    
                    //从文件创建图像
                    $temp = $gd_allow_types[$fileinfo['mime']]($file);
                    $temp_wm = $gd_allow_types[$wminfo['mime']]($this->watermark_file);
                    //水印位置
                    switch ($this->watermark_pos) {
                        case 1 : //顶部居左
                            $dst_x = 0; $dst_y = 0; break;
                        case 2 : //顶部居中
                            $dst_x = ($fileinfo[0] - $wminfo[0]) / 2; $dst_y = 0; break;
                        case 3 : //顶部居右
                            $dst_x = $fileinfo[0]; $dst_y = 0; break;
                        case 4 : //底部居左
                            $dst_x = 0; $dst_y = $fileinfo[1]; break;
                        case 5 : //底部居中
                            $dst_x = ($fileinfo[0] - $wminfo[0]) / 2; $dst_y = $fileinfo[1]; break;
                        case 6 : //底部居右
                            $dst_x = $fileinfo[0]-$wminfo[0]; $dst_y = $fileinfo[1]-$wminfo[1]; break;
                        default : //随机
                            $dst_x = mt_rand(0,$fileinfo[0]-$wminfo[0]); $dst_y = mt_rand(0,$fileinfo[1]-$wminfo[1]);
                    }
                    if (function_exists('ImageAlphaBlending')) ImageAlphaBlending($temp_wm,True); //设定图像的混色模式
                    if (function_exists('ImageSaveAlpha')) ImageSaveAlpha($temp_wm,True); //保存完整的 alpha 通道信息
                    //为图像添加水印
                    if (function_exists('imageCopyMerge')) {
                        ImageCopyMerge($temp,$temp_wm,$dst_x,$dst_y,0,0,$wminfo[0],$wminfo[1],$this->watermark_trans);
                    } else {
                        ImageCopyMerge($temp,$temp_wm,$dst_x,$dst_y,0,0,$wminfo[0],$wminfo[1]);
                    }
                    //保存图片
                    switch ($fileinfo['mime']) {
                        case 'image/jpeg' :
                            @imageJPEG($temp,$file);
                            break;
                        case 'image/png' :
                            @imagePNG($temp,$file);
                            break;
                        case 'image/gif' :
                            @imageGIF($temp,$file);
                            break;
                    }
                    //销毁零时图像
                    @imageDestroy($temp);
                    @imageDestroy($temp_wm);
                }
            }
        }
        //获取文件扩展名
        function fileext($filename) {
            return strtolower(substr(strrchr($filename,'.'),1,10));
        }
    }
    ?>