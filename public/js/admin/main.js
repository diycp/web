/*左侧从菜单*/

$(document).ready(function(){



$('.admin-menu-parent').click(function(){
	var $this = $(this);
	$this.find('a').find('#toggle-angle').toggleClass('fa-angle-down')

	// var extend = $('.admin-menu-child').attr('style');
	// if (extend != 'display:none;') {
	// 	extend.slideUp('fast')
	// }

	$this.find('ul').slideToggle('slow');
  

  });
});