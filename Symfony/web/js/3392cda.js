/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */
(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery)

/*
 * 	Ribbon Slider 1.0 - jQuery plugin
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
	$.fn.ribbonSlider = function (options) {
		var options = jQuery.extend ({
			speed: 300,
			pause: 0,
			handlePlayer: true,
			showControls: true
		}, options);
		
		var slider=$(this);
		var slides=$(this).find('li');
		var ribbon=$(this).find('ul');
		var sliderWidth=slider.width();
		var ribbonWidth=ribbon.width();
		var disabled=false;		
		var autoSlide;
		
		function setWidth() {
			var ribbonWidth=0;
			slides.each(function() {
				ribbonWidth+=$(this).outerWidth(true);
			});			
			ribbon.width(ribbonWidth);
		}
		
		function setCurrentSlide(slide) {
			slides.removeClass('current');
			slide.addClass('current');
		}
		
		function checkLimit() {
			slider.parent().removeClass('limit-left limit-right');
			if(ribbon.position().left>=0) {
				slider.parent().addClass('limit-left');
			} else if(ribbon.position().left<=sliderWidth-ribbonWidth) {
				slider.parent().addClass('limit-right');
				return false;
			}
			return true;
		}
		
		function addControls() {	
			//add controls
			slider.parent().append('<a class="arrow arrow-left" href="#"></a><a class="arrow arrow-right" href="#"></a>');
			
			//handle controls
			slider.parent().find('.arrow').click(function() {	
				if($(this).hasClass('arrow-left')) {
					animate(slides.filter('.current').prev('li'));
				} else {				
					animate(slides.filter('.current').next('li'));
				}
				
				return false;
			});
		}
		
		function addInterface() {
			//mousewheel support
			if ($.isFunction($.fn.mousewheel)) {
				slider.mousewheel(function(event, delta) {
					if(delta>0) {
						animate(slides.filter('.current').prev('li'));
					} else {
						animate(slides.filter('.current').next('li'));
					}
					
					clearInterval(autoSlide);					
					return false;
				});
			}
			
			//draggable support
			if ($.isFunction($.fn.draggable)) {
				ribbon.draggable({ axis: 'x', drag: function() {
					if(disabled) {
						return false;
					}
				
					if(ribbon.position().left>0 || (ribbonWidth+ribbon.position().left)<sliderWidth) {
						disabled=true;
						
						//limit position
						var limitPos=-(ribbonWidth-sliderWidth);						
						if(ribbon.position().left>0) {
							limitPos=0;
						}
						
						ribbon.animate({ left: limitPos }, options.speed, function() {							
							disabled=false;
							
							//check limit
							checkLimit();
						});
					}
				
					//find current slide
					var slideIndex=0;
					slides.each(function() {
						if($(this).position().left>-ribbon.position().left) {
							slideIndex=$(this).index();
							return false;					
						}
					});
					
					//set current slide
					setCurrentSlide(slides.filter(':eq('+slideIndex+')'));
					
					//stop rotation
					clearInterval(autoSlide);
					
				}, stop: function() {
					//check limit
					checkLimit();
				}
				});
			}			
		}
		
		function animate(slide) {
			//disable slider
			if (disabled==true || slide.length==0) {
				return;
			}
			
			//set step
			var step=slide.position().left;			
			if((ribbonWidth-step)<sliderWidth) {
				step=ribbonWidth-sliderWidth;
			}
			
			//animate slider
			ribbon.stop().animate({ left: -step }, options.speed, function() {
				//set current slide
				if((ribbonWidth-step)>sliderWidth) {
					setCurrentSlide(slide);			
				}
				
				//check limit
				checkLimit();
				
				disabled=false;
			});			
		}
		
		//rotate slider
		function rotate() {			
			autoSlide=setInterval(function() {
				if(checkLimit()) {
					animate(slides.filter('.current').next('li'));
				} else {				
					animate(slides.filter(':first-child'));
				}
			},options.pause);
			
			slider.add(slider.parent().find('.arrow')).click(function() {
				clearInterval(autoSlide);
			});
		}
	 
		function init() {		
			//set first slide
			slides.filter(':first').addClass('current');
		
			//set ribbon width
			setWidth();
			
			//add slider controls
			if(options.showControls) {
				addControls();
			}
						
			//add devices interface
			addInterface();
			
			//rotate
			if(options.pause!=0) {
				rotate();
			}
		}
		
		function resizeSlider() {
			setWidth();
			sliderWidth=slider.width();
			ribbonWidth=ribbon.width();
		}
		
		//init slider
		init();
		
		//window resize event
		$(document).ready(function() {
			resizeSlider();			
		});
	}
})(jQuery);