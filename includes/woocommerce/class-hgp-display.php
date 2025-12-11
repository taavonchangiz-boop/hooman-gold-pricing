<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class HGP_Display {

    public function __construct() {
        add_action( 'woocommerce_single_product_summary', [ $this, 'render_specs_box' ], 25 );
        add_shortcode( 'gold_specs', [ $this, 'render_specs_box_shortcode' ] );
        add_action( 'wp_head', [ $this, 'render_css' ] );
    }

    public function render_specs_box_shortcode() {
        ob_start();
        $this->render_specs_box();
        return ob_get_clean();
    }

    public function render_specs_box() {
        global $product;
        
        if ( ! $product ) {
            $product_id = get_the_ID();
            if ( $product_id ) $product = wc_get_product( $product_id );
        }
        
        if ( ! $product ) return;

        $product_id = $product->get_id();
        
        // منطق هوشمند: چک می‌کنیم آیا محصول (یا متغیرهایش) وزن دارند یا خیر
        // این کار برای این است که اگر محصول طلایی نبود، باکس خالی نمایش ندهیم
        $weight = get_post_meta( $product_id, '_gold_weight', true );
        $ajrat = get_post_meta( $product_id, '_ajrat_percent', true );

        if ( ( empty($weight) || $weight == 0 ) && $product->is_type( 'variable' ) ) {
            $variations = $product->get_visible_children();
            if ( ! empty( $variations ) ) {
                $first_variation_id = $variations[0];
                $weight = get_post_meta( $first_variation_id, '_gold_weight', true );
                if ( empty($ajrat) ) {
                    $ajrat = get_post_meta( $first_variation_id, '_ajrat_percent', true );
                }
            }
        }

        // اگر محصول کلاً وزن نداشت، یعنی طلایی نیست و باکس را نشان نده
        if ( empty($weight) || $weight == 0 ) return;

        // دریافت مقادیر
        if ( $ajrat === '' ) $ajrat = get_option( 'hgp_default_ajrat', 7 );
        $profit = get_option( 'hgp_default_profit', 7 );
        $tax = get_option( 'hgp_default_tax', 9 );
        $live_price = HGP_Calculator::get_live_gold_price();

        ?>
        <div class="hgp-specs-container">
            <h5 class="hgp-specs-title"><i class="dashicons dashicons-chart-bar" style="color:#d4af37;"></i> آنالیز دقیق قیمت</h5>
            <div class="hgp-specs-grid">
                
                <div class="hgp-spec-item">
                    <span class="hgp-label">قیمت دقیق هر گرم طلا</span>
                    <span class="hgp-value"><?php echo number_format($live_price); ?> <small>تومان</small></span>
                </div>

                <div class="hgp-spec-item">
                    <span class="hgp-label">اجرت ساخت</span>
                    <span class="hgp-value"><?php echo $ajrat; ?>٪</span>
                </div>

                <div class="hgp-spec-item">
                    <span class="hgp-label">سود فروشنده</span>
                    <span class="hgp-value"><?php echo $profit; ?>٪</span>
                </div>

                <div class="hgp-spec-item">
                    <span class="hgp-label">مالیات</span>
                    <span class="hgp-value"><?php echo $tax; ?>٪</span>
                </div>

            </div>
            <div class="hgp-formula-hint">
                فرمول محاسباتی: (وزن × قیمت لحظه‌ای) + اجرت + سود + مالیات<br>
                <small style="color:#666;">* قیمت نهایی با انتخاب وزن دقیق محاسبه می‌شود.</small> 
                </div>
        </div>
        <?php
    }

    public function render_css() {
        ?>
        <style>
            .hgp-specs-container { 
                margin: 20px 0; 
                border: 1px solid #e1e1e1; 
                border-radius: 12px; 
                padding: 20px; 
                background: #fff; 
                box-shadow: 0 5px 15px rgba(0,0,0,0.03);
                direction: rtl; 
            }
            .hgp-specs-title { 
                font-size: 15px; 
                font-weight: 800; 
                margin-bottom: 15px; 
                color: #333; 
                display: flex; 
                align-items: center; 
                gap: 8px; 
                border-bottom: 1px solid #f0f0f0;
                padding-bottom: 10px;
            }
            .hgp-specs-grid { 
                display: flex; 
                flex-wrap: wrap; 
                gap: 12px; 
            }
            .hgp-spec-item { 
                background-color: #f8f9fa; 
                border-radius: 10px; 
                padding: 12px; 
                flex: 1 1 45%; /* دو ستون در هر سطر */
                display: flex; 
                flex-direction: column; 
                align-items: center; 
                text-align: center; 
                border: 1px solid #eee; 
                transition: all 0.3s ease;
            }
            .hgp-spec-item:hover {
                background-color: #fff;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                border-color: #d4af37;
            }
            .hgp-label { 
                font-size: 12px; 
                color: #777; 
                margin-bottom: 6px; 
            }
            .hgp-value { 
                font-size: 16px; 
                font-weight: 800; 
                color: #222; 
            }
            .hgp-value small { 
                font-size: 11px; 
                font-weight: normal; 
                color: #888; 
            }
            .hgp-formula-hint { 
                font-size: 11px; 
                color: #999; 
                margin-top: 15px; 
                text-align: right; 
                border-top: 1px dashed #eee; 
                padding-top: 10px; 
                width: 100%; 
                line-height: 1.8; 
            }
        </style>
        <?php
    }
}