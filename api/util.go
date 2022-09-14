package api

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"sort"
	"strings"
	"time"
	"yanue/model"
	"yanue/util"
)

type Post struct {
	*model.Post
	Time string
	Date string
	Word int
	Tags []string
	Cat  string
}

func adaptPost(post *model.Post, cats map[int]string) *Post {
	t := time.Unix(int64(post.Created), 0)
	return &Post{
		Post: post,
		Time: t.Format("2006年01月02日 15:04"),
		Date: t.Format("2006-01-02"),
		Word: len([]rune(post.Content)),
		Tags: getTags(post.Tags),
		Cat:  cats[post.Cid],
	}
}

func adaptPosts(list []*model.Post, cats map[int]string) []*Post {
	newList := make([]*Post, 0)
	for _, post := range list {
		newList = append(newList, adaptPost(post, cats))
	}
	return newList
}

func genPage(path string, page int, totalPage int) []Page {
	list := make([]Page, 0)
	for i := 1; i <= totalPage; i++ {
		nowPage := i
		list = append(list, Page{
			Page:    nowPage,
			Link:    fmt.Sprintf("%v?page=%d", path, nowPage),
			Current: nowPage == page,
		})
	}
	return list
}

type Archive struct {
	Count int
	Name  string
	List  []*Post
}

func doArchive(list []*model.Post, catNames map[int]string) (archives, tags, cats []Archive) {
	archiveMaps := make(map[string][]*Post, 0)
	catMaps := make(map[string][]*Post, 0)
	tagMaps := make(map[string][]*Post, 0)
	for _, post := range list {
		item := adaptPost(post, catNames)
		for _, tag := range item.Tags {
			tagMaps[tag] = append(tagMaps[tag], item)
		}
		catMaps[item.Cat] = append(catMaps[item.Cat], item)
		month := time.Unix(int64(post.Created), 0).Format("2006年01月")
		archiveMaps[month] = append(archiveMaps[month], item)
	}
	for name, posts := range archiveMaps {
		archives = append(archives, Archive{Name: name, Count: len(posts), List: posts})
	}
	for name, posts := range catMaps {
		cats = append(cats, Archive{Name: name, Count: len(posts), List: posts})
	}
	for name, posts := range tagMaps {
		tags = append(tags, Archive{Name: name, Count: len(posts), List: posts})
	}
	sort.SliceStable(archives, func(i int, j int) bool {
		return archives[i].Name > archives[j].Name
	})
	sort.SliceStable(cats, func(i int, j int) bool {
		return cats[i].Name > cats[j].Name
	})
	sort.SliceStable(tags, func(i int, j int) bool {
		return tags[i].Name > tags[j].Name
	})
	return
}

func getTags(tagStr string) (tags []string) {
	list := strings.Split(tagStr, ",")
	exist := make(map[string]bool, 0)
	for _, tag := range list {
		tag = strings.TrimSpace(tag)
		if len(tag) == 0 {
			continue
		}
		if !exist[tag] {
			tags = append(tags, tag)
		}
		exist[tag] = true
	}
	return
}

type SideData struct {
	Archives []Archive
	Tags     []Archive
	Cats     []Archive
	Count    int
}

func getSideData() (data SideData) {
	list, _ := model.Model.GetPostList("published=1", 0, 1000)
	catNames, _ := model.Model.GetCats()
	archives, tags, cats := doArchive(list, catNames)
	data = SideData{
		Archives: archives,
		Tags:     tags,
		Cats:     cats,
		Count:    len(list),
	}
	return
}

func isAdminLogon(c *gin.Context) (user *model.AdminUser, ok bool) {
	var err error
	// 设置cookie
	token, err := c.Cookie("token")
	uid, err := c.Cookie("uid")
	user, err = model.UserModel.TokenVerify(util.ToInt(uid), token)
	if err != nil {
		return
	}
	// 登录已过期
	if time.Now().Unix()-int64(user.LastLogin) > 12*3600 {
		return
	}
	ok = true
	return
}
