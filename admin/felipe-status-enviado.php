<?php

/**
 * Adiciona uma ação de status de pedido (enviado) para a lista de pedidos dropdown (admin)
 */
function custom_dropdown_bulk_actions_shop_order( $actions ) {
    $new_actions = array();

    foreach ($actions as $key => $action) {
        if ('mark_processing' === $key)
            $new_actions['mark_shipped'] = __( 'Change status to shipped', 'felipe-status-enviado' );

        $new_actions[$key] = $action;
    }
    return $new_actions;
}
add_filter( 'bulk_actions-edit-shop_order', 'custom_dropdown_bulk_actions_shop_order', 50, 1 );


/**
 * Adiciona um botão de ação de status de pedido 'enviado' (admin)
 */
function add_custom_order_status_actions_button( $actions, $order ) {

    if ( $order->has_status( array( 'on-hold', 'processing', 'pending' ) ) ) {

        // The key slug defined for your action button
        $action_slug = 'shipped';

        // Set the action button
        $actions[$action_slug] = array(
            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status='.$action_slug.'&order_id='.$order->get_id() ), 'woocommerce-mark-order-status' ),
            'name'      => __( 'Shipped', 'felipe-status-enviado' ),
            'action'    => $action_slug,
        );
    }
    return $actions;
}
add_filter( 'woocommerce_admin_order_actions', 'add_custom_order_status_actions_button', 100, 2 );


/**
 * Função para adicionar ação de 'shipped' ao 'ações de pedido' meta box (admin)
 */ 
function add_order_meta_box_actions($actions){

    global $theorder;

    // Make sure we're in a subscription typeof order
    if ( ! $theorder->has_status( 'shipped' ) ) {
        $actions['shipped'] = __( 'Change the status to shipped', 'felipe-status-enviado' );
    }
    return $actions; 
}
add_action( 'woocommerce_order_actions', 'add_order_meta_box_actions');


/**
 * Add callback if Shipped action called (admin)
 */
function order_shipped_callback($order){

    $order->update_status( 'shipped' );
    $order->add_order_note( __( 'The order has been shipped!! ', 'felipe-status-enviado' ), false, true );
}
add_action( 'woocommerce_order_action_shipped', 'order_shipped_callback', 10, 1);


/**
 * Display track number field value on the order edit page (admin)
 */
function tracking_number_field_display_admin_order_meta( $order ){
    
    $track_number = get_post_meta($order->get_id(), 'wc_admin_order_data_track_number', true);
   
    if ( ! $order->has_status( 'shipped' ) ) {

        echo '<h3>' . esc_html__( 'Tracking Number', 'felipe-status-enviado' ) . ' </h3>';
        woocommerce_wp_text_input( array( 
            'id' => 'wc_admin_order_data_track_number',
            'placeholder' => '',
            'desc_tip'    => 'true',
            'value' => $track_number
            )
        );
    } else {
        echo '<h3>' . esc_html__( 'Tracking Number', 'felipe-status-enviado' ) . ' </h3>';
        echo '<p><strong>' . $track_number  . '</strong></p>';
    }
}
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'tracking_number_field_display_admin_order_meta', 10, 1 );


/**
 * Função para salvar o código de rastreio de um pedido (admin)
 */
function renew_save_again($post_id, $post, $update){
    
    $slug = 'shop_order';
    if(is_admin()){

        // If this isn't a 'woocommercer order' post, don't update it.
        if ( $slug != $post->post_type ) {
            return;
        }
        if( isset( $_POST[ 'wc_admin_order_data_track_number' ] ) ){
            update_post_meta( $post_id, 'wc_admin_order_data_track_number', sanitize_text_field( $_POST[ 'wc_admin_order_data_track_number' ] ) );
        }
    }
}
add_action('save_post', 'renew_save_again', 10, 3);


/**
 * Função para enviar e-mail quando o pedido for alterado para o status de "enviado" (admin)
 */
function email_shipping_notification($order_id, $checkout=null) {
   
   global $woocommerce, $post;
   $order = new WC_Order( $order_id );
   if($order->status === 'shipped' ) {
    
        $msg_subject = get_option( 'wc_settings_emailshippedorder_title', true );
        $msg_content = get_option( 'wc_settings_emailshippedorder_description', true );

        $mailer = $woocommerce->mailer();
        $message = $mailer->wrap_message(sprintf( $msg_subject, $order->get_order_number() ), $msg_content );
        $mailer->send( $order->billing_email, sprintf( $msg_subject, $order->get_order_number() ), $message );
     
     }
}
add_action("woocommerce_order_status_changed", "email_shipping_notification");


/**
 * Função para customizar: (1) a cor do botão "enviado" e (2) a ação "enviado". Ambas na lista de pedidos (wc -> pedidos) (admin)
 */
function customizacao_css_shipping(){
    
    echo '<style>
    .order-status.status-shipped{
        background:#3fff33;
        color:#ffffff
    }
    .column-wc_actions a.shipped::after{content:"\f145"}.widefat
    </style>';
}
add_action( 'admin_head', 'customizacao_css_shipping' );

?>