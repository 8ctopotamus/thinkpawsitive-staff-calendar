(function($) {
  const modal = document.getElementById('thinkpawsitive-modal')
  const closeBtn = document.getElementsByClassName("close")[0]
  const modalContent = document.getElementById('thinkpawsitive-booking-details')
  const calendar = $('#thinkpawsitive-staff-calendar').fullCalendar({
    header: {
      left: 'today prev,next',
      center: 'title',
      right: 'month,basicWeek,basicDay'
    },
    defaultDate: new Date(Date.now()),
    navLinks: true, // can click day/week names to navigate views
    editable: false,
    eventLimit: true, // allow "more" link when too many events
    events: tp_bookings, // this variable comes from the shortcode's php loop
    eventClick: showEventDetails
  })

  function showEventDetails(calEvent, jsEvent, view) {
    modalContent.innerHTML = `<p>${calEvent.title} - ${calEvent.product_name}</p>`
    modal.style.display = "block"
    $(this).css('border-color', 'red')
  }

  function closeModal() {
    modalContent.innerHTML = ''
    modal.style.display = "none"
  }

  closeBtn.onclick = closeModal

  window.onclick = function(event) {
    if (event.target == modal) {
      closeModal()
    }
  }

})(jQuery)
