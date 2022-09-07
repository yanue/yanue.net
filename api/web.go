package api

import (
	"github.com/gin-gonic/gin"
	"math"
	"net/http"
	"strings"
	"time"
	"yanue/model"
	"yanue/util"
)

type WebHandler struct {
}

func NewWebHandler() *WebHandler {
	web := new(WebHandler)
	return web
}

type Post struct {
	*model.Post
	Time string
	Word int
	Tags []string
	Cat  string
}

// 首页
func (s *WebHandler) Home(c *gin.Context) {
	list, _ := model.Model.GetPostList("published=1", 0, 9)
	cats, _ := model.Model.GetCats()
	newList := make([]Post, 0)
	for _, post := range list {
		newList = append(newList, Post{
			Post: post,
			Time: time.Unix(int64(post.Created), 0).Format("2006年01月02日 15:04"),
			Word: len([]rune(post.Content)),
			Tags: strings.Split(post.Tags, ","),
			Cat:  cats[post.Cid],
		})
	}
	c.HTML(http.StatusOK, "index", gin.H{
		"title":   "",
		"content": "",
		"list":    newList,
	})
}

// like: /post-1.html or /post/2.html

func (s *WebHandler) Post(c *gin.Context) {
	cats, _ := model.Model.GetCats()
	id := c.Param("id")
	id = strings.TrimSuffix(id, ".html")
	postId := util.ToInt(id)
	post, err := model.Model.GetPost(postId)
	if err != nil {
		c.HTML(http.StatusOK, "404", gin.H{})
	} else {
		var nextId, prevId int
		var nextTitle, prevTitle string
		if next, err := model.Model.NextPost(postId); err == nil {
			nextTitle = next.Title
			nextId = next.Id
		}
		if prev, err := model.Model.PrevPost(postId); err == nil {
			prevTitle = prev.Title
			prevId = prev.Id
		}
		// 更新浏览次数
		_ = model.Model.PostView(postId)
		word := len([]rune(post.Content))
		newPost := Post{
			Post: post,
			Time: time.Unix(int64(post.Created), 0).Format("2006年01月02日 15:04"),
			Word: len([]rune(post.Content)),
			Tags: strings.Split(post.Tags, ","),
			Cat:  cats[post.Cid],
		}
		c.HTML(http.StatusOK, "post", gin.H{
			"title":     post.Title,
			"content":   post.Content,
			"word":      word,
			"post":      newPost,
			"nextTitle": nextTitle,
			"nextId":    nextId,
			"prevTitle": prevTitle,
			"prevId":    prevId,
		})
	}
}

func (s *WebHandler) More(c *gin.Context) {
	pageSize = 10
	page := util.ToInt(c.Query("page"))
	where := "published=1"
	cnt := model.Model.Count(where)
	list, _ := model.Model.GetPostList("", page, pageSize)
	cats, _ := model.Model.GetCats()
	totalPage := int(math.Ceil(float64(cnt) / float64(pageSize)))
	if page < 1 {
		page = 1
	}
	if page > totalPage {
		page = totalPage
	}
	newList := make([]Post, 0)
	for _, post := range list {
		newList = append(newList, Post{
			Post: post,
			Time: time.Unix(int64(post.Created), 0).Format("2006年01月02日 15:04"),
			Word: len([]rune(post.Content)),
			Tags: strings.Split(post.Tags, ","),
			Cat:  cats[post.Cid],
		})
	}
	c.HTML(http.StatusOK, "more", gin.H{
		"list":      newList,
		"count":     cnt,
		"page":      page,
		"size":      pageSize,
		"totalPage": totalPage,
		"pages":     genPage("/more", page, totalPage),
	})
}

func (s *WebHandler) Page(c *gin.Context) {
	c.HTML(http.StatusOK, "page.html", gin.H{
		"title": "hello gin " + strings.ToLower(c.Request.Method) + " method",
	})
}

func (s *WebHandler) Map(c *gin.Context) {
	c.HTML(http.StatusOK, "map", gin.H{
		"title":       "经纬度在线查询,地名(批量)查询经纬度,经纬度(批量)查询地名",
		"keywords":    "经纬度,查询,经纬度查询,经纬度在线查询,经纬度查找地名,经纬度(批量)查询,经纬度转换地名,地名批量查询经纬度,查询地名返回经纬度,根据经纬度批量查询地名,google map经纬度 ,yanue.net",
		"description": "运用google map api开发的地图系统，实现经纬度(批量)在线查询,地名批量查询经纬度,google 经纬度查询地名,经纬度查找地名,查询地名返回经纬度,根据经纬度批量查询地名,google map运用geocoder.geocode实例",
	})
}

func (s *WebHandler) ToLatLng(c *gin.Context) {
	c.HTML(http.StatusOK, "toLatLng", gin.H{
		"title":       "在线查询经纬度,通过地名查询经纬度(手动精确定位)",
		"keywords":    "经纬度,查询,经纬度查询,经纬度在线查询,经纬度查找地名,查询地名返回经纬度(手动精确定位),鼠标经过地图区域提示经纬度",
		"description": "在线查询经纬度,通过地名查询经纬度(手动精确定位)，实现鼠标经过提示经纬度，自动填充地名地点名称，输入完成后可直接点击enter键进行解析，地理位置不准确，可以拖动重新解析，解析后经纬度信息显示完整",
	})
}

func (s *WebHandler) Gps(c *gin.Context) {
	c.HTML(http.StatusOK, "gps", gin.H{
		"title":       "GPS坐标转换经纬度,GPS转谷歌百度地图经纬度",
		"keywords":    "GPS,GPS转换,GPS坐标转换经纬度，GPS转谷歌地图经纬度，GPS免费接口,GPS免费转换接口,gpsApi.php,map.yanue.net,半叶寒羽-原创作品,GPS定位,GPS to lat lng，GPS Coordinate Converter",
		"description": "GPS,GPS转换,GPS坐标转换经纬度，GPS转谷歌地图经纬度，GPS免费接口,GPS免费转换接口,gpsApi.php,map.yanue.net,半叶寒羽-原创作品,GPS,GPS to lat lng，GPS Coordinate Converter,GPS转中文地址,GPS转Google地址!详情见https://map.yanue.net/gps.html",
	})
}
