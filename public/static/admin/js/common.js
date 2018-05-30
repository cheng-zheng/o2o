/**
 * Created by Administrator on 2017/7/25.
 */
/*页面 全屏-添加*/
function o2o_edit(title,url){
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}

/*添加或者编辑缩小的屏幕*/
function o2o_s_edit(title,url,w,h){
    layer_show(title,url,w,h);
}
/*-删除*/
function o2o_del(id,url){

    layer.confirm('确认要删除吗？',function(index){
        window.location.href=url;
    });
}
/**
 *
 */
$('.list-order input').blur(function(){
    // 编写我们的抛送逻辑
    // 获取主键id
    var id = $(this).attr('attr-id');
    // 获取value
    var val = $(this).val();
    var listOrder = {
        'id' : id,
        'listorder' : val
    };
    //alert(listOrder);
    var url = SCOPE.listorder_url;
    $.post(url, listOrder, function(result){
        if(result.code == 1){
            alert(result.msg);
            location.href = result.data;
        }else {
            alert(result.msg);
        }
    }, 'json');
});
/*城市相关二级内容*/
$('.cityId').change(function(){
    var city_id = $(this).val();
    var url = SCOPE.city_url;
    var postDate = {'id': city_id};
    $.post(url, postDate, function(result){
        if(result.status==1){
            var Data = result.data;
            var city_html='';
            for(var i=0; i<Data.length; i++){
                city_html += '<option value="'+Data[i].id+'">'+Data[i].name+'</option>';
            }
            $('.se_city_id').html(city_html);
        }else if(result.status==0){
            $('.se_city_id').html('');
        }
    },'json');
});
/*分类相关二级内容*/
$('.categoryId').change(function(){
    var categoryId_id = $(this).val();
    var url = SCOPE.category_url;
    var postDate = {'id': categoryId_id};
    $.post(url, postDate, function(result){
        if(result.status==1){
            var cData = result.data;
            var category_html='';
            $(cData).each(function(i){
                category_html += '<input name="se_category_id[]" type="checkbox" id="checkbox-moban" value="'+this.id+'"/>'+this.name+'<label for="checkbox-moban">&nbsp;</label>';
            });
            $('.se_category_id').html(category_html);
        }else if(result.status==0){
            $('.se_category_id').html('');
        }
    },'json');
});


