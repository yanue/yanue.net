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

func (api *WebHandler) Page(c *gin.Context) {
	c.HTML(http.StatusOK, "page.html", gin.H{
		"title": "hello gin " + strings.ToLower(c.Request.Method) + " method",
	})
}
