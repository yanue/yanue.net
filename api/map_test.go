package api

import (
	"fmt"
	"testing"
)

func TestNewGpsOffset(t *testing.T) {
	gpsOffset.loadDat("../assets/google_offset.dat")
	tmpLng := 114.066112 // 经度
	tmpLat := 22.548515
	a, b := gpsOffset.geoLatLng(tmpLat, tmpLng)
	fmt.Println("a,b", a, b)
}
