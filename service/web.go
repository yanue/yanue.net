package service

import (
	"github.com/gin-gonic/gin"
	"html/template"
	"log"
	"net/http"
	"strings"
)

var layoutTemplate = "layout.tmpl"

type WebHandler struct {
}

func NewWebHandler() *WebHandler {
	web := new(WebHandler)
	return web
}

var baseTpl = []string{"./template/test.hml"}

// 首页
func (api *WebHandler) Home(c *gin.Context) {
	tpl := append(baseTpl, "./template/index.html")
	b, err := template.ParseFiles(tpl...)
	log.Println("b,err", b, err)

	c.HTML(http.StatusOK, "index.html", gin.H{
		"title": "hello gin " + strings.ToLower(c.Request.Method) + " method",
	})
}
