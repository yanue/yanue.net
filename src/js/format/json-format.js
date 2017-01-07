define(function(require,exports){
    window.SINGLE_TAB = "  ";
    window.QuoteKeys = true;


    // 压缩
    exports.compress = function(code){
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
    // 格式化
    exports.init = function(){
        $(document).on('click', '#json-format-btn',function() {
            process();
        });
        // 全选
        $(document).on('click', '.SelectAllClicked',function() {
            SelectAllClicked();
        });

    };

    function $id(id){ return document.getElementById(id); }

    function IsArray(obj) {
        return  obj &&
            typeof obj === 'object' &&
            typeof obj.length === 'number' &&
            !(obj.propertyIsEnumerable('length'));
    }

    function process(){
        SetTab();
        var json = $('#code-area').val();
        window.IsCollapsible = false;
        var html = "";
        try{
            if(json == "") json = "\"\"";
            var obj = eval("["+json+"]");
            html = processObject(obj[0], 0, false, false, false);
            $id("Canvas").innerHTML = "<PRE class='CodeContainer'>"+html+"</PRE>";
        }catch(e){
            alert("JSON数据格式不正确:\n"+e.message);
            $id("Canvas").innerHTML = "";
        }
    }

    window._dateObj = new Date();
    window._regexpObj = new RegExp();
    function processObject(obj, indent, addComma, isArray, isPropertyContent){
        var html = "";
        var comma = (addComma) ? "<span class='Comma'>,</span> " : "";
        var type = typeof obj;
        var clpsHtml ="";
        if(IsArray(obj)){
            if(obj.length == 0){
                html += GetRow(indent, "<span class='ArrayBrace'>[ ]</span>"+comma, isPropertyContent);
            }else{
                clpsHtml = window.IsCollapsible ? "<span><i class=\"icon icon-collapse-alt\"></i></span><span class='collapsible'>" : "";
                html += GetRow(indent, "<span class='ArrayBrace'>[</span>"+clpsHtml, isPropertyContent);
                for(var i = 0; i < obj.length; i++){
                    html += processObject(obj[i], indent + 1, i < (obj.length - 1), true, false);
                }
                clpsHtml = window.IsCollapsible ? "</span>" : "";
                html += GetRow(indent, clpsHtml+"<span class='ArrayBrace'>]</span>"+comma);
            }
        }else if(type == 'object'){
            if (obj == null){
                html += FormatLiteral("null", "", comma, indent, isArray, "Null");
            }else if (obj.constructor == window._dateObj.constructor) {
                html += FormatLiteral("new Date(" + obj.getTime() + ") /*" + obj.toLocaleString()+"*/", "", comma, indent, isArray, "Date");
            }else if (obj.constructor == window._regexpObj.constructor) {
                html += FormatLiteral("new RegExp(" + obj + ")", "", comma, indent, isArray, "RegExp");
            }else{
                var numProps = 0;
                for(var prop in obj) numProps++;
                if(numProps == 0){
                    html += GetRow(indent, "<span class='ObjectBrace'>{ }</span>"+comma, isPropertyContent);
                }else{
                    clpsHtml = window.IsCollapsible ? "<span><i class=\"icon icon-collapse-alt\"></i></span><span class='collapsible'>" : "";
                    html += GetRow(indent, "<span class='ObjectBrace'>{</span>"+clpsHtml, isPropertyContent);

                    var j = 0;

                    for(var prop in obj){

                        var quote = window.QuoteKeys ? "\"" : "";

                        html += GetRow(indent + 1, "<span class='PropertyName'>"+quote+prop+quote+"</span>: "+processObject(obj[prop], indent + 1, ++j < numProps, false, true));

                    }

                    clpsHtml = window.IsCollapsible ? "</span>" : "";

                    html += GetRow(indent, clpsHtml+"<span class='ObjectBrace'>}</span>"+comma);

                }

            }

        }else if(type == 'number'){

            html += FormatLiteral(obj, "", comma, indent, isArray, "Number");

        }else if(type == 'boolean'){

            html += FormatLiteral(obj, "", comma, indent, isArray, "Boolean");

        }else if(type == 'function'){

            if (obj.constructor == window._regexpObj.constructor) {

                html += FormatLiteral("new RegExp(" + obj + ")", "", comma, indent, isArray, "RegExp");

            }else{

                obj = FormatFunction(indent, obj);

                html += FormatLiteral(obj, "", comma, indent, isArray, "Function");

            }

        }else if(type == 'undefined'){

            html += FormatLiteral("undefined", "", comma, indent, isArray, "Null");

        }else{

            html += FormatLiteral(obj.toString().split("\\").join("\\\\").split('"').join('\\"'), "\"", comma, indent, isArray, "String");

        }

        return html;

    }

    function FormatLiteral(literal, quote, comma, indent, isArray, style){

        if(typeof literal == 'string')

            literal = literal.split("<").join("&lt;").split(">").join("&gt;");

        var str = "<span class='"+style+"'>"+quote+literal+quote+comma+"</span>";

        if(isArray) str = GetRow(indent, str);

        return str;

    }

    function FormatFunction(indent, obj){

        var tabs = "";

        for(var i = 0; i < indent; i++) tabs += window.TAB;

        var funcStrArray = obj.toString().split("\n");

        var str = "";

        for(var i = 0; i < funcStrArray.length; i++){

            str += ((i==0)?"":tabs) + funcStrArray[i] + "\n";

        }

        return str;

    }

    function GetRow(indent, data, isPropertyContent){

        var tabs = "";

        for(var i = 0; i < indent && !isPropertyContent; i++) tabs += window.TAB;

        if(data != null && data.length > 0 && data.charAt(data.length-1) != "\n")

            data = data+"\n";

        return tabs+data;

    }

    function TraverseChildren(element, func, depth){

        for(var i = 0; i < element.childNodes.length; i++){

            TraverseChildren(element.childNodes[i], func, depth + 1);

        }

        func(element, depth);

    }



    function SetTab(){

        var select = $id("TabSize");

        window.TAB = MultiplyString(parseInt(select.options[select.selectedIndex].value), window.SINGLE_TAB);

    }

    function MultiplyString(num, str){

        var sb =[];

        for(var i = 0; i < num; i++){

            sb.push(str);

        }

        return sb.join("");

    }

    function SelectAllClicked(){

        if(!!document.selection && !!document.selection.empty) {

            document.selection.empty();

        } else if(window.getSelection) {

            var sel = window.getSelection();

            if(sel.removeAllRanges) {

                window.getSelection().removeAllRanges();

            }

        }



        var range =

            (!!document.body && !!document.body.createTextRange)

                ? document.body.createTextRange()

                : document.createRange();



        if(!!range.selectNode)

            range.selectNode($id("Canvas"));

        else if(range.moveToElementText)

            range.moveToElementText($id("Canvas"));



        if(!!range.select)

            range.select($id("Canvas"));

        else

            window.getSelection().addRange(range);

    }

});
