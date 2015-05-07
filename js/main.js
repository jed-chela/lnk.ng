
var linker = {
	
	lock_but:false,
	
	init:function(){
		linker.long_inp = $('.long_url') ;
		linker.long_sub = $('#long_url_ajax_button') ;
		linker.result_box = $('.short_url_box') ;
	
		linker.long_sub.click(function(){
			linker.process() ;
		});
	},
	
	process:function(){
		var l_url = linker.long_inp.val() ;
		if(l_url != ""){
			linker.long_inp.attr('disabled',true) ;
			linker.result_box.html("<img class='ajax_loader_pic' src='"+CONST_rootdir+"images/icons/ajax-loader.gif' />") ;
			params = {long_url:l_url} ;
			if(linker.lock_but == false){
				linker.lock_but = true ;
				linker.sendQuery(params, linker.result_box) ;
			}
		}
		
		//short_url_box
	},
	
	sendQuery:function(params, update_elem){
		var rootdir = CONST_rootdir ;
		$.ajax({ 
			type: 'POST',  url: rootdir + 'shorten_xhr', data:params, dataType:'json'
		})
		.done(function(response){
			if(response){ 
				if(response.success == true){
					update_elem.html(response.html) ;
					$('.short_url').selectText() ;
					linker.long_inp.val("") ;
					linker.long_inp.attr('disabled',false) ;
				}else{
					linker.result_box.html("<div class='ref_error'>An error occurred! The URL failed to shorten.</div>") ;
					linker.long_inp.attr('disabled',false) ;
				}
			}
			
			linker.lock_but = false ;
			
		})
	}
}

$.fn.selectText = function(){
    var doc = document
        , element = this[0]
        , range, selection
    ;
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();        
        range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};

$(document).ready(function() {
	linker.init() ;
});