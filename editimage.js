jQuery(document).ready(function($) {
	
	var a1; var a2; var a3; var a4; var a5;
	
	function urlDecode(str) {
	    // Decodes URL-encoded string  
	    // 
	    // version: 1103.1210
	    // discuss at: http://phpjs.org/functions/urldecode    // +   original by: Philip Peterson
	    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +      input by: AJ
	    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +   improved by: Brett Zamir (http://brett-zamir.me)    // +      input by: travc
	    // +      input by: Brett Zamir (http://brett-zamir.me)
	    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +   improved by: Lars Fischer
	    // +      input by: Ratheous    // +   improved by: Orlando
	    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
	    // +      bugfixed by: Rob
	    // +      input by: e-mike
	    // +   improved by: Brett Zamir (http://brett-zamir.me)    // %        note 1: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
	    // %        note 2: Please be aware that this function expects to decode from UTF-8 encoded strings, as found on
	    // %        note 2: pages served as UTF-8
	    // *     example 1: urldecode('Kevin+van+Zonneveld%21');
	    // *     returns 1: 'Kevin van Zonneveld!'    // *     example 2: urldecode('http%3A%2F%2Fkevin.vanzonneveld.net%2F');
	    // *     returns 2: 'http://kevin.vanzonneveld.net/'
	    // *     example 3: urldecode('http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a');
	    // *     returns 3: 'http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a'
	    return decodeURIComponent((str + '').replace(/\+/g, '%20'));
	}
	
	function urlEncode(str) {
		//Image names are already urlencoded in joomla.
		str = urlDecode(str);

	    // http://kevin.vanzonneveld.net
	    // +   original by: Philip Peterson
	    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +      input by: AJ
	    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // %          note: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
	    // *     example 1: urlencode('Kevin van Zonneveld!');
	    // *     returns 1: 'Kevin+van+Zonneveld%21'
	    // *     example 2: urlencode('http://kevin.vanzonneveld.net/');
	    // *     returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
	    // *     example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
	    // *     returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
	                                     
	    var histogram = {}, histogram_r = {}, code = 0, tmp_arr = [];
	    var ret = str.toString();
	    
	    var replacer = function(search, replace, str) {
	        var tmp_arr = [];
	        tmp_arr = str.split(search);
	        return tmp_arr.join(replace);
	    };
	    
	    // The histogram is identical to the one in urldecode.
	    histogram['!']   = '%21';
	    histogram['%20'] = '+';
	    
	    // Begin with encodeURIComponent, which most resembles PHP's encoding functions
	    ret = encodeURIComponent(ret);
	    
	    for (search in histogram) {
	        replace = histogram[search];
	        ret = replacer(search, replace, ret) // Custom replace. No regexing
	    }

	    // Uppercase for full PHP compatibility
	    return ret.replace('/(\%([a-z0-9]{2}))/g', function(full, m1, m2) {
	        return "%"+m2.toUpperCase();
	    });
	    
	    return ret;
	}

	$("[id*='imgedit-open-btn-']").removeAttr('onclick');
	
    $("[id*='imgedit-open-btn-']").click(function() {
    	
    	var filepath = $(this).parent().parent().parent().parent().parent().find("input.urlfield").val();
    	filepath = filepath.replace(PieParams.host+"/", "");
    	var d=new Date();
    	
    	if (isIframe()) {
	    	
    		a1 = $('#TB_window', window.parent.document).width();
	    	a2 = $('#TB_window', window.parent.document).height();
	    	a3 = $('#TB_window', window.parent.document).css('margin-left');
	    	a4 = $('#TB_iframeContent', window.parent.document).width();
	    	a5 = $('#TB_iframeContent', window.parent.document).height();

	    	var b1 = $(window.parent.document).width()-60;
	    	var b2 = $(window.parent.document).height()-60;
	    	var b3 = "-"+parseInt(($(window.parent.document).width()-60)/2)+"px";
	    	var b4 = $(window.parent.document).width()-60;
	    	var b5 = $(window.parent.document).height()-60;
	    	
			setParentLightbox(b1, b2, b3, b4, b5);
    	}
		
		var finalUrl = PieParams.host + '/?pie-lite=1&imagesrc='+urlEncode(filepath)+'&language='+urlEncode(PieParams.language)+'&version='+urlEncode(PieParams.version)+'&systemversion='+urlEncode(PieParams.wordpressversion)+'&system=wordpress';
    	tb_show("",finalUrl+"&TB_iframe=true&height="+($(window).height()-80)+"&width="+($(window).width()-80));

		if (isIframe()) {
			
			$("#TB_closeWindowButton").click(function()
	        {
				setParentLightbox(a1, a2, a3, a4, a5);
	        });	

			$("#TB_overlay").click(function()
	        {
				setParentLightbox(a1, a2, a3, a4, a5);
	        });	
		}
    });	
    
    function isIframe() {
    	return (window.parent.location != window.location);
    }
   
    function setParentLightbox(a1, a2, a3, a4, a5) {
    	$('#TB_window', window.parent.document).width(a1);
    	$('#TB_window', window.parent.document).height(a2);
    	$('#TB_window', window.parent.document).css('margin-left', a3);
    	$('#TB_iframeContent', window.parent.document).width(a4);
    	$('#TB_iframeContent', window.parent.document).height(a5);
    }
    
});