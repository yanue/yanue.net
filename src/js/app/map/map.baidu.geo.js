define(function (require, exports) {

    /*
     * 函数名： geoServer()
     * 功能：地名解析成经纬度,经纬度解析成地名
     * 作者：yanue
     * 使用方法：
     //初始化
     var geo = new geoServer();
     // 解析地名 传入值地名 结果：纬度lat,经度lng
     geo.toLatLng(地名);
     // 解析经纬度 传入值（纬度lat,经度lng）：26.57, 106.72 结果是地名
     geo.toAddress(经纬度字符串);
     */
    function geoServer() {

        var latlng = new google.maps.LatLng(26.57, 106.72);

        //初始化地图
        geoServer.map = new google.maps.Map(document.getElementById("map_canvas"), {
            center: latlng,
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        //添加一个marker
        geoServer.marker = new google.maps.Marker({
            map: geoServer.map,
            position: latlng,
            draggable: true,
            title: '当前经纬度：' + latlng + ' 可点击拖动'
        });

        //实例化Geocoder服务
        geoServer.geocoder = new google.maps.Geocoder();

        this.init();
    }

    geoServer.address = null;//需要解析的地名
    geoServer.latLng = null;//需要解析的经纬度字符串
    geoServer.prototype = {
        'init': function () {
            var _this = this;

            // 执行解析
            $('.geoBtn').click(function (e) {
                var val = $('#address').val();
                _this.toLatLng(val);
                e.stopImmediatePropagation();
            });

            // 自动补全
            var input = document.getElementById('address');
            new google.maps.places.Autocomplete(input);

            // enter 触发
            $("#address").keyup(function (e) {
                if (!e) var e = window.event;
                if (e.keyCode != 13) return;
                $(".geoBtn").trigger('click');
            });

            // Register event listeners
            google.maps.event.addListener(geoServer.map, 'mouseover', function () {
                $('#overTip').show();

            });
            google.maps.event.addListener(geoServer.map, 'mouseout', function () {
                $('#overTip').hide();

            });
            google.maps.event.addListener(geoServer.map, 'mousemove', function (e) {
                _this.upShow(e);
            });

        },


        //解析地名
        toLatLng: function (address) {
            var _this = this;
            //接收
            geoServer.address = address;
            //执行geocode解析地名
            geoServer.geocoder.geocode({'address': geoServer.address }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    // 取第0个数据
                    var latlng = results[0].geometry.location;
                    geoServer.map.setCenter(latlng);
                    geoServer.map.setZoom(15);

                    // 改变标点信息
                    geoServer.marker.setPosition(latlng);
                    geoServer.marker.setOptions({'title': '当前经纬度：' + latlng + ' 可点击拖动'})

                    // 设置viewport
                    if (results[0].geometry.viewport) {
                        var boundsOverlay = new google.maps.Rectangle({
                            'bounds': results[0].geometry.viewport,
                            'strokeColor': '#ff0000',
                            'strokeOpacity': 1.0,
                            'strokeWeight': 2.0,
                            'fillOpacity': 0.0
                        });
                        boundsOverlay.setMap(geoServer.map);
                    }

                    $('#latLngRange').html(results[0].geometry.viewport.toString());

                    // Update current position info.
                    _this.updateMarkerPosition(results[0].geometry.location);
                    _this.geocodePosition(results[0].geometry.location);

                    // Add dragging event listeners.
                    google.maps.event.addListener(geoServer.marker, 'dragstart', function () {
                        _this.updateMarkerAddress('拖动...');
                    });

                    google.maps.event.addListener(geoServer.marker, 'drag', function () {
                        _this.updateMarkerStatus('正在拖动...');
                        _this.updateMarkerPosition(geoServer.marker.getPosition());
                    });

                    google.maps.event.addListener(geoServer.marker, 'dragend', function () {
                        _this.updateMarkerStatus('拖动结束');
                        _this.geocodePosition(geoServer.marker.getPosition());
                    });


                } else {
                    alert('解析错误：' + status)
                }
            });
        },

        geocodePosition: function (pos) {//改变经纬度时获取信息
            var _this = this;
            geoServer.geocoder.geocode({
                latLng: pos
            }, function (responses) {
                if (responses && responses.length > 0) {
                    _this.updateMarkerAddress(responses[0].formatted_address);
                } else {
                    _this.updateMarkerAddress('地址不能正确解析');
                }
            });
        },
        updateMarkerStatus: function (str) {//更新地标状态信息
            $('#markerStatus').html(str);
        },

        updateMarkerAddress: function (str) {//地标所在位置地址
            if (str == "Cannot determine address at this location.") {
                str = "未能解析出当前位置地名";
            }
            $('#endAddress').html(str);
        },

        updateMarkerPosition: function (latLng) {//地标所在位置经纬度
            $('#info').html([
                latLng.lat(),
                latLng.lng()
            ].join(', '));

            $('#lat').val(latLng.lat())//当前纬度
            $('#lng').val(latLng.lng());//当前经度
        },
        /**
         *
         * @param e
         */
        upShow: function (e) {
            var nx = e.pixel.x;
            var ny = e.pixel.y;
            $('#overTip').html(e.latLng.A + ',' + e.latLng.k + '<br >' + nx + 'px,' + ny + 'px')
                .css({'left': nx + 8, 'top': ny + 8});
        },
        //解析经纬度
        toAddress: function (latLng) {
            geoServer.latLng = latLng.toString();
            //去除中间所有空格，将中文'，'号替换成英文','并按','分割
            geoServer.latLng = geoServer.latLng.replace(/[(^\s+)(\s+$)]/g, "").replace('，', ',').split(',');
            //第一个值为纬度并转化为float类型
            var lat = parseFloat(geoServer.latLng[0]);
            //第二个值为经度并转化为float类型
            var lng = parseFloat(geoServer.latLng[1]);
            //执行geocode解析经纬度
            geoServer.geocoder.geocode({
                //传入经纬度
                'location': new google.maps.LatLng(lat, lng)
            }, function (showResults, status) {
                //create div to show result
                var newElement = window.document.createElement('p')
                if (status == google.maps.GeocoderStatus.OK) {
                    //获取解析后的经纬度
                    var location = showResults[0].geometry.location;
                    //切换地图位置
                    geoServer.map.setCenter(location);
                    geoServer.marker.setPosition(location);
                    //获取解析后的地址
                    var address = showResults[0].formatted_address;
                    // insert result innerHTML
                    newElement.innerHTML = geoServer.latLng + "：" + address;
                } else {
                    // insert error innerHTML
                    newElement.innerHTML = geoServer.latLng + "：error " + status;
                }
                //append child
                document.getElementById('showResults').appendChild(newElement);
            });
        }
    }

    exports.toLatLng = function () {
        window.onload = function () {
            new geoServer();
        };
    }

});