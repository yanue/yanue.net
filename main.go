package main

import (
	"fmt"
	"github.com/foolin/goview/supports/ginview"
	"github.com/gin-gonic/gin"
	"log"
	"net/http"
	"os"
	"os/exec"
)

func init() {
	log.SetFlags(log.Lshortfile | log.LstdFlags)
	InitGoogleDat()
}

func main() {
	gin.SetMode(gin.ReleaseMode)

	// 初始化
	engine := gin.New()
	engine.Use(gin.Logger())
	engine.Use(gin.Recovery())

	r := new(router)
	r.Engine = engine
	// 模板引擎
	r.HTMLRender = ginview.Default()
	r.StaticFile("/favicon.ico", "./assets/favicon.ico")

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

func InitGoogleDat() {
	_, err := os.Stat("./data/google_offset.dat")
	log.Println("InitGoogleDat", err)
	if err != nil {
		cmd := exec.Command("/bin/sh", "-c", "git clone --progress https://gitee.com/yanue/file.git data")
		cmd.Stdout = os.Stdout
		cmd.Stderr = os.Stderr
		err := cmd.Run()
		if err != nil {
			fmt.Println("cmd.Output: ", err)
			return
		}
	}
}
