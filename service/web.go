package service

import (
	"github.com/gin-gonic/gin"
	"net/http"
	"strings"
)

type WebHandler struct {
}

func NewWebHandler() *WebHandler {
	web := new(WebHandler)
	return web
}

// 首页
func (api *WebHandler) Home(c *gin.Context) {
	c.HTML(http.StatusOK, "index", gin.H{
		"title": "hello gin " + strings.ToLower(c.Request.Method) + " method",
	})
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
	c.HTML(http.StatusOK, "page.html", gin.H{
		"title": "hello gin " + strings.ToLower(c.Request.Method) + " method",
	})
}
