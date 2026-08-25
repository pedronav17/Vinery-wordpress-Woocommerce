<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Class: Widget - NM_Elementor_Product_Brands  */
class NM_Elementor_Product_Brands extends \Elementor\Widget_Base {
    
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
   }
    
	public function get_name() {
		return 'nm-product-brands';
	}

	public function get_title() {
		return __( 'Product Brands', 'nm-framework-admin' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
        return [ 'savoy-theme' ];
	}
    
    public function get_script_depends() {
		return [ 'nm-elementor-widgets', 'nm-masonry' ];
	}
    
	protected function register_controls() {
        
        $this->start_controls_section(
			'section_product_brands_settings',
			[
				'label' => __( 'Product Brands', 'nm-framework-admin' ),
			]
		);
        
        $this->add_control(
			'thumbnails',
			[
				'label'         => __( 'Hover Thumbnails', 'nm-framework-admin' ),
				'type'          => Controls_Manager::SWITCHER,
				'separator'     => 'before',
                'return_value'  => '1',
                'default'       => '',
			]
		);
        
		$this->end_controls_section();
        
    }

	protected function render() {
        $product_brands = $this->get_settings_for_display();
        
        $settings = array();
        
        foreach( $product_brands as $setting => $value ) {
            if ( substr( $setting, 0, 1 ) === '_' ) { continue; } // Continue if setting name starts with "_" (Elementor adds this to its own settings)
            if ( empty( $value ) && $value !== '0' ) { continue; } // Don't add empty settings, except "0" values
            
            $settings[$setting] = $value;
        }
        
        echo nm_shortcode_product_brands( $settings );
    }

}