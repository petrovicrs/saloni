jQuery(document).ready(function($) {

	function showLoader() {
		var $loader = $(document.createElement("div")).attr("id", "calendar-ajax-loader").show();
		$("#calendar-block-wrapper").append($loader);
	}
	function hideLoader() {
		jQuery("#calendar-ajax-loader").remove();
	}

	showLoader();

	/*===================== TOOLTIP ============================*/
	var viewportWidth = $(window).width();
	var viewportHeight = $(window).height();
	var narrowScreen = false;

	$(window).resize(function() {
		viewportWidth = $(window).width();
		viewportHeight = $(window).height();
		setWeekdaysWidth();
		adjustViewsHeight();
		__setTooltipPosition();
	});
	setWeekdaysWidth();

	__setTooltipPosition();



	/*================== NAVIGATION ========================*/
	$("#calendar-block .calendar-nav").live("click", function(e) {
		showLoader();
		var date = $(this).closest("a").attr("data-goto");
		var language = $("#calendar-block").attr("lang");
		$.ajax({
			url: Drupal.settings.basePath + "calendar-navigation",
			type: "POST",
			dataType: "json",
			data: "date=" + date + "&language=" + language,
			success: function(data) {
				hideLoader();
				$("#calendar-block-wrapper").html(data.calendar);
				$("#calendar-read-more").attr("href", $(".calendar-block-title a").attr("href"));
				setWeekdaysWidth();
				if($(window).width() < 1200 ){
					__mobile_settings();
				}
			}
		});

		e.preventDefault();
		return false;
	});

	//============= BACK TO MONTHLY VIEW LINK ==========================
	$("#calendar-block .calendar-daily-view-margin .level-up").live("click", function(e) {
		showLoader();

		var date = $("#calendar-block-wrapper .calendar-block-title a").attr("data-goto");
		var language = $("#calendar-block").attr("lang");
		$.ajax({
			url: Drupal.settings.basePath + "calendar-navigation",
			type: "POST",
			dataType: "json",
			data: "date=" + date + "&language=" + language,
			success: function(data) {
				showLoader();
				$("#calendar-block-wrapper").html(data.calendar);
				$("#calendar-read-more").attr("href", $(".calendar-block-title a").attr("href"));
				setWeekdaysWidth();
			}
		});

		e.preventDefault();
		return false;
	});

	// Prevent link around navbar
	$("#calendar-block th.nav a").live("click", function(e) {
		e.preventDefault();
		return false;
	});
	/*============= READ MORE LINK ===============*/
	$("#calendar-read-more").attr("href", $(".calendar-block-title a").attr("href"));

	hideLoader();

	// GO back link
	$("#calendar-block .calendar-daily-view-margin .level-up").live('touchstart', function(e) {
		  $(this).trigger("click");
		  e.preventDefault();
	});

	/*======== HELPER FUNCTIONS ==================*/

	/**
	 * Trim weekday's name
	 */
	function setWeekdaysWidth() {
		if(viewportWidth <= 780) {
			$("#calendar-block-wrapper #calendar-block .day-title-cell").each(function() {
				$(this).html($(this).attr("data-name").substr(0, 3));
			});
		}
		else {
			$("#calendar-block-wrapper #calendar-block .day-title-cell").each(function() {
				$(this).html($(this).attr("data-name"));
			});
		}
	}

	/**
	 * Adjusts height of monthly/daily view on resize, rotate etc.
	 */
	function adjustViewsHeight() {
		$blockHeight = $("#calendar-block-wrapper").height();
		$title = $("#block-calendar-calendar .calendar-view-title");
		var offset = $title.height() + parseInt($title.css("marginTop")) + parseInt($title.css("marginBottom") + parseInt($("#calendar-block-wrapper .calendar-daily-view").css("margin-bottom")));
		$("#calendar-view").css("height", parseInt($blockHeight - offset) + "px");
		$("#calendar-view").slimScroll({
			position: 'right',
			railVisible: false,
			alwaysVisible: false
		});
	}

	function __mobile_settings(){
		var windowWidth = $(window).width();
		$('#calendar-block .day-cell-event').each(function() {
			var $cell = $(this);
			$tooltip = $($(this).find(".ms-tooltip"));
			var tooltipHeight = $tooltip.height();
			$tooltip.width(3 * $cell.width());
			if($cell.offset().left - $tooltip.width() >= 0){
				$tooltip.css({
					top: parseInt($cell.height()+2) +"px",
					right: "5px",
					display: "block"
				});
			}else{
				$tooltip.css({
					top: parseInt($cell.height()+2) +"px",
					right: "auto",
					left: '0px',
					display: "block"
				});
			}
			$tooltip.removeClass("activeTooltip");
			$tooltip.removeClass("tdTooltipHover");
			$tooltip.addClass("tooltipHover");
			$tooltip.addClass("inactiveTooltip");
		});
		$('#calendar-block .day-cell-event').click(function() {
			if(!$(this).find('.ms-tooltip').hasClass('activeTooltip')){
				$('.ms-tooltip.activeTooltip').removeClass("activeTooltip").addClass("inactiveTooltip");
				$(this).find('.ms-tooltip.inactiveTooltip').removeClass("inactiveTooltip").addClass("activeTooltip");
			}
		});

		$(document).live('touchstart',function (e){
		    var container = $("#calendar-block .day-cell-event .activeTooltip");
		    if (!container.is(e.target) && container.has(e.target).length === 0){
		        container.removeClass("activeTooltip").addClass("inactiveTooltip");
		    }
		});
	}


	function __setTooltipPosition(){
		var calendar_block = $('#calendar-block');
		var $tooltip;
		var windowWidth = $(window).width();
		if(windowWidth >= 1200 ){
			$('#calendar-block .day-cell-event').live("mouseover", function(ev) {
				var $cell = $(this);
				$tooltip = $($(this).find(".ms-tooltip"));
				var tooltipHeight = $tooltip.height();

				// if($cell.offset().left + 3 * $cell.width() > windowWidth){
					$tooltip.css({
						top: parseInt($cell.height()+5) +"px",
						left: "0",
						display: "block"
					});
				// }else{
				// 	$tooltip.css({
				// 		top: parseInt($cell.height()+5) +"px",
				// 		right: parseInt(- 2 * $cell.width()) + "px",
				// 		display: "block"
				// 	});
				// }
				$tooltip.removeClass("inactiveTooltip");
				$tooltip.removeClass("tooltipHover");
				$tooltip.addClass("tdTooltipHover");
				$tooltip.addClass("activeTooltip");
			}).live("mouseout", function() {
				$tooltip.removeClass("tdTooltipHover");
				if(!$tooltip.hasClass("tooltipHover")){
					$tooltip.addClass("inactiveTooltip");
				}
			});

			$('#calendar-block .day-cell-event .ms-tooltip.activeTooltip').live("mouseover", function(){
				$(this).removeClass("inactiveTooltip");
				$(this).removeClass("tdTooltipHover");
				$(this).addClass("tooltipHover");
				$(this).addClass("activeTooltip");
			}).live("mouseout", function() {
				$(this).removeClass("tooltipHover")
				if(!$(this).hasClass("tdTooltipHover")){
					$(this).addClass("inactiveTooltip");
				}
			});
		}else{
			__mobile_settings();
		}
	}

});
