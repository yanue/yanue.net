define(function(require, exports) {
    var site_url = app.site_url;
    exports.uploadImg = function(cbFn, imgIds, otherData) {
        var defaultIds = {
            'drag_drop_id': 'dragdrop', //拖拽容器id
            'upload_btn_id': 'pickfiles', //上传按钮id
            'file_list': 'filelist', //上传队列列表容器id
            'container_id': 'container' //...
        };
        var defaultOther = {
            'url': site_url+'api/upload/img',
            'file_size': '4mb', //文件大小
            'file_ext': 'jpg,gif,png' //文件类型
        };

        imgIds = $.extend(defaultIds, imgIds);
        otherData = $.extend(defaultOther, otherData);

        require('tools/plupload/plupload.min'); //导入图片上传插件

        var fileNums = 1;
        var uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4', // 何种上传类型的优先调用顺序
            dragdrop: true, //允许拖拽，仅HTML5支持
            drop_element: imgIds.drag_drop_id, // 拖拽容器id
            browse_button: imgIds.upload_btn_id, // 上传按钮id
            container: document.getElementById(imgIds.container_id), // ... or DOM Element itself
            url: otherData.url, // 请求url
            file_data_name: 'filedata',
            flash_swf_url: 'plupload/Moxie.swf', // flash上传必要文件
            filters: {
                max_file_size: otherData.file_size,
                mime_types:[
                    {title: 'Image files', extensions : otherData.file_ext}
                ]
            }, // 文件大小，文件类型
            init: {
                // 清空文件队列列表
                PostInit: function() {
                    $('#'+imgIds.file_list).html('');
                },
                // 添加文件后触发
                FilesAdded: function(up, files) {
                    if (files.length <= fileNums) {
                        plupload.each(files, function(file) {
                            $('#'+imgIds.file_list).html('<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>');
                        });
                    } else {
                        alert('一次只允许上传'+fileNums+'个文件');
                    }
                    files.length = 0;
                },
                // 百分比进度条
                UploadProgress: function(up, file) {
                    $('#'+file.id+' b:first').html('<span>' + file.percent + '%</span>');
                },
                // 上传成功后触发
                FileUploaded: function(up, file, obj) {
                    var rs = $.parseJSON(obj.response); //PHP上传成功后返回的参数
                    cbFn(rs);
                },
                // 队列有改变时触发
                QueueChanged: function(up) {
                    if (up.files.length <= fileNums) {
                        //上传文件
                        $('#upload-start').click(function() {
                            uploader.start(); // 上传
                            up.files.length = 0;
                        });
                    }
                },
                // 发生错误时触发
                Error: function(up, err) {
                    alert(err.message);
                }
            }
        });
        uploader.init();
    }

    exports.uploadVideo = function(cbFn, imgIds, otherData) {
        var defaultIds = {
            'drag_drop_id': 'dragdropvideo', //拖拽容器id
            'upload_btn_id': 'pickvideo', //上传按钮id
            'file_list': 'videolist', //上传队列列表容器id
            'container_id': 'videoContainer' //...
        };
        var defaultOther = {
            'url': site_url+'api/upload/video',
            'file_size': '200mb', //文件大小
            'file_ext': '3gp,mp4,mpeg.ogg,webm,' //文件类型
        };

        imgIds = $.extend(defaultIds, imgIds);
        otherData = $.extend(defaultOther, otherData);

        require('tools/plupload/plupload.min'); //导入图片上传插件

        var fileNums = 1;
        var uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4', // 何种上传类型的优先调用顺序
            dragdrop: false, //允许拖拽，仅HTML5支持
            drop_element: imgIds.drag_drop_id, // 拖拽容器id
            browse_button: imgIds.upload_btn_id, // 上传按钮id
            container: document.getElementById(imgIds.container_id), // ... or DOM Element itself
            url: otherData.url, // 请求url
            file_data_name: 'filedata',
            flash_swf_url: 'plupload/Moxie.swf', // flash上传必要文件
            filters: {
                max_file_size: otherData.file_size,
                mime_types:[
                    {title: 'Image files', extensions : otherData.file_ext}
                ]
            }, // 文件大小，文件类型
            init: {
                // 清空文件队列列表
                PostInit: function() {
                    $('#'+imgIds.file_list).html('');
                },
                // 添加文件后触发
                FilesAdded: function(up, files) {
                    if (files.length <= fileNums) {
                        plupload.each(files, function(file) {
                            $('#'+imgIds.file_list).html('<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>');
                        });
                    } else {
                        alert('一次只允许上传'+fileNums+'个文件');
                    }
                    files.length = 0;
                },
                // 百分比进度条
                UploadProgress: function(up, file) {
                    $('#'+file.id+' b:first').html('<span>' + file.percent + '%</span>');
                },
                // 上传成功后触发
                FileUploaded: function(up, file, obj) {
                    var rs = $.parseJSON(obj.response); //PHP上传成功后返回的参数
                    cbFn(rs);
                },
                // 队列有改变时触发
                QueueChanged: function(up) {
                    if (up.files.length <= fileNums) {
                        //上传文件
                        $('#upload-video').click(function() {
                            uploader.start(); // 上传
                            console.log('video')
                            up.files.length = 0;
                        });
                    }
                },
                // 发生错误时触发
                Error: function(up, err) {
                    alert(err.message);
                }
            }
        });
        uploader.init();
    }

});