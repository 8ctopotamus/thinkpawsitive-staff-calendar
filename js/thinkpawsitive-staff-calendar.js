(function($) {
  // NOTE: the 'tp_bookings' variable comes from the shortcode's php loop.
  if (tp_bookings) {
    $('#thinkpawsitive-staff-calendar').fullCalendar({
      header: {
        left: 'today prev,next',
        center: 'title',
        right: 'month,basicWeek,basicDay'
      },
      // defaultDate: '2019-01-12',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: tp_bookings,
    })
  }
})(jQuery)
