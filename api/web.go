package api

import (
	"github.com/gin-gonic/gin"
	"net/http"
	"strings"
	"yanue/model"
	"yanue/util"
)

type WebHandler struct {
}

func NewWebHandler() *WebHandler {
	web := new(WebHandler)
	return web
}

// 首页
func (api *WebHandler) Home(c *gin.Context) {
	post, err := model.Model.GetPost(166)
	if err != nil {
		c.HTML(http.StatusOK, "404", gin.H{})
	} else {
		content := "nginx-lua-GraphicsMagick\n========================\n\nNginx+Lua+GraphicsMagick，实现自定义图片尺寸功能，支持两种模式\\[固定高宽模式,定高或定宽模式\\]，支持FastDFS文件存储\n\ngithub地址:[https://github.com/yanue/nginx-lua-GraphicsMagick](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick\\\")\n\n[](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#说明\\\")说明\n--------------------------------------------------------------\n\n*   类似淘宝图片，实现自定义图片尺寸功能，可根据图片加后缀100x100.jpg(固定高宽),\\-100.jpg(定高),\\_100-.jpg(定宽)形式实现自定义输出图片大小。\n*   主要将自定义尺寸的图片放在完全独立的thumb目录（自定义目录）,并保持原有的图片目录结构。\n\n[](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#2016-01-14更新说明\\\")2016-01-14更新说明\n--------------------------------------------------------------------------------------\n\n*   新增定高或定宽裁切模式 左右结构,用\"-\"号区分未知高或未知宽(\"-\"号不会被浏览器url转义),如 如: xx.jpg\\_100-.jpg 宽100,高自动 如: xx.jpg\\_-100.jpg 高100,宽自动\n*   新增 php 动态获取图片尺寸的类文件\n\n[](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#2016-04-22更新说明\\\")2016-04-22更新说明\n--------------------------------------------------------------------------------------\n\n*   新增图片含有 query 参数图片裁剪的支持(做了伪静态跳转) 列如: xxx.jpg?a=b&c=d\\_750x750.jpg 或 xxx.jpg?params\\_750x750.jpg 最终跳转为: xxx.jpg\\_750x750.jpg\n\n#### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#文件夹规划\\\")文件夹规划\n\nimg.xxx.com(如/var/www/img)\\\\n|\\-- img1\\\\n|   \\`\\-- 001\\\\n|       \\`\\-- 001.jpg\\\\n|\\-- img2\\\\n|   \\`\\-- notfound.jpg\\\\n|\\-- img3\\\\n|   \\`\\-- 001\\\\n|       \\`\\-- 001.jpg\n\n#### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#自定义尺寸后的路径\\\")自定义尺寸后的路径\n\nthumb（如/tmp/thumb,可在conf文件里面更改）\\\\n    \\`\\-- img1\\\\n        \\`\\-- 001\\\\n            |\\-- 001\\_200x160.jpg 固定高和宽\\\\n            |\\-- 001\\_-100.jpg 定高\\\\n            |\\-- 001\\_200-.jpg 定宽\n\n*   其中img.xxx.com为图片站点根目录，img1,img2...目录是原图目录\n*   缩略图目录根据保持原有结构，并单独设置目录，可定时清理。\n\n#### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#链接地址对应关系\\\")链接地址对应关系\n\n*   原图访问地址：`http://img.xxx.com/xx/001/001.jpg`\n*   缩略图访问地址：`http://img.xxx.com/xx/001/001.jpg_100x100.jpg` 即为宽100,高100\n*   自动宽地址: `http://img.xxx.com/xx/001/001.jpg_-100.jpg` 用\"-\"表示自动,即为高100,宽自动\n*   自动高地址: `http://img.xxx.com/xx/001/001.jpg_100-.jpg` 用\"-\"表示自动,即为宽100,高自动\n\n#### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#访问流程\\\")访问流程\n\n*   首先判断缩略图是否存在，如存在则直接显示缩略图；\n*   缩略图不存在,则判断原图是否存在，如原图存在则拼接graphicsmagick(gm)命令,生成并显示缩略图,否则返回404\n\n[](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#安装\\\")安装\n--------------------------------------------------------------\n\nCentOS6 安装过程见 [nginx+lua+GraphicsMagick安装](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick/blob/master/nginx-install.md\\\")\n\n[](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#配置\\\")配置\n--------------------------------------------------------------\n\n### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#依赖\\\")依赖\n\n*   Nginx\n\n./configure --prefix=/usr/local/nginx \\\\\\\\\\\\n--user=www \\\\\\\\\\\\n--group=www \\\\\\\\\\\\n--sbin-path=/usr/sbin/nginx \\\\\\\\\\\\n--conf-path=/etc/nginx/nginx.conf \\\\\\\\\\\\n--pid-path=/var/run/nginx.pid  \\\\\\\\\\\\n--lock-path=/var/run/nginx.lock \\\\\\\\\\\\n--error-log-path=/opt/logs/nginx/error.log \\\\\\\\\\\\n--http-log-path=/opt/logs/nginx/access.log \\\\\\\\\\\\n--with-http\\_ssl\\_module \\\\\\\\\\\\n--with-http\\_realip\\_module \\\\\\\\\\\\n--with-http\\_sub\\_module \\\\\\\\\\\\n--with-http\\_flv\\_module \\\\\\\\\\\\n--with-http\\_dav\\_module \\\\\\\\\\\\n--with-http\\_gzip\\_static\\_module \\\\\\\\\\\\n--with-http\\_stub\\_status\\_module \\\\\\\\\\\\n--with-http\\_addition\\_module \\\\\\\\\\\\n--with-http\\_spdy\\_module \\\\\\\\\\\\n--with-pcre \\\\\\\\\\\\n--with-zlib=../zlib-1.2.8 \\\\\\\\\\\\n--add-module=../nginx-http-concat \\\\\\\\\\\\n--add-module=../lua-nginx-module \\\\\\\\\\\\n--add-module=../ngx\\_devel\\_kit \\\\\\\\\n\n*   GraphicsMagick(1.3.18)\n    *   libjpeg\n    *   libpng\n    *   inotify(可选)\n\n### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#配置文件说明\\\")配置文件说明\n\n##### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#nginx-配置文件-etcnginx\\\")nginx 配置文件 /etc/nginx\n\n##### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#vhost-为站点配置\\\")vhost 为站点配置\n\n*   [demo.conf](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick/blob/master/vhost/demo.conf\\\") 为 普通站点 配置文件,包含固定高宽和定高,定宽两种模式配置\n*   [fdfs.conf](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick/blob/master/vhost/fdfs.conf\\\") 为 fastdfs 配置文件,包含固定高宽和定高,定宽两种模式配置 ##### lua 为裁切图片处理目录\n*   [autoSize.lua](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick/blob/master/lua/autoSize.lua\\\") 定高或定宽模式裁切图片处理lua脚本\n*   [cropSize.lua](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick/blob/master/lua/cropSize.lua\\\") 固定高宽模式裁切图片处理lua脚本\n\n#### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#nginx-vhost-demo配置\\\")nginx vhost demo配置\n\nserver{\\\\n    listen  80\\\\n\\\\n    # set var for thumb pic\\\\n    set $upload\\_path /opt/uploads;\\\\n    set $img\\_original\\_root  $upload\\_path;# original root;\\\\n    set $img\\_thumbnail\\_root $upload\\_path/cache/thumb;\\\\n    set $img\\_file $img\\_thumbnail\\_root$uri;\\\\n\\\\n    # like：/xx/xx/xx.jpg\\_100-.jpg or /xx/xx/xx.jpg\\_-100.jpg\\\\n    location ~\\* ^(.+\\\\\\\\.(jpg|jpeg|gif|png))\\_((\\\\\\\\d+\\\\\\\\\\-)|(\\\\\\\\\\-\\\\\\\\d+))\\\\\\\\.(jpg|jpeg|gif|png)$ {\\\\n            root $img\\_thumbnail\\_root;    # root path for croped img\\\\n            set $img\\_size $3;\\\\n\\\\n            if (!\\-f $img\\_file) {    # if file not exists\\\\n                    add\\_header X-Powered-By 'Nginx+Lua+GraphicsMagick By Yanue';  #  header for test\\\\n                    add\\_header file-path $request\\_filename;    #  header for test\\\\n                    set $request\\_filepath $img\\_original\\_root$1;    # origin\\_img full path：/document\\_root/1.gif\\\\n                    set $img\\_size $3;    # img width or height size depends on uri\\\\n                    set $img\\_ext $2;    # file ext\\\\n                    content\\_by\\_lua\\_file /etc/nginx/lua/autoSize.lua;    # load lua\\\\n            }\\\\n    }\\\\n\\\\n    # like: /xx/xx/xx.jpg\\_100x100.jpg\\\\n    location ~\\* ^(.+\\\\\\\\.(jpg|jpeg|gif|png))\\_(\\\\\\\\d+)+x(\\\\\\\\d+)+\\\\\\\\.(jpg|jpeg|gif|png)$ {\\\\n            root $img\\_thumbnail\\_root;    # root path for croped img\\\\n\\\\n            if (!\\-f $img\\_file) {    # if file not exists\\\\n                    add\\_header X-Powered-By 'Nginx+Lua+GraphicsMagick By Yanue';  #  header for test\\\\n                    add\\_header file-path $request\\_filename;    #  header for test\\\\n                    set $request\\_filepath $img\\_original\\_root$1;    # origin\\_img file path\\\\n                    set $img\\_width $3;    # img width\\\\n                    set $img\\_height $4;    # height\\\\n                    set $img\\_ext $5;    # file ext\\\\n                    content\\_by\\_lua\\_file /etc/nginx/lua/cropSize.lua;    # load lua\\\\n            }\\\\n    }\\\\n\\\\n    location = /favicon.ico {\\\\n                log\\_not\\_found off;\\\\n                access\\_log off;\\\\n    }\\\\n}\n\n#### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#nginx-fastdfs配置\\\")nginx fastdfs配置\n\nserver{\\\\n    listen      80;\\\\n    server\\_name xxx.com;\\\\n\\\\n    set $img\\_thumbnail\\_root /opt/fastdfs/thumb; #set thumb path\\\\n    set $img\\_file $img\\_thumbnail\\_root$uri;   #thumb file\\\\n\\\\n    # like：/pic/M00/xx/xx/xx.jpg\\_100-.jpg or /pic/M00/xx/xx/xx.jpg\\_-100.jpg\\\\n    location ~\\* ^(\\\\\\\\/(\\\\\\\\w+)(\\\\\\\\/M00)(.+\\\\\\\\.(jpg|jpeg|gif|png)))\\_((\\\\\\\\d+\\\\\\\\\\-)|(\\\\\\\\\\-\\\\\\\\d+))\\\\\\\\.(jpg|jpeg|gif|png)$ {\\\\n            root $img\\_thumbnail\\_root;    # root path for croped img\\\\n            set $fdfs\\_group\\_root /opt/fastdfs/$2/store0/data; #set fastdfs group path $2\\\\n\\\\n            if (!\\-f $img\\_file) {    # if thumb file not exists\\\\n                    add\\_header X-Powered-By 'Nginx+Lua+GraphicsMagick By Yanue';  #  header for test\\\\n                    add\\_header file-path $request\\_filename;    #  header for test\\\\n                    set $request\\_filepath $fdfs\\_group\\_root$4;    # origin\\_img full path：/document\\_root/1.gif\\\\n                    set $img\\_size $6;    # img width or height size depends on uri : img size like \"-100\" or \"100-\", \"-\" means auto size\\\\n                    set $img\\_ext $5;    # file ext\\\\n                    content\\_by\\_lua\\_file /etc/nginx/lua/autoSize.lua;    # load auto width or height crop Lua file\\\\n            }\\\\n    }\\\\n\\\\n    # like：/pic/M00/xx/xx/xx.jpg\\_200x100.jpg\\\\n    location ~\\* ^(\\\\\\\\/(\\\\\\\\w+)(\\\\\\\\/M00)(.+\\\\\\\\.(jpg|jpeg|gif|png))\\_(\\\\\\\\d+)+x(\\\\\\\\d+)+\\\\\\\\.(jpg|jpeg|gif|png))$ {\\\\n            root $img\\_thumbnail\\_root;    # root path for croped img\\\\n            set $fdfs\\_group\\_root /opt/fastdfs/$2/store0/data; #set fastdfs group path $2\\\\n\\\\n            if (!\\-f $img\\_file) {   # if thumb file not exists\\\\n                    add\\_header X-Powered-By 'Nginx+Lua+GraphicsMagick By Yanue';  #  header for test\\\\n                    add\\_header file-path $request\\_filename;    #  header for test\\\\n                    set $request\\_filepath $fdfs\\_group\\_root$4;    # real file path\\\\n                    set $img\\_width $6;    #  img width\\\\n                    set $img\\_height $7;    #  img height\\\\n                    set $img\\_ext $5;     # file ext\\\\n                    content\\_by\\_lua\\_file /etc/nginx/lua/cropSize.lua;    # load crop Lua file\\\\n            }\\\\n    }\\\\n\\\\n    location /pic/M00 {\\\\n            alias /opt/fastdfs/pic/store0/data;\\\\n            ngx\\_fastdfs\\_module;\\\\n    }\\\\n\\\\n    location /chat/M00 {\\\\n            alias /opt/fastdfs/chat/store0/data;\\\\n            ngx\\_fastdfs\\_module;\\\\n    }\\\\n\\\\n    location = /favicon.ico {\\\\n            log\\_not\\_found off;\\\\n            access\\_log off;\\\\n    }\\\\n}\n\n### [](\\\"https://github.com/yanue/nginx-lua-GraphicsMagick#最后说明\\\")最后说明\n\n*   lua 脚本处理并未做任何图片尺寸限制,这样很容易被恶意改变宽和高参数而随意生成大量文件,浪费资源和空间,请根据直接情况自行处理\n\n参考:[https://github.com/hopesoft/nginx-lua-image-module](\\\"https://github.com/hopesoft/nginx-lua-image-module\\\")"
		c.HTML(http.StatusOK, "index", gin.H{"title": "", "content": content, "post": post})
	}
}

func (api *WebHandler) Map(c *gin.Context) {
	c.HTML(http.StatusOK, "map", gin.H{
		"title":       "经纬度在线查询,地名(批量)查询经纬度,经纬度(批量)查询地名",
		"keywords":    "经纬度,查询,经纬度查询,经纬度在线查询,经纬度查找地名,经纬度(批量)查询,经纬度转换地名,地名批量查询经纬度,查询地名返回经纬度,根据经纬度批量查询地名,google map经纬度 ,yanue.net",
		"description": "运用google map api开发的地图系统，实现经纬度(批量)在线查询,地名批量查询经纬度,google 经纬度查询地名,经纬度查找地名,查询地名返回经纬度,根据经纬度批量查询地名,google map运用geocoder.geocode实例",
	})
}

func (api *WebHandler) ToLatLng(c *gin.Context) {
	c.HTML(http.StatusOK, "toLatLng", gin.H{
		"title":       "在线查询经纬度,通过地名查询经纬度(手动精确定位)",
		"keywords":    "经纬度,查询,经纬度查询,经纬度在线查询,经纬度查找地名,查询地名返回经纬度(手动精确定位),鼠标经过地图区域提示经纬度",
		"description": "在线查询经纬度,通过地名查询经纬度(手动精确定位)，实现鼠标经过提示经纬度，自动填充地名地点名称，输入完成后可直接点击enter键进行解析，地理位置不准确，可以拖动重新解析，解析后经纬度信息显示完整",
	})
}

func (api *WebHandler) Gps(c *gin.Context) {
	c.HTML(http.StatusOK, "gps", gin.H{
		"title":       "GPS坐标转换经纬度,GPS转谷歌百度地图经纬度",
		"keywords":    "GPS,GPS转换,GPS坐标转换经纬度，GPS转谷歌地图经纬度，GPS免费接口,GPS免费转换接口,gpsApi.php,map.yanue.net,半叶寒羽-原创作品,GPS定位,GPS to lat lng，GPS Coordinate Converter",
		"description": "GPS,GPS转换,GPS坐标转换经纬度，GPS转谷歌地图经纬度，GPS免费接口,GPS免费转换接口,gpsApi.php,map.yanue.net,半叶寒羽-原创作品,GPS,GPS to lat lng，GPS Coordinate Converter,GPS转中文地址,GPS转Google地址!详情见https://map.yanue.net/gps.html",
	})
}

func (api *WebHandler) Page(c *gin.Context) {

}

// like: /post-1.html or /post/2.html

func (api *WebHandler) Post(c *gin.Context) {
	id := c.Param("id")
	id = strings.TrimSuffix(id, ".html")
	post, err := model.Model.GetPost(util.ToInt(id))
	if err != nil {
		c.HTML(http.StatusOK, "404", gin.H{})
	} else {
		c.HTML(http.StatusOK, "post", gin.H{"title": post.Title, "content": post.Content, "post": post})
	}
}
