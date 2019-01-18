<?php
if ( empty($_GET['start']) || empty($_GET['end']) ) {
  http_response_code(400);
  die();
}

$cats = array(
  51 => ['label' => 'Bath', 'class' => 'bath'],
  32 => ['label' => 'Swim', 'class' => 'swim'],
  46 => ['label' => 'Turf Mat', 'class' => 'turf'],
  47 => ['label' => 'Mat Rental', 'class' => 'mat'],
  50 => ['label' => 'Nail Service', 'class' => 'nails'],
  35 => ['label' => 'Water Treadmill', 'class' => 'treadmill'],
  48 => ['label' => 'Weave Pole Rental', 'class' => 'weave'],
);

$WCBookings = new WP_Query(array(
  'post_type' => 'wc_booking',
  'post_status' => array('confirmed', 'complete', 'paid', 'processing'),
  'posts_per_page' => -1,
  'date_query'  => array (
    'after' => date("Y-n-j", $_GET['start']),
    'before' => date("Y-n-j", $_GET['end']),
  )
));

$bookings = [];

if ( $WCBookings->have_posts() ) :
  while ( $WCBookings->have_posts() ) : $WCBookings->the_post();
    $booking = new WC_Booking( get_the_id() );
    $product = $booking->get_product();
    $product_cats = $product->get_category_ids();
    foreach ($product_cats as $prodCat):
      if ( array_key_exists($prodCat, $cats) ):
        $bookings[] = array(
          'id' => $booking->get_customer()->user_id,
          'email' => $booking->get_customer()->email,
          'title' => $booking->get_customer()->name,
          'product_name' => $product->get_name(),
          'start' => $booking->get_start_date(),
          'end' => $booking->get_end_date(),
          'className' => $cats[$prodCat]['class'],
          'category' => $cats[$prodCat]['label'],
          'phone' => get_user_meta( $booking->get_customer()->user_id, 'billing_phone', true ),
        );
      endif;
    endforeach;
  endwhile;
  wp_reset_postdata();
else :
  echo 'No bookables found.';
endif;

echo json_encode($bookings);
die();

?>
