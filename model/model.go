package model

import (
	"encoding/json"
	"fmt"
	md "github.com/JohannesKaufmann/html-to-markdown"
	"log"
	"strings"
	"time"
)

type model struct {
}

func (s *model) GetPost(id int) (item *Post, err error) {
	item = new(Post)
	err = db.First(item, id).Error
	return
}

func (s *model) GetPostList(where string, page int, limit int) (list []*Post, err error) {
	if page < 1 {
		page = 1
	}
	list = make([]*Post, 0)
	err = db.Model(&Post{}).Where(where).Order("id desc").Offset((page - 1) * limit).Limit(limit).Find(&list).Error
	return
}

func (s *model) UpdatePost(id int, item *Post) (err error) {
	updateFields := []string{"cid", "title", "tags", "content", "modified", "published", "recommend"}
	item.Modified = int(time.Now().Unix())
	err = db.Model(&Post{}).Where("id = ?", id).Select(updateFields).Updates(item).Error
	return
}

func (s *model) DelPost(id int) (err error) {
	err = db.Model(&Post{}).Delete(&Post{}, id).Error
	return
}

func (s *model) CreatePost(item *Post) (err error) {
	item.Id = 0
	item.Created = int(time.Now().Unix())
	item.Modified = int(time.Now().Unix())
	err = db.Model(&Post{}).Create(item).Error
	return
}

func (s *model) Count(where string) int {
	var count int64
	_ = db.Model(&Post{}).Where(where).Count(&count)
	return int(count)
}

func (s *model) GetCats() (cats []*PostCat, err error) {
	cats = make([]*PostCat, 0)
	err = db.Model(&PostCat{}).Find(&cats).Error
	return
}

func (s *model) ConvHtmlToMarkdown() {
	return
	list := make([]*Post, 0)
	err := db.Model(&Post{}).Find(&list).Error
	if err != nil {
	}
	for _, post := range list {
		s.conv(post)
	}
	return
}

var converter = md.NewConverter("", true, nil)

func (s *model) conv(item *Post) (err error) {
	markdown, err := converter.ConvertString(item.Content)
	if err != nil {
		log.Println("err", err)
		panic(err)
	}
	item.Content = markdown
	//fmt.Println("item", item.ID)
	//fmt.Println(markdown)
	db.Save(item)
	return
}

func (s *model) ConvTags() {
	type T struct {
		Id   int    `json:"id"`
		Name string `json:"name"`
	}
	list := make([]*Post, 0)
	err := db.Model(&Post{}).Find(&list).Error
	if err != nil {
	}
	for _, post := range list {
		tags := make([]T, 0)
		err = json.Unmarshal([]byte(strings.ReplaceAll(post.Tags, "\\", "")), &tags)
		fmt.Println("err", post.Id, err)
		if err != nil {
			continue
		}
		newTags := make([]string, 0)
		for _, tag := range tags {
			newTags = append(newTags, tag.Name)
		}
		post.Tags = strings.Join(newTags, ",")
		db.Save(post)
	}
}
