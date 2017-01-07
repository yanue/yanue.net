<?php
/**
 * Created by PhpStorm.
 * User: yanue-mi
 * Date: 13-12-25
 * Time: 下午2:00
 */

namespace App\Sns\Helper;

use App\Passport\Lib\UserBase;
use App\Passport\Model\UserProfileModel;
use App\Sns\Lib\Activity;
use App\Sns\Lib\Club;
use App\Sns\Lib\Diary;
use App\Sns\Lib\Requirement;

class UserCenter
{
    private static $testComment = '{"total":2,"offset":0,"limit":"10","list":[{"avatar":"513","content":"\u8fd9\uff01","item":"45","nick":"adminadmin","posted":1387273984,"replies":4,"reply_content":[{"reply_for":{"user":"10000004","nick":"\u5f20\u4e09","avatar":"http:\/\/192.168.1.168:8080\/avatar\/M00\/00\/01\/wKgBqlK6sqeANQXeAAE55HcKklE808.png"},"poster":{"user":"10000004","nick":"\u5f20\u4e09","avatar":"http:\/\/192.168.1.168:8080\/avatar\/M00\/00\/01\/wKgBqlK6sqeANQXeAAE55HcKklE808.png"},"content":"\u6211\u4e5f\u8bf4\u4e24\u53e5asdasdasds","posted":1388132186},{"reply_for":{"user":"10000004","nick":"\u5f20\u4e09","avatar":"http:\/\/192.168.1.168:8080\/avatar\/M00\/00\/01\/wKgBqlK6sqeANQXeAAE55HcKklE808.png"},"poster":{"user":"10000004","nick":"\u5f20\u4e09","avatar":"http:\/\/192.168.1.168:8080\/avatar\/M00\/00\/01\/wKgBqlK6sqeANQXeAAE55HcKklE808.png"},"content":"\u6211\u4e5f\u8bf4\u4e24\u53e5asdasdasdasd","posted":1388146071},{"reply_for":{"user":"10000004","nick":"\u5f20\u4e09","avatar":"http:\/\/192.168.1.168:8080\/avatar\/M00\/00\/01\/wKgBqlK6sqeANQXeAAE55HcKklE808.png"},"poster":{"user":"10000004","nick":"\u5f20\u4e09","avatar":"http:\/\/192.168.1.168:8080\/avatar\/M00\/00\/01\/wKgBqlK6sqeANQXeAAE55HcKklE808.png"},"content":"\u6211\u4e5f\u8bf4\u4e24\u53e5asdasdasdasdadasdasd","posted":1388146074},{"reply_for":{"user":"10000004","nick":"\u5f20\u4e09","avatar":"http:\/\/192.168.1.168:8080\/avatar\/M00\/00\/01\/wKgBqlK6sqeANQXeAAE55HcKklE808.png"},"poster":{"user":"10000004","nick":"\u5f20\u4e09","avatar":"http:\/\/192.168.1.168:8080\/avatar\/M00\/00\/01\/wKgBqlK6sqeANQXeAAE55HcKklE808.png"},"content":"\u6211\u4e5f\u8bf4\u4e24 \u554a\u5b9e\u6253\u4e0a\u7684","posted":1388197864}],"type":"5","user":"10000004","id":"52b01f008e08cace49efb54d"}]}';

    //
    public static function getLatestReq($uid = NULL, $offset, $limit = 10)
    {
        if ($uid > 0) {
            $res = Requirement::instance($uid)->listMines($offset, $limit);
        } else {
            $res = Requirement::listAll($offset, $limit, 1);
        }
        /* if ($res['total'] > 0) {
            foreach ($res['list'] as &$v) {
               $v['comments_content'] = Comment::listComments(DataStats::DATA_TYPE_REQUIREMENT, $v['id']);
               $v['comments_content'] = json_decode(self::$testComment, true);
            }
        } */
        return $res;
    }

    public static function getLatestNote($uid = NULL, $offset, $limit = 10)
    {
        $res = Diary::listDiariesDetail($offset, $limit, null, $uid);

        /* if ($res['total'] > 0) {
            foreach ($res['list'] as &$v) {
                $v['comments_content'] = Comment::listComments(DataStats::DATA_TYPE_DIARY, $v['id'], 0, 10);
            }
        } */
        return $res;
    }

    public static function getLatestActivity($uid = NULL, $offset, $limit = 10)
    {
        $res = Activity::listActivities($offset, $limit, null, false, $uid);

        /* if ($res['total'] > 0) {
            foreach ($res['list'] as &$v) {
                $v['comments_content'] = Comment::listComments(DataStats::DATA_TYPE_ACTIVITY, $v['id'], 0, 10);
            }

        } */

        return $res;
    }

    public static function getSuggestActivities($uid, $limit = 5)
    {
        $activity = Activity::listActivities(0, $limit, null, false, $uid);
        return $activity;
    }

    public static function getInterestUsers($uid, $limit = 5)
    {
        $m = new UserProfileModel();
        $m->user = $uid;
        $user = $m->toArray();
        $interests = isset($user['interests']) ? $user['interests'] : '';
        $interests = json_decode($interests, true);
        $str = [];
        if ($interests) {
            foreach ($interests as $val) {
                $str[] = 'LOCATE(\'"id":"' . $val['id'] . '"\',`p.interests`) > 0';
            }
            $sql = 'SELECT  u.id,u.nick,u.avatar,u.gender FROM user.user sa u LEFT JOIN user.user_profile as p ON  u.id = p.user WHERE u.id != ' . $uid . ' and  ' . implode(' OR ', $str) . '  LIMIT ' . ($limit * 2) . ' order by u.created desc';
            $users = $m->findAll($sql);
        } else {
            $sql = 'SELECT u.id,u.nick,u.avatar,u.gender FROM user.user as u  where  u.id != ' . $uid . '   order by created desc LIMIT ' . ($limit * 2);
            $users = $m->findAll($sql);
        }

        unset($str);
        $tempUsers = [];
        foreach ($users as $user) {
            if (count($tempUsers) >= $limit) {
                break;
            }
            if (UserBase::isFriend($uid, $user['id'])) {
                continue;
            }
            array_push($tempUsers, $user);
        }
        unset($users);
        return $tempUsers;
    }

    public static function getInterestClubs($uid, $limit = 5)
    {
        if($uid > 0) {
            return Club::instance($uid)->getList(0, 3);
        }
        else {
            return Club::getList(0, 3);
        }
    }


} 