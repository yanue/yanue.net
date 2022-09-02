package main

import (
	"bytes"
	"github.com/gin-contrib/cors"
	"github.com/gin-contrib/static"
	"github.com/gin-gonic/gin"
	"io"
	"log"
	"net/http"
	"strings"
	"yanue/api"
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
	r.GET("/post-:id", web.Post)
	r.GET("/post/:id", web.Post)
	r.GET("/page", web.Page)
	r.GET("/map", web.Map)
	r.GET("/toLatLng", web.ToLatLng)
	r.GET("/gps", web.Gps)
	r.GET("/gps.html", web.Gps)

	admin := api.NewAdminHandler()
	r.GET("/login", admin.Login)
	r.GET("/admin", admin.Admin)

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
