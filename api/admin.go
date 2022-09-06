package api

import (
	"fmt"
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
	// 设置cookie
	token, err := c.Cookie("token")
	fmt.Println("err", token, err)
	uid, err := c.Cookie("uid")
	fmt.Println("err", uid, err)

	user, err := model.UserModel.TokenVerify(util.ToInt(uid), token)
	if err != nil {
		c.HTML(http.StatusOK, "admin/login.html", gin.H{})
		return
	}
	fmt.Println("user", user)

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
		"post":      post,
		"cats":      cats,
		"pages":     genPage(page, totalPage),
	})
}
