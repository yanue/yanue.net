$(function () {
  // 百度地图初始化
  var map = new BMap.Map("map_canvas");
  map.enableDragging();
  map.enableScrollWheelZoom();
  var point = new BMap.Point(114.057868, 22.543099);
  map.centerAndZoom(point, 12);
  var myGeo = new BMap.Geocoder();
  var markerClusterer = new BMapLib.MarkerClusterer(map, {markers: []});

  var result = []; // 最终解析结果
  var exportName = "";
  var n = 1;

  // 全屏切换
  $('#fullscreenBtn').on('click', function () {
    $('#inner').toggleClass('fullscreen');
    var text = $('#inner').hasClass('fullscreen') ? '退出全屏' : '全屏模式';
    $('#fullscreenBtn').text(text);
  });

  // 地址 → 经纬度
  $('#toLatLngBtn').on('click', function (e) {
    exportName = "通过地址解析经纬度-" + (n++);
    result = [["序号", "输入地址", "解析经度", "解析纬度", "返回信息"]];
    $('#showResults').html("").fadeIn();
    map.clearOverlays();
    markerClusterer.clearMarkers();

    var addrStr = $('#addr').val();
    var addrs = addrStr.split('\n').filter(function (line) {
      return line.trim() !== '';
    });

    var tasks = addrs.map(function (addr, i) {
      return {index: i + 1, value: addr};
    });

    runGeoQueue(tasks, geoSearch, function () {
      console.log("地址解析全部完成");
    }, 10);

    e.stopImmediatePropagation();
  });

  // 经纬度 → 地址
  $('#toAddressBtn').on('click', function (e) {
    exportName = "通过经纬度解析地址-" + (n++);
    result = [["序号", "输入经度", "输入纬度", "解析地址", "返回信息"]];
    $('#showResults').html("").fadeIn();
    map.clearOverlays();
    markerClusterer.clearMarkers();

    var latLngStr = $('#latLng').val();
    var pairs = latLngStr.split('\n').filter(function (line) {
      return line.trim() !== '';
    });

    var tasks = pairs.map(function (pair, i) {
      return {index: i + 1, value: pair};
    });

    runGeoQueue(tasks, geoParse, function () {
      console.log("经纬度解析全部完成");
    }, 10);

    //最简单的用法，生成一个marker数组，然后调用markerClusterer类即可。
    e.stopImmediatePropagation();
  });

  function geoSearch(i, addr) {
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
        $("#showResults").append(str);
        result[i] = [i, addr, point.lng, point.lat, JSON.stringify(point)]
      } else {
        var str = addr + '：解析失败 <br>';
        $('#showResults').append(str);
        result[i] = [i, addr, "", "", "解析失败"]
      }
    });
  }

  function geoParse(i, str) {
    str = str.toString();
    //去除中间所有空格，将中文'，'号替换成英文','并按','分割
    str = str.replace(/[(^\s+)(\s+$)]/g, "").replace('，', ',').split(',');
    //第一个值为纬度并转化为float类型
    var lat = parseFloat(str[1]);
    //第二个值为经度并转化为float类型
    var lng = parseFloat(str[0]);
    if (lat == 0 || lng == 0 || isNaN(lat) || isNaN(lng)) {
      result[i] = [i, str[1], str[0], "非经纬度"]
      return false;
    }
    var po = new BMap.Point(lng, lat);
    myGeo.getLocation(po, function (rs) {
      if (rs) {
        var str1 = lng + "," + lat + "：" + rs.address + '<br>';
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
        $('#showResults').append(str1);
        result[i] = [i, lng, lat, rs.address, JSON.stringify(rs)]
      } else {
        var str = lng + ',' + lat + ': 解析失败<br>';
        $('#showResults').append(str);
        result[i] = [i, lng, lat, "解析失败", ""]
      }
    });
  }

  $('#clearAddress').on('click', function () {
    $('#addr').val("");
  });
  $('#clearLatLng').on('click', function () {
    $('#latLng').val("");
  });
  $('#clearResult').on('click', function () {
    $('#showResults').html("等待解析");
  });
  $("#exportResult").on('click', function () {
    exportsCSV(result, exportName)
  })
});

/**
 * 限制并发执行任务的核心函数（最大并发数 limit）
 * @param tasks Array<{index, value}>
 * @param handler function(index, value, done)
 * @param doneCallback 全部完成回调
 * @param limit 并发数
 */
function runGeoQueue(tasks, handler, doneCallback, limit) {
  var queue = tasks.slice(0); // 任务克隆
  var running = 0;
  var max = limit || 10;
  var total = tasks.length;
  var completed = 0;

  function next() {
    while (running < max && queue.length > 0) {
      var t = queue.shift();
      running++;
      handler(t.index, t.value, function () {
        running--;
        completed++;
        if (completed >= total) {
          if (typeof doneCallback === 'function') doneCallback();
        } else {
          next();
        }
      });
    }
  }

  next();
}

/**
 * [escapeCSV 转义CSV内容]
 * @return {String}       [转义后的内容]
 * @param value
 */
function escapeCSV(value) {
  if (value == null) return '';
  var str = value.toString();
  // 如果包含逗号、双引号、换行符，则需要用双引号包围，并对内部 " 转义
  if (/["\n\r,]/.test(str)) {
    str = '"' + str.replace(/"/g, '""') + '"';
  }
  return str;
}

/**
 * [exportsCSV 导出数据到CSV]
 * @param  {Array}  [_body=[]]      [内容]
 * @param  {String} [name='excel'}] [文件名]
 * @return {[type]}                 [无]
 */
function exportsCSV(_body, name) {
  var output = _body.map(row => { // 格式化表内容
    // 先将每个单元格的内容进行转义
    return row.map(escapeCSV).join(','); // 使用分号分隔
  })
  console.log("output", output)
  if (!window.Blob) {
    alert("你的浏览器不支持!")
    return
  }
  // 创建一个文件CSV文件
  var BOM = '\uFEFF' // 中文乱码问题
  var blob = new Blob([BOM + output.join("\n")], {type: 'text/csv'})
  // IE
  if (navigator.msSaveOrOpenBlob) {
    // 解决大文件下载失败
    // 保存到本地文件
    navigator.msSaveOrOpenBlob(blob, `${name}.csv`)
  } else {
    var downloadLink = document.createElement('a')
    // downloadLink.href = uri
    downloadLink.setAttribute('href', URL.createObjectURL(blob)) // 因为url有最大长度限制，encodeURI是会把字符串转化为url，超出限制长度部分数据丢失导致下载失败,为此我采用创建Blob（二进制大对象）的方式来存放缓存数据，具体代码如下：
    downloadLink.download = `${name}.csv`
    document.body.appendChild(downloadLink)
    downloadLink.click()
    document.body.removeChild(downloadLink)
  }
}
