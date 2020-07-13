package service

import (
	"github.com/gin-gonic/gin"
)

type ApiHandlerSite struct {
	*ApiHandler
}

func NewApiHandlerSite() *ApiHandlerSite {
	api := new(ApiHandlerSite)
	api.ApiHandler = NewApiHandler()
	return api
}

// 综合信息: 如币种,交易所,合约周期
func (api *ApiHandlerSite) Config(c *gin.Context) {
	res := ""
	api.OutRight(c, res)
}
