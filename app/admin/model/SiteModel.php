<?php
/**
 * SiteModel.php
 *
 * @author     yanue <yanue@outlook.com>

 * @time     2013-12-01
 */

namespace App\Admin\Model;


use Library\Core\PDOModel;

class SiteModel extends PDOModel
{
    public $table = "site";
    public $_users = "users";
    public $_user_oauth = "user_oauth";
    public $_post = "post";
    public $_mall_circle = "mall_circle";
    public $_mall = "mall";
    public $_brands = "brands";
    public $_brand_stores = "brand_stores";
    public $_promotions = "promotions";

    public function getSiteSettings()
    {
        return $this->from($this->table)->fetch();
    }

    public function up($data)
    {
        return $this->update($this->table)->set($data)->where('id=1')->execute();
    }

    public function getCount()
    {


    }

} 