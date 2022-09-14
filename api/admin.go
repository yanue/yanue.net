package api

import (
	"github.com/gin-gonic/gin"
	"math"
	"net/http"
	"yanue/model"
	"yanue/util"
)

type AdminHandler struct {
	ApiHandler
}

func NewAdminHandler() *AdminHandler {
	web := new(AdminHandler)
	return web
}

var pageSize = 20

func (s *AdminHandler) Admin(c *gin.Context) {
	user, isLogin := isAdminLogon(c)
	if !isLogin {
		c.HTML(http.StatusOK, "admin/login.html", gin.H{})
		return
	}
	page := util.ToInt(c.Query("page"))
	id := util.ToInt(c.Query("id"))
	where := ""
	cnt := model.Model.Count(where)
	list, _ := model.Model.GetPostList("", page, pageSize)
	post, err := model.Model.GetPost(id)
	cats, _ := model.Model.GetCats()
	totalPage := int(math.Ceil(float64(cnt) / float64(pageSize)))
	if page < 1 {
		page = 1
	}
	if page > totalPage {
		page = totalPage
	}
	showForm := true
	if id > 0 && err != nil {
		showForm = false
	}
	c.HTML(http.StatusOK, "admin/admin.html", gin.H{
		"list":      list,
		"count":     cnt,
		"totalPage": totalPage,
		"err":       err,
		"show_form": showForm,
		"id":        id,
		"page":      page,
		"size":      pageSize,
		"post":      adaptPost(post, cats),
		"cats":      cats,
		"user":      user.Id,
		"pages":     genPage("/admin", page, totalPage),
	})
}
