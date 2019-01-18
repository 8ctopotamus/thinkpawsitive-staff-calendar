(function($) {
  const modal = document.getElementById('thinkpawsitive-modal')
  const closeBtn = document.getElementsByClassName("close")[0]
  const modalContent = document.getElementById('thinkpawsitive-booking-details')
  const $loading = $('#loading')
  const loadingBgColor = 'rgba(84,49,126,.15)'
  const $container = $('#thinkpawsitive-calendar-container')
  const calendar = $('#thinkpawsitive-staff-calendar').fullCalendar({
    header: {
      left: 'today prev,next',
      center: 'title',
      right: 'month,basicWeek,basicDay'
    },
    defaultDate: new Date(Date.now()),
    navLinks: true, // can click day/week names to navigate views
    editable: false,
    lazyFetching: true,
    eventLimit: true, // allow "more" link when too many events
    loading: (bool) => {
      if (bool) {
        showLoading()
      } else {
        hideLoading()
      }
    },
    eventClick: showEventDetails,
    events: function(start, end, timezone, callback) {
      $.get({
        url: wp_data.ajax_url,
        dataType: 'json',
        data: {
          action: 'thinkpawsitive_fetch_bookings',
          start: start._d,
          end: end._d,
        },
        success: function(events) {
          callback(events)
          hideLoading()
        },
        error: (err) => {
          hideLoading()
          alert(`Calendar: ${err.statusText}`)
        }
      })
    }
  })

  function showEventDetails(calEvent, jsEvent, view) {
    modalContent.innerHTML = `<div>
      <em class="${calEvent.className}--text">${calEvent.category}</em>
      <h3>${calEvent.product_name}</h3>
      <div class="start-end-times">
        <div>
          <span>Start</span><br/>
          <strong>${calEvent.start}</strong>
        </div>
        <div>
          <span>End</span>
          <strong>${calEvent.end}</strong>
        </div>
      </div>
      <h4>${calEvent.title}</h4>
      <p><i class="fas fa-mobile-alt"></i> <a href="tel: ${calEvent.phone}" title="Call ${calEvent.phone}">${calEvent.phone}</a></p>
      <p><i class="fas fa-envelope-alt"></i> <a href="mailto: ${calEvent.email}" title="Email ${calEvent.email}">${calEvent.email}</a></p>
    </div>`
    modal.style.display = "block"
  }

  function showLoading() {
    $container.css('background', loadingBgColor)
    $loading.show()
  }

  function hideLoading() {
    $container.css('background', 'transparent')
    $loading.hide()
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
