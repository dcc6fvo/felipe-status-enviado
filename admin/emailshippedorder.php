<?php

/**
 * Cria a aba "e-mail de pedido enviado" dentro de Woocommerce > settings
 */
function add_settings_tab( $settings_tabs ) {
            
    $settings_tabs['emailshippedorder'] = __( 'E-mail shipped order' , 'felipe-status-enviado' );
    return $settings_tabs;
}
add_filter( 'woocommerce_settings_tabs_array',  'add_settings_tab' , 70 );

/**
 * Gera a tela "e-mail de pedido enviado" com os campos assunto e conteúdo
 */
function tab_content() {

    woocommerce_admin_fields( get_fields() );
}
add_action( 'woocommerce_settings_tabs_emailshippedorder', 'tab_content' );

/**
 * Salva os campos assunto e conteúdo da tela "e-mail de pedido enviado"
 */
function update_settings() {

    woocommerce_update_options( get_fields() );
}
add_action( 'woocommerce_update_options_emailshippedorder', 'update_settings' );


/**
 * Componentes da tela "e-mail de pedido enviado"
 */
function get_fields() {

    $settings = array(
        'section_title' => array(
        'name'     => __( 'E-mail shipped order' , 'felipe-status-enviado' ),
        'type'     => 'title',
        'desc'     => '',
        'id'       => 'wc_settings_emailshippedorder_section_title'
        ),
        'title' => array(
        'name' => __( 'Title', 'felipe-status-enviado' ),
        'type' => 'text',
        'desc' => __( 'This is the e-mail subject' , 'felipe-status-enviado' ),
        'id'   => 'wc_settings_emailshippedorder_title'
        ),
        'description' => array(
        'name' => __( 'Message' , 'felipe-status-enviado' ),
        'type' => 'textarea',
        'desc' => __( 'This is the e-mail message body' , 'felipe-status-enviado' ),
        'id'   => 'wc_settings_emailshippedorder_description'
        ),
        'section_end' => array(
        'type' => 'sectionend',
        'id' => 'wc_settings_emailshippedorder_section_end'
        )
    );

    return apply_filters( 'wc_settings_tab_emailshippedorder_settings', $settings );
}

?>