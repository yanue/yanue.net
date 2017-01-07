<?php
/**
 * gps经纬度修正
 *
 * 功能说明:利用0.01精度校正库offset.dat文件修正中国地图经纬度偏移。
 *         该校正适用于 Google map China, Microsoft map china ,MapABC 等，这些地图构成方法是一样的。
 * 使用方法:
        $gps = new GpsOffset();
        echo $gps->geoLatLng($lat,$lng);
 * 注意: 请在服务器开启offset.dat读取权限
 * @author yanue (yanue@outlook.com)
 * @version 1.0
 * @copyright yanue.net
 * @time 2013-06-30
 */

class GpsOffset {
    const datMax = 9813675;# 该文件最大数据为9813675条
    private $fp = null;
    /*
     * 构造函数，打开 offset.dat 文件并初始化类中的信息
     * @param string $filename
     * @return null
     */
    function __construct($filename = "offset.dat") {
        if (($this->fp = @fopen($filename, 'rb')) !== false) {
            //注册析构函数，使其在程序执行结束时执行
            register_shutdown_function(array(&$this, '__construct'));
        }
    }

    /*
     * 读取dat文件并查找偏移像素值
     * 说明:
     * dat文件结构:该文件为0.01精度校正数据,并以lng和lat递增形式组合.
     * 其中以8个字节为一组:
     * lng : 2字节经度,如12151表示121.51
     * lat : 2字节纬度,如3130表示31.30
     * x_off : 2字节地图x轴偏移像素值
     * y_off : 2字节地图y轴偏移像素值
     * 因此采用二分法并以lng+lat的值作为条件
     * 注意:请在服务器开启offset.dat读取权限
     *
     */
    private function fromEarthToMars($lat,$lng){
        $tmpLng=intval($lng * 100);
        $tmpLat=intval($lat * 100);
        $left = 0; //开始记录
        $right = self::datMax; //结束记录
        $searchLngLat = $tmpLng.$tmpLat;
        // 采用用二分法来查找查数据
        while($left <= $right){
            $recordCount =(floor(($left+$right)/2))*8; // 取半
            fseek ( $this->fp, $recordCount , SEEK_SET ); // 设置游标
            $c = fread($this->fp,8); // 读8字节
            $lng = unpack('s',substr($c,0,2));
            $lat = unpack('s',substr($c,2,2));
            $x = unpack('s',substr($c,4,2));
            $y = unpack('s',substr($c,6,2));
            $curLngLat=$lng[1].$lat[1];
            if ($curLngLat==$searchLngLat){
                fclose($this->fp);
                return array('x'=>$x[1],'y'=>$y[1]);
                break;
            }else if($curLngLat<$searchLngLat){
                $left=($recordCount/8) + 1;
            }else if($curLngLat>$searchLngLat){
                $right=($recordCount/8) - 1;
            }
        }
        fclose($this->fp);
        return false;
    }

    // 转换经纬度到
    public function geoLatLng($lat,$lng){
        $offset =$this->fromEarthToMars($lat,$lng);
        $lngPixel=$this->lngToPixel($lng,18)+$offset['x'];
        $latPixel=$this->latToPixel($lat,18)+$offset['y'];
        $mixLat = $this->pixelToLat($latPixel,18);
        $mixLng = $this->pixelToLng($lngPixel,18);
        return array('lat'=>$mixLat,'lng'=>$mixLng);
    }
    //经度到像素X值
    private function lngToPixel($lng,$zoom) {
        return ($lng+180)*(256<<$zoom)/360;
    }

    //纬度到像素Y值
    private function latToPixel($lat, $zoom) {
        $siny = sin($lat * pi() / 180);
        $y=log((1+$siny)/(1-$siny));
        return (128<<$zoom)*(1-$y/(2*pi()));
    }

    //像素X到经度
    private function pixelToLng($pixelX,$zoom){
        return $pixelX*360/(256<<$zoom)-180;
    }

    //像素Y到纬度
    private function pixelToLat($pixelY, $zoom) {
        $y = 2*pi()*(1-$pixelY /(128 << $zoom));
        $z = pow(M_E, $y);
        $siny = ($z -1)/($z +1);
        return asin($siny) * 180/pi();
    }

    public function __destruct(){
        if($this->fp){
            fclose($this->fp);
        }
        $this->fp = null;
    }
}