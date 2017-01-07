 var geocoder;
 var map;
 window.onload=function(){
	$("mapLoading").style.display='none';
	$("map_canvas").style.display='block';
  initialize();
}
function initialize() {//初始化
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(26.57, 106.72);
    var myOptions = {
      zoom: 8,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map($("map_canvas"), myOptions);
    
   var input = document.getElementById('address');
   new google.maps.places.Autocomplete(input);

      
    
    var latLngControl = new LatLngControl(map);
        
        // Register event listeners
        google.maps.event.addListener(map, 'mouseover', function(mEvent) {
          latLngControl.set('visible', true);
        });
        google.maps.event.addListener(map, 'mouseout', function(mEvent) {
          latLngControl.set('visible', false);
        });
        google.maps.event.addListener(map, 'mousemove', function(mEvent) {
          latLngControl.updatePosition(mEvent.latLng);
        });
    
 //按下enter键执行  
 enterPress();
}
function codeAddress(address) {//解析地址
  var address = address || $("address").value;
  if (/\s*^\-?\d+(\.\d+)?\s*\,\s*\-?\d+(\.\d+)?\s*$/.test(address)) {//如果是经纬度
    var latlng = parseLatLng(address);
    if (latlng == null) {
      $("address").value = "";
    } else {

    }
  } else {//解析地理位置
    geocoder.geocode({ 'address': address},geo);
  }
}
function parseLatLng(value) {//格式化经纬度
	value.replace('/\s//g');
	var coords = value.split(',');
	var lat = parseFloat(coords[0]);
	var lng = parseFloat(coords[1]);
	if (isNaN(lat) || isNaN(lng)) {
	  return null;
	} else {
	  return new google.maps.LatLng(lat, lng);
	}
}
function geo(results, status) {//进行地理解析
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      map.setZoom(15);
      var marker = new google.maps.Marker({
          map: map, 
          title: '当前经纬度：'+results[0].geometry.location+' 可点击拖动',
          position: results[0].geometry.location,
          draggable: true,
      });

      if (results[0].geometry.viewport) {

        var  boundsOverlay = new google.maps.Rectangle({
          'bounds': results[0].geometry.viewport,
          'strokeColor': '#ff0000',
          'strokeOpacity': 1.0,
          'strokeWeight': 2.0,
          'fillOpacity': 0.0
        });
        boundsOverlay.setMap(map);
      }

      $('latLngRange').innerHTML=results[0].geometry.viewport;
		   // Update current position info.
		  updateMarkerPosition(results[0].geometry.location);
      geocodePosition(results[0].geometry.location);
		  
		  // Add dragging event listeners.
		  google.maps.event.addListener(marker, 'dragstart', function() {
		    updateMarkerAddress('拖动...');
		  });
		  
		  google.maps.event.addListener(marker, 'drag', function() {
		    updateMarkerStatus('正在拖动...');
		    updateMarkerPosition(marker.getPosition());
		  });
		  
		  google.maps.event.addListener(marker, 'dragend', function() {
		    updateMarkerStatus('拖动结束');
		    geocodePosition(marker.getPosition());
		  });

    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }     
}
function enterPress(){//按下enter键
   $("address").onkeyup = function(e) {
  if (!e) var e = window.event;
  if (e.keyCode != 13) return;
  $("go").click();
 }
}
function geocodePosition(pos) {//改变经纬度时获取信息
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      updateMarkerAddress(responses[0].formatted_address);
    } else {
      updateMarkerAddress('地址不能正确解析');
    }
  });
}
function updateMarkerStatus(str) {//更新地标状态信息
  $('markerStatus').innerHTML = str;
}
function updateMarkerPosition(latLng) {//地标所在位置经纬度
  $('info').innerHTML = [
    latLng.lat(),
    latLng.lng()
  ].join(', ');

  $('lat').value=latLng.lat();//当前纬度
  $('lng').value=latLng.lng();//当前经度
}
function updateMarkerAddress(str) {//地标所在位置地址
	if(str=="Cannot determine address at this location."){
	  str="未能解析出当前位置地名";
	}
  $('endAddress').innerHTML = str;
}
function $(id){
  return document.getElementById(id);
} /**
       * LatLngControl class displays the LatLng and pixel coordinates
       * underneath the mouse within a container anchored to it.
       * @param {google.maps.Map} map Map to add custom control to.
       */
      function LatLngControl(map) {
        /**
         * Offset the control container from the mouse by this amount.
         */
        this.ANCHOR_OFFSET_ = new google.maps.Point(8, 8);
        
        /**
         * Pointer to the HTML container.
         */
        this.node_ = this.createHtmlNode_();
        
        // Add control to the map. Position is irrelevant.
        map.controls[google.maps.ControlPosition.TOP].push(this.node_);
        
        // Bind this OverlayView to the map so we can access MapCanvasProjection
        // to convert LatLng to Point coordinates.
        this.setMap(map);
        
        // Register an MVC property to indicate whether this custom control
        // is visible or hidden. Initially hide control until mouse is over map.
        this.set('visible', false);
      }
      
      // Extend OverlayView so we can access MapCanvasProjection.
      LatLngControl.prototype = new google.maps.OverlayView();
      LatLngControl.prototype.draw = function() {};
      
      /**
       * @private
       * Helper function creates the HTML node which is the control container.
       * @return {HTMLDivElement}
       */
      LatLngControl.prototype.createHtmlNode_ = function() {
        var divNode = document.createElement('div');
        divNode.id = 'latlng-control';
        divNode.index = 9999;
        return divNode;
      };
      
      /**
       * MVC property's state change handler function to show/hide the
       * control container.
       */
      LatLngControl.prototype.visible_changed = function() {
        this.node_.style.display = this.get('visible') ? '' : 'none';
      };
      
      /**
       * Specified LatLng value is used to calculate pixel coordinates and
       * update the control display. Container is also repositioned.
       * @param {google.maps.LatLng} latLng Position to display
       */
      LatLngControl.prototype.updatePosition = function(latLng) {
        var projection = this.getProjection();
        var point = projection.fromLatLngToContainerPixel(latLng);
        
        // Update control position to be anchored next to mouse position.
        this.node_.style.left = point.x + this.ANCHOR_OFFSET_.x + 'px';
        this.node_.style.top = point.y + this.ANCHOR_OFFSET_.y + 'px';
        
        // Update control to display latlng and coordinates.
        this.node_.innerHTML = [
          latLng.toUrlValue(4),
          '<br/>',
          point.x,
          'px, ',
          point.y,
          'px'
        ].join('');
      };
      
      /**
       * Called on the intiial pageload.
       */