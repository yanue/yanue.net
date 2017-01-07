define(function (require, exports) {
    var site_url = app.site_url;
    require('tools/xheditor/xheditor-1.2.1.min.js');

    exports.init = function (elem) {
        var allPlugin = {
            Code: {c: 'btnCode', t: '插入代码', h: 1, e: function () {
                var _this = this;
                var htmlCode = '<div><select id="xheCodeType"><option value="php">PHP</option><option value="html">HTML/XML</option><option value="js">Javascript</option><option value="css">CSS</option><option value="java">Java</option><option value="py">Python</option><option value="pl">Perl</option><option value="rb">Ruby</option><option value="cs">C#</option><option value="c">C++/C</option><option value="vb">VB/ASP</option><option value="">其它</option></select></div><div><textarea id="xheCodeValue" wrap="soft" spellcheck="false" style="width:300px;height:100px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';
                var jCode = $(htmlCode), jType = $('#xheCodeType', jCode), jValue = $('#xheCodeValue', jCode), jSave = $('#xheSave', jCode);
                jSave.click(function () {
                    _this.loadBookmark();
                    _this.pasteHTML('<pre class="prettyprint linenums prettyprinted lang-' + jType.val() + ' ">' + _this.domEncode(jValue.val()) + '</pre>');
                    _this.hidePanel();
                    return false;
                });
                _this.saveBookmark();
                _this.showDialog(jCode);
            }}
        };
        $(elem).ready(function () {
            $(elem).xheditor({
                plugins: allPlugin,
                tools: "full",
                remoteImgSaveUrl: site_url + '/api/upload/saveimg?from=xheditor',
                upLinkUrl: site_url + '/api/upload/file?from=xheditor',
                upLinkExt: "zip,rar,txt,doc,xls,ppt,docx,pptx,xlsx",
                upImgUrl: site_url + '/api/upload/img?from=xheditor',
                upImgExt: "jpg,jpeg,gif,png",
                upMediaUrl: site_url + '/api/upload/media?from=xheditor',
                upMediaExt: "mp4,3gp,ogg,webm"
            });
        });
    }
});