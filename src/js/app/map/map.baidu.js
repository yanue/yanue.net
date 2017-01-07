define(function (require, exports) {
    exports.geocode_map = function () {
        $(function () {
            // 百度地图API功能
            var map = new BMap.Map("allmap");
            map.enableDragging();
            map.enableScrollWheelZoom();
            var point = new BMap.Point(114.05786799999998, 22.543099);
            map.centerAndZoom(point, 12);

            var myGeo = new BMap.Geocoder();

            var markerClusterer = new BMapLib.MarkerClusterer(map, {markers: []});


            $('#toLatLntBtn').live('click', function (e) {
                $('#result').html("").fadeIn();
                map.clearOverlays();
                markerClusterer.clearMarkers();
                var addrStr = $('#addrs').val();
                var addrs = addrStr.split('\n');
                for (var i in addrs) {
                    var addr = addrs[i];
                    geoSearch(addr);
                }
                e.stopImmediatePropagation();
            });

            $('#toAddrBtn').live('click', function (e) {
                $('#result').html("").fadeIn();
                map.clearOverlays();
                markerClusterer.clearMarkers();
                makers = [];
                var addrStr = $('#latlng').val();
                var addrs = addrStr.split('\n');
                for (var i in addrs) {
                    var addr = addrs[i];
                    geoParse(addr);
                }

                //最简单的用法，生成一个marker数组，然后调用markerClusterer类即可。
                e.stopImmediatePropagation();
            });

            function geoSearch(addr) {
                myGeo.getPoint(addr, function (point) {
                    if (point) {
                        var str = addr + ":" + point.lng + "," + point.lat + "<br>";
                        var po = new BMap.Point(point.lng, point.lat);
                        map.centerAndZoom(po, 12);

                        var _marker = new BMap.Marker(po);

                        _marker.addEventListener("click", function (e) {
                            this.openInfoWindow(new BMap.InfoWindow(str));
                        });

                        _marker.addEventListener("mouseover", function (e) {
                            this.setTitle("位于: " + point.lng + "," + point.lat);
                        });

                        markerClusterer.addMarker(_marker);
                        map.addOverlay(_marker);              // 将标注添加到地图中
                        $("#result").append(str);
                    }
                });
            }


            function geoParse(str) {
                str = str.toString();
                //去除中间所有空格，将中文'，'号替换成英文','并按','分割
                str = str.replace(/[(^\s+)(\s+$)]/g, "").replace('，', ',').split(',');
                //第一个值为纬度并转化为float类型
                var lat = parseFloat(str[1]);
                //第二个值为经度并转化为float类型
                var lng = parseFloat(str[0]);
                if (lat == 0 || lng == 0 || isNaN(lat) || isNaN(lng)) return false;
                var po = new BMap.Point(lng, lat);
                console.log(po);
                myGeo.getLocation(po, function (rs) {
                    if (rs) {
                        var str = lng + "," + lat + "：" + rs.address + '<br>';
                        var po = new BMap.Point(lng, lat);
                        var _marker = new BMap.Marker(po);

                        _marker.addEventListener("click", function (e) {
                            this.openInfoWindow(new BMap.InfoWindow(str));
                        });

                        _marker.addEventListener("mouseover", function (e) {
                            this.setTitle("位于: " + point.lng + "," + point.lat);
                        });

                        markerClusterer.addMarker(_marker);
                        map.centerAndZoom(po, 12);
                        map.addOverlay(_marker);              // 将标注添加到地图中

                        $('#result').append(str);
                    }
                });
            }


            $('.clear').live('click', function () {
                $('#result').html("");
            });
            $('.hide').live('click', function () {
                $('#result').fadeOut();
            });
            $('.expand').live('click', function () {
                $('#result').fadeIn();
            });
        });
    }
});