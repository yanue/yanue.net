package api

import (
	"fmt"
	"strings"
	"time"
	"yanue/model"
)

type Post struct {
	*model.Post
	Time string
	Word int
	Tags []string
	Cat  string
}

func adaptPost(post *model.Post, cats map[int]string) *Post {
	return &Post{
		Post: post,
		Time: time.Unix(int64(post.Created), 0).Format("2006年01月02日 15:04"),
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
	Month string
	List  []*Post
}

func archives(list []*model.Post, cats map[int]string) (all map[string][]*Post, tags map[string]int) {
	all = make(map[string][]*Post, 0)
	tags = make(map[string]int, 0)
	for _, post := range list {
		month := time.Unix(int64(post.Created), 0).Format("2006年01月")
		item := adaptPost(post, cats)
		all[month] = append(all[month], item)
		for _, tag := range item.Tags {
			tags[tag] += 1
		}
	}
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
			tags = append(tags)
		}
		exist[tag] = true
	}
	return
}
