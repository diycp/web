/*左侧从菜单*/

$(document).ready(function(){

var extend = 	

$(".admin-menu-parent").click(function(){
	var $this = $(this);
	$this.find('a i').toggleClass('fa-angle-down')
	$this.find('ul').slideToggle("slow");
  

  });
});