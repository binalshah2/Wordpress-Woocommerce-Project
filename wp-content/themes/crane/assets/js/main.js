$(document).ready(function () {
	
	"use strict"
	
	$('.menu').click(function(){
		$('nav').toggleClass('active');
		console.log("click");
	});

	$('.menu-icon').click(function(){
		$('.menu-icon').toggleClass('activated')
	});

	$('.menu-item-has-children').click(function(e){
		// e.preventDefault();
		$(this).find('ul').toggleClass("show-child-menu");
	});
		  
	$('#next').hover(function () {  
		$('#sliderWrapper').animate({scrollLeft: $(this).siblings("#sliderWrapper").width()}, 3000);
	}, function() {
		$('#sliderWrapper').stop();
	});

	$('#prev').hover(function () {  
		$('#sliderWrapper').animate({scrollLeft: 0 }, 3000);
	}, function() {
		$('#sliderWrapper').stop();
	});

	$('#next2').hover(function () {  
		$('#sliderWrapper2').animate({scrollLeft: $(this).siblings("#sliderWrapper2").width()}, 3000);
	}, function() {
		$('#sliderWrapper2').stop();
	});

	$('#prev2').hover(function () {  
		$('#sliderWrapper2').animate({scrollLeft: 0 }, 3000);
	}, function() {
		$('#sliderWrapper2').stop();
	});

	$('#next3').hover(function () {  
		$('#sliderWrapper3').animate({scrollLeft: $(this).siblings("#sliderWrapper3").width()}, 3000);
	}, function() {
		$('#sliderWrapper3').stop();
	});

	$('#prev3').hover(function () {  
		$('#sliderWrapper3').animate({scrollLeft: 0 }, 3000);
	}, function() {
		$('#sliderWrapper3').stop();
	});

	$('#next4').hover(function () {  
		$('#sliderWrapper4').animate({scrollLeft: $(this).siblings("#sliderWrapper4").width()}, 3000);
	}, function() {
		$('#sliderWrapper4').stop();
	});

	$('#prev4').hover(function () {  
		$('#sliderWrapper4').animate({scrollLeft: 0 }, 3000);
	}, function() {
		$('#sliderWrapper4').stop();
	});

	$('#next5').hover(function () {  
		$('#sliderWrapper5').animate({scrollLeft: $(this).siblings("#sliderWrapper5").width()}, 3000);
	}, function() {
		$('#sliderWrapper5').stop();
	});

	$('#prev5').hover(function () {  
		$('#sliderWrapper5').animate({scrollLeft: 0 }, 3000);
	}, function() {
		$('#sliderWrapper5').stop();
	});

	$('#next6').hover(function () {  
		$('#sliderWrapper6').animate({scrollLeft: $(this).siblings("#sliderWrapper6").width()}, 3000);
	}, function() {
		$('#sliderWrapper6').stop();
	});

	$('#prev6').hover(function () {  
		$('#sliderWrapper6').animate({scrollLeft: 0 }, 3000);
	}, function() {
		$('#sliderWrapper6').stop();
	});

	$('#next7').hover(function () {  
		$('#sliderWrapper7').animate({scrollLeft: $(this).siblings("#sliderWrapper7").width()}, 3000);
	}, function() {
		$('#sliderWrapper7').stop();
	});

	$('#prev7').hover(function () {  
		$('#sliderWrapper7').animate({scrollLeft: 0 }, 3000);
	}, function() {
		$('#sliderWrapper7').stop();
	});



	/*Testimonials JS*/

	$(document).on('ready', function() {


		$(".center").slick({
			dots: true,
			arrows: false,
			infinite: true,
			centerMode: true,
			slidesToShow: 3,
			slidesToScroll: 1,
			centerPadding: '0px',
			autoplay: true,
			responsive: [
			{
				breakpoint: 992,
				settings: {
					arrows: false,
					centerMode: false,
					centerPadding: '40px',
					slidesToShow: 1,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 768,
				settings: {
					arrows: false,
					centerMode: false,
					// centerPadding: '40px',
					slidesToShow: 1,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 480,
				settings: {
					arrows: false,
					centerMode: false,
					// centerPadding: '40px',
					slidesToShow: 1,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 360,
				settings: {
					arrows: false,
					centerMode: false,
					centerPadding: '0px',
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
			]
		});

		$('body').on('click',".open-category-details",function(){
		// $(".open-category-details").click(function(){

			var banner_id = $(this).attr("data-id");
			var banenrNavigation = "<li><a href='javascript:void(0)' class='back-to-banner'><i class='fa fa-chevron-circle-left'></i></a></li>";
			// var descrptionButtons = "";
			$.each(headerCategories,function(index,category){
				if(category.term_id == banner_id){
					// if(category.pdf.trim() != ''){
					// 	descrptionButtons += "<a class='banner-btn' target='_new' href='" + category.pdf + "'>Download</a>";
					// }
					// descrptionButtons += "<a class='banner-btn open-rental-contact-form' target='_new' data-subject='Inquiry for rent " + category.name + "' href='javascript:void(0);'>Rent Now</a>";
					$('.banner_image').fadeOut("fast",function(){
						$(this).attr("src",category.image).fadeIn('slow');
					});
					$(".banner_name").fadeOut("fast",function(){
						$(this).html(category.name).fadeIn('slow');
					});
					// $(".banner_buttons").fadeOut("fast",function(){
					// 	$(this).html(descrptionButtons).fadeIn('slow');
					// });
					$(".banner_description").fadeOut("fast",function(){
						$(this).html(category.description).fadeIn('slow');
					});
				}else{
					banenrNavigation += "<li><a href='javascript:void(0)' class='open-category-details' data-id='" + category.term_id + "'>" + category.name + "</a></li>";
				}
			});
			$(".detail-links").html(banenrNavigation);
			$(".banner-slide").animate({
				marginLeft: '0%'
			}, 400);
		});

		$('body').on('click',".back-to-banner",function(){
			$(".banner-slide").animate({
				marginLeft: '-100%'
			}, 400);
		});
		
		$('.mascot').animate({top: '-45px' }, 1000,function(){
			$('.mascot').animate({top: '-65px' }, 200,function(){
				$('.mascot').animate({top: '-45px' }, 100,function(){
					$('.mascot').animate({top: '-55px' }, 200,function(){
						$('.mascot').animate({top: '-45px' }, 150,function(){
						});
					});
				});
			});
		});
		
		$(".tab-content .active .product").toggleClass('slide-out');
		$(".tab-content .active .product").toggleClass('slide-in');

		$(".tabs-nav a").click(function(){
			var slider = $(this).attr('data-slider');
			var tab = $(this).attr('href');
			$(".slider-" + slider + " .tab-content .active .product").removeClass('slide-in');
			window.setTimeout(function() {
				$('.' + slider + '-product').removeClass('in active');
				$('#' + tab).addClass('in active');
			}, 200);
			
			window.setTimeout(function() {
				$('#' + tab + ' .product').toggleClass('slide-out');
				$('#' + tab + ' .product').toggleClass('slide-in');
				
			}, 500);
		});
	});

	$('body').on('click',".open-rental-contact-form",function(){
		$("html").css('overflow-y','hidden');
		$("input[name='your-subject']").val($(this).attr('data-subject'));
		$(".rental-form").animate({
			left: '0'
		}, 800);
	});

	$(".close-form i").click(function(){
		$("html").css('overflow-y','auto');
		$(".rental-form").animate({left: '-200%'}, 800);
		$(".detail-page").animate({left: '-200%'}, 800);
		clearInterval(autoSlider);
	});

	$(".product-details h3, .product-details p").click(function(){
		$("html").css('overflow-y','hidden');
		var $this = $(this).parent(); 

		$(".detail-page .product-slider").html($($this).children('.slides').html());
		$(".detail-page .banner_name").html($($this).children('.product-title').html());
		
		$(".detail-page .banner_buttons").html($($this).children('.banner_buttons').html());

		$(".detail-page .banner_description").html($($this).children('.description').html());

		$(".detail-page").animate({left: '0'}, 400);

		/* product slider [start] */
		// $('#pagination-wrap ul').html('');
		$('#slider-wrap ul#slider').css('left', 0);
		if($this.find('#slider-wrap ul li').length > 1){
			pos = 0;
			totalSlides = $('.product-slider ul li').length;
			sliderWidth = $('.product-slider').width();
			$('#slider-wrap ul#slider').width(sliderWidth*totalSlides);
			$('#slider-wrap ul li').width(sliderWidth);
			$('#slider-wrap ul li img').width(sliderWidth);
			$.each($this.find('#slider-wrap ul li'), function() { 		
				//create a pagination
				var li = document.createElement('li');
				// $('#pagination-wrap ul').append(li);	   
			});
			// countSlides();
			pagination();
			autoSlider = setInterval(slideRight, 3000);
		}
		 /* product slider [end] */
	});

	! function ($) {

		var fullWidth = $(".tab-content").width();
		$(".sliderWrapper").mousemove(function (e) {
			var scrollWidth = $(this)[0].scrollWidth;
			if(fullWidth < scrollWidth){
				var screenMove = (100 * e.pageX) / fullWidth;
				var divMove = (screenMove * (scrollWidth - fullWidth)) / 100;
				$(".sliderWrapper").scrollLeft(divMove);				
			}
		});
	
	}(window.jQuery);

	$('body').on('click',"#next",function(){
		slideRight();
	});
	
	$('body').on('click',"#previous",function(){
		slideLeft();
	});
	$('.product-slider').hover(
		function(){ $(this).addClass('active'); clearInterval(autoSlider); }, 
		function(){ $(this).removeClass('active'); autoSlider = setInterval(slideRight, 3000); }
	);

		
	function slideLeft(){
		pos--;
		if(pos==-1){ pos = totalSlides-1; }
		$('.product-slider #slider-wrap ul#slider').css('left', -(sliderWidth*pos)); 	
		
		//*> optional
		// countSlides();
		pagination();
	}

	function slideRight(){
		pos++;
		if(pos==totalSlides){ pos = 0; }
		$('.product-slider #slider-wrap ul#slider').css('left', -(sliderWidth*pos)); 
		
		//*> optional 
		// countSlides();
		pagination();
	}

	// function countSlides(){
	// 	$('.product-slider #counter').html(pos+1 + ' / ' + totalSlides);
	// }

	function pagination(){
		// $('.product-slider #pagination-wrap ul li').removeClass('active');
		// $('.product-slider #pagination-wrap ul li:eq('+pos+')').addClass('active');
	}
});

var pos = 0;
var totalSlides = 0;
var sliderWidth = 0;
var autoSlider; 
var currentSldier; 

		
	