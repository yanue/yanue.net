<?php
namespace App\Admin\Lib;

use Aliyun\OSS\OSSClient;
use Library\Core\Config;
use Library\Util\Ajax;

/**
 * 上传基础类(获取文件流)
 *
 * Class test
 */
class Upload
{

    public $upExt = "";
    public $maxAttachSize = 209715200; // 2M
    public $inputField = 'file'; // 表单文件域
    public $batField = 'files'; // 表单文件域
    public $attachDir = 'uploads'; //附件根目录

    public $allowBuff = array();
    public $skipFiles = array();

    public function __construct()
    {
        $this->ajax = new Ajax();
    }

    /**
     * 获取上传后的流数据
     * ----支持html5上传
     *
     */
    public function upOne()
    {
        // 判断上传方式
        if (isset($_SERVER['HTTP_CONTENT_DISPOSITION'])
            && preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i', $_SERVER['HTTP_CONTENT_DISPOSITION'], $info)
        ) {
            //1. HTML5 方式上传
            // HTML5上传（firefox和chrome核心的浏览器）
            $localName = urldecode($info[2]); //上传的文件名称
            # 缓存文件及后缀
            $ext = pathinfo($localName);
            $ext = $ext['extension'];
            $buff = file_get_contents("php://input");

            if (empty($buff) || $buff == null) {
                $this->_error(UPLOAD_ERR_TMP_NAME_NOT_EXIST, '无文件上传');
            }

            // 返回信息 todo size
            return array('buff' => $buff, 'ext' => $ext);
        } else {
            //2. 普通方式上传
            $upfile = isset($_FILES[$this->inputField]) ? $_FILES[$this->inputField] : null;
            if (!$upfile) {
                $this->_error(UPLOAD_ERR_FILE_FIELD_NOT_RECEIVED, '表单文件域' . $this->inputField . '未接收到数据');
            }
            // 上传出错
            $errno = isset($upfile['error']) ? $upfile['error'] : 0;
            if ($errno > 0) {
                if (is_array($errno)) {
                    $this->_error(UPLOAD_ERR_BATCH_IS_NOT_ALLOWED, '请确认filedata文件域参数');
                } else {
                    $this->_error(2000 + $errno);
                }
            }

            if (empty($upfile['tmp_name']) || $upfile['tmp_name'] == null) {
                $this->_error(UPLOAD_ERR_TMP_NAME_NOT_EXIST, '无文件上传');
            }

            // 匹配格式
            $pattern = '/\.(' . $this->upExt . ')$/i';
            if (!preg_match($pattern, $upfile['name'], $sExt)) {
                $this->_error(UPLOAD_ERR_FILE_EXT_ONLY_ALLOWED, $this->upExt);
            };

            // 文件太大
            if ($this->maxAttachSize < $upfile['size']) {
                $this->_error(UPLOAD_ERR_UPLOAD_FILE_IS_TOO_LARGE, '最大不能超过：' . (round($this->maxAttachSize / 1024, 2)) . 'K');
            };
            $this->tmpSize = $upfile['size'];
            # 缓存文件及后缀
            $buff = file_get_contents($upfile['tmp_name']);
            @unlink($upfile['tmp_name']);
            unset($upfile['tmp_name']);
            return array('buff' => $buff, 'ext' => $sExt[1]);
        }

    }

    /**
     * 批量上传
     */
    public function upBatch()
    {
        //2. 普通方式上传
        $upfiles = isset($_FILES[$this->batField]) ? $_FILES[$this->batField] : null;
        if (!$upfiles) {
            $this->_error(UPLOAD_ERR_FILE_FIELD_NOT_RECEIVED, '表单文件域' . $this->batField . '未接收到数据');
        }

        // 上传出错
        $errnos = isset($upfiles['error']) ? $upfiles['error'] : 0;

        // 判断是否批量上传
        if (!is_array($errnos)) {
            $this->_error(UPLOAD_ERR_ONLY_SUPPORT_BATCH_UPLOAD);
        }

        $tmp = array();
        // change array
        foreach ($upfiles as $name => $subs) {
            foreach ($subs as $key => $sub) {
                $tmp[$key][$name] = $sub;
            }
        }
        $skipFiles = array();
        $allows = array();
        foreach ($tmp as $key => $file) {

            list($name, $type, $tmp_name, $error, $size) = array_values($file);

            if (!$name) {
                continue;
            }

            // 上传错误
            if ($error > 0) {
                $fail['file'] = $name;
                $fail['msg'] = $this->_getErrMsg(2000 + $error);
                $skipFiles[$key] = $fail;
                continue;
            }

            // 匹配格式
            $pattern = '/\.(' . $this->upExt . ')$/i';
            if (!preg_match($pattern, $name, $sExt)) {
                $fail['file'] = $name;
                $fail['msg'] = $this->_getErrMsg(UPLOAD_ERR_FILE_EXT_ONLY_ALLOWED);
                $skipFiles[$key] = $fail;
                continue;
            };

            // 文件太大
            if ($this->maxAttachSize < $size) {
                $fail['msg'] = $this->_getErrMsg(UPLOAD_ERR_UPLOAD_FILE_IS_TOO_LARGE) . ',最大不能超过：' . (round($this->maxAttachSize / 1024, 2)) . 'K';
                $fail['file'] = $name;
                $skipFiles[$key] = $fail;
                continue;
            };

            # 缓存文件及后缀
            $allow['name'] = $name;
            $allow['ext'] = $sExt[1];
            $allow['buff'] = file_get_contents($tmp_name);
            $allows[$key] = $allow;
        }

        # 获取通过上传的文件和失败的文件
        $this->allowBuff = $allows;
        $this->skipFiles = $skipFiles;

    }

    /**
     * @param $file array ('buff' => $buff, 'ext' => $sExt[1], 'md5' => $md5);
     * @return array
     */
    public function saveFile($file)
    {
        $filename = $this->getLocalPath(md5($file['buff']), $file['ext']);
        if (!file_exists($filename['full_path'])) {
            file_put_contents($filename['full_path'], $file['buff']);
        }

        $this->saveToAliOss($filename['url'], $file);
        unset($file);

        return array('url' => $filename['url'], 'full_path' => $filename['full_path']);
    }

    /**
     * save file to aliYun Oss
     */
    public function saveToAliOss($file, $buff, $bucket = '')
    {
        //  async to aliYun Oss
        Config::load('aliyun');

        // bucket
        $bucket = $bucket ? $bucket : OSS_DEFAULT_BUCKET;

        require_once WEB_ROOT . '/plugin/aliyun/aliyun.php';

        $client = OSSClient::factory(array(
            'AccessKeyId' => OSS_ACCESS_ID,
            'AccessKeySecret' => OSS_ACCESS_KEY,
        ));

        $res = $client->putObject(array(
            'Bucket' => $bucket,
            'Key' => ltrim($file, '/'),
            'Content' => $buff['buff'],
            'ContentLength' => strlen($buff['buff'])
        ));

        unset($buff);
        return $res;
    }


    /**
     * 根据文件md5获取文件存放路径 保证文件唯一性
     *
     * @param $md5
     * @param $sExt
     * @return array
     */
    private function getLocalPath($md5, $sExt)
    {
        $fileDir = '/' . strtolower($sExt) . '/' . strtoupper(substr($md5, 0, 2)) . '/' . strtoupper(substr($md5, 2, 2));
        $fullPath = WEB_ROOT . '/' . $this->attachDir . $fileDir;
        if (!is_dir($fullPath)) {
            @mkdir($fullPath, 0777, true);
        }
        $newFilename = $md5 . '.' . $sExt;
        $targetPath = $fullPath . '/' . $newFilename;
        $fileUrl = $fileDir . '/' . $newFilename;
        return array('url' => $fileUrl, 'full_path' => $targetPath);
    }

    //get remote img
    public function saveRemoteImg($sUrl)
    {
        if (substr($sUrl, 0, 10) == 'data:image') { // base64
            if (!preg_match('/^data:image\/(' . $this->upExt . ')/i', $sUrl, $sExt)) return false;
            $sExt = $sExt[1];
            $imgContent = base64_decode(substr($sUrl, strpos($sUrl, 'base64,') + 7));
        } else { //
            if (!preg_match('/\.(' . $this->upExt . ')$/i', $sUrl, $sExt)) return false;
            $sExt = $sExt[1];
            $imgContent = $this->getContentFromUrl($sUrl);
        }

        return array('buff' => $imgContent, 'ext' => $sExt);
    }

    // get file content from url
    public function getContentFromUrl($sUrl, $jumpNums = 0)
    {
        $arrUrl = parse_url(trim($sUrl));
        if (!$arrUrl) return false;
        $host = $arrUrl['host'];
        $port = isset($arrUrl['port']) ? $arrUrl['port'] : 80;
        $path = $arrUrl['path'] . (isset($arrUrl['query']) ? "?" . $arrUrl['query'] : "");
        $fp = @fsockopen($host, $port, $errno, $errstr, 30);
        if (!$fp) return false;
        $output = "GET $path HTTP/1.0\r\nHost: $host\r\nReferer: $sUrl\r\nConnection: close\r\n\r\n";
        stream_set_timeout($fp, 60);
        @fputs($fp, $output);
        $Content = '';
        while (!feof($fp)) {
            $buffer = fgets($fp, 4096);
            $info = stream_get_meta_data($fp);
            if ($info['timed_out']) return false;
            $Content .= $buffer;
        }
        @fclose($fp);

        if (preg_match('/^HTTP\/\d.\d (301|302)/is', $Content) && $jumpNums < 5) {
            if (preg_match("/Location:(.*?)\r\n/is", $Content, $murl)) return getUrl($murl[1], $jumpNums + 1);
        }
        if (!preg_match('/^HTTP\/\d.\d 200/is', $Content)) return false;
        $Content = explode("\r\n\r\n", $Content, 2);
        $Content = $Content[1];
        if ($Content) return $Content;
        else return false;
    }

    /**
     * 上传错误输出并退出
     *
     */
    function _error($code, $msg = '')
    {
        $this->ajax->outError($code, $msg);
    }

    public function _getErrMsg($code)
    {
        return $this->ajax->getErrorMsg($code);
    }

}