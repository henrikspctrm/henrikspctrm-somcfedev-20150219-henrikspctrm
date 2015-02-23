(function($) {
      $(function(){
        $('li.subp:has(ul)')
          .click(function(event){
            if (this == event.target) {
              if ($(this).children('ul').is(':hidden')) {
	          $(this).children('.icon-right-dir').removeClass("icon-right-dir").addClass("icon-down-dir");
                  $(this).children('ul').slideDown();
                  $(this).children('.icon-sort').show();
              }
              else {
	          $(this).children('.icon-down-dir').removeClass("icon-down-dir").addClass("icon-right-dir");
                    $(this).children('.icon-sort').hide();
                  $(this).children('ul').slideUp();
              }
            }
            return false;
          })
             .css({cursor:'pointer'})
             .prepend("<span class='icon-right-dir'>  </span>")
             .children('ul, .icon-sort').hide()	;	
	
    $('.icon-sort').click(function(event) {
	$(this).toggleClass('asc'); 
    	var $sort = this;
    	var $list = $(this).next('ul');
    	var $listLi = $($list.children('li'),$list);
    	$listLi.sort(function(a, b){
        var keyA = $(a).clone().children().remove().end().text();
        var keyB = $(b).clone().children().remove().end().text();
        if($($sort).hasClass('asc')){
	        return (keyA < keyB) ? 1 : -1;
        } else {
            return (keyA > keyB) ? 1 : -1;
        }
    	});	
    	$.each($listLi, function(index, row){
        $list.append(row);
    	});
    		event.preventDefault();
	});
		 
        $('li.subp:not(:has(ul))').css({
              cursor: 'default'
        });
      	});
})( jQuery );