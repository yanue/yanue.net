package api

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"time"
	"yanue/model"
	"yanue/util"
)

const UserMaxLoginAttempt = 5

var LoginExpireTime = 10 * time.Hour

func (s *AdminHandler) DoLogin(c *gin.Context) {
	var req = new(model.AdminUser)
	err := c.ShouldBindJSON(req)
	if err != nil {
		s.OutError(c, 1, err.Error())
		return
	}
	user, err := model.UserModel.GetAdmin(req.Username)
	if err != nil {
		s.OutError(c, 1, "用户或密码错误")
		return
	}
	if user.Status == model.UserStatusDisable {
		s.OutError(c, 1, "用户已被禁用，无法登录")
		return
	}
	if user.Status == model.UserStatusLocked {
		lastTime := int(time.Now().Unix()) - user.LastLogin
		// 2小时后重试
		if lastTime > 2*60*60 {
			user.Status = model.UserStatusOk
			user.LoginFailed = 0
		} else {
			s.OutError(c, 1, fmt.Sprintf("用户或密码错误%d次,已被锁定,请%d秒后重试", UserMaxLoginAttempt, 2*60*60-lastTime))
			return
		}
	}
	user.Token = ""
	user.LastIp = c.ClientIP()
	user.LastLogin = int(time.Now().Unix())

	// 验证密码
	if !model.UserModel.PasswordVerify(req.Password, user.Salt, user.Password) {
		user.LoginFailed += 1
		last := UserMaxLoginAttempt - user.LoginFailed
		if last == 0 {
			user.Status = model.UserStatusLocked
			s.OutError(c, 1, fmt.Sprintf("用户或密码错误%d次,已被锁定,请2小时后重试", UserMaxLoginAttempt))
		} else {
			s.OutError(c, 1, fmt.Sprintf("还可以重试%d次", last))
		}
		_ = model.UserModel.Save(user.Id, user)
		return
	}
	// 登录错误数清0
	user.LoginFailed = 0
	_ = model.UserModel.Save(user.Id, user)

	expireTime := time.Now().Add(LoginExpireTime).Unix()
	// 生成jwt并返回
	token, err := s.Sign(map[string]interface{}{"uid": user.Id, "exp": expireTime})
	if err != nil {
		s.OutError(c, 1, "生成token失败", err.Error())
		return
	}
	// 登录错误数清0
	user.Token = token
	err = model.UserModel.Save(user.Id, user)
	if err != nil {
		s.OutError(c, 1, "生成token失败", err.Error())
		return
	}
	s.OutRight(c, gin.H{"uid": user.Id, "token": token})
}

func (s *AdminHandler) Create(c *gin.Context) {
	var item = new(model.Post)
	err := c.ShouldBindJSON(item)
	if err != nil {
		s.OutError(c, 1, err.Error())
		return
	}
	// 替换图片
	newContent, err := SaveImageToLocal(item.Content)
	if err != nil {
		s.OutError(c, 5, err.Error())
		return
	}
	item.Content = newContent
	err = model.Model.CreatePost(item)
	if err != nil {
		s.OutError(c, 2, err.Error())
		return
	}
	s.OutRight(c, "ok")
}

func (s *AdminHandler) Save(c *gin.Context) {
	id := util.ToInt(c.Param("id"))
	var item = new(model.Post)
	err := c.ShouldBindJSON(item)
	if err != nil {
		s.OutError(c, 1, err.Error())
		return
	}
	if id != item.Id {
		s.OutError(c, 2, "id参数错误")
		return
	}
	_, err = model.Model.GetPost(id)
	if err != nil {
		s.OutError(c, 3, err.Error())
		return
	}
	// 替换图片
	newContent, err := SaveImageToLocal(item.Content)
	if err != nil {
		s.OutError(c, 5, err.Error())
		return
	}
	item.Content = newContent
	err = model.Model.UpdatePost(id, item)
	if err != nil {
		s.OutError(c, 4, err.Error())
		return
	}
	s.OutRight(c, "ok")
}

func (s *AdminHandler) Del(c *gin.Context) {
	id := util.ToInt(c.Param("id"))
	var item = new(model.Post)
	err := c.ShouldBindJSON(item)
	if err != nil {
		s.OutError(c, 1, err.Error())
		return
	}
	if id != item.Id {
		s.OutError(c, 2, "id参数错误")
		return
	}
	_, err = model.Model.GetPost(id)
	if err != nil {
		s.OutError(c, 3, err.Error())
		return
	}
	err = model.Model.DelPost(id)
	if err != nil {
		s.OutError(c, 4, err.Error())
		return
	}
	s.OutRight(c, "ok")
}

type Page struct {
	Page    int
	Link    string
	Current bool
}
