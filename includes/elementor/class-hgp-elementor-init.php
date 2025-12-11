<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class HGP_Elementor_Init {
    public function __construct() {
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
    }

    public function register_widgets( $widgets_manager ) {
        // ویجت اول: تابلو قیمت (Ticker)
        require_once( __DIR__ . '/widgets/widget-gold-ticker.php' );
        $widgets_manager->register( new \Elementor_Gold_Ticker_Widget() );

        // ویجت دوم: جدول شفافیت (Specs) - جدید
        require_once( __DIR__ . '/widgets/widget-gold-specs.php' );
        $widgets_manager->register( new \Elementor_Gold_Specs_Widget() );
    }
}
new HGP_Elementor_Init();