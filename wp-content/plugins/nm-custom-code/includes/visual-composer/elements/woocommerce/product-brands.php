<?php
    
// VC element: nm_product_brands
vc_map(array(
    'name'			=> esc_html__( 'Product Brands', 'nm-framework-admin' ),
    'category'		=> esc_html__( 'WooCommerce', 'nm-framework-admin' ),
    'description'	=> esc_html__( 'Display a list with Product Brands', 'nm-framework-admin' ),
    'base'			=> 'nm_product_brands',
    'icon'			=> 'icon-wpb-woocommerce',
    'params'			=> array(
        array(
            'type' 			=> 'checkbox',
            'heading' 		=> esc_html__( 'Hover Thumbnails', 'nm-framework-admin' ),
            'param_name' 	=> 'thumbnails',
            'description'	=> esc_html__( 'Display brand thumbnails on-hover.', 'nm-framework-admin' ),
            'value'			=> array(
                esc_html__( 'Enable', 'nm-framework-admin' ) => '1'
            )
        )
    )
));
