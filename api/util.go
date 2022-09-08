package api

import (
	"fmt"
	"sort"
	"strings"
	"time"
	"yanue/model"
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
	Month string
	Count int
	List  []*Post
}

func doArchive(list []*model.Post, catNames map[int]string) (archives []Archive, tags map[string]int, cats map[string]int) {
	archiveMaps := make(map[string][]*Post, 0)
	tags = make(map[string]int, 0)
	cats = make(map[string]int, 0)
	catCounts := make(map[int]int, 0)
	for _, post := range list {
		item := adaptPost(post, catNames)
		for _, tag := range item.Tags {
			tags[tag] += 1
		}
		catCounts[post.Cid] += 1
		month := time.Unix(int64(post.Created), 0).Format("2006年01月")
		archiveMaps[month] = append(archiveMaps[month], item)
	}
	for cid, count := range catCounts {
		cats[catNames[cid]] = count
	}
	sort.SliceStable(archives, func(i int, j int) bool {
		return archives[i].Month > archives[j].Month
	})
	archives = make([]Archive, 0)
	for month, posts := range archiveMaps {
		archives = append(archives, Archive{
			Month: month,
			Count: len(posts),
			List:  posts,
		})
	}
	sort.SliceStable(archives, func(i int, j int) bool {
		return archives[i].Month > archives[j].Month
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

func getSideData() (data map[string]any) {
	list, _ := model.Model.GetPostList("published=1", 0, 1000)
	catNames, _ := model.Model.GetCats()
	archives, tags, cats := doArchive(list, catNames)
	data = make(map[string]any, 0)
	data["archives"] = archives
	data["tags"] = tags
	data["cats"] = cats
	return
}
