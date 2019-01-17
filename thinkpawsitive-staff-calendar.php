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

function thinkpawsitive_staff_calendar_scripts_styles() {
  wp_register_style('fullcalendar', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css');
  wp_register_style('fullcalendar-print', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.print.css', '', false, 'print');
  wp_register_style('thinkpawsitivestaffcalendar-css', plugins_url('/css/thinkpawsitive-staff-calendar.css',  __FILE__ ));
  wp_register_script('moment-js', '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.23.0/moment.min.js', '', false, true );
  wp_register_script('fullcalendar-js', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js', array('jquery'), false, true );
  wp_register_script('thinkpawsitivestaffcalendar-js', plugins_url('/js/thinkpawsitive-staff-calendar.js',  __FILE__ ), array('jquery'), false, true );
}
add_action('wp_enqueue_scripts', 'thinkpawsitive_staff_calendar_scripts_styles');

function thinkpawsitive_staff_cal_func( $atts ) {
  wp_enqueue_style('fullcalendar');
  wp_enqueue_style('fullcalendar-print');
  wp_enqueue_style('thinkpawsitivestaffcalendar-css');
  wp_enqueue_script('moment-js');
  wp_enqueue_script('fullcalendar-js');
  wp_enqueue_script('thinkpawsitivestaffcalendar-js');

  if (isset($_GET['date'])) {
    $moStart = strtotime("first day of" . $_GET['date']);
    $moEnd = strtotime("last day of" . $_GET['date']);
  } else {
    $moStart = strtotime("first day of this month");
    $moEnd = strtotime("last day of this month");
  }

  $WCBookings = new WP_Query(array(
    'post_type' => 'wc_booking',
    'post_status' => array('confirmed', 'complete', 'paid', 'processing'),
    'posts_per_page' => -1,
    'date_query' => array(
      'after' => date("Y-n-j", $moStart),
      'before' => date("Y-n-j", $moEnd),
      'inclusive' => true,
    ),
  ));

  $bookings = [];

  if ( $WCBookings->have_posts() ) :
    while ( $WCBookings->have_posts() ) : $WCBookings->the_post();
      $booking = new WC_Booking( get_the_id() );

      $product = $booking->get_product();
      $product_cats = $product->get_category_ids();
      // $order_id = $booking->get_order()->get_id();

      $billing_phone_number = get_user_meta( $booking->get_customer()->user_id, 'billing_phone', true );

      $bookings[] = array(
        'id' => $booking->get_customer()->user_id,
        'email' => $booking->get_customer()->email,
        'phone' => $billing_phone_number,
        'title' => $booking->get_customer()->name,
        'product_name' => $product->get_name(),
        'start' => $booking->get_start_date(),
        'end' => $booking->get_end_date()
      );
    endwhile;
    wp_reset_postdata();
  else :
    echo 'No bookables found.';
  endif;

  $html = '<script>';
  $html .= 'const tp_bookings = ' . json_encode($bookings);
  $html .= '</script>';
	$html .= '<div id="thinkpawsitive-staff-calendar"></div>';
  return $html;
}
add_shortcode( 'thinkpawsitive-staff-calendar', 'thinkpawsitive_staff_cal_func' );

?>
