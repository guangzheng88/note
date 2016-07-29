1.卸载
	sudo dpkg -r sublime-text
	sudo rm -rf /home/lilia/.config/sublime-text-3/

２．安装
	sudo dpkg -i sublime-text_build-3114_i386.deb
	
３．解决中文输入法
	安装编译环境
	sudo apt-get install build-essential
	sudo apt-get install libgtk2.0-dev
	下载sublime-imfix.c文件
	下载地址：
	http://pan.baidu.com/s/1eQcPeE2
	进入该文件所在目录编译该文件
	gcc -shared -o libsublime-imfix.so sublime-imfix.c  `pkg-config --libs --cflags gtk+-2.0` -fPIC
	编译完之后会在同目录下生成一个libsublime-imfix.so文件
	这个文件保存好，以后要用
	现在如果要启动sublime只需要命令行执行
	LD_PRELOAD=./libsublime-imfix.so subl
	就可以了，当然是在libsublime-imfix.so目录下面执行。
	在桌面建立一个空白文档,将
	LD_PRELOAD=./libsublime-imfix.so subl
	这句话粘贴进去,然后修改此文件为可执行文件就ok了

４．preferencess > settings-user配置
{
	"auto_complete_triggers":
	[
		{
			"characters": " ",
			"selector": "text.html"
		}
	],
	"color_scheme": "Packages/Color Scheme - Default/Monokai Bright.tmTheme",
	"font_face": "Consolas",
	"font_size": 13,
	"highlight_line": true,
	"highlight_modified_tabs": true,
	"ignored_packages":
	[
		"Vintage",
		"Theme - Nil"
	],
	"save_on_focus_lost": true,
	"scroll_past_end": true,
	"soda_classic_tabs": true,
	"soda_folder_icons": true,
	"tab_size": 4,
	"translate_tabs_to_spaces": true,
	"update_check": false,
	"word_wrap": true
}


