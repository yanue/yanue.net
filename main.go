package main

import (
	"github.com/foolin/goview"
	"github.com/foolin/goview/supports/ginview"
	"github.com/gin-gonic/gin"
	"html/template"
	"log"
	"net/http"
	"yanue/model"
)

func init() {
	log.SetFlags(log.Lshortfile | log.LstdFlags)
	model.InitDB()
}

func main() {
	gin.SetMode(gin.ReleaseMode)

	// 初始化
	engine := gin.New()
	engine.Use(gin.Logger())
	engine.Use(gin.Recovery())

	r := new(router)
	r.Engine = engine
	cfg := goview.DefaultConfig
	cfg.Funcs = template.FuncMap{
		"unescape": func(s string) template.HTML {
			return template.HTML(s)
		},
	}
	r.HTMLRender = ginview.New(cfg)
	r.StaticFile("/favicon.ico", "./assets/img/favicon.ico")

	// 路由
	r.route()
	// 跨域处理
	r.cors()

	// http server
	srv = &http.Server{
		Addr:    HttpAddr,
		Handler: r,
	}

	// 启动http server
	log.Println("running at:", HttpAddr)
	if err := srv.ListenAndServe(); err != nil && err != http.ErrServerClosed {
		log.Fatalf("listen: %s\n", err)
	}
}
