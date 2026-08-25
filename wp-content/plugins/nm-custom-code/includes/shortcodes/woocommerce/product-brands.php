<?php
    
	// Shortcode: nm_product_brands
	function nm_shortcode_product_brands( $atts, $content = NULL ) {
		if ( function_exists( 'nm_add_page_include' ) ) {
            nm_add_page_include( 'product_brands' );
        }               
        
        $wrapper_class = ( isset( $atts['thumbnails'] ) && $atts['thumbnails'] === '1' ) ? ' show-thumbnails' : '';
        
        return '<div class="nm-product-brands' . esc_attr( $wrapper_class ) . '">' . do_shortcode( '[product_brand_list show_empty_brands="true"]' ) . '</div>';
	}
	
	add_shortcode( 'nm_product_brands', 'nm_shortcode_product_brands' );
	