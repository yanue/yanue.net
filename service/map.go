package service

import (
	"bufio"
	"bytes"
	"encoding/binary"
	"fmt"
	"log"
	"math"
	"os"
)

type GpsOffset struct {
	datMax int
	data   []byte
}

var gpsOffset = newGpsOffset()

func newGpsOffset() *GpsOffset {
	gps := new(GpsOffset)
	return gps
}

func (gps *GpsOffset) loadDat(datFile string) {
	fp, err := os.Open(datFile)
	if err != nil {
		log.Println("os.Open", err)
		return
	}
	defer fp.Close()
	info, err := fp.Stat()
	if err != nil {
		log.Println("fp.Stat", err)
		return
	}

	// calculate the bytes size
	var size = info.Size()
	data := make([]byte, size)

	// read into buffer
	buffer := bufio.NewReader(fp)
	_, err = buffer.Read(data)
	if err != nil {
		log.Println("buffer.Read", err)
		return
	}
	gps.data = data
	gps.datMax = len(gps.data)
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
func (gps *GpsOffset) fromEarthToMars(lat, lng int, multiple int) (int, int, bool) {
	left := 0               //开始记录
	right := gps.datMax / 8 //结束记录
	// 如12151,3130组合成121513130
	searchLngLat := lng*multiple + lat
	// 采用用二分法来查找查数据
	for left <= right {
		recordCount := int(math.Floor(float64((left+right)/2))) * 8 // 取半
		last := recordCount + 8
		if last > gps.datMax {
			break
		}

		// 从recordCount位置读8字节
		c := gps.data[recordCount : recordCount+8]
		// 按2个字节分割,并转换成int
		lng := bytesToInt(c[0:2])
		lat := bytesToInt(c[2:4])
		x := bytesToInt(c[4:6])
		y := bytesToInt(c[6:8])
		// 如12151,3130组合成121513130
		curLngLat := lng*multiple + lat

		//log.Println(fmt.Sprintf("find: left=%v,right=%v,max=%v,recordCount=%v,searchLngLat=%v curLngLat=%v,c=%v,lng=%v,lat=%v,x=%v,y=%v", left, right, gps.datMax/8, recordCount, searchLngLat, curLngLat, c, lng, lat, x, y))

		if curLngLat == searchLngLat {
			return x, y, true
		} else if curLngLat < searchLngLat {
			left = int(recordCount/8) + 1
		} else if curLngLat > searchLngLat {
			right = int(recordCount/8) - 1
		}
	}

	return 0, 0, false
}

func (gps *GpsOffset) geoLatLng(lat, lng float64) (float64, float64) {
	if len(gps.data) == 0 {
		return 0, 0
	}
	tmpLat := int(lat * 100)
	tmpLng := int(lng * 100)
	multiple := math.Pow10(len(fmt.Sprintf("%d", tmpLat)))
	x, y, result := gps.fromEarthToMars(tmpLat, tmpLng, int(multiple))
	log.Println("x, y, result", len(gps.data), x, y, result, tmpLat, tmpLng, int(multiple))
	if !result {
		return 0, 0
	}
	lngPixel := gps.lngToPixel(lng, 18) + float64(x)
	latPixel := gps.latToPixel(lat, 18) + float64(y)
	mixLat := gps.pixelToLat(latPixel, 18)
	mixLng := gps.pixelToLng(lngPixel, 18)
	return mixLat, mixLng
}

func bytesToInt(bys []byte) int {
	byteBuff := bytes.NewBuffer(bys)
	var tmp uint16
	// 注意使用: LittleEndian
	_ = binary.Read(byteBuff, binary.LittleEndian, &tmp)
	return int(tmp)
}

//经度到像素X值
func (gps *GpsOffset) lngToPixel(lng float64, zoom int) float64 {
	tmp := 256 << zoom
	return (lng + 180) * float64(tmp) / 360
}

//纬度到像素Y值
func (gps *GpsOffset) latToPixel(lat float64, zoom int) float64 {
	sinY := math.Sin(lat * math.Pi / 180)
	y := math.Log((1 + sinY) / (1 - sinY))
	tmp := 128 << zoom
	return float64(tmp) * (1 - y/(2*math.Pi))
}

//像素X到经度
func (gps *GpsOffset) pixelToLng(pixelX float64, zoom int) float64 {
	tmp := 256 << zoom
	return pixelX*360/float64(tmp) - 180
}

//像素Y到纬度
func (gps *GpsOffset) pixelToLat(pixelY float64, zoom int) float64 {
	tmp := 128 << zoom
	y := 2 * math.Pi * (1 - pixelY/float64(tmp))
	// php -r 'echo M_E;' = 2.718281828459
	z := math.Pow(2.718281828459, y)
	sinY := (z - 1) / (z + 1)
	return math.Asin(sinY) * 180 / math.Pi
}
