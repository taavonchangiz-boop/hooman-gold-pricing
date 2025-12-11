<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Gold_Ticker_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'hgp_gold_ticker'; }
    public function get_title() { return 'تابلو قیمت طلا (Hooman)'; }
    public function get_icon() { return 'eicon-price-table'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [ 'label' => 'تنظیمات محتوا', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => 'عنوان',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'قیمت لحظه‌ای هر گرم طلا', // تغییر متن پیش‌فرض
            ]
        );

        $this->add_control(
            'subtitle_text',
            [
                'label' => 'متن زیرنویس',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'بروزرسانی قیمت دقیق و لحظه‌ای', // تغییر متن پیش‌فرض
            ]
        );

        $this->end_controls_section();

        // استایل‌ها
        $this->start_controls_section(
            'style_section',
            [ 'label' => 'استایل', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => 'رنگ قیمت',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [ '{{WRAPPER}} .hgp-price-value' => 'color: {{VALUE}}' ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // دریافت قیمت از کلاس محاسبه‌گر
        if ( class_exists('HGP_Calculator') ) {
            $price = HGP_Calculator::get_live_gold_price();
        } else {
            $price = 0;
        }
        
        $formatted_price = number_format($price) . ' تومان';

        echo '<div class="hgp-gold-ticker-wrapper" style="text-align:center; padding:15px; border:1px solid #ddd; border-radius:12px; background:#fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">';
        echo '<h4 class="hgp-title" style="margin:0 0 10px; font-size:16px; color:#555;">' . esc_html($settings['title_text']) . '</h4>';
        echo '<div class="hgp-price-value" style="font-size:26px; font-weight:800; color:#333; margin-bottom:5px;">' . $formatted_price . '</div>';
        echo '<small style="color:#2ecc71; font-weight:bold; font-size:12px;">' . esc_html($settings['subtitle_text']) . '</small>';
        echo '</div>';
    }
}