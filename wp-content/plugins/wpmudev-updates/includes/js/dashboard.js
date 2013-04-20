jQuery(function($) { // DOM READY WRAPPER
	wpmudev.init();
	
	$('.main-community-topics ul li:first p a span.ui-hide-triangle').addClass('ui-show-triangle');
  
	// if clicked outside updatesPanel when it's expanded, collapse it
	$('html').on('click', function() {
		wpmudev.updatesPanel.hide();
	});
	// updatesPanes show / hide button handler
	$('a.updates-fold').on('click', function(e) {
		e.stopPropagation();
		e.preventDefault();
		($('#updates-data').hasClass('updates-data-active')) ? wpmudev.updatesPanel.hide() : wpmudev.updatesPanel.show();
	});

	// ONLY ACTIVATE SUGGESTIVE SEARCH IF PLACAHOLDER VARS ARE DEFINED
	if ((typeof suggestedProjects) !== 'undefined') {
		$('#suggestive-dash-search')
			.attr("data-search", "plugin")
			.doubleSuggest({
				localSource  : suggestedProjects,
				remoteSource : false,
				selectValue  : "name",
				seekValue    : "name",
				minChars     : 2,
				onSelect: function (data) {
					if ("type" in data) $('#suggestive-dash-search').attr("data-search", data.type);
				},
				resultsComplete: function () {
					// Hide results if nothing to show
					if (!$(".ds-result-item").length) $("#ds-results-suggestive-dash-search").hide();
					else $("#ds-results-suggestive-dash-search").show();
				}
			})
			.on('keydown', function (e) {
				if (9 == e.keyCode || 39 == e.keyCode) {
					var $el = $(".ds-result-item:first");
					if ($el.length) {
						var data = $el.data();
						if ("type" in data) $('#suggestive-dash-search').attr("data-search", data.type);
						$("#suggestive-dash-search").val($el.text());
						return false;
					}
				} else if (13 == e.keyCode) {
					$("#project-search-go").click();
				}
			});
	}
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
	
	// Handle project search box
	$("#project-search-go").click(function () {
		var tmp = window.location;
		var scope = ("theme" == $('#suggestive-dash-search').attr("data-search")) ? '?page=wpmudev-themes' : '?page=wpmudev-plugins';
		tmp.hash = "#search=" + $("#suggestive-dash-search").val();
		tmp.search = scope || '?page=wpmudev-plugins';
		window.location = tmp;
	});

	// DASHBOARD API-KEY FORMS, handlers to toggle between 'create acc' & 'log-in'
	if ($('body').hasClass('toplevel_page_wpmudev')) {
		$('#already-member').on('click', function(){
			$('#api-signup').slideUp(600);
			$('#api-login').slideDown(750);
			return false;
		});
		$('#not-member').on('click', function(){
			$('#api-login').slideUp(600);
			$('#api-signup').slideDown(750);
			return false;
		});
	}

	// js fix to fill out height of #wpcontent (so that we don't get white background)
	function processHeight() {
		var browserHeight = document.body.offsetHeight,
		        wpcontent = document.getElementById('wpcontent'),
		  wpcontentHeight = wpcontent.offsetHeight;
		
		if ( wpcontentHeight < browserHeight ) {
			wpcontent.style.height = browserHeight + 'px';
		}
	}

	$('.layer').height($('.premium-content').height());
	
	processHeight();
	window.onresize = function(){
		processHeight();
	}

	// <button class="cta" data-href="..."  /> clicks
	$("button.wpmu-button[data-href]").on("click", function () {
		var href = $(this).attr("data-href");
		if (href) window.location = href;
		return false;
	});

}); // END OF DOM WRAPPER


/* ===========================
   FUNCTION DECLARATIONS BELOW 
*/

var wpmudev = {
	tooltip: function(selector) {
		var tips  = selector,
			tipsl = tips.length,
			i;

		for (i = 0; i < tipsl; i++) {
			jQuery(tips[i]).on('mouseenter mouseleave', function() {
				jQuery(this).has('section').toggleClass('tooltipHover');
			});
		}
	},

	expandOnHover: function(tableSelector) {
		var arg = arguments,
			l   = arg.length,
			i   = 0;
		for (i; i < l; i++) {
			var $parent = jQuery(arg[i]);
			$parent
				.on('mouseenter', 'tr:not(.hiddenrow)', function() {
					var $this = jQuery(this),
						w = $this.width(),
						h = $this.height()
					;
					$parent.find(".reason").css("display", "none");
					$this.next().find('div.reason').css({
						'top'     : -1,
						'left'    : 0,
						'width'   : w,
						'display' : 'block'
					});

				})
				.on('mouseleave', 'tr.hiddenrow', function() {
					jQuery(this).find('div.reason').css('display', 'none');
				})
			;
		}
	},

	// need to fall-back on jQuery based animation if no CSS3 transition
	updatesPanel: {
		hide: function() {
			jQuery('#updates-data').removeClass('updates-data-active');
			jQuery('.overlay').animate({
				'opacity': '0.1'
			}, 500, function() {
				jQuery('.overlay').css('display', 'none')
			});
			jQuery('a.updates-fold').html('<span class="symbol">{</span>&nbsp;&nbsp;&nbsp;show');
		},

		show: function() {
			jQuery('#updates-data').addClass('updates-data-active');
			jQuery('.overlay').css('display', 'block').animate({
				'opacity': '0.8'
			}, 700);
			jQuery('a.updates-fold').html('<span class="symbol">}</span>&nbsp;&nbsp;hide');
		},

		init: function() {
			var that = wpmudev.updatesPanel;
			jQuery('#updates-data').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				that.show();
			});
		}
	},

	collapsableElements: function() {
		jQuery('.accordion-title p').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $_txtSpan  = jQuery(this).find('span.ui-hide-triangle').prev(),
				$_triangle = jQuery(this).find('span.ui-hide-triangle'),
				$_content  = jQuery(this).parent().find('ul');

			function show() {
				$_txtSpan.text('HIDE');
				$_content.slideDown( 'fast','swing' );
			}

			function hide() {
		      $_txtSpan.text('SHOW');
		      $_content.slideUp( 'fast','swing' );
			}

			if($_txtSpan.length){
			  //$_txtSpan.text() === 'SHOW' ? show() : hide();
			  $_content.is(":visible") ? hide() : show();
			  $_triangle.toggleClass('ui-show-triangle');
			}
		});
	},

	hoverToExpand: function() {
		jQuery('ul.hover-to-expand').on('mouseenter', 'li', function() {
			var $_productSearchField = jQuery('#suggestive-dash-search'),
				$_productSearchPanel = jQuery('div#ds-results-suggestive-dash-search');

			if($_productSearchPanel.filter(':visible').length || $_productSearchField.is(':focus')){
				} else {
					jQuery(this).find('div.expanded-content').css({
						'opacity': '1',
						'z-index': '3'
					}).animate({
						'top': '-50%'
					}, 'fast').find('ul').slideDown('fast');
			  }
		}).on('mouseleave', 'li', function() {
			jQuery(this).find('div.expanded-content').css('z-index', '1').animate({
				'top': '0%'
			}, 'fast').find('ul').slideUp('fast', function() {
				jQuery(this).parent().css('opacity', '0');
			});
		});
	},
  // panelContainerHeight prop. containing returned elem height. 
  // i might need it later on, for comparison
  panelContainerHeight : undefined,

	layoutCalculations: function() {

		var $_spacer   = jQuery('.spacer'),
			  $_base     = jQuery('#dash-main-content').height(),
			  $_gradient = jQuery('#right-section-gradient'),
			  that       = this;

		jQuery(window).load(function() {
			$_spacer.css( 'padding-bottom', ( 80 + $_base) );
			$_gradient.css( 'padding-bottom', ( 60 + $_base) );
			return (that.panelContainerHeight = $_base);
		});
	},

	"validate": function (what, $src) {
		wpmudev.validation_pending($src);
		if (!$src.val()) return wpmudev.clear_validation($src);
		return jQuery.post(ajaxurl, {
			"action": "wpmudev_validate_" + what,
			"value": $src.val(),
		}, function (data) {
			if ("status" in data && parseInt(data.status) == 0) return wpmudev.validation_ok($src);
			wpmudev.validation_failure(data.message, $src);
		}, 'json');
	},

	"validate_username": function () { wpmudev.validate("username", jQuery("#user_name")); },

	"validate_email": function () { wpmudev.validate("email", jQuery("#email_addr")); },

	"validate_password": function () { wpmudev.validate("password", jQuery("#password")); },

	"validation_ok": function ($el) {
		var $validation = $el.nextAll(".validation");
		if (!$validation.length) return false;
		$validation
			.removeClass("error")
			.addClass("ok")
			.html('<span class="icon-ok"></span>')
			.show()
		;
	},

	"validation_failure": function (text, $el) {
		var $validation = $el.nextAll(".validation");
		if (!$validation.length) return false;
		$validation
			.removeClass("ok")
			.addClass("error")
			.html('<span class="icon-remove-sign"></span>' + text)
			.show()
		;
	},

	"validation_pending": function ($el) {
		wpmudev.clear_validation();
		var $validation = $el.nextAll(".validation");
		$validation
			.html('Waiting...')
			.show()
		;
	},

	"clear_validation": function ($el) {
		$el = $el && $el.nextAll ? $el : jQuery(this);
		var $validation = $el.nextAll(".validation");
		if (!$validation.length) return false;
		$validation
			.removeClass("error").removeClass("ok")
			.html('')
			.hide()
		;
	},

	"setup_validation": function () {
		jQuery(".validation").removeClass("error").hide();
		jQuery("#user_name")
			.on("blur", this.validate_username)
			.on("focus", this.clear_validation)
		;
		jQuery("#email_addr")
			.on("blur", this.validate_email)
			.on("focus", this.clear_validation)
		;
		jQuery("#password")
			.on("blur", this.validate_password)
			.on("focus", this.clear_validation)
		;
		// Form submission validation
		jQuery("#api-signup").on("submit", function () {
			var safe_to_proceed = true; // Assume best case scenario
			var elements = [
				{"type": "username", "source": jQuery("#user_name")},
				{"type": "email", "source": jQuery("#email_addr")},
				{"type": "password", "source": jQuery("#password")}
			];
			var promises = [];
			jQuery.each(elements, function () {
				var $validation = this.source.nextAll(".validation");
				if (!$validation.is(".ok")) {
					safe_to_proceed = false;
					if (!$validation.is(".error")) {
						var promise = wpmudev.validate(this.type, this.source);
						if ("object" == typeof promise) promises.push(promise);
					}
				}
			});
			if (promises.length) jQuery.when.apply(null, promises).done(function () {
				jQuery("#api-signup").submit();
			});
			return safe_to_proceed;
		});
	},

	init: function() {
		this.layoutCalculations();
		this.tooltip(jQuery('.tooltip'));
		this.expandOnHover('table.hoverExpand');
		this.updatesPanel.init();
		this.collapsableElements();
		this.hoverToExpand();

		this.setup_validation();
	}
}; // end WPMU DEV obj
