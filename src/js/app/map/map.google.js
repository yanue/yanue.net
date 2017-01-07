define(function (require, exports) {
    exports.geocode = function () {
        $(function () {
            $('#toLatLntBtn').live('click', function (e) {
                $('#result').html("").fadeIn();
                var addrStr = $('#addrs').val();
                var addrs = addrStr.split('\n');
                for (var i in addrs) {
                    var addr = addrs[i].trim();
                    if (addr) {
                        geoToLatlng(addr);
                    }
                }
                e.stopImmediatePropagation();
            });

            $('#toAddrBtn').live('click', function (e) {
                $('#result').html("").fadeIn();
                makers = [];
                var addrStr = $('#latlng').val();
                var addrs = addrStr.split('\n');
                for (var i in addrs) {
                    var addr = addrs[i].trim();
                    if (addr) {
                        geoToAddr(addr);
                    }
                }

                //最简单的用法，生成一个marker数组，然后调用markerClusterer类即可。
                e.stopImmediatePropagation();
            });

            function geoToLatlng(addr) {
                $.ajax({
                    method: 'post',
                    url: 'http://maps.googleapis.com/maps/api/geocode/json?',
                    data: {address: addr, sensor: false, language: 'zh_cn'},
                    success: function (res) {
                        console.log(res)
                        var output = '';
                        if (res.status == "OK") {
                            output = '<li>' + addr + ':' + res.results[0].geometry.location.lat + ',' + res.results[0].geometry.location.lng + '</li>';
                        } else {
                            output = '<li>' + addr + ':' + res.status + '</li>';
                        }
                        console.log(output)
                        $('#result').append(output);
                    }
                });
            }


            function geoToAddr(str) {
                $.ajax({
                    method: 'post',
                    url: 'http://maps.googleapis.com/maps/api/geocode/json?',
                    data: {latlng: str, sensor: false, language: 'zh_cn'},
                    success: function (res) {
                        var output = '';
                        if (res.status == "OK") {
                            output = '<li>' + str + ':' + res.results[0].formatted_address + '</li>';
                        } else {
                            output = '<li>' + str + ':' + res.status + '</li>';
                        }
                        $('#result').append(output);
                    }
                });
            }

            $('.clear').live('click', function () {
                $('#result').html("");
            });

            $('.expand').live('click', function () {
                $('#result').fadeIn();
            });
        });

    }
});