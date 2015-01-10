jQuery(function($) {
$(document).ready(function(e) {
   var smxt=$('#sms'),xty=$('#yykz'),smzd=$('#smzd'),yyzd=$('#yyzd'),xtinfo=$('.info .xy'),zdinfo=$('.info .zdy'),install=$('.cst_install'),uninstall=$('.cst_uninstall');
   smxt.on('click',function(){
	    xtinfo.html('<img src="'+ wct_loading +'" />');
	   	$.ajax( {
		url: ajaxurl,
		data:{
			action : 'cst',
			scan : 'system'
		},
		type:'post',
		cache:false,
		success:function(data) {
			xtinfo.html(data.snum);
		},
		error : function() {
			alert("扫描出错或系统异常，请刷新页面再试！");
		}
		});
	   });
	smzd.on('click',function(){
	    zdinfo.html('<img src="'+ wct_loading +'" />');
	   	$.ajax( {
		url: ajaxurl,
		data:{
			action : 'cst',
			scan : 'user'
		},
		type:'post',
		cache:false,
		success:function(data) {
			zdinfo.html(data.snum);
		},
		error : function() {
			alert("扫描出错或系统异常，请刷新页面再试！");
		}
		});
	   });
	xty.on('click',function(){
	   	$.ajax( {
		url: ajaxurl,
		data:{
			action : 'cst',
			inc : 'system'
		},
		type:'post',
		cache:false,
		success:function(data) {
			if( data.inc == 200){
				alert('生成系统引用成功');
			}else alert('生成系统引用失败或无系统扩展，请刷新再试！');
		},
		error : function() {
			alert("生成系统引用失败或系统异常，请刷新页面再试！");
		}
		});
	   });
	yyzd.on('click',function(){
	   	$.ajax( {
		url: ajaxurl,
		data:{
			action : 'cst',
			inc : 'user'
		},
		type:'post',
		cache:false,
		success:function(data) {
			if( data.inc == 200){
				alert('生成自定义引用成功');
			}else alert('生成自定义引用失败或无自定义扩展，请刷新再试！');
		},
		error : function() {
			alert("生成自定义引用失败或系统异常，请刷新页面再试！");
		}
		});
	   });
	   install.on('click',function(){
		$.ajax( {
		url: ajaxurl,
		data:{
			action : 'cst_install',
			todo : $(this).attr('data')
		},
		type:'post',
		cache:false,
		success:function(data) {
			if( data.install == 200 ){
				alert('扩展安装成功。');
			}else alert('安装扩展失败，远程服务器出错或离线！');
		},
		error : function() {
			alert("服务器内部错误，请刷新后重试");
		}
		});
	   });
	   uninstall.on('click',function(){
		$.ajax( {
		url: ajaxurl,
		data:{
			action : 'cst_uninstall',
			todo : $(this).attr('data')
		},
		type:'post',
		cache:false,
		success:function(data) {
			if( data.install == 200 ){
				alert('扩展卸载成功。');
			}else alert('安装卸载失败，远程服务器出错或离线！');
		},
		error : function() {
			alert("服务器内部错误，请刷新后重试");
		}
		});
	   });
});
});