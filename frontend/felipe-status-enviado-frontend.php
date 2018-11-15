<?php

/**
 * Registrando um status de pedido customizado "enviado"
 */
function register_custom_order_statuses() {
    register_post_status('wc-shipped', array(
        'label' => __( 'Shipped', 'felipe-status-enviado' ),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>' )
    ));
}
add_action('init', 'register_custom_order_statuses');


/**
 * Adicionando um status de pedido customizado para a lista do 'woocommerce order statuses' 
 */
function add_custom_order_statuses( $order_statuses ) {
    
    $order_statuses['wc-shipped'] = __('Shipped', 'felipe-status-enviado' );
    
    return $order_statuses;
}
add_filter('wc_order_statuses', 'add_custom_order_statuses');

/**
 * Adicionando o campo "status do pedido" e "codigo de rastreio" para a pagina de minha-conta/view-order/x do cliente
 */
function action_wc_order_details_after_customer_details($order) {
 
   echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/gojs/1.8.33/go-debug.js"></script>';

   echo '<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">';
   echo '<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">';
   echo '<h2 class="woocommerce-column__title">' . esc_html__( 'Order history status', 'felipe-status-enviado' ) .  '</h2>';

   if ( $order->has_status( 'on-hold' ) ) {

	    $str = '<b>'. esc_html__( 'on-hold', 'felipe-status-enviado' ) . '</b>' . ' -> ' . esc_html__( 'processing', 'felipe-status-enviado' ) . ' -> ' . __( 'shipped', 'felipe-status-enviado' ) . ' -> ' . esc_html__( 'completed', 'felipe-status-enviado' );
    }  
    
    elseif  ( $order->has_status( 'processing' ) ) {

        $str = esc_html__( 'on-hold', 'felipe-status-enviado' ) . ' -> <b>' . esc_html__( 'processing', 'felipe-status-enviado' ) . '</b> -> ' . esc_html__( 'shipped', 'felipe-status-enviado' ) . ' -> ' . esc_html__( 'completed', 'felipe-status-enviado' ); 
    }

    elseif  ( $order->has_status( 'shipped' ) ) {

        $str = esc_html__( 'on-hold', 'felipe-status-enviado' ) . ' -> ' . esc_html__( 'processing', 'felipe-status-enviado' ) . ' -> <b> ' . esc_html__( 'shipped', 'felipe-status-enviado' ) . ' </b> -> ' . esc_html__( 'completed', 'felipe-status-enviado' ); 
    }

    elseif ( $order->has_status( 'completed' ) ) {

        $str = esc_html__( 'on-hold', 'felipe-status-enviado' ) . ' -> ' . esc_html__( 'processing', 'felipe-status-enviado' ) . ' -> ' . esc_html__( 'shipped', 'felipe-status-enviado' ) . ' -> <b> ' . esc_html__( 'completed', 'felipe-status-enviado' ) . '</b>';  
    }

    echo "<address><p class='woocommerce-customer-details--email'>".$str."</p></address>";
    echo "</div>";

    echo '<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">';
    $track_number = get_post_meta($order->get_id(), 'wc_admin_order_data_track_number', true);
  
    echo '<h2 class="woocommerce-column__title">' . esc_html__( 'Tracking Number', 'felipe-status-enviado' ) . '</h2>';
    echo "<address>";
    if( ! empty($track_number) ){

    	echo '<p class="woocommerce-customer-details--email"><strong>' . $track_number  . '</strong></p>';

    }
    else{
    	echo '<p class="woocommerce-customer-details--email"><span style="color:red">' .__('Not yet.', 'felipe-status-enviado' ) . '</span></p>';
    }
   
   	echo "</address></div></section>";
}
add_action('woocommerce_order_details_after_customer_details', 'action_wc_order_details_after_customer_details', 1, 1);

?>