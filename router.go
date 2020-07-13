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

func run() {
	gin.SetMode(gin.ReleaseMode)

	// 初始化
	engine := gin.New()
	engine.Use(gin.Logger())
	engine.Use(gin.Recovery())

	r := new(router)
	r.Engine = engine

	// 跨域处理
	r.cors()

	// rest 接口路由
	r.restRoute()

	srv = &http.Server{
		Addr:    HttpAddr,
		Handler: r,
	}

	// web前端目录
	r.Use(static.Serve("/", static.LocalFile("./dist/", false)))

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

	// 启动http server
	log.Println("running at:", HttpAddr)
	if err := srv.ListenAndServe(); err != nil && err != http.ErrServerClosed {
		log.Fatalf("listen: %s\n", err)
	}
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

/**
 * restful 接口列表
 */
func (r *router) restRoute() {
	// 前缀路径: /api
	g := r.Group("/api", ginRawData())

	s := service.NewApiHandlerSite()
	g1 := g.Group("/site")
	g1.GET("/config", s.Config) // 综合信息: 如币种,交易所,合约周期
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
