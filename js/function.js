(function($) {
$(function(){

/* ------------------------------
	slide nav  
------------------------------ */
var $menu       = $( '#mcl_slidein_nav_list' ),
    $menuBtn    = $( '#mcl_slidein_nav_btn' ),
    $body       = $( document.body ),
    $layer      = $( '#mcl_slidein_nav_layer' ),
    menuWidth   = $menu.outerWidth(),
    menuPos     = mcl_slidein_nav.position,
    menuPosTop  = parseInt(mcl_slidein_nav.position_top),
    menuPosSide = parseInt(mcl_slidein_nav.position_side);  
    
    $menu.css( menuPos, -menuWidth );
    
    $menuBtn
    .css( menuPos , menuPosSide + 'px')
    .css( 'top' , menuPosTop + 'px');
	
    $menuBtn.on( 'click', function(){
    	$body.toggleClass( 'open' );
        if($body.hasClass( 'open' )){
        	menu_open();                        
        } else {
        	menu_close();    
        }             
    });
   
    $layer.on('click', function(){
        menu_close();
        $body.removeClass( 'open' );
    });
    
    $menu.find('a').on('click', function(){
      var page_in_link = $(this).attr('href').match(/^#/g);
      if( page_in_link || page_in_link == null ){
        menu_close();
        $body.removeClass( 'open' );
      }
    });
    
    // nav open func -------------
    function menu_open(){
	    
	    $layer.fadeIn( 200 );
	    if( menuPos == 'left' ){
		    $menu.show().animate( { 'left' : 0 }, 300 );
		    $menuBtn.animate( { 'left' :  menuWidth + menuPosSide }, 300 );
	    }
	    else if( menuPos == 'right' ){
		    $menu.show().animate( { 'right' : 0 }, 300 );
		    $menuBtn.animate( { 'right' : menuWidth + menuPosSide }, 300 );
	    }
    }    
    
    // nav close func -------------
  	function menu_close(){
  		
  		if( menuPos == 'left' ){
  			$menu.animate( { 'left' : -menuWidth }, 300, function(){ 
  				$([$menu[0],$layer[0]]).fadeOut( 200 );
  			});
  			$menuBtn.animate( { 'left' : menuPosSide }, 300 );
  		}
  		else if( menuPos == 'right' ){
  		    $menu.animate( { 'right' : -menuWidth }, 300, function(){ 
  			    $([$menu[0],$layer[0]]).fadeOut( 200 );
  			});
  			$menuBtn.animate( { 'right' : menuPosSide }, 300 );
  	  }
  	}
 
});   
})(jQuery);
