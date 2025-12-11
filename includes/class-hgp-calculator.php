<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class HGP_Calculator {

    public static function get_live_gold_price() {
        // خواندن قیمت از تنظیمات (که توسط API یا دستی پر شده)
        return (float) get_option( 'hgp_base_gold_price', 0 );
    }

    public static function calculate_price( $weight, $ajrat_percent = null, $profit_percent = null, $tax_percent = null ) {
        $gold_price = self::get_live_gold_price();
        
        if ( $gold_price <= 0 || $weight <= 0 ) return 0;

        // دریافت مقادیر پیش‌فرض اگر خالی بودند
        if ( $ajrat_percent === null || $ajrat_percent === '' ) $ajrat_percent = get_option( 'hgp_default_ajrat', 7 );
        if ( $profit_percent === null ) $profit_percent = get_option( 'hgp_default_profit', 7 );
        if ( $tax_percent === null ) $tax_percent = get_option( 'hgp_default_tax', 9 );

        // فرمول محاسبه
        $raw_price = $weight * $gold_price;
        $price_with_ajrat = $raw_price + ( $raw_price * ( $ajrat_percent / 100 ) );
        $price_with_profit = $price_with_ajrat + ( $price_with_ajrat * ( $profit_percent / 100 ) );
        $final_price = $price_with_profit + ( $price_with_profit * ( $tax_percent / 100 ) );

        return round( $final_price, -3 ); // رند کردن به ۱۰۰۰ تومان
    }
}