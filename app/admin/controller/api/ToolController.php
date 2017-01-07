<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 3/3/14
 * Time: 5:21 PM
 */

namespace App\Admin\Controller\Api;

use App\Admin\Lib\Upload;
use Library\Util\Ajax;

class ToolController extends ApiController
{
    public function fixPhotoAction()
    {
        $pics = $this->request('pic');
        if (!$pics) {
            Ajax::outError(ERROR_INVALID_REQUEST_PARAM);
        }

        if (is_array($pics)) { // array
            foreach ($pics as $pic) {
                $this->parseFix($pic);
            }
        } else {
            $this->parseFix($pics);
        }

        Ajax::outRight(true);
    }

    private function parseFix($pic)
    {
        $upload = new Upload();
        $url = parse_url($pic);
        $path = $url['path'];
        $buff['buff'] = file_get_contents(WEB_ROOT . '/uploads/' . ltrim($path, '/'));

        return $upload->saveToAliOss($path, $buff);
    }

} 