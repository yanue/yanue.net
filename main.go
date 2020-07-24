package main

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"github.com/yanue/yanue.net/bindata"
	"log"
	"net/http"
	"os"
	"os/exec"
)

// step1. go get -u github.com/go-bindata/go-bindata
// step2. go:generate
// link: https://jaycechant.info/2020/go-bindata-golang-static-resources-embedding/
// Before buildling, run go generate.
//go:generate go-bindata -o=bindata/bindata.go -ignore="\\.DS_Store|desktop.ini" -pkg=bindata assets/... template/...

func RestoreAllAssets() {
	assets := bindata.AssetNames()
	for _, s := range assets {
		err := bindata.RestoreAsset("", s)
		if err != nil {
			log.Println("RestoreAsset err:", err.Error())
		}
	}
}

func init() {
	log.SetFlags(log.Lshortfile | log.LstdFlags)
	RestoreAllAssets()
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
