/**
 * Fluid-Grid setup
 */
function setHeights() {
    jQuery('.fluid-grid').each(function () {
        var grid = jQuery(this);
        var cells = jQuery(this).find('.fluid-grid-cell');
        var targets = jQuery(this).find('.fluid-grid-target');
        cells.css('height', 'auto');
        targets.css('height', 'auto');

        var perRow = Math.floor((grid.outerWidth() + 2) / cells.first().outerWidth());
        if (perRow == null || perRow < 2) // If error or only one item in a row
        {
            return true;
        }
        for (var i = 0; i < cells.length; i += perRow) { // Loop through rows
            var maxHeight = 0;
            var row = cells.slice(i, i + perRow);
            // Find max height of row and set to all cells
            row.each(function () {
                var thisHeight = jQuery(this).outerHeight();
                if (jQuery(this).find(".fluid-grid-target").length) {
                    thisHeight = jQuery(this).find(".fluid-grid-target").outerHeight();
                }
                if (thisHeight > maxHeight) {
                    maxHeight = thisHeight;
                }
            });
            row.each(function () {
                if (jQuery(this).find(".fluid-grid-target").length) {
                    jQuery(this).find(".fluid-grid-target").css('height', maxHeight);
                } else {
                    jQuery(this).css('height', maxHeight);
                }
            });
        }
    });
}

/**
 * RSForms No-label setup
 */
function buildNoLabelForm() {
    jQuery("form.no-label input[type='text'], form.no-label textarea, form.no-label select, form.no-label input[type='submit'], form.no-label [id^=g-recaptcha-]").each(function () {
        jQuery(this).parents('.formControls').prev().addClass("sr-only");
        jQuery(this).parents('.formControls').removeClass("col-sm-6");
        var placeholder = jQuery(this).parents('.formControls').prev().html();
        placeholder = placeholder.replace('<strong class="formRequired">(', '').replace(')</strong>', '');
        jQuery(this).attr("placeholder", placeholder);
    });
    jQuery("form.no-label .col-1").removeClass("col-1").addClass("col-md-1");
	jQuery("form.no-label .col-2").removeClass("col-2").addClass("col-md-2");
	jQuery("form.no-label .col-3").removeClass("col-3").addClass("col-md-3");
	jQuery("form.no-label .col-4").removeClass("col-4").addClass("col-md-4");
	jQuery("form.no-label .col-5").removeClass("col-5").addClass("col-md-5");
	jQuery("form.no-label .col-6").removeClass("col-6").addClass("col-md-6");
	jQuery("form.no-label .col-7").removeClass("col-7").addClass("col-md-7");
	jQuery("form.no-label .col-8").removeClass("col-8").addClass("col-md-8");
	jQuery("form.no-label .col-9").removeClass("col-9").addClass("col-md-9");
	jQuery("form.no-label .col-10").removeClass("col-10").addClass("col-md-10");
	jQuery("form.no-label .col-11").removeClass("col-11").addClass("col-md-11");
	jQuery("form.no-label .col-12").removeClass("col-12").addClass("col-md-12");
}

/**
 * Setup Smooth Scroll to work on links with anchor tags
 */
function setupSmoothScroll() {
    // jQuery('a[href*="#"]:not([href=#])').not('[data-toggle="collapse"]').click(function () {
        // if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            // scrollTo(this.hash);
            // return false;
        // }
    // });
}

/**
 * Smooth Scroll function - Scroll to tag
 */
function scrollTo(hash) {
    var target = jQuery(hash);
    if (target.length) {
        var newTop = target.offset().top - 30;
        if (jQuery(".main-menu-affix").length || jQuery(".main-menu-fixed")) { // If sticky nav
            if (jQuery("#mainmenu").length) {
                newTop -= jQuery("#mainmenu").height();
            }
            if (jQuery("#wrapper-header").length) {
                newTop -= jQuery("#wrapper-header").height();
            }
        }
        jQuery('html,body').animate({
            scrollTop: newTop
        }, 750);
    }
}

jQuery(document).ready(function () {
    buildNoLabelForm();
    setHeights();
    setupSmoothScroll();
});

jQuery(window).load(function () {
    setHeights();
    // Auto smooth scroll to #tag
    // if (window.location.hash) {
        // scrollTo(window.location.hash);
    // }
});

jQuery(window).resize(function () {
    setHeights();
});