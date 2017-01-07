define(function(require,exports){

    // 横排
    exports.toHorizontal = function (code){
        code = code.replace(/\r\n/ig,'');
        code = code.replace(/(\s){2,}/ig,'$1');
        code = code.replace(/\t/ig,'');
        code = code.replace(/\n\}/ig,'\}');
        code = code.replace(/\n\{\s*/ig,'\{');
        code = code.replace(/(\S)\s*\}/ig,'$1\}');
        code = code.replace(/(\S)\s*\{/ig,'$1\{');
        code = code.replace(/\{\s*(\S)/ig,'\{$1');
        return code;
    };

    // 竖排
    exports.toVertical = function (code){
        code = code.replace(/(\s){2,}/ig,'$1');
        code = code.replace(/(\S)\s*\{/ig,'$1 {');
        code = code.replace(/\*\/(.[^\}\{]*)}/ig,'\*\/\n$1}');
        code = code.replace(/\/\*/ig,'\n\/\*');
        code = code.replace(/;\s*(\S)/ig,';\n\t$1');
        code = code.replace(/\}\s*(\S)/ig,'\}\n$1');
        code = code.replace(/\n\s*\}/ig,'\n\}');
        code = code.replace(/\{\s*(\S)/ig,'\{\n\t$1');
        code = code.replace(/(\S)\s*\*\//ig,'$1\*\/');
        code = code.replace(/\*\/\s*([^\}\{]\S)/ig,'\*\/\n\t$1');
        code = code.replace(/(\S)\}/ig,'$1\n\}');
        code = code.replace(/(\n){2,}/ig,'\n');
        code = code.replace(/:/ig,':');
        code = code.replace(/  /ig,' ');
        return code;
    };

    // 格式化
    exports.format = function(code){
        code = code.replace(/\s*([\{\}\:\;\,])\s*/g, "$1");
        code = code.replace(/;\s*;/g, ";"); //清除连续分号
        code = code.replace(/\,[\s\.\#\d]*{/g, "{");
        code = code.replace(/([^\s])\{([^\s])/g, "$1 {\n\t$2");
        code = code.replace(/([^\s])\}([^\n]*)/g, "$1\n}\n$2");
        code = code.replace(/([^\s]);([^\s\}])/g, "$1;\n\t$2");
        return code;
    };

    //压缩代码
    exports.pack =function (code) {
        //去除注释
        code = code.replace(/\/\*((.|\n|\t)*?)\*\//g,"");
        //除去首尾空格
        code = code.replace(/(\s)*{\s*/g,"{").replace(/(\s)*}\s*/g,"}");
        //去除样式间空格
        code  = code.replace(/(\s)*;\s*/g,";");
        //去除样式名称后面空格
        code  = code.replace(/:(\s)*/g,":");
        //去除换行符
        code  = code.replace(/(\n|\t)+/g,"");
        //去除末尾分号
        code  = code.replace(/;}/g,"}");
        //IE6下css-letter留空的问题
        if(/first\-letter{/g.test(code)){
            code  = code.replace(/first\-letter{/g,"first-letter {");
        }
        return code;
    };
});