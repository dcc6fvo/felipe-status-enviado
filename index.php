<?php

/*
 * Plugin Name: felipe-status-enviado
 * Description: Meu primeiro plugin desenvolvido para wordpress
 * Version: 1.0
 * Author: Felipe Volpato
 * Author URI: http://www.felipevolpato.com
 * Text Domain: felipe-status-enviado
 * Domain Path: /languages
 */

/**
 * Configuração do "text domain" do plugin
 */
function my_plugin_load_plugin_textdomain() {
    load_plugin_textdomain( 'felipe-status-enviado', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'my_plugin_load_plugin_textdomain' );

if ( is_admin() ) {
    //Implementação de uma nova aba (dentro de wc->settings) para configurar o e-mail automatico quando um pedido é alterado para status enviado
    require_once( dirname( __FILE__ ) . '/admin/emailshippedorder.php' );
    //Arquivo com as modificações principais do plug-in
    require_once( dirname( __FILE__ ) . '/admin/felipe-status-enviado.php' );
}

//Arquivo com as modificações no frontend
require_once( dirname( __FILE__ ) . '/frontend/felipe-status-enviado-frontend.php' );

/**
 * Função para visualizar log no console do navegador
 */
function logThis($object){
    
    echo "<script>console.log(".json_encode(var_export($object, true)).");</script>";
}