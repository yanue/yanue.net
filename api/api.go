package api

import (
	"github.com/dgrijalva/jwt-go"
	"github.com/gin-gonic/gin"
	"net/http"
	"strings"
	"yanue/util"
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

func (s *ApiHandler) OutRight(c *gin.Context, data interface{}) {
	c.JSON(http.StatusOK, &JsonResp{
		Code: 0,
		Msg:  "",
		Data: data,
	})
}

func (s *ApiHandler) OutError(c *gin.Context, errno int, msg ...string) {
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

func (s *ApiHandler) Sign(params map[string]interface{}) (string, error) {
	var claim = jwt.NewWithClaims(jwt.SigningMethodHS256, jwt.MapClaims(params))
	return claim.SignedString([]byte(""))
}

func (s *ApiHandler) GetUid(c *gin.Context) int {
	return c.GetInt("uid")
}

func (s *ApiHandler) GpsOffset(c *gin.Context) {
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
	s.OutRight(c, res)
}
