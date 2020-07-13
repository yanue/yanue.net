package service

import (
	"github.com/gin-gonic/gin"
	"net/http"
	"strings"
)

type JsonResp struct {
	Code int         `json:"code"`
	Msg  string      `json:"msg"`
	Data interface{} `json:"data"`
}

type ApiInterface interface {
	OutRight(c *gin.Context, data interface{})
	OutError(c *gin.Context, errno int, msg ...string)
}

func NewApiHandler() *ApiHandler {
	api := new(ApiHandler)
	return api
}

type ApiHandler struct {
	ApiInterface
}

func (api *ApiHandler) OutRight(c *gin.Context, data interface{}) {
	c.JSON(http.StatusOK, &JsonResp{
		Code: 0,
		Msg:  "",
		Data: data,
	})
}

func (api *ApiHandler) OutError(c *gin.Context, errno int, msg ...string) {
	var errMsg string

	if len(msg) > 0 {
		errMsg = strings.Join(msg, ", ")
	}

	if errMsg == "" {
		errMsg = "错误码:[" + string(errno) + "]"
	}

	c.JSON(http.StatusOK, JsonResp{
		Code: errno,
		Msg:  errMsg,
		Data: struct{}{},
	})
}
