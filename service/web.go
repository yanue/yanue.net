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
func (api *WebHandler) Page(c *gin.Context) {
	c.HTML(http.StatusOK, "page.html", gin.H{
		"title": "hello gin " + strings.ToLower(c.Request.Method) + " method",
	})
}
