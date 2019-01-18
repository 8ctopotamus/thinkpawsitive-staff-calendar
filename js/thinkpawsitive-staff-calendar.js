(function($) {
  const $loading = $('#loading')
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
    eventClick: showEventDetails,
    eventAfterAllRender: () => $loading.hide()
  })

  function showEventDetails(calEvent, jsEvent, view) {
    modalContent.innerHTML = `<div>
      <em class="${calEvent.className}--text">${calEvent.category}</em>
      <h3>${calEvent.product_name}</h3>
      <div class="start-end-times">
        <span>
          Start<br/>
          <strong>${calEvent.start}</strong>
        </span>
        <span>
          End<br/>
          <strong>${calEvent.end}</strong>
        </span>
      </div>
      <h4>${calEvent.title}</h4>
      <p><a href="tel: ${calEvent.phone}" title="Call ${calEvent.phone}">${calEvent.phone}</a></p>
    </div>`
    modal.style.display = "block"
    $(this).css('border-color', 'blue')
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
