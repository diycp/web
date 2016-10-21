/*左侧从菜单*/

$(document).ready(function(){
	$('.admin-menu-parent').find('.admin-menu-dropdown-toggle').click(function() {
	    var $this = $(this)
	    var menuList = $this.parent().parent();

	    menuList.find('.admin-menu-parent').each(function(index, value) {
	        var exists = $(this).find('botton').hasClass('admin-menu-botton')
	        if (exists) {
	            $(this).find('botton').removeClass('admin-menu-botton')
	        }
	    })

	    $this.find('#toggle-angle').toggleClass('active')
	    $this.addClass('admin-menu-botton')
	    $this.siblings('ul').slideToggle('slow')
	});
	
//菜单选中样式
	$('.sub_button').each(function(){
		var href = $(this).find('a').attr('href')
		var loc = String(window.location.pathname)
		if (href == loc){
			$(this).parent().siblings('botton').addClass('admin-menu-botton')
        	$(this).addClass('menu-active')
		}
	})


 


});