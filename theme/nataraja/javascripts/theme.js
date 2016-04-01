/* ========================================================================

YUNG: theme.js
Main Theme JS file

@Author: Andrew ch 
@URL: http://andrewch.eu
 
=========================================================================
 */

'use strict';


jQuery(document).ready(function( $ ) {
	
	// Loader & Masonry
	//===================

	$(window).on("load", function(){
		setTimeout(function(){
			$('.loading').addClass("hidden");
			$('.loader-logo').addClass("slideOutUp");
			$('.loader').addClass("slideOutUp");
			$('body').addClass("body-animated");
		}, 900);
		$('.masonry-grid').isotope({
			itemSelector: '.masonry-grid-item'
		});
	});

	// Playback Player Widget & Album Single
	//===================
	
	$(function(){
		$('.playback-widget').mediaelementplayer({
			loop: false,
			shuffle: false,
			audioWidth: '100%',
			playlist: true,
			playlistposition: 'bottom',
			audioHeight: 30,
			features: ['playlistfeature', 'prevtrack', 'playpause', 'nexttrack', 'playlist', 'current', 'progress', 'volume'],
			keyActions: []
		});
		$('.album-single-widget').mediaelementplayer({
			loop: false,
			shuffle: false,
			audioWidth: '100%',
			playlist: false,
			playlistposition: 'bottom',
			audioHeight: 30,
			features: ['playpause'],
			keyActions: []
		});
	});

	// Magnific
	//===================

	$('.zoom-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button
		tPrev: 'Previous (Left arrow key)', // title for left button
		tNext: 'Next (Right arrow key)', // title for right button
		mainClass: 'mfp-with-zoom mfp-img-mobile',
		image: {
			verticalFit: true,
			navigateByImgClick: true,
			titleSrc: function(item) {
				return item.el.attr('title');
			}
		},
		gallery: {
			enabled: true,
			arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button
			tPrev: 'Previous (Left arrow key)', // title for left button
			tNext: 'Next (Right arrow key)', // title for right button
			callbacks: {
				buildControls: function() {
					// re-appends controls inside the main container
					this.arrowLeft.appendTo(this.contentContainer);
					this.arrowRight.appendTo(this.contentContainer);
					console.log() ;
				}
			}

		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button
			tPrev: 'Previous (Left arrow key)', // title for left button
			tNext: 'Next (Right arrow key)', // title for right button
			opener: function(element) {
				return element.find('img');
			}
		},
	});

	// Portfolio Overlay effect
	//===================

	$(".masonry-grid-item, .grid-item").on({
		mouseenter: function () {
			TweenMax.to($(this).find('h2'), .4, {opacity: '1', y: '0', delay:.2, ease: Quart.easeOut});
			TweenMax.to($(this).find('.item-overlay'), .3, {opacity: '1', ease: Quart.easeOut});
			TweenMax.to($(this).find('p'), .4, {opacity: '1', y: '0', delay: .4, ease: Quart.easeOut});
		},
		mouseleave: function () {
			TweenMax.to($(this).find('h2'), .4, {opacity: '0', y: '-30', delay: .2, ease: Quart.easeOut});
			TweenMax.to($(this).find('.item-overlay'), .3, {opacity: '0', ease: Quart.easeOut});
			TweenMax.to($(this).find('p'), .4, {opacity: '0', y: '-30', delay:.4, ease: Quart.easeOut});
		}
	});

	// Header Opacity
	//===================

	$(window).on('scroll', function() {
		var element = $('.header-introduction-small, .header-introduction');
		var ft = $(this).scrollTop();
		element.css({ 'opacity' : (1 - ft/800) });
	});

	// Countdown
	//===================

	$('.counter').counterUp({
		delay: 10,
		time: 1000
	});

	// Sidebar Menu
	//===================

	// Dropdown

	( function( $ ) {
		$('#cssmenu ul ul li:odd').addClass('odd');
		$('#cssmenu ul ul li:even').addClass('even');
		$('#cssmenu > ul > li > a').on('click', function() {
			$('#cssmenu li').removeClass('active');
			$(this).closest('li').addClass('active'); 
			var checkElement = $(this).next();
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				$(this).closest('li').removeClass('active');
				checkElement.slideUp('normal');
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('#cssmenu ul ul:visible').slideUp('normal');
				checkElement.slideDown('normal');
			}
			if($(this).closest('li').find('ul').children().length == 0) {
				return true;
			} else {
				return false;   
			}     
		});
	} )( jQuery ); 

	// Menu

	var tl = new TimelineLite();  
	tl.set($(".navigation-menu"), {y:0,x:0,autoAlpha:0});

	$('#navicon').on('click', function () {
		var tl = new TimelineLite();  
		var clicks = $(this).data('clicks');
		if (clicks) {

			// Second state
			// ----
			tl.staggerTo($(".navigation-menu"),.5, {y:0,autoAlpha:0, repeat:0, ease: Power3.easeOut });
			tl.staggerTo($(".navigation-menu > ul > li"),.5, {y:0,opacity:0, repeat:0, ease: Power3.easeOut });
			$('body').removeClass('overflow-hidden');

		  } else {
			// First state
			// ----
 			tl.staggerTo($(".navigation-menu"),.5, {y:0,autoAlpha:1, repeat:0, ease: Power3.easeOut });
			tl.staggerTo($(".navigation-menu > ul > li"),.8, {y:-50, opacity:1, repeat:0, ease: Power3.easeOut }, .2);
			$('body').addClass('overflow-hidden');
		  }
		  $(this).data("clicks", !clicks);
	});
	
	// Hamburger Icon Animation

	(function () {
		var active;
		active = true;
		$('#navicon').on('click', function () {
			if (active === true) {
				$(this).removeClass('inactive').addClass('active');
				active = false;
			} else {
				$(this).removeClass('active').addClass('inactive');
				active = true;
			}
		});
	}.call(this));


	// On Scroll Animations
	//===================

	function onScrollInit( items, trigger ) {
		items.each( function() {
			var osElement = $(this),
				osAnimationClass = osElement.attr('data-os-animation'),
				osAnimationDelay = osElement.attr('data-os-animation-delay');
		 
			osElement.css({
				'-webkit-animation-delay':  osAnimationDelay,
				'-moz-animation-delay':     osAnimationDelay,
				'animation-delay':          osAnimationDelay
			});
		 
			var osTrigger = ( trigger ) ? trigger : osElement;
		 
			osTrigger.waypoint(function() {
				osElement.addClass('animated').addClass(osAnimationClass);
			},{
				triggerOnce: true,
				offset: '90%'
			});
		});
	}

	setTimeout(function() {
		onScrollInit( $('.os-animation') );
		onScrollInit( $('.staggered-animation'), $('.staggered-animation-container') );
	}, 600);
	
	// Back to top button
	//===================

	$('.go-top').on('click', function (event) {
		event.preventDefault();
		{$('html, body').velocity('scroll',{duration: 1000, offset:0});}
	});

});
