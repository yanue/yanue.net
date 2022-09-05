package model

import (
	"crypto/sha256"
	"encoding/hex"
	jwt "github.com/appleboy/gin-jwt/v2"
	"github.com/gin-gonic/gin"
	"time"
	"yanue/util"
)

type userModel struct {
}

func (s *userModel) GetAdmin(username string) (item *AdminUser, err error) {
	item = new(AdminUser)
	err = db.Model(&AdminUser{}).Where("username=?", username).First(&item).Error
	return
}

func (s *userModel) GetById(id int) (item *AdminUser, err error) {
	item = new(AdminUser)
	err = db.Model(&AdminUser{}).Where("id=?", id).First(&item).Error
	return
}

func (s *userModel) getByToken(token string) (item *AdminUser, err error) {
	item = new(AdminUser)
	err = db.Model(&AdminUser{}).Where("token=?", token).First(&item).Error
	return
}

func (s *userModel) Save(id int, item *AdminUser) (err error) {
	updateFields := map[string]any{
		"last_login":   item.LastLogin,
		"last_ip":      item.LastIp,
		"login_count":  item.LoginCount,
		"login_failed": item.LoginFailed,
		"status":       item.Status,
		"token":        item.Token,
	}
	err = db.Model(&AdminUser{}).Where("id = ?", id).Updates(updateFields).Error
	return
}

func (s *userModel) TokenVerify(id int, token string) (item *AdminUser, err error) {
	item = new(AdminUser)
	err = db.Model(&AdminUser{}).Where("id = ? and token=?", id, token).First(&item).Error
	return
}

/**
 * 生成密码及盐
 */
func (s *userModel) PasswordGenerate(rawPwd string) (hashPwd, salt string) {
	bSalt := util.GenRandomBytes(64)
	h := sha256.New()
	h.Write([]byte(rawPwd))
	h.Write(bSalt)
	hashPwd = hex.EncodeToString(h.Sum(nil))
	return hashPwd, string(bSalt)
}

/**
 * 验算给定的明文密码是否与加密密码相符。
 */
func (s *userModel) PasswordVerify(rawPwd, salt, hashPwd string) bool {
	h := sha256.New()
	h.Write([]byte(rawPwd + salt))
	//log.Println("PasswordVerify", rawPwd, hashPwd, hex.EncodeToString(h.Sum(nil)))
	return hex.EncodeToString(h.Sum(nil)) == hashPwd
}

/**
 * jwt中间件
 *
 * return: gin.HandlerFunc
 */
func (s *userModel) JwtMiddleWare() gin.HandlerFunc {
	middleware, _ := jwt.New(&jwt.GinJWTMiddleware{
		Realm: "yanue.net",
		Key:   []byte(""),
		Unauthorized: func(c *gin.Context, code int, message string) {
			c.Abort()
			c.String(code, "%v", gin.H{"code": code, "msg": message})
		},
		// 增加缓存检查
		Authorizator: func(data interface{}, c *gin.Context) bool {
			id, ok := jwt.ExtractClaims(c)["uid"].(float64)
			uid := int(id)
			if !ok || uid <= 0 {
				return false
			}
			user, err := s.GetById(uid)
			if err != nil {
				return false
			}
			jToken := jwt.GetToken(c)
			// 判断缓存中是否存在
			if jToken != user.Token {
				return false
			}
			// 登录已过期
			if time.Now().Unix()-int64(user.LastLogin) > 12*3600 {
				// 删除过期的key
				return false
			}
			c.Set("uid", uid)
			c.Set("user", user)
			return true
		},
		TokenLookup:   "header: Authorization",
		TokenHeadName: "Bearer",
		TimeFunc:      time.Now,
	})

	return middleware.MiddlewareFunc()
}
