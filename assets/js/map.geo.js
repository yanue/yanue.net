$(function () {
  var map = new BMap.Map("map_canvas");
  map.enableDragging();
  map.enableScrollWheelZoom();
  var point = new BMap.Point(114.057868, 22.543099);
  map.centerAndZoom(point, 12);
  var myGeo = new BMap.Geocoder();
  var markerClusterer = new BMapLib.MarkerClusterer(map, {markers: []});
  var result = [];
  var exportName = "";
  var n = 1;
  $('#fullscreenBtn').on('click', function () {
    $('#inner').toggleClass('fullscreen');
    var text = $('#inner').hasClass('fullscreen') ? '退出全屏' : '全屏模式';
    $('#fullscreenBtn').text(text);
  });
  $('#toLatLngBtn').on('click', function (e) {
    exportName = "通过地址解析经纬度-" + (n++);
    result = [["序号", "输入地址", "解析经度", "解析纬度", "返回信息"]];
    $('#showResults').html("").fadeIn();
    map.clearOverlays();
    markerClusterer.clearMarkers();
    var addrs = $('#addr').val().split('\n').filter(line => line.trim() !== '');
    var tasks = addrs.map((addr, i) => ({index: i + 1, value: addr}));
    $("#status").html("开始解析...");
    runGeoQueue(tasks, geoSearch, function () {
      console.log("地址解析全部完成");
      $("#status").html("解析完成");
    }, 10);
    e.stopImmediatePropagation();
  });
  $('#toAddressBtn').on('click', function (e) {
    exportName = "通过经纬度解析地址-" + (n++);
    result = [["序号", "输入经度", "输入纬度", "解析地址", "返回信息"]];
    $('#showResults').html("").fadeIn();
    map.clearOverlays();
    markerClusterer.clearMarkers();
    var pairs = $('#latLng').val().split('\n').filter(line => line.trim() !== '');
    var tasks = pairs.map((pair, i) => ({index: i + 1, value: pair}));
    $("#status").html("开始解析...");
    runGeoQueue(tasks, geoParse, function () {
      console.log("经纬度解析全部完成");
      $("#status").html("解析完成");
    }, 10);
    e.stopImmediatePropagation();
  });

  function geoSearch(i, addr, done) {
    myGeo.getPoint(addr, function (point) {
      let str = '';
      if (point) {
        str = addr + ":" + point.lng + "," + point.lat + "<br>";
        const po = new BMap.Point(point.lng, point.lat);
        map.centerAndZoom(po, 12);
        const _marker = new BMap.Marker(po);
        _marker.addEventListener("click", function () {
          this.openInfoWindow(new BMap.InfoWindow(str));
        });
        _marker.addEventListener("mouseover", function () {
          this.setTitle("位于: " + point.lng + "," + point.lat);
        });
        markerClusterer.addMarker(_marker);
        map.addOverlay(_marker);
        result[i] = [i, addr, point.lng, point.lat, JSON.stringify(point)];
      } else {
        str = addr + '：解析失败 <br>';
        result[i] = [i, addr, "", "", "解析失败"];
      }
      $('#showResults').append(str);
      done(); // 回调通知任务完成
    });
  }

  function geoParse(i, str, done) {
    str = str.toString().replace(/\s+/g, "").replace('，', ',').split(',');
    const lng = parseFloat(str[0]);
    const lat = parseFloat(str[1]);
    if (isNaN(lng) || isNaN(lat) || lng === 0 || lat === 0) {
      const failText = str.join(',') + ': 解析失败<br>';
      $('#showResults').append(failText);
      result[i] = [i, lng || "", lat || "", "非经纬度", ""];
      done();
      return;
    }
    const po = new BMap.Point(lng, lat);
    myGeo.getLocation(po, function (rs) {
      let text = '';
      if (rs) {
        text = lng + "," + lat + "：" + rs.address + '<br>';
        const _marker = new BMap.Marker(po);
        _marker.addEventListener("click", function () {
          this.openInfoWindow(new BMap.InfoWindow(text));
        });
        _marker.addEventListener("mouseover", function () {
          this.setTitle("位于: " + lng + "," + lat);
        });
        markerClusterer.addMarker(_marker);
        map.centerAndZoom(po, 12);
        map.addOverlay(_marker);
        result[i] = [i, lng, lat, rs.address, JSON.stringify(rs)];
      } else {
        text = lng + ',' + lat + ': 解析失败<br>';
        result[i] = [i, lng, lat, "解析失败", ""];
      }
      $('#showResults').append(text);
      done(); // 通知任务完成
    });
  }

  $('#clearAddress').on('click', () => $('#addr').val(""));
  $('#clearLatLng').on('click', () => $('#latLng').val(""));
  $('#clearResult').on('click', () => $('#showResults').html("等待解析"));
  $("#exportResult").on('click', () => exportsCSV(result, exportName));
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
