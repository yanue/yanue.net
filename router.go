package main

import (
	"bytes"
	"github.com/gin-contrib/cors"
	"github.com/gin-contrib/sessions"
	"github.com/gin-contrib/sessions/cookie"
	"github.com/gin-contrib/static"
	"github.com/gin-gonic/gin"
	"io"
	"log"
	"net/http"
	"strings"
	"yanue/api"
	"yanue/model"
)

var srv *http.Server

type router struct {
	*gin.Engine
}

/**
 * 跨域处理
 */
func (r *router) cors() {
	cfg := cors.DefaultConfig()
	cfg.AllowHeaders = []string{"x-url-path", "content-type", "Authorization"}
	cfg.AllowMethods = []string{"POST", "OPTIONS", "GET", "PUT", "PATCH", "DELETE"}
	cfg.AllowAllOrigins = true

	r.Use(cors.New(cfg))
}

func (r *router) route() {
	web := api.NewWebHandler()

	// 前缀路径: /api
	r.GET("/", web.Home)
	r.GET("/archives", web.Archives)
	r.GET("/tags", web.Tags)
	r.GET("/tags/:tag", web.Tags)
	r.GET("/categories", web.Categories)
	r.GET("/categories/:cat", web.Categories)
	r.GET("/more", web.More)
	r.GET("/post-:id", web.Post)
	r.GET("/post/:id", web.Post)
	r.GET("/about", web.About)
	r.GET("/about.html", web.About)
	r.GET("/map", web.Map)
	r.GET("/geo.html", web.Map)
	r.GET("/toLatLng", web.ToLatLng)
	r.GET("/gps", web.Gps)
	r.GET("/gps.html", web.Gps)

	admin := api.NewAdminHandler()

	r.GET("/admin", admin.Admin)
	r.POST("/login", admin.DoLogin)
	r.PUT("/admin/:id", model.UserModel.JwtMiddleWare(), admin.Save)
	r.DELETE("/admin/:id", model.UserModel.JwtMiddleWare(), admin.Del)
	r.POST("/admin/create", model.UserModel.JwtMiddleWare(), admin.Create)
	// 设置生成sessionId的密钥
	store := cookie.NewStore([]byte("yanue.net"))
	// admin是返回給前端的sessionId名
	r.Use(sessions.Sessions("admin", store))

	// 前缀路径: /api
	webApi := api.NewApiHandler()
	g := r.Group("/api/map", ginRawData())
	g.GET("/gpsOffset", webApi.GpsOffset) // google地图纠偏

	// 静态资源
	r.Use(static.Serve("/assets/", static.LocalFile("./assets/", false)))

	// 未知路由
	r.NoRoute(func(c *gin.Context) {
		path := strings.Split(c.Request.URL.Path, "/")
		if len(path) > 1 {
			if path[1] == "api" {
				c.AbortWithStatusJSON(http.StatusNotFound, api.JsonResp{
					Code: 404,
					Msg:  "ErrPageNotFound",
					Data: struct{}{},
				})
				return
			}
		}
		c.File("dist/index.html")
	})
}

func ginRawData() gin.HandlerFunc {
	return func(ctx *gin.Context) {
		data, err := ctx.GetRawData()
		if err != nil {
			log.Println("ginRawData", err.Error())
		}
		ctx.Set("_rawData", string(data))
		ctx.Request.Body = io.NopCloser(bytes.NewBuffer(data))
		ctx.Next()
	}
}
