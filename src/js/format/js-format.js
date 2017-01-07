define(function(require,exports){
    require('format/base');

    // 压缩(加密)
    exports.compress = function(code,encrypt){
        var packer = new Packer;
        if (encrypt) {
            var output = packer.pack(code, 1, 0);
        } else {
            var output = packer.pack(code, 0, 0);
        }
        return output;
    };

    // 解密（eval）
    exports.decrypt = function(code){

    };

    // 格式化
    exports.format =  function (code, indent_size, indent_character, max_char) {
        return js_beautify(code);
    };

});