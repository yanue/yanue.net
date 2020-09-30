$(function () {
  function GpsToLatLng() {
    this.init();
  }

  GpsToLatLng.Gmap = null;
  GpsToLatLng.GMaker = null;
  GpsToLatLng.prototype = {
    //初始化
    'init': function () {
      this.gMapInit();
      this.bMapInit();
    },

    //google map初始化
    'gMapInit': function () {
      $(function () {
      });
    },

    //baidu map初始化
    'bMapInit': function () {
      $(function () {
        BaiMap = new BMap.Map("baiduMaps");
        var point = new BMap.Point(114.012, 22.537);
        BaiMap.centerAndZoom(point, 4);
        BaiMap.addControl(new BMap.NavigationControl());
        BaiMap.enableScrollWheelZoom();
        BaiMap.enableContinuousZoom();
        BaiMap.enableInertialDragging();
      });
    },

    // 获取input值进行查询
    'geoLatLng': function (val) {
      var _this = this;
      $("#getLatLng").live('click', function () {
        $("#status").css({'display': 'inline-block'});
        var gpsLat = $("#txtGPSLat").val();
        var gpsLng = $("#txtGPSLng").val();
        if (gpsLat != "" && gpsLng != "") {
          _this._ajaxGetGps(gpsLat, gpsLng);
        } else {
          alert("输入经纬度不能为空(单位°)!");
        }
      });
    },

    // ajax查询gps转换值
    '_ajaxGetGps': function (lat, lng) {
      $(".data-span").text('');
      var _this = this;
      $.getJSON("/api/map/gpsOffset", {lat: lat, lng: lng}, function (res) {
        console.log("data", res)
        $("#status").css({'display': 'none'});
        if (res.code == 0) {
          _this._setGmap(res.data.lat, res.data.lng);
        } else {
          alert("谷歌地图gps转换失败!");
        }
      });
      $.ajax({
        url: 'https://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=' + lng + '&y=' + lat + '&ak=CG8eakl6UTlEb1OakeWYvofh',
        type: "GET",
        dataType: "jsonp", //指定服务器返回的数据类型
        success: function (data) {
          $("#status").css({'display': 'none'});
          if (data.error == 0) {
            _this._setBmap(atob(data.y), atob(data.x));
          } else {
            alert("百度地图gps转换失败!");
          }
        }
      });
    },

    // 改变百度地图中心
    '_setBmap': function (lat, lng) {
      $("#spanBaiduLat").text(lat);
      $("#spanBaiduLng").text(lng);

      BaiMap.clearOverlays();
      var latlng = new BMap.Point(lng, lat);
      var marker = new BMap.Marker(latlng)
      marker.addEventListener("click", function () {
        var infoWindow = new BMap.InfoWindow('转换后:纬度' + lat + ', 经度[lng]:' + lng);
        marker.openInfoWindow(infoWindow);
      });
      BaiMap.addOverlay(marker);
      BaiMap.centerAndZoom(latlng, 17);
    },

    //设置谷歌地图中心
    '_setGmap': function (lat, lng) {
      var _this = this;
      if (lat != 0 && lng != 0) {
        $("#spanGoogleLat").text(lat);
        $("#spanGoogleLng").text(lng);
      }
    }
  }
  var gps = new GpsToLatLng();
  gps.geoLatLng();
});