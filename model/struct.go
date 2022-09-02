package model

type AdminUsers struct {
	Uid        int    `json:"uid" gorm:"column:uid"`
	UserName   string `json:"user_name" gorm:"column:user_name"`
	Password   string `json:"password" gorm:"column:password"`
	Email      string `json:"email" gorm:"column:email"`
	TrueName   string `json:"true_name" gorm:"column:true_name"`
	Level      int    `json:"level" gorm:"column:level"`
	CreateTime int    `json:"create_time" gorm:"column:create_time"`
	ModifyTime int    `json:"modify_time" gorm:"column:modify_time"`
	LastLogin  int    `json:"last_login" gorm:"column:last_login"`
	LastIp     string `json:"last_ip" gorm:"column:last_ip"`
	LoginCount int    `json:"login_count" gorm:"column:login_count"`
}

func (m *AdminUsers) TableName() string {
	return "admin_users"
}

type Post struct {
	ID        int    `json:"id" gorm:"column:id"`
	Cid       int64  `json:"cid" gorm:"column:cid"`
	Tags      string `json:"tags" gorm:"column:tags"`
	Title     string `json:"title" gorm:"column:title"`
	SubTitle  string `json:"sub_title" gorm:"column:sub_title"`
	Author    string `json:"author" gorm:"column:author"`
	Keywords  string `json:"keywords" gorm:"column:keywords"`
	CoverImg  string `json:"cover_img" gorm:"column:cover_img"`
	Content   string `json:"content" gorm:"column:content"`
	Created   int    `json:"created" gorm:"column:created"`
	Modified  int    `json:"modified" gorm:"column:modified"`
	Source    string `json:"source" gorm:"column:source"`
	Published int64  `json:"published" gorm:"column:published"`
	Recommend int64  `json:"recommend" gorm:"column:recommend"`
	Comments  int    `json:"comments" gorm:"column:comments"`
	Views     int    `json:"views" gorm:"column:views"`
	WpID      int    `json:"wp_id" gorm:"column:wp_id"`
}

func (m *Post) TableName() string {
	return "post"
}

type PostCat struct {
	ID       int    `json:"id" gorm:"column:id"`
	Alias    string `json:"alias" gorm:"column:alias"`
	Name     string `json:"name" gorm:"column:name"`
	EnName   string `json:"en_name" gorm:"column:en_name"`
	ParentID int    `json:"parent_id" gorm:"column:parent_id"`
	IsParent int    `json:"is_parent" gorm:"column:is_parent"`
	Detail   string `json:"detail" gorm:"column:detail"`
}

func (m *PostCat) TableName() string {
	return "post_cat"
}

type PostTags struct {
	ID    int    `json:"id" gorm:"column:id"`
	Name  string `json:"name" gorm:"column:name"`
	Count int    `json:"count" gorm:"column:count"`
}

func (m *PostTags) TableName() string {
	return "post_tags"
}
