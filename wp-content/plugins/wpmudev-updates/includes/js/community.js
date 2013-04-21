jQuery(document).ready(function($) {


	jQuery('.most-popular-topics h1').parent().each(function () {
		var $me = $(this);
		if (!$me.is("li")) return true;
		$me.on('click', function (e) {
			if ($(e.target).is('a[href^="http://"]')) return true; // Allow external links to do their thing.
			var $_content  = $me.parent().find('ul');

			$_content.is(":visible")
				? $_content.find("table").hide().end().slideUp('fast')
				: $_content.slideDown('fast').find("table").show()
			;
			return false;
		});
	});

	//handle forum search box
	$('#forum-search-go').click(function() {
		var searchUrl = 'http://premium.wpmudev.org/forums/search.php?q=' + $('#forum-search-q').val();
		window.open(searchUrl, '_blank');
		return false;
	});
	//catch the enter key
	$('#forum-search-q').keypress(function(e) {
		if(e.which == 13) {
			$(this).blur();
			$('#forum-search-go').focus().click();
		}
	});

	// handle container heights, raw js because fast & simple

	(function(){
		var isDisabled = document.getElementById( 'support-disabled' );

		if ( isDisabled ) { 
			document.getElementById( 'wpbody-content' ).style.paddingTop = 0;
			processHeight( 'support-layer' );
			window.onresize = function(){
				processHeight( 'support-layer' );
			}

		} else {
			processHeight( 'wpcontent' );
			window.onresize = function(){
				processHeight( 'wpcontent' );
			}
		}
	})();

	function processHeight( element ) {							// accepts ID string, as many agruments as needed
		for ( var i = 0, j=arguments.length; i < j; i++ ) {

			var el 		  = document.getElementById( arguments[i] ),
				docHeight = getDocHeight(),
				uaHeight  = $(window).height();

			if ( el ) { 
				if ( docHeight > uaHeight ){
					el.removeAttribute('style');
					docHeight = getDocHeight();
					el.style.height = docHeight + 'px';
				} else {
					el.removeAttribute('style');
					docHeight = getDocHeight();
					el.style.height = uaHeight + 'px';
				}
			}
		}

		// get document height function
		// credit to James Padolsey (http://james.padolsey.com/javascript/get-document-height-cross-browser/)
		function getDocHeight() {
		    var D = document;
		    return Math.max(
		        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
		        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
		        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
		    );
		}
	}

});