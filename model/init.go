package model

import (
	"gorm.io/driver/sqlite"
	"gorm.io/gorm"
)

var Model = new(model)
var db *gorm.DB

func InitDB() {
	var err error
	db, err = gorm.Open(sqlite.Open("data/data.db"), &gorm.Config{})
	if err != nil {
		panic("failed to connect database")
	}
}
