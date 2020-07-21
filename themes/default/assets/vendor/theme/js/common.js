/**
 * Elektron - An Admin Toolkit
 * @version 0.3.1
 * @license MIT
 * @link https://github.com/onokumus/elektron#readme
 */
'use strict';

// Bootstrap Tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})


// Bootstrap Popover
$(function () {
    $('[data-toggle="popover"]').popover()
})
$('.popover-dismiss').popover({
    trigger: 'focus'
})


// Todays Date
$(function () {
    var interval = setInterval(function () {
        var momentNow = moment();
        $('#today-date').html(momentNow.format('MMMM . DD') + ' '
                + momentNow.format('. dddd').substring(0, 5).toUpperCase());
    }, 100);
});


// Task list
$('.task-list').on('click', 'li.list', function () {
    $(this).toggleClass('completed');
});


// Loading
$(function () {
    $(".loading-wrapper").fadeOut(2000);
});


/* copy from mics.js*/
$(function () {
  var body = $('body');
  var sidebar = $('.sidebar');

  //Close other submenu in sidebar on opening any

  sidebar.on('show.bs.collapse', '.collapse', function () {
    sidebar.find('.collapse.show').collapse('hide');
  });


  //Change sidebar and content-wrapper height
  applyStyles();

  function applyStyles() {
    //Applying perfect scrollbar
    if (!body.hasClass("rtl")) {
      if (body.hasClass("sidebar-fixed")) {
        var fixedSidebarScroll = new PerfectScrollbar('#sidebar .nav');
      }
    }
  }

  $('[data-toggle="minimize"]').on("click", function () {
    if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
      body.toggleClass('sidebar-hidden');
    } else {
      body.toggleClass('sidebar-icon-only');
    }
  });

});

$(function () {
  $('[data-toggle="offcanvas"]').on("click", function () {
    $('.sidebar-offcanvas').toggleClass('active')
  });
});

/*copy from hoverable-collapse.js */
$(document).on('mouseenter mouseleave', '.sidebar .nav-item', function(ev) {
  var body = $('body');
  var sidebarIconOnly = body.hasClass("sidebar-icon-only");
  var sidebarFixed = body.hasClass("sidebar-fixed");
  if (!('ontouchstart' in document.documentElement)) {
    if (sidebarIconOnly) {
      if (sidebarFixed) {
        if (ev.type === 'mouseenter') {
          body.removeClass('sidebar-icon-only');
        }
      } else {
        var $menuItem = $(this);
        if (ev.type === 'mouseenter') {
          $menuItem.addClass('hover-open')
        } else {
          $menuItem.removeClass('hover-open')
        }
      }
    }
  }
});