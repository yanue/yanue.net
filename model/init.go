package model

import (
	"gorm.io/driver/sqlite"
	"gorm.io/gorm"
)

var Model = new(model)
var UserModel = new(userModel)
var db *gorm.DB

func InitDB() {
	var err error
	db, err = gorm.Open(sqlite.Open("data/data.db"), &gorm.Config{})
	if err != nil {
		panic("failed to connect database")
	}
	err = db.AutoMigrate(&AdminUser{}, &Post{})
}

// 用户状态
type UserStatus int

const (
	UserStatusDisable UserStatus = 0 // 用户已禁用
	UserStatusOk      UserStatus = 1 // 正常
	UserStatusLocked  UserStatus = 2 // 用户已锁定
)
