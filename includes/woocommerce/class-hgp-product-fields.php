<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class HGP_Product_Fields {

    public function __construct() {
        // فیلد برای محصول ساده
        add_action( 'woocommerce_product_options_general_product_data', [ $this, 'add_simple_fields' ] );
        add_action( 'woocommerce_process_product_meta', [ $this, 'save_simple_fields' ] );

        // فیلد برای متغیرها (Variation)
        add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'add_variation_fields' ], 10, 3 );
        add_action( 'woocommerce_save_product_variation', [ $this, 'save_variation_fields' ], 10, 2 );
    }

    public function add_simple_fields() {
        echo '<div class="options_group">';
        woocommerce_wp_text_input([
            'id' => '_gold_weight',
            'label' => 'وزن طلا (گرم)',
            'desc_tip' => 'true',
            'description' => 'با وارد کردن وزن، قیمت بصورت اتوماتیک محاسبه می‌شود.',
            'type' => 'number', 'custom_attributes' => ['step' => 'any']
        ]);
        woocommerce_wp_text_input([
            'id' => '_ajrat_percent',
            'label' => 'اجرت ساخت (%)',
            'type' => 'number', 'custom_attributes' => ['step' => 'any']
        ]);
        echo '</div>';
    }

    public function save_simple_fields( $post_id ) {
        if ( isset( $_POST['_gold_weight'] ) ) update_post_meta( $post_id, '_gold_weight', sanitize_text_field( $_POST['_gold_weight'] ) );
        if ( isset( $_POST['_ajrat_percent'] ) ) update_post_meta( $post_id, '_ajrat_percent', sanitize_text_field( $_POST['_ajrat_percent'] ) );
    }

    public function add_variation_fields( $loop, $variation_data, $variation ) {
        echo '<div class="variation-custom-fields" style="background:#f0f0f1; padding:10px; border-left:4px solid #007cba;">';
        
        woocommerce_wp_text_input([
            'id' => "_gold_weight_var[{$loop}]",
            'name' => "_gold_weight_var[{$loop}]", // نام آرایه‌ای برای ذخیره صحیح
            'label' => 'وزن این متغیر (گرم)',
            'value' => get_post_meta( $variation->ID, '_gold_weight', true ),
            'type' => 'number', 'custom_attributes' => ['step' => 'any']
        ]);

        woocommerce_wp_text_input([
            'id' => "_ajrat_percent_var[{$loop}]",
            'name' => "_ajrat_percent_var[{$loop}]",
            'label' => 'اجرت این متغیر (%)',
            'value' => get_post_meta( $variation->ID, '_ajrat_percent', true ),
            'type' => 'number', 'custom_attributes' => ['step' => 'any']
        ]);
        
        echo '<p class="description">نکته: قیمت عادی را خالی بگذارید یا 0 وارد کنید. سیستم خودش قیمت را می‌سازد.</p>';
        echo '</div>';
    }

    public function save_variation_fields( $variation_id, $i ) {
        if ( isset( $_POST['_gold_weight_var'][$i] ) ) update_post_meta( $variation_id, '_gold_weight', sanitize_text_field( $_POST['_gold_weight_var'][$i] ) );
        if ( isset( $_POST['_ajrat_percent_var'][$i] ) ) update_post_meta( $variation_id, '_ajrat_percent', sanitize_text_field( $_POST['_ajrat_percent_var'][$i] ) );
    }
}