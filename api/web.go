package api

import (
	"bytes"
	"fmt"
	"github.com/gin-gonic/gin"
	"github.com/yuin/goldmark"
	"github.com/yuin/goldmark/extension"
	"github.com/yuin/goldmark/parser"
	"github.com/yuin/goldmark/renderer/html"
	"go.abhg.dev/goldmark/toc"
	"html/template"
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

func (s *WebHandler) Home(c *gin.Context) {
	s.showList(c)
}

func (s *WebHandler) Archives(c *gin.Context) {
	c.HTML(http.StatusOK, "archives", gin.H{
		"side":      getSideData(),
		"title":     "归档",
		"activeNav": "archives",
		"find":      "",
	})
}

func (s *WebHandler) Tags(c *gin.Context) {
	side := getSideData()
	cat := c.Param("tag")
	find := ""
	for _, archive := range side.Tags {
		if cat == archive.Name {
			find = cat
		}
	}
	c.HTML(http.StatusOK, "tags", gin.H{
		"side":      getSideData(),
		"find":      find,
		"title":     "标签",
		"activeNav": "tags",
		"count":     len(side.Tags),
	})
}

func (s *WebHandler) Categories(c *gin.Context) {
	side := getSideData()
	cat := c.Param("cat")
	find := ""
	for _, archive := range side.Cats {
		if cat == archive.Name {
			find = cat
		}
	}
	c.HTML(http.StatusOK, "categories", gin.H{
		"side":      side,
		"find":      find,
		"title":     "分类",
		"activeNav": "categories",
		"count":     len(side.Cats),
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
		_, isLogin := isAdminLogon(c)
		newPost := adaptPost(post, cats)
		md := goldmark.New(
			goldmark.WithExtensions(
				extension.GFM,
				extension.Typographer,
				extension.DefinitionList,
				&toc.Extender{Title: "文章目录"},
			),
			goldmark.WithParserOptions(
				parser.WithAutoHeadingID(),
			),
			goldmark.WithRendererOptions(
				html.WithUnsafe(),
				html.WithHardWraps(),
				html.WithXHTML(),
			),
		)
		var buf bytes.Buffer
		err := md.Convert([]byte(post.Content), &buf)
		if err != nil {

		}
		// 新窗口跳转
		newPost.Content = strings.ReplaceAll(buf.String(), "<a ", "<a target='_blank'")
		c.HTML(http.StatusOK, "post", gin.H{
			"title":     post.Title,
			"content":   post.Content,
			"post":      newPost,
			"nextTitle": nextTitle,
			"nextId":    nextId,
			"prevTitle": prevTitle,
			"prevId":    prevId,
			"isLogin":   isLogin,
			"activeNav": "home",
			"side":      getSideData(),
			"n":         (time.Now().Unix() - post.Created) / (86400 * 360),
			"unescaped": func(str string) template.HTML { return template.HTML(str) },
		})
	}
}

func (s *WebHandler) More(c *gin.Context) {
	s.showList(c)
}

func (s *WebHandler) showList(c *gin.Context) {
	size := 10
	page := util.ToInt(c.Query("page"))
	key := strings.ReplaceAll(strings.ReplaceAll(strings.Trim(c.DefaultQuery("key", ""), " "), "\"", ""), "'", "")
	where := "published=1"
	if len(key) > 0 {
		keyArg := fmt.Sprintf("%v%v%v", "%", key, "%")
		where = fmt.Sprintf("%s and (`title` like '%s' or `content` like '%s') ", where, keyArg, keyArg)
	}
	cnt := model.Model.Count(where)
	list, _ := model.Model.GetPostList(where, page, size)
	cats, _ := model.Model.GetCats()
	totalPage := int(math.Ceil(float64(cnt) / float64(size)))
	if page < 1 {
		page = 1
	}
	if page > totalPage {
		page = totalPage
	}
	newList := adaptPosts(list, cats)
	c.HTML(http.StatusOK, "more", gin.H{
		"list":       newList,
		"count":      cnt,
		"page":       page,
		"size":       size,
		"totalPage":  totalPage,
		"activeNav":  "home",
		"search_key": key,
		"title":      fmt.Sprintf("yanue.net - 第%v页", page),
		"pages":      genPage("/more", page, totalPage, key),
		"side":       getSideData(),
	})
}

func (s *WebHandler) About(c *gin.Context) {
	c.HTML(http.StatusOK, "about", gin.H{
		"title":     "关于我",
		"activeNav": "about",
	})
}

func (s *WebHandler) Map(c *gin.Context) {
	c.HTML(http.StatusOK, "map/map.html", gin.H{
		"title":       "经纬度在线查询,地名(批量)查询经纬度,经纬度(批量)查询地名",
		"keywords":    "经纬度,查询,经纬度查询,经纬度在线查询,经纬度查找地名,经纬度(批量)查询,经纬度转换地名,地名批量查询经纬度,查询地名返回经纬度,根据经纬度批量查询地名,google map经纬度 ,yanue.net",
		"description": "运用google map api开发的地图系统，实现经纬度(批量)在线查询,地名批量查询经纬度,google 经纬度查询地名,经纬度查找地名,查询地名返回经纬度,根据经纬度批量查询地名,google map运用geocoder.geocode实例",
	})
}

func (s *WebHandler) ToLatLng(c *gin.Context) {
	c.HTML(http.StatusOK, "map/map_toLatLng.html", gin.H{
		"title":       "在线查询经纬度,通过地名查询经纬度(手动精确定位)",
		"keywords":    "经纬度,查询,经纬度查询,经纬度在线查询,经纬度查找地名,查询地名返回经纬度(手动精确定位),鼠标经过地图区域提示经纬度",
		"description": "在线查询经纬度,通过地名查询经纬度(手动精确定位)，实现鼠标经过提示经纬度，自动填充地名地点名称，输入完成后可直接点击enter键进行解析，地理位置不准确，可以拖动重新解析，解析后经纬度信息显示完整",
	})
}

func (s *WebHandler) Gps(c *gin.Context) {
	c.HTML(http.StatusOK, "map/map_gps.html", gin.H{
		"title":       "GPS坐标转换经纬度,GPS转谷歌百度地图经纬度",
		"keywords":    "GPS,GPS转换,GPS坐标转换经纬度，GPS转谷歌地图经纬度，GPS免费接口,GPS免费转换接口,gpsApi.php,map.yanue.net,半叶寒羽-原创作品,GPS定位,GPS to lat lng，GPS Coordinate Converter",
		"description": "GPS,GPS转换,GPS坐标转换经纬度，GPS转谷歌地图经纬度，GPS免费接口,GPS免费转换接口,gpsApi.php,map.yanue.net,半叶寒羽-原创作品,GPS,GPS to lat lng，GPS Coordinate Converter,GPS转中文地址,GPS转Google地址!详情见https://map.yanue.net/gps.html",
	})
}
