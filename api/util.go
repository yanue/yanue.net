package api

import "fmt"

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
