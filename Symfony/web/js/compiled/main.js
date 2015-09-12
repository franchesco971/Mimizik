/*
 * 	Fade Slider 1.0 - jQuery plugin
 *	written by Ihor Ahnianikov	
 *  http://ahnianikov.com
 *
 *	Copyright (c) 2012 Ihor Ahnianikov
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
(function($) {
	$.fn.fadeSlider = function (options) {
		var options = jQuery.extend ({
			speed: 400,
			pause: 0
		}, options);
	 
		var slider=$(this);
		var list=$(this).children('ul');
		var disabled=false;
		var autoSlide;
		var arrows=slider.parent().parent().find('.arrow');
		
		//build slider sliderect
		function init() {
		
			//set slides dimensions
			list.children('li').hide();
			
			//show first slide
			list.find('li:first-child').addClass('current').show();
			
			//arrows
			if(slider.parent().hasClass('main-fade-slider')) {
				arrows=slider.parent().find('.arrow');
			}
			
			arrows.click(function() {
				//next slide
				if($(this).hasClass('arrow-left')) {
					animate('left');
				} else {
					animate('right');
				}

				//stop slider
				clearInterval(autoSlide);
			});
			
			//rotate slider
			if(options.pause!=0) {	
				rotate();
			}
		}
		
		//rotate slider
		function rotate() {			
			autoSlide=setInterval(function() { animate('right') },options.pause);
		}
				
		//show next slide
		function animate(direction) {
		
			if(disabled) {
				return;
			} else {
				//disable animation
				disabled=true;
			}			
			
			//get current slide
			var currentSlide=list.children('li.current');
			var nextSlide;
			
			//get next slide for current direction
			if(direction=='left') {
				if(list.children('li.current').prev('li').length) {
					nextSlide=list.children('li.current').prev('li');
				} else {
					nextSlide=list.children('li:last-child');
				}
			} else {
				if(list.children('li.current').next('li').length) {
					nextSlide=list.children('li.current').next('li');
				} else {
					nextSlide=list.children('li:first-child');
				}				
			}
			
			//animate slider height
			list.animate({'height':nextSlide.outerHeight()},options.speed);
			
			//stop all videos
			if(list.find('iframe').length) {
				list.find('iframe').each(function() {
					callPlayer($(this).parent().attr('id'),'pauseVideo');
				});
			}
			
			//animate slides
			nextSlide.css({'position':'absolute','z-index':'2'}).fadeIn(options.speed, function() {
			
				//set current slide class
				currentSlide.hide().removeClass('current');
				nextSlide.addClass('current').css({'position':'relative', 'z-index':'1'});	
					
				//enable animation
				disabled=false;
			});
		
		}
		
		//Control Video
		function callPlayer(frame_id, func, args) {
			if (window.jQuery && frame_id instanceof jQuery) frame_id = frame_id.get(0).id;
			var iframe = document.getElementById(frame_id);
			if (iframe && iframe.tagName.toUpperCase() != 'IFRAME') {
				iframe = iframe.getElementsByTagName('iframe')[0];
			}

			if (!callPlayer.queue) callPlayer.queue = {};
			var queue = callPlayer.queue[frame_id],
				domReady = document.readyState == 'complete';

			if (domReady && !iframe) {
				window.console && console.log('callPlayer: Frame not found; id=' + frame_id);
				if (queue) clearInterval(queue.poller);
			} else if (func === 'listening') {
				if (iframe && iframe.contentWindow) {
					func = '{"event":"listening","id":' + JSON.stringify(''+frame_id) + '}';
					iframe.contentWindow.postMessage(func, '*');
				}
			} else if (!domReady || iframe && (!iframe.contentWindow || queue && !queue.ready)) {
				if (!queue) queue = callPlayer.queue[frame_id] = [];
				queue.push([func, args]);
				if (!('poller' in queue)) {
					queue.poller = setInterval(function() {
						callPlayer(frame_id, 'listening');
					}, 250);
					messageEvent(1, function runOnceReady(e) {
						var tmp = JSON.parse(e.data);
						if (tmp && tmp.id == frame_id && tmp.event == 'onReady') {
							clearInterval(queue.poller);
							queue.ready = true;
							messageEvent(0, runOnceReady);

							while (tmp = queue.shift()) {
								callPlayer(frame_id, tmp[0], tmp[1]);
							}
						}
					}, false);
				}
			} else if (iframe && iframe.contentWindow) {
				if (func.call) return func();
				iframe.contentWindow.postMessage(JSON.stringify({
					"event": "command",
					"func": func,
					"args": args || [],
					"id": frame_id
				}), "*");
			}
			function messageEvent(add, listener) {
				var w3 = add ? window.addEventListener : window.removeEventListener;
				w3 ? w3('message', listener, !1) : (add ? window.attachEvent : window.detachEvent)('onmessage', listener);
			}
		}
		
		//resize slider
		function resize() {			
			list.height(list.find('li.current').outerHeight());
		}
		
		//init slider
		init();	
		
		//window resize event
		$(window).resize(function() {
			resize();
		});
	}
})(jQuery);
/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);
//Load Tweets
function loadTweets(twitters) {
  var statusHTML = [];
  for (var i=0; i<twitters.length; i++){
    var username = twitters[i].user.screen_name;
    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
      return '<a href="'+url+'">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
    });
    statusHTML.push('<li>'+status+'</li>');
  }
  return '<ul>'+statusHTML.join('')+'</ul>';
}

//Resize Layout
function resizeLayout() {
	//Sticky Footer
	if(jQuery('.footer').offset().top+jQuery('.footer').outerHeight()<jQuery(window).height()) {
		jQuery('.footer').addClass('sticky');
	}
}

//DOM Loaded
jQuery(document).ready(function($) {
	
	//Dropdown Menu
	$('.header .menu ul li').hoverIntent(
		function() {
			var menuItem=$(this);			
			menuItem.children('ul').slideToggle(300, function() {
				menuItem.addClass('hover');
			});
		},
		function() {
			var menuItem=$(this);
			menuItem.children('ul').slideToggle(300, function() {
				menuItem.removeClass('hover');
			});
		}
	);
	
	//Select Menu
	$('.select-menu select').fadeTo(0,0);
	$('.select-menu select').change(function() {
		window.location.href=$(this).find('option:selected').attr('value');
	});
	$('.select-menu option').attr('selected','');
	if($('.page-title').length) {
		$('.select-menu span').text($('.page-title > .container > span').text());
	} else {
		$('.select-menu span').text($('.select-menu select option:eq(0)').text());
	}
	$('.select-menu option').each(function(index,value) {
		if($(this).attr('value')==$(location).attr('href')) {
			$(this).attr('selected','selected');
			$('.select-menu span').text($(this).text());
		}
	});
	
	//Audio Player
	if($('.featured-player').length) {			
		var autoplay=false;
		var playlist=[];
		var player=$('.featured-player');
		
		if($('.featured-player').find('.autoplay').val()=='true') {
			autoplay=true;
		}
		
		player.find('.playlist a').each(function() {
			playlist.push({
				title:$(this).text(),
				artist:$(this).attr('title'),
				mp3:$(this).attr('href')
			});
		});
		var myPlaylist = new jPlayerPlaylist({
			jPlayer: '#jquery_jplayer',
			cssSelectorAncestor: '#jp_container'
		}, playlist, {
			swfPath: template_directory+'js/jplayer/Jplayer.swf',
			supplied: 'mp3',
			solution: 'html,flash',
			wmode: 'window',
			playlistOptions: { autoPlay: autoplay }
		});		
		if(player.find('.button-container').hasClass('hidden')) {
			player.addClass('without-button');
		} else {
			player.addClass('with-button');
			player.find('.button').attr('href',player.find('.playlist a:first-child').attr('data-link'));
		}
		player.find('.container').show();
	}
	
	//Track Player
	if($('.track-player').length && $('audio').length) {
		var as = audiojs.createAll();
	}
	
	$('.track-play-button').click(function() {
		var trackPlayer=$(this).parent().parent().next('tr');
		if($(this).hasClass('active')) {
			//hide player
			trackPlayer.hide();
		
			//stop playing
			if(trackPlayer.find('.audiojs').hasClass('playing') && trackPlayer.find('object').length==0) {
				trackPlayer.find('.pause').trigger('click');
			}			
		} else {	
			//hide and stop other players
			$('.track-play-button').removeClass('active');
			$('.track-player').hide();
			$('.audiojs').each(function() {
				if($(this).hasClass('playing') && trackPlayer.find('object').length==0) {
					$(this).find('.pause').trigger('click');
				}
			});
		
			//start playing
			if(trackPlayer.find('object').length==0) {
				trackPlayer.find('.play').trigger('click');
			}			

			//show player
			trackPlayer.fadeIn(700);
		}
		$(this).toggleClass('active');		
		return false;
	});

	//Ribbon Slider
	if($('.ribbon-slider').length) {
		var mousewheelScrolling=true;
		if($('.ribbon-slider input.slider-mousewheel').val()=='true') {
			mousewheelScrolling=false;
		}
		
		$('.ribbon-slider').ribbonSlider({
			speed:parseInt($('.ribbon-slider input.slider-speed').val()),
			pause:parseInt($('.ribbon-slider input.slider-pause').val()),
			mousewheel: mousewheelScrolling			
		});
	}	
	
	//Fade Slider
	if($('.fade-slider').length) {
		$('.fade-slider').each(function() {
			var sliderOptions={};
			if($(this).find('input.slider-speed').length) {
				sliderOptions.speed=parseInt($(this).find('input.slider-speed').val());
			}
			if($(this).find('input.slider-pause').length) {
				sliderOptions.pause=parseInt($(this).find('input.slider-pause').val());
			}
			$(this).fadeSlider(sliderOptions);
		});
	}	
	
	//Carousel Slider
	if($('.carousel-slider').length) {
		$('.carousel-slider').each(function() {
			$(this).easySlider({
				speed: 500,
				auto: false,
				continuous: true			
			});
		});
	}
	
	//Social Widget
	if($('.social-widget').length) {
		if($('.social-widget .pane.twitter').length) {
			$.getJSON('http://api.twitter.com/1/statuses/user_timeline/'+$('.pane.twitter input.twitter-username').val()+'.json?count='+parseInt($('.pane.twitter input.tweets-number').val())+'&callback=?', function(tweets) {
				$('.social-widget .pane.twitter').html(loadTweets(tweets));
			});
		}
	}
	
	//Tabs
	if($('.tabs-container').length) {	
		$('.tabs-container').each(function() {
			
			var tabs=$(this);
		
			//show first pane
			tabs.find('.panes .pane:first-child').show();
			tabs.find('.tabs li:first-child').addClass('current');
			
			tabs.find('.tabs li').click(function() {
				//set active state to tab
				tabs.find('.tabs li').removeClass('current');
				$(this).addClass('current');
				
				//show current tab
				tabs.find('.pane').hide();
				tabs.find('.pane:eq('+$(this).index()+')').show();			
			});
		});		
		$('.tabs-container .tabs a').click(function() {
			$(this).parent().trigger('click');
			return false;
		});
	}
	
	//Placeholders
	var fields = $('input[type="text"],input[type="password"],textarea').get();
	$.each(fields, function(index, item){
		item = $(item);
		var defaultStr = item.val();
	
		item.focus(function () {
			var me = $(this);
			if(me.val() == defaultStr){
				me.val('');
			}
		});
		item.blur(function () {
			var me = $(this);			
			if(!me.val()){
				me.val(defaultStr);
			}
		});
	});
	
	//Submit Button
	$('.submit:not(.disabled)').click(function() {
		$(this).parent('form').submit();
		return false;
	});
	
	//Tip Cloud
	$('.tip-container .button').click(function() {
		var tipCloud=$(this).parent().find('.tip-cloud');
		$('.tip-cloud').hide();
		tipCloud.show().addClass('visible');
		return false;
	});
	
	$('body').click(function() {
		$('.tip-cloud.visible').hide().removeClass('visible');
	});
	
	//Subscribe Widget	
	function subscribe() {
		var email=$('#subscribe_form input#email').val();
		var data = {
			action: 'themex_subscribe',
			email: email
		};
		
		//hide message
		$('.widget-subscribe .alert').slideUp(300);
		
		//post data to server		
		$.post(ajaxurl, data, function(response) {
			$(".widget-subscribe .alert-box").remove();
			$("#subscribe_form").before(response).slideDown(300);
		});
		
		return false;
	}
	if($('.widget-subscribe').length) {
		$('#subscribe_form .subscribe-button').click( function() { subscribe(); return false; } );
		$('#subscribe_form').submit( function() { subscribe(); return false; } );
	}	
	
	//Comment Form
	$('div#respond').addClass('formatted-form');
	
	//Login Form
	$('a.login-link').click(function() {
		$('.login-form').stop().hide().fadeIn(200);
		return false;
	});
	$('.login-close-button').click(function() {
		$('.login-form').stop().fadeOut(200);
		return false;
	});
	$('.login-form form').submit(function() {
		var form=$(this);
		var loader=form.find('.formatted-form-loader');
		var message=$(this).parent().find('.alert');
		var data = {
			action: 'themex_login',
			username: form.find('#themex_username').val(),
			password: form.find('#themex_password').val(),
			nonce: form.find('#themex_nonce').val()
		};
		
		//hide message
		loader.show();
		message.slideUp(300);
		
		//post data to server		
		$.post(ajaxurl, data, function(response) {
			if(response.match('success') != null) {
				window.location.reload(true);
			} else {
				message.html(response).slideDown(300);
				loader.hide();
			}			
		});
		
		return false;
	});
	
	//IE8 Browser Filter
	/*if (jQuery.browser.msie  && parseInt(jQuery.browser.version, 10) === 8) {
		jQuery('body').addClass('ie8'); 
		$('.featured-event:last-child, .featured-post:last-child, .footer-widgets .column:last-child, .subheader .menu ul ul li:last-child, .artist-social-links a:last-child,.releases-filter li:last-child').addClass('last-child');
	} else if(navigator.userAgent.indexOf('Chrome') == -1) {
		jQuery('body').addClass('webkit-browser');
	}*/
	
	//Window Loaded
	$(window).bind('load', function() {
		resizeLayout();
	});
	
});
/**
 * This jQuery plugin displays pagination links inside the selected elements.
 * 
 * This plugin needs at least jQuery 1.4.2
 *
 * @author Gabriel Birke (birke *at* d-scribe *dot* de)
 * @version 2.1
 * @param {int} maxentries Number of entries to paginate
 * @param {Object} opts Several options (see README for documentation)
 * @return {Object} jQuery Object
 */
 (function($){
	/**
	 * @class Class for calculating pagination values
	 */
	$.PaginationCalculator = function(maxentries, opts) {
		this.maxentries = maxentries;
		this.opts = opts;
	}
	
	$.extend($.PaginationCalculator.prototype, {
		/**
		 * Calculate the maximum number of pages
		 * @method
		 * @returns {Number}
		 */
		numPages:function() {
			return Math.ceil(this.maxentries/this.opts.items_per_page);
		},
		/**
		 * Calculate start and end point of pagination links depending on 
		 * current_page and num_display_entries.
		 * @returns {Array}
		 */
		getInterval:function(current_page)  {
			var ne_half = Math.floor(this.opts.num_display_entries/2);
			var np = this.numPages();
			var upper_limit = np - this.opts.num_display_entries;
			var start = current_page > ne_half ? Math.max( Math.min(current_page - ne_half, upper_limit), 0 ) : 0;
			var end = current_page > ne_half?Math.min(current_page+ne_half + (this.opts.num_display_entries % 2), np):Math.min(this.opts.num_display_entries, np);
			return {start:start, end:end};
		}
	});
	
	// Initialize jQuery object container for pagination renderers
	$.PaginationRenderers = {}
	
	/**
	 * @class Default renderer for rendering pagination links
	 */
	$.PaginationRenderers.defaultRenderer = function(maxentries, opts) {
		this.maxentries = maxentries;
		this.opts = opts;
		this.pc = new $.PaginationCalculator(maxentries, opts);
	}
	$.extend($.PaginationRenderers.defaultRenderer.prototype, {
		/**
		 * Helper function for generating a single link (or a span tag if it's the current page)
		 * @param {Number} page_id The page id for the new item
		 * @param {Number} current_page 
		 * @param {Object} appendopts Options for the new item: text and classes
		 * @returns {jQuery} jQuery object containing the link
		 */
		createLink:function(page_id, current_page, appendopts){
			var lnk, np = this.pc.numPages();
			page_id = page_id<0?0:(page_id<np?page_id:np-1); // Normalize page id to sane value
			appendopts = $.extend({text:page_id+1, classes:""}, appendopts||{});
			if(page_id == current_page){
				lnk = $("<span class='current'>" + appendopts.text + "</span>");
			}
			else
			{
				lnk = $("<a>" + appendopts.text + "</a>")
					//.attr('href', this.opts.link_to.replace(/__id__/,page_id));
                                        .attr('href', this.opts.link_to.replace(/__id__/,page_id+1));
			}
			if(appendopts.classes){ lnk.addClass(appendopts.classes); }
			lnk.data('page_id', page_id);
			return lnk;
		},
		// Generate a range of numeric links 
		appendRange:function(container, current_page, start, end, opts) {
			var i;
			for(i=start; i<end; i++) {
				this.createLink(i, current_page, opts).appendTo(container);
			}
		},
		getLinks:function(current_page, eventHandler) {
			var begin, end,
				interval = this.pc.getInterval(current_page),
				np = this.pc.numPages(),
				fragment = $("<div class='pagination'></div>");
			
			// Generate "Previous"-Link
			if(this.opts.prev_text && (current_page > 0 || this.opts.prev_show_always)){
				fragment.append(this.createLink(current_page-1, current_page, {text:this.opts.prev_text, classes:"prev"}));
			}
			// Generate starting points
			if (interval.start > 0 && this.opts.num_edge_entries > 0)
			{
				end = Math.min(this.opts.num_edge_entries, interval.start);
				this.appendRange(fragment, current_page, 0, end, {classes:'sp'});
				if(this.opts.num_edge_entries < interval.start && this.opts.ellipse_text)
				{
					jQuery("<span>"+this.opts.ellipse_text+"</span>").appendTo(fragment);
				}
			}
			// Generate interval links
			this.appendRange(fragment, current_page, interval.start, interval.end);
			// Generate ending points
			if (interval.end < np && this.opts.num_edge_entries > 0)
			{
				if(np-this.opts.num_edge_entries > interval.end && this.opts.ellipse_text)
				{
					jQuery("<span>"+this.opts.ellipse_text+"</span>").appendTo(fragment);
				}
				begin = Math.max(np-this.opts.num_edge_entries, interval.end);
				this.appendRange(fragment, current_page, begin, np, {classes:'ep'});
				
			}
			// Generate "Next"-Link
			if(this.opts.next_text && (current_page < np-1 || this.opts.next_show_always)){
				fragment.append(this.createLink(current_page+1, current_page, {text:this.opts.next_text, classes:"next"}));
			}
			$('a', fragment).click(eventHandler);
			return fragment;
		}
	});
	
	// Extend jQuery
	$.fn.pagination = function(maxentries, opts){
		
		// Initialize options with default values
		opts = jQuery.extend({
			items_per_page:10,
			num_display_entries:11,
			current_page:0,
			num_edge_entries:0,
			link_to:"#",
			prev_text:"Prev",
			next_text:"Next",
			ellipse_text:"...",
			prev_show_always:true,
			next_show_always:true,
			renderer:"defaultRenderer",
			callback:function(){return false;}
		},opts||{});
		
		var containers = this,
			renderer, links, current_page;
		
		/**
		 * This is the event handling function for the pagination links. 
		 * @param {int} page_id The new page number
		 */
		function paginationClickHandler(evt){
			var links, 
				new_current_page = $(evt.target).data('page_id'),
				continuePropagation = selectPage(new_current_page);
			if (!continuePropagation) {
				evt.stopPropagation();
			}
			return continuePropagation;
		}
		
		/**
		 * This is a utility function for the internal event handlers. 
		 * It sets the new current page on the pagination container objects, 
		 * generates a new HTMl fragment for the pagination links and calls
		 * the callback function.
		 */
		function selectPage(new_current_page) {
			// update the link display of a all containers
			containers.data('current_page', new_current_page);
			links = renderer.getLinks(new_current_page, paginationClickHandler);
			containers.empty();
			links.appendTo(containers);
			// call the callback and propagate the event if it does not return false
			var continuePropagation = opts.callback(new_current_page, containers);
			return continuePropagation;
		}
		
		// -----------------------------------
		// Initialize containers
		// -----------------------------------
		current_page = opts.current_page;
		containers.data('current_page', current_page);
		// Create a sane value for maxentries and items_per_page
		maxentries = (!maxentries || maxentries < 0)?1:maxentries;
		opts.items_per_page = (!opts.items_per_page || opts.items_per_page < 0)?1:opts.items_per_page;
		
		if(!$.PaginationRenderers[opts.renderer])
		{
			throw new ReferenceError("Pagination renderer '" + opts.renderer + "' was not found in jQuery.PaginationRenderers object.");
		}
		renderer = new $.PaginationRenderers[opts.renderer](maxentries, opts);
		
		// Attach control events to the DOM elements
		var pc = new $.PaginationCalculator(maxentries, opts);
		var np = pc.numPages();
		containers.bind('setPage', {numPages:np}, function(evt, page_id) { 
				if(page_id >= 0 && page_id < evt.data.numPages) {
					selectPage(page_id); return false;
				}
		});
		containers.bind('prevPage', function(evt){
				var current_page = $(this).data('current_page');
				if (current_page > 0) {
					selectPage(current_page - 1);
				}
				return false;
		});
		containers.bind('nextPage', {numPages:np}, function(evt){
				var current_page = $(this).data('current_page');
				if(current_page < evt.data.numPages - 1) {
					selectPage(current_page + 1);
				}
				return false;
		});
		
		// When all initialisation is done, draw the links
		links = renderer.getLinks(current_page, paginationClickHandler);
		containers.empty();
		links.appendTo(containers);
		// call callback function
		opts.callback(current_page, containers);
	} // End of $.fn.pagination block
	
})(jQuery);

/*
 * jQuery Reveal Plugin 1.0
 * www.ZURB.com
 * Copyright 2010, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
*/


(function($) {

/*---------------------------
 Defaults for Reveal
----------------------------*/
	 
/*---------------------------
 Listener for data-reveal-id attributes
----------------------------*/

	//$('a[data-reveal-id]').live('click', function(e) {
        $('div').on('click','a[data-reveal-id]', function(e) {
		e.preventDefault();
		var modalLocation = $(this).attr('data-reveal-id');
		$('#'+modalLocation).reveal($(this).data());
	});

/*---------------------------
 Extend and Execute
----------------------------*/

    $.fn.reveal = function(options) {
        
        
        var defaults = {  
	    	animation: 'fadeAndPop', //fade, fadeAndPop, none
		    animationspeed: 300, //how fast animtions are
		    closeonbackgroundclick: true, //if you click background will modal close?
		    dismissmodalclass: 'close-reveal-modal' //the class of a button or element that will close an open modal
    	}; 
    	
        //Extend dem' options
        var options = $.extend({}, defaults, options); 
	
        return this.each(function() {
        
/*---------------------------
 Global Variables
----------------------------*/
        	var modal = $(this),
        		topMeasure  = parseInt(modal.css('top')),
				topOffset = modal.height() + topMeasure,
          		locked = false,
				modalBG = $('.reveal-modal-bg');

/*---------------------------
 Create Modal BG
----------------------------*/
			if(modalBG.length == 0) {
				modalBG = $('<div class="reveal-modal-bg" />').insertAfter(modal);
			}		    
     
/*---------------------------
 Open & Close Animations
----------------------------*/
			//Entrance Animations
			modal.bind('reveal:open', function () {
			  modalBG.unbind('click.modalEvent');
				$('.' + options.dismissmodalclass).unbind('click.modalEvent');
				if(!locked) {
					lockModal();
					if(options.animation == "fadeAndPop") {
						modal.css({'top': $(document).scrollTop()-topOffset, 'opacity' : 0, 'visibility' : 'visible'});
						modalBG.fadeIn(options.animationspeed/2);
						modal.delay(options.animationspeed/2).animate({
							"top": $(document).scrollTop()+topMeasure + 'px',
							"opacity" : 1
						}, options.animationspeed,unlockModal());					
					}
					if(options.animation == "fade") {
						modal.css({'opacity' : 0, 'visibility' : 'visible', 'top': $(document).scrollTop()+topMeasure});
						modalBG.fadeIn(options.animationspeed/2);
						modal.delay(options.animationspeed/2).animate({
							"opacity" : 1
						}, options.animationspeed,unlockModal());					
					} 
					if(options.animation == "none") {
						modal.css({'visibility' : 'visible', 'top':$(document).scrollTop()+topMeasure});
						modalBG.css({"display":"block"});	
						unlockModal()				
					}
				}
				modal.unbind('reveal:open');
			}); 	

			//Closing Animation
			modal.bind('reveal:close', function () {
			  if(!locked) {
					lockModal();
					if(options.animation == "fadeAndPop") {
						modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
						modal.animate({
							"top":  $(document).scrollTop()-topOffset + 'px',
							"opacity" : 0
						}, options.animationspeed/2, function() {
							modal.css({'top':topMeasure, 'opacity' : 1, 'visibility' : 'hidden'});
							unlockModal();
						});					
					}  	
					if(options.animation == "fade") {
						modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
						modal.animate({
							"opacity" : 0
						}, options.animationspeed, function() {
							modal.css({'opacity' : 1, 'visibility' : 'hidden', 'top' : topMeasure});
							unlockModal();
						});					
					}  	
					if(options.animation == "none") {
						modal.css({'visibility' : 'hidden', 'top' : topMeasure});
						modalBG.css({'display' : 'none'});	
					}		
				}
				modal.unbind('reveal:close');
			});     
   	
/*---------------------------
 Open and add Closing Listeners
----------------------------*/
        	//Open Modal Immediately
    	modal.trigger('reveal:open')
			
			//Close Modal Listeners
			var closeButton = $('.' + options.dismissmodalclass).bind('click.modalEvent', function () {
			  modal.trigger('reveal:close')
			});
			
			if(options.closeonbackgroundclick) {
				modalBG.css({"cursor":"pointer"})
				modalBG.bind('click.modalEvent', function () {
				  modal.trigger('reveal:close')
				});
			}
			$('body').keyup(function(e) {
        		if(e.which===27){ modal.trigger('reveal:close'); } // 27 is the keycode for the Escape key
			});
			
			
/*---------------------------
 Animations Locks
----------------------------*/
			function unlockModal() { 
				locked = false;
			}
			function lockModal() {
				locked = true;
			}	
			
        });//each call
    }//orbit plugin call
})(jQuery);
        
