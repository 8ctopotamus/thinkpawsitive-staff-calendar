<?php
/*
  Plugin Name: Thinkpawsitive Staff Calendar
  Plugin URI:  https://icshelpsyou.com
  Description: A calendar of bookings.
  Version:     1.0
  Author:      ICS, LLC
  Author URI:  https://icshelpsyou.com
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
** Setup
*/
function thinkpawsitive_staff_calendar_scripts_styles() {
  wp_register_style('fullcalendar', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css');
  wp_register_style('fullcalendar-print', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.print.css', '', false, 'print');
  wp_register_style('thinkpawsitivestaffcalendar-css', plugins_url('/css/thinkpawsitive-staff-calendar.css',  __FILE__ ));
  wp_register_script('moment-js', '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.23.0/moment.min.js', '', false, true );
  wp_register_script('fullcalendar-js', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js', array('jquery'), false, true );
  wp_register_script('thinkpawsitivestaffcalendar-js', plugins_url('/js/thinkpawsitive-staff-calendar.js',  __FILE__ ), array('jquery'), false, true );
}
add_action('wp_enqueue_scripts', 'thinkpawsitive_staff_calendar_scripts_styles');

/*
** Set up wp_ajax requests for frontend UI.
** NOTE: _nopriv_ makes ajaxurl work for logged out users.
*/
add_action( 'wp_ajax_thinkpawsitive_fetch_bookings', 'thinkpawsitive_fetch_bookings' );
add_action( 'wp_ajax_nopriv_thinkpawsitive_fetch_bookings', 'thinkpawsitive_fetch_bookings' );
function thinkpawsitive_fetch_bookings() {
  include( plugin_dir_path( __FILE__ ) . 'inc/thinkpawsitive-fetch-bookings.php' );
}

/*
** Shortcode
*/
function thinkpawsitive_staff_cal_func( $atts ) {
  wp_enqueue_style('fullcalendar');
  wp_enqueue_style('fullcalendar-print');
  wp_enqueue_style('thinkpawsitivestaffcalendar-css');
  wp_enqueue_script('moment-js');
  wp_enqueue_script('fullcalendar-js');
  wp_localize_script( 'thinkpawsitivestaffcalendar-js', 'wp_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
  wp_enqueue_script('thinkpawsitivestaffcalendar-js');

  $html = '<div id="thinkpawsitive-calendar-container">';
    // loading
    $html .= '<div id="loading" class="pixel-spinner">';
      $html .= '<div class="pixel-spinner-inner"></div>';
    $html .= '</div>';
    // calendar
    $html .= '<div id="thinkpawsitive-staff-calendar"></div>';
  $html .= '</div>';

  // modal
  $html .= '<div id="thinkpawsitive-modal" class="modal">';
    $html .= '<div class="thinkpawsitive-modal-content animated animatedFadeInUp fadeInUp">';
      $html .= '<span class="close">&times;</span>';
      $html .= '<div id="thinkpawsitive-booking-details"></div>';
    $html .= '</div>';
  $html .= '</div>';

  return $html;
}
add_shortcode( 'thinkpawsitive-staff-calendar', 'thinkpawsitive_staff_cal_func' );

?>
