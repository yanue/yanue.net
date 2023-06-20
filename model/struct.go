package model

type AdminUser struct {
	Id          int        `json:"id" gorm:"column:id"`
	Username    string     `json:"username" gorm:"column:username"`
	Password    string     `json:"password" gorm:"column:password"`
	Salt        string     `json:"salt" gorm:"column:salt"`
	Email       string     `json:"email" gorm:"column:email"`
	LastLogin   int        `json:"last_login" gorm:"column:last_login"`
	LastIp      string     `json:"last_ip" gorm:"column:last_ip"`
	LoginCount  int        `json:"login_count" gorm:"column:login_count"`
	LoginFailed int        `json:"login_failed" gorm:"column:login_failed"`
	Token       string     `json:"token" gorm:"column:token"`
	Status      UserStatus `json:"status" gorm:"column:status"`
}

func (m *AdminUser) TableName() string {
	return "admin_user"
}

type Post struct {
	Id        int    `json:"id" gorm:"column:id"`
	Cid       int    `json:"cid" gorm:"column:cid"`
	Tags      string `json:"tags" gorm:"column:tags"`
	Title     string `json:"title" gorm:"column:title"`
	Keywords  string `json:"keywords" gorm:"column:keywords"`
	CoverImg  string `json:"cover_img" gorm:"column:cover_img"`
	Content   string `json:"content" gorm:"column:content"`
	Created   int64  `json:"created" gorm:"column:created"`
	Modified  int64  `json:"modified" gorm:"column:modified"`
	Published int    `json:"published" gorm:"column:published"`
	Recommend int    `json:"recommend" gorm:"column:recommend"`
	Comments  int    `json:"comments" gorm:"column:comments"`
	Views     int    `json:"views" gorm:"column:views"`
}

func (m *Post) TableName() string {
	return "post"
}

type PostCat struct {
	Id   int    `json:"id" gorm:"column:id"`
	Name string `json:"name" gorm:"column:name"`
}

func (m *PostCat) TableName() string {
	return "post_cat"
}

type PostTags struct {
	Id    int    `json:"id" gorm:"column:id"`
	Name  string `json:"name" gorm:"column:name"`
	Count int    `json:"count" gorm:"column:count"`
}

func (m *PostTags) TableName() string {
	return "post_tags"
}
