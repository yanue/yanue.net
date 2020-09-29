package main

import (
	"bytes"
	"github.com/gin-contrib/cors"
	"github.com/gin-contrib/static"
	"github.com/gin-gonic/gin"
	"github.com/yanue/yanue.net/service"
	"io/ioutil"
	"log"
	"net/http"
	"strings"
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
	web := service.NewWebHandler()

	// 前缀路径: /api
	r.GET("/", web.Home)
	r.GET("/page", web.Page)
	r.GET("/map", web.Map)

	// 前缀路径: /api
	api := service.NewApiHandler()
	g := r.Group("/api/map", ginRawData())
	g.GET("/gpsOffset", api.GpsOffset) // google地图纠偏

	// 静态资源
	r.Use(static.Serve("/assets/", static.LocalFile("./assets/", false)))

	// 未知路由
	r.NoRoute(func(c *gin.Context) {
		path := strings.Split(c.Request.URL.Path, "/")
		if len(path) > 1 {
			if path[1] == "api" {
				c.AbortWithStatusJSON(http.StatusNotFound, service.JsonResp{
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
		ctx.Request.Body = ioutil.NopCloser(bytes.NewBuffer(data))
		ctx.Next()
	}
}
