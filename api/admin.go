package api

import (
	"github.com/gin-gonic/gin"
	"net/http"
	"strings"
)

type AdminHandler struct {
}

func NewAdminHandler() *AdminHandler {
	web := new(AdminHandler)
	return web
}

func (api *AdminHandler) Login(c *gin.Context) {
	c.HTML(http.StatusOK, "login", gin.H{
		"title": "hello gin " + strings.ToLower(c.Request.Method) + " method",
	})
}

func (api *AdminHandler) Admin(c *gin.Context) {
	c.HTML(http.StatusOK, "admin", gin.H{
		"title": "hello gin " + strings.ToLower(c.Request.Method) + " method",
	})
}
