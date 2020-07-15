package main

import (
	"github.com/gin-gonic/gin"
	"log"
	"net/http"
)

func init() {
	log.SetFlags(log.Lshortfile)
}

func main() {
	gin.SetMode(gin.ReleaseMode)

	// 初始化
	engine := gin.New()
	engine.Use(gin.Logger())
	engine.Use(gin.Recovery())

	r := new(router)
	r.Engine = engine

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
