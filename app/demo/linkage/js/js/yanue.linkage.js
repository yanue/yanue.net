/**
 * @author yanue
 */
function linkage(){
    this.selectCt();
    this.getCtId();
}
linkage.dqName = null;
linkage.CountyName = null;
linkage.prototype = {
    // 获取地区值，下级添加县级名称
    selectCt: function(dq){
        var _this = this;
        $("#selectDq").change(function(e){
        	//
        	  $("#txtCountyId").val(null);
            linkage.CountyName = null;
            var dqnum = parseInt($('select#selectDq option:selected').val());
            // 用数组下标选择每个地区下县市信息数组
            var dq = [null, 'GuiYang', 'BiJie', 'LiuPanShui', 'QianXi', 'AnShun', 'QianNan', 'QianDong', 'TongRen', 'ZunYi'];
            linkage.schollArray = window[dq[dqnum]] || [];
            linkage.dqName = dq[dqnum] || null;
            if (dqnum == 0) {
                $("#selectCt").hide();// 隐藏县级内容
            }
            //实际地区id：
            //var trueDqVal=[0,2,13,12,16,11,15,3,14,10];
            // 赋值地区id
						//$("#txtDiquId").val(trueDqVal[$(this).val()]);
						$("#txtDiquId").val($(this).val());//获取地区id赋值给表单
            _this.addCounty();
            e.stopImmediatePropagation();
        });
    },
    // 添加县
    addCounty: function(){
        $("#countyDiv").empty();// 移除原有数据
        $("#countyDiv").append('<span class="ts">选择县市：</span> <select name="selectCt" class="select" id="selectCt"><option value="0" selected="selected">不指定</option></select>'); // 为Select追加一个Option(下拉项)
        // 去除重复县 重点！！！！！！
        var countyArr = linkage.schollArray;
        var hash = {};
        for (var i = 0; i < countyArr.length; i++) {
            (hash[countyArr[i]] == undefined) &&
            (hash[countyArr[i]["cid"] + "," + countyArr[i]["county"]] = countyArr[i]["cid"] +
            "," +
            countyArr[i]["county"]);
        }
        for (var o in hash) {
            cid = o.split(',')[0];
            county = o.split(',')[1];
            $("#selectCt").append("<option value='" + cid + "'>" + county + "</option>"); // 为Select追加一个Option(下拉项)
        }
        //美化下拉框 调用jquery.chosen
        $("#selectCt").chosen();
        
    },
    //获取县市的id
    getCtId : function() {
		$("#selectCt").live("change",function(e) {
			  //获取县市id赋值给表单
        $("#txtCountyId").val($('select#selectCt option:selected').val());
				e.stopImmediatePropagation();
		});
	}
};
