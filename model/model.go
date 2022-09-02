package model

import (
	md "github.com/JohannesKaufmann/html-to-markdown"
	"log"
)

type model struct {
}

func (s *model) GetPost(id int) (item *Post, err error) {
	item = new(Post)
	err = db.First(item, id).Error
	return
}

func (s *model) GetPostList(where string, page int, limit int) (list []*Post, err error) {
	list = make([]*Post, 0)
	err = db.Model(&Post{}).Where(where).Offset(page * limit).Limit(limit).Find(&list).Error
	return
}

func (s *model) Conv() {
	return
	list := make([]*Post, 0)
	err := db.Model(&Post{}).Find(&list).Error
	if err != nil {
	}
	for _, post := range list {
		s.ConvHtmlToMarkdown(post)
	}
	return
}

var converter = md.NewConverter("", true, nil)

func (s *model) ConvHtmlToMarkdown(item *Post) (err error) {
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
