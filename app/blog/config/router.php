<?php
$route['sitemap'] = "about/sitemap"; // tag
$route['links'] = "about/links"; // tag
$route['tags'] = "post/tags"; // tag
$route['category'] = "post/category"; // tag

$route['post-(\d+)'] = "post/detail/id/$1"; //post view
$route['topic/([0-9a-zA-Z]+)'] = "post/cat/var/$1"; // category
$route['tag/(.*)'] = "post/tag/var/$1"; // tag
$route['archives/(\d+)'] = "post/wp/id/$1";
$route['archives/(\d+).html'] = "post/wp/id/$1";

return $route;