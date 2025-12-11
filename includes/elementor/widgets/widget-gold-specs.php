<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Gold_Specs_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'hgp_gold_specs'; }
    public function get_title() { return 'جدول شفافیت قیمت (Hooman)'; }
    public function get_icon() { return 'eicon-product-info'; }
    public function get_categories() { return [ 'woocommerce-elements-single' ]; } // در دسته محصولات ووکامرس

    protected function register_controls() {
        $this->start_controls_section(
            'style_section',
            [ 'label' => 'استایل باکس', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => 'رنگ پس‌زمینه باکس',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [ '{{WRAPPER}} .hgp-specs-container' => 'background-color: {{VALUE}}' ],
            ]
        );
        
        $this->add_control(
            'item_bg_color',
            [
                'label' => 'رنگ پس‌زمینه آیتم‌ها',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f9f9f9',
                'selectors' => [ '{{WRAPPER}} .hgp-spec-item' => 'background-color: {{VALUE}}' ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        // اگر افزونه نصب است، از کلاس نمایشگر استفاده کن
        if ( class_exists( 'HGP_Display' ) ) {
            // ساختن یک شیء موقت برای دسترسی به متد نمایش
            $display = new HGP_Display();
            $display->render_specs_box(); // این تابع باکس را چاپ می‌کند
        } else {
            echo 'ماژول نمایشگر فعال نیست.';
        }
    }
}