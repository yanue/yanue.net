<?php
/**
 * Created by PhpStorm.
 * User: yanue-mi
 * Date: 14-1-4
 * Time: 下午6:12
 */

namespace App\Sns\Helper;


use App\Admin\Model\Top\TopModel;
use App\Passport\Model\UserFriendModel;
use App\Passport\Model\UserModel;
use App\shop\Model\ItemModel;
use App\Sns\Lib\Activity;
use App\Sns\Lib\Club;
use App\Sns\Lib\DataStats;
use App\Sns\Lib\Diary;
use App\Sns\Lib\Requirement;
use App\Sns\Model\ActivityModel;
use App\Sns\Model\ClubModel;
use App\Sns\Model\DiaryModel;
use App\Sns\Model\UserViewLogModel;

class TopGet
{

    public static function plateData($channel, $plate, $type, $limit = 2, $other_where = '')
    {
        $t = $type ? ' and  cid= "' . $type . '"' : '';
        $where = 'channel = "' . $channel . '" and plate = "' . $plate . '"' . $t . ' ' . $other_where;
        $m = new TopModel();
        $data = $m->getTopData($where, $limit);

        return $data;
    }

    public static function plateName($channel, $alias)
    {
        $m = new TopModel();
        $data = $m->getPlate(array('channel' => $channel, 'alias' => $alias));
        return $data;
    }

    public static function plateTypes($plate, $limit = 6)
    {
        $m = new TopModel();
        $data = $m->getTopTypeData('plate = "' . $plate . '"', $limit);
        return $data;
    }

    public static function topNeed($num)
    {
        $list = Requirement::listAll(0, $num);
        return $list['list'];
    }

    /*  焦点位置最新游记 */

    public static function focusNote($num)
    {
        $list = Diary::listDiaries(0, $num);
        return $list['list'];
    }

    /*  游记排行榜 */

    public static function noteTop($num)
    {
        $uv = new UserViewLogModel();
        $sql_count = 'SELECT item,COUNT(id) as count FROM sns.user_view_log WHERE type=' . DataStats::DATA_TYPE_DIARY . ' GROUP BY item ORDER BY COUNT(id) DESC LIMIT ' . $num . ';';
        $res = $uv->findAll($sql_count);
        foreach ($res as &$v) {
            $diary = new DiaryModel();
            $diary->id = $v['item'];
            $diary->exists();
            $v['title'] = $diary->getAttribute('title');
        }
        return $res;
    }

    /*  游记作者排行榜 */

    public static function noteTopUser($num)
    {
        $uv = new UserViewLogModel();
        $sql_count = 'SELECT user,COUNT(id) as count FROM sns.user_view_log WHERE user IS NOT NULL and type=' . DataStats::DATA_TYPE_DIARY . ' GROUP BY user ORDER BY COUNT(id) DESC LIMIT ' . $num . ';';
        $res = $uv->findAll($sql_count);
        foreach ($res as &$v) {
            $user = new UserModel();
            $user->id = $v['user'];
            $user->exists();
            $v['title'] = $user->getAttribute('nick');
        }
        return $res;
    }

    /*  最新游记列表 */

    public static function latestNoteList($num)
    {
        $list = Diary::listDiariesDetail(0, $num);
        return $list['list'];
    }

    /*  焦点位置最新俱乐部 */

    public static function latestClub($num)
    {
        $list = Activity::listActivities(0, $num);
        return $list['list'];
    }

    /*  焦点位置最新线路 */

    public static function latestItem($num)
    {
        $item = new ItemModel();
        $list = $item->getList(0, 10, 'item_id,item_name,price', 'is_on_sale=1', 'add_time desc');
        return $list;
    }

    /* 俱乐部类别最新活动 */
    public static function latestClubActivity($type, $num = 6)
    {
        $a = new ActivityModel();
        $list = $a->getList(0, $num, 'id,name', 'is_disabled =0 and type= ' . $type, 'created desc');
        return $list;
    }

    /*  俱乐部类别活跃榜 */

    public static function clubTop($type, $num = 6)
    {
        $clubModel = new ClubModel();
        $res = $clubModel->getList(0, $num, 'id,name,logo,members,city_name', 'is_dismissed = 0 and type=' . $type, 'activities desc,members desc,grade desc,created desc');
        return $res;
    }

    public static function homeFriend($num = 5)
    {
        $uf = new UserFriendModel();
        $sql = 'SELECT f.user,u.nick,u.avatar,f.friend,f.remark,f.added FROM user.user_friend AS f LEFT JOIN user.user AS u ON f.user = u.id ' .
            'WHERE f.is_followed=1 AND NOT ISNULL(f.remark)  GROUP BY f.user ORDER BY f.added DESC LIMIT ' . $num;
        $data = $uf->findAll($sql);
        return $data;
    }

} 