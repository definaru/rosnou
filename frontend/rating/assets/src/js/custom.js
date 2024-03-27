// ANIMATIONS

wow = new WOW(
	{
		boxClass: 'wow',      // default
		animateClass: 'animated', // default
		offset: 0,          // default
		mobile: true,       // default
		live: true        // default
	}
)
wow.init();

// BOOTSTRAP TOOGLES & POPOVERS
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
});

$(function () {
	$('[data-toggle="popover"]').popover()
});

// FIX TOP PANEL
$(window).scroll(function () {
	// on scroll, make the navigation fixed
	if ($(this).scrollTop() > 100) {
		$('#main-nav').addClass('fixed-top');
	} else {
		$('#main-nav').removeClass('fixed-top');
	}
});


// CLOSE BUTTON
$(document).ready(function () {
	$("a.close").click(function () {
		$(this).parent().slideUp("slow");
		return false;
	});
});

//ARTICLES on HOVER
$(document).ready(function () {
	$("#homearticles .topic a").hover(function () {
		$(this).offsetParent().addClass("borderblue");
	}, function () {
		$(this).offsetParent().removeClass("borderblue");
	});
});

$(document).ready(function () {
	$("#experts .panel a").hover(function () {
		$(this).offsetParent().find(".panel").addClass("borderblue");
	}, function () {
		$(this).offsetParent().find(".panel").removeClass("borderblue");
	});
});


$(document).ready(function () {
	$(".topic-item .overlay-entry").hover(function () {
		$(this).offsetParent().siblings().addClass("noimg");
	}, function () {
		$(this).offsetParent().siblings().removeClass("noimg");
	});
});


//GRAY BLOCK on the MAIN PAGE
$(document).ready(function () {
	function ImgSize() {
		var height = $(".topic-thumb img").height();
		var top = height + 72;
		$('#homenews:before').css({top: top + 'px'});
	}

	$(window).resize(function () {
		ImgSize();
	});

});

//FORM STYLE Dimox
/*$(document).ready(function(){
 $('.dimox input, .dimox select, .dimox textarea').styler();
 $('.dimox .jq-file__browse').addClass('btn btn-green');
 });*/

//FORM STYLE Dimox
$(document).ready(function () {
	$('.dimox input').styler();
});

//SELECT STYLE select2.js
$(document).ready(function () {
    bindSelect2($('.rnselect'));

	$('#site-category').on('change', function(){
		window.location = '/uchastniki/?fields_filter[site_category]=' + $(this).val();
	});

});

function bindSelect2(selector) {
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }

        var $state = $(
            '<div class="option"><div class="option-text"> ' + state.text + '</div></div>'
        );
        return $state;
    }

    selector.select2({
        //minimumResultsForSearch: Infinity,
        templateResult: formatState
    });
}

//tableFilter, tablesorter
$(document).ready(function() {
	$('#tablesorter').tableFilter();
	$('#tablesorter').tablesorter();
});

