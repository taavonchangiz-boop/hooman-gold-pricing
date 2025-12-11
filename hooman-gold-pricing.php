<?php
/*
Plugin Name: سیستم حرفه‌ای قیمت‌گذاری طلا هومن وب  
Description: سیستم حرفه‌ای قیمت‌گذاری طلا 
Version: 3.5.0
Author: Hooman Naghshi
Author URI: https://hoomanweb.ir
Text Domain: hooman-gold
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'HGP_VERSION', '3.5.0' );
define( 'HGP_PATH', plugin_dir_path( __FILE__ ) );
define( 'HGP_URL', plugin_dir_url( __FILE__ ) );

// 1. لود کردن ماژول‌های هسته
require_once HGP_PATH . 'includes/class-hgp-calculator.php';
require_once HGP_PATH . 'includes/class-hgp-api-manager.php'; // <--- این خط حیاتی است!

// 2. لود کردن ماژول‌های نمایشی
require_once HGP_PATH . 'includes/class-hgp-topbar.php';
require_once HGP_PATH . 'includes/woocommerce/class-hgp-display.php';

// 3. لود کردن تنظیمات و منطق ووکامرس
require_once HGP_PATH . 'includes/admin/class-hgp-settings.php';
require_once HGP_PATH . 'includes/woocommerce/class-hgp-product-fields.php';
require_once HGP_PATH . 'includes/woocommerce/class-hgp-pricing-logic.php';

// 4. لود کردن المنتور
add_action( 'plugins_loaded', 'hgp_init_external_integrations' );
function hgp_init_external_integrations() {
    if ( did_action( 'elementor/loaded' ) ) {
        require_once HGP_PATH . 'includes/elementor/class-hgp-elementor-init.php';
    }
}

// شروع سیستم
class HGP_Master {
    public function __construct() {
        // ترتیب اجرا مهم است
        new HGP_API_Manager(); // <--- این خط هم حیاتی است (اجرای کلاس لایسنس)
        new HGP_Settings();
        new HGP_Product_Fields();
        new HGP_Pricing_Logic();
        new HGP_Display(); 
        new HGP_Topbar();
    }
}

new HGP_Master();