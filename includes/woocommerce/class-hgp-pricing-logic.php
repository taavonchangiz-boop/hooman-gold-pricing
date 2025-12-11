<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class HGP_Pricing_Logic {

    public function __construct() {
        // تغییر قیمت نمایش (Frontend)
        add_filter( 'woocommerce_product_get_price', [ $this, 'override_price' ], 10, 2 );
        add_filter( 'woocommerce_product_variation_get_price', [ $this, 'override_price' ], 10, 2 );
        
        // تغییر رنج قیمت متغیر (از قیمت min تا max)
        add_filter( 'woocommerce_variation_prices_price', [ $this, 'override_variation_price_range' ], 10, 3 );
        add_filter( 'woocommerce_variation_prices_regular_price', [ $this, 'override_variation_price_range' ], 10, 3 );
    }

    public function override_price( $price, $product ) {
        $product_id = $product->get_id();
        $weight = get_post_meta( $product_id, '_gold_weight', true );

        // اگر وزن طلا نداشت، همان قیمت عادی ووکامرس را برگردان
        if ( ! $weight || $weight <= 0 ) {
            return $price;
        }

        $ajrat = get_post_meta( $product_id, '_ajrat_percent', true );
        
        // فراخوانی کلاس ماشین حساب
        return HGP_Calculator::calculate_price( $weight, $ajrat );
    }

    public function override_variation_price_range( $price, $variation, $product ) {
        // این فانکشن باعث می‌شود کش قیمت متغیرها درست کار کند و رنج قیمت صحیح نمایش داده شود
        return $this->override_price( $price, $variation );
    }
}