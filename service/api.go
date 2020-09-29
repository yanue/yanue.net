package service

import (
	"github.com/gin-gonic/gin"
	"github.com/yanue/yanue.net/util"
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

// 综合信息: 如币种,交易所,合约周期
func (api *ApiHandler) GpsOffset(c *gin.Context) {
	lng := util.ToFloat64(c.Query("lng"))
	lat := util.ToFloat64(c.Query("lat"))
	var res struct {
		Lng float64 `json:"lng"`
		Lat float64 `json:"lat"`
	}
	lat1, lng1 := gpsOffset.geoLatLng(lat, lng)
	res.Lat = lat1
	res.Lng = lng1

	// $bMap = json_decode(file_get_contents('https://api.map.baidu.com/ag/coord/convert?from=0&to=4&x='.$lng.'&y='.$lat));
	// $bLatLng = array('lat'=>base64_decode($bMap->y),'lng'=>base64_decode($bMap->x));
	api.OutRight(c, res)
}
