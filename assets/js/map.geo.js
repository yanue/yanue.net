$(function () {
  var map = new BMapGL.Map("map_canvas");
  map.enableDragging();
  map.enableScrollWheelZoom();
  var point = new BMapGL.Point(114.057868, 22.543099);
  map.centerAndZoom(point, 10);
  var myGeo = new BMapGL.Geocoder();
  var result = [];
  var exportName = "";
  var n = 1;

// 全屏切换按钮事件
  $('#fullscreenBtn').on('click', function () {
    // 切换容器的 fullscreen 样式
    $('#inner').toggleClass('fullscreen');
    // 修改按钮文字
    var text = $('#inner').hasClass('fullscreen') ? '退出全屏' : '全屏模式';
    $('#fullscreenBtn').text(text);
    // ⚡ 保留切换前的中心
    var center = map.getCenter();
    var zoom = map.getZoom();
    // ⚡ 通知地图容器大小已变化
    setTimeout(function () {
      map.checkResize(); // 必须，否则大小变化不生效
      map.centerAndZoom(center, zoom); // 保持原中心和缩放
    }, 200); // 设置一点延时，确保 DOM 已经完成 resize
  });

  // 全局运行状态标志，防止重复点击
  let isRunning = false;

  $('#toLatLngBtn').on('click', function (e) {
    e.stopImmediatePropagation();

    // ------------------- 并发控制逻辑 -------------------
    if (isRunning) {
      alert("任务正在执行中，请稍候再试...");
      return;
    }
    isRunning = true; // 标记任务开始

    exportName = "通过地址解析经纬度-" + (n++);
    result = [["序号", "输入地址", "解析经度", "解析纬度", "返回信息"]];
    $('#showResults').html("").fadeIn();
    map.clearOverlays();

    var addrs = $('#addr').val().split('\n').filter(line => line.trim() !== '');
    var tasks = addrs.map((addr, i) => ({index: i + 1, value: addr}));

    $("#status").html("开始解析...");

    // runGeoQueue(tasks, workerFn, callback, 并行数)
    runGeoQueue(tasks, geoSearch, function () {
      console.log("地址解析全部完成");
      $("#status").html("解析完成");

      // ------------------- 任务结束，释放锁 -------------------
      isRunning = false;
    }, 2);
  });

  $('#toAddressBtn').on('click', function (e) {
    e.stopImmediatePropagation();

    // ------------------- 并发控制逻辑 -------------------
    if (isRunning) {
      alert("任务正在执行中，请稍候再试...");
      return;
    }
    isRunning = true; // 标记任务开始

    exportName = "通过经纬度解析地址-" + (n++);
    result = [["序号", "输入经度", "输入纬度", "解析地址", "返回信息"]];
    $('#showResults').html("").fadeIn();
    map.clearOverlays();

    var pairs = $('#latLng').val().split('\n').filter(line => line.trim() !== '');
    var tasks = pairs.map((pair, i) => ({index: i + 1, value: pair}));

    $("#status").html("开始解析...");

    runGeoQueue(tasks, geoParse, function () {
      console.log("经纬度解析全部完成");
      $("#status").html("解析完成");

      // ------------------- 任务结束，释放锁 -------------------
      isRunning = false;
    }, 2);
  });

// 创建标注并支持点击后居中
  function addMarker(lng, lat, text) {
    // 创建坐标点
    var point = new BMapGL.Point(lng, lat);

    // 创建 Marker
    var marker = new BMapGL.Marker(point);

    // 创建文本标签
    var label = new BMapGL.Label(text, {});
    marker.setLabel(label);

    // 点击 Marker 时，把它移到地图中心
    marker.addEventListener("click", function () {
      // 将这个点设置为中心，缩放级别保持一致
      map.centerAndZoom(point, 10);
    });

    // 添加到地图
    map.addOverlay(marker);
    // 设置地图中心
    map.centerAndZoom(point, 10);
  }

  /**
   * 对地址进行地理编码搜索，并增加超时保护
   * @param {Number} i   序号
   * @param {String} addr 要解析的地址
   * @param {Function} done 任务完成回调
   * @param {Number} timeoutMs 超时时间（毫秒），默认5秒
   */
  function geoSearch(i, addr, done, timeoutMs = 5000) {
    let timeoutId;
    let finished = false; // 防止多次回调

    // 定义完成函数，保证只执行一次
    function finish(str, data) {
      if (finished) return; // 只允许调用一次
      finished = true;
      clearTimeout(timeoutId);

      $('#showResults').append(str);
      result[i] = data;
      done(); // 通知外部任务完成
    }

    // 设置超时处理
    timeoutId = setTimeout(() => {
      const str = addr + '：解析超时 <br>';
      finish(str, [i, addr, "", "", "解析超时"]);
    }, timeoutMs);

    // 调用异步地理编码服务
    myGeo.getPoint(addr, function (point) {
      if (point) {
        const str = addr + ":" + point.lng + "," + point.lat + "<br>";
        addMarker(point.lng, point.lat, i + ":" + str);
        finish(str, [i, addr, point.lng, point.lat, JSON.stringify(point)]);
      } else {
        const str = addr + '：解析失败 <br>';
        finish(str, [i, addr, "", "", "解析失败"]);
      }
    });
  }

  /**
   * 对经纬度进行逆地理解析（坐标 -> 地址），带超时处理
   * @param {Number} i   序号
   * @param {String} str 输入的经纬度字符串 "lng,lat"
   * @param {Function} done 回调通知任务完成
   * @param {Number} timeoutMs 超时时间，默认5秒
   */
  function geoParse(i, str, done, timeoutMs = 5000) {
    str = str.toString().replace(/\s+/g, "").replace('，', ',').split(',');
    const lng = parseFloat(str[0]);
    const lat = parseFloat(str[1]);

    // 校验输入是否为有效经纬度
    if (isNaN(lng) || isNaN(lat) || lng === 0 || lat === 0) {
      const failText = str.join(',') + ': 解析失败<br>';
      $('#showResults').append(failText);
      result[i] = [i, lng || "", lat || "", "非经纬度", ""];
      done();
      return;
    }

    let finished = false;
    let timeoutId;

    // 封装统一的结束逻辑
    function finish(text, data) {
      if (finished) return;  // 防止多次调用
      finished = true;
      clearTimeout(timeoutId); // 清理超时定时器
      $('#showResults').append(text + '<br>');
      result[i] = data;
      done(); // 通知外部任务完成
    }

    // 设置超时保护
    timeoutId = setTimeout(() => {
      const text = lng + ',' + lat + ': 解析超时';
      finish(text, [i, lng, lat, "解析超时", ""]);
    }, timeoutMs);

    // 发起逆地理解析
    const po = new BMapGL.Point(lng, lat);
    myGeo.getLocation(po, function (rs) {
      if (rs) {
        const text = lng + "," + lat + "：" + rs.address;
        addMarker(lng, lat, i + ":" + text);
        finish(text, [i, lng, lat, rs.address, JSON.stringify(rs)]);
      } else {
        const text = lng + ',' + lat + ': 解析失败';
        finish(text, [i, lng, lat, "解析失败", ""]);
      }
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
      console.log("开始任务", t.index, t.value);
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
