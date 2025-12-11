<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class HGP_Settings {
    
    private $option_group = 'hgp_options_group';

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_menu() {
        add_menu_page(
            'تنظیمات سیستم طلا', 'سیستم طلا', 'manage_options', 'hooman-gold', [ $this, 'render_page' ], 'dashicons-shield', 50
        );
    }
    
    public function register_settings() {
        // لایسنس
        register_setting( $this->option_group, 'hgp_license_key', [ $this, 'sanitize_license' ] );
        
        // تنظیمات API
        register_setting( $this->option_group, 'hgp_api_provider' ); // انتخاب نوع سرویس (navasan یا custom)
        register_setting( $this->option_group, 'hgp_navasan_token' );
        register_setting( $this->option_group, 'hgp_custom_api_url' );
        register_setting( $this->option_group, 'hgp_custom_json_path' );

        // تنظیمات قیمت و فرمول
        register_setting( $this->option_group, 'hgp_base_gold_price' );
        register_setting( $this->option_group, 'hgp_default_ajrat' );
        register_setting( $this->option_group, 'hgp_default_profit' );
        register_setting( $this->option_group, 'hgp_default_tax' );
    }

    public function sanitize_license( $input ) {
        if ( ! empty( $input ) ) {
            $api_manager = new HGP_API_Manager();
            $api_manager->verify_license_remote( $input );
        }
        return sanitize_text_field( $input );
    }

    public function render_page() {
        $status = get_option( 'hgp_license_status' );
        $is_active = ( $status === 'valid' );
        
        // خواندن مقدار انتخاب شده برای نمایش شرطی فیلدها
        $provider = get_option('hgp_api_provider', 'navasan');
        ?>
        <div class="wrap">
            <h1>💎 پنل تنظیمات سیستم طلا (HoomanWeb)</h1>
            
            <?php if ( $is_active ): ?>
                <div class="notice notice-success inline"><p>✅ لایسنس معتبر و فعال است.</p></div>
            <?php else: ?>
                <div class="notice notice-error inline"><p>❌ لایسنس فعال نیست. لطفاً کد معتبر وارد کنید.</p></div>
            <?php endif; ?>

            <form method="post" action="options.php">
                <?php settings_fields( $this->option_group ); ?>
                
                <div style="background:#fff; padding:20px; border-radius:10px; margin-top:20px; border-left:4px solid #007cba; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                    <h3>۱. فعال‌سازی افزونه</h3>
                    <p>کد لایسنس دریافتی از هومن وب را وارد کنید.</p>
                    <input type="text" name="hgp_license_key" value="<?php echo esc_attr( get_option('hgp_license_key') ); ?>" class="regular-text" placeholder="HW-GOLD-..." />
                </div>

                <div style="background:#fff; padding:20px; border-radius:10px; margin-top:20px; border:1px solid #ccd0d4;">
                    <h2>۲. منبع قیمت لحظه‌ای</h2>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">انتخاب سرویس دهنده</th>
                            <td>
                                <select name="hgp_api_provider" id="hgp_api_provider_select">
                                    <option value="navasan" <?php selected($provider, 'navasan'); ?>>سایت نوسان (Navasan.tech) - پیشنهادی</option>
                                    <option value="custom" <?php selected($provider, 'custom'); ?>>API سفارشی (سایر سایت‌ها)</option>
                                </select>
                                <p class="description">اگر توکن نوسان دارید گزینه اول، در غیر این صورت گزینه دوم را انتخاب کنید.</p>
                            </td>
                        </tr>

                        <tr valign="top" class="hgp-navasan-fields">
                            <th scope="row">توکن سایت نوسان</th>
                            <td>
                                <input type="text" name="hgp_navasan_token" value="<?php echo esc_attr( get_option('hgp_navasan_token') ); ?>" class="regular-text" />
                            </td>
                        </tr>

                        <tr valign="top" class="hgp-custom-fields">
                            <th scope="row">آدرس API سفارشی</th>
                            <td>
                                <input type="text" name="hgp_custom_api_url" value="<?php echo esc_attr( get_option('hgp_custom_api_url') ); ?>" class="large-text" placeholder="https://api.site.com/gold" />
                            </td>
                        </tr>
                        <tr valign="top" class="hgp-custom-fields">
                            <th scope="row">مسیر قیمت در JSON</th>
                            <td>
                                <input type="text" name="hgp_custom_json_path" value="<?php echo esc_attr( get_option('hgp_custom_json_path') ); ?>" class="regular-text" placeholder="مثال: data->prices->gold_18k" />
                                <p class="description">اگر خروجی جیسون تودرتو است، با علامت -> جدا کنید.</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="background:#fff; padding:20px; border-radius:10px; margin-top:20px; border:1px solid #ccd0d4;">
                    <h2>۳. تنظیمات محاسباتی</h2>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">قیمت فعلی طلا</th>
                            <td>
                                <input type="text" name="hgp_base_gold_price" value="<?php echo esc_attr( get_option('hgp_base_gold_price') ); ?>" /> تومان
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">درصدها (پیش‌فرض)</th>
                            <td>
                                اجرت: <input type="number" step="any" name="hgp_default_ajrat" value="<?php echo esc_attr( get_option('hgp_default_ajrat', 7) ); ?>" class="small-text" /> % &nbsp;&nbsp;
                                سود: <input type="number" step="any" name="hgp_default_profit" value="<?php echo esc_attr( get_option('hgp_default_profit', 7) ); ?>" class="small-text" /> % &nbsp;&nbsp;
                                مالیات: <input type="number" step="any" name="hgp_default_tax" value="<?php echo esc_attr( get_option('hgp_default_tax', 9) ); ?>" class="small-text" /> %
                            </td>
                        </tr>
                    </table>
                </div>
                
                <?php submit_button('ذخیره تنظیمات'); ?>
            </form>

            <script>
                jQuery(document).ready(function($){
                    function toggleFields() {
                        var selected = $('#hgp_api_provider_select').val();
                        if(selected === 'navasan') {
                            $('.hgp-navasan-fields').show();
                            $('.hgp-custom-fields').hide();
                        } else {
                            $('.hgp-navasan-fields').hide();
                            $('.hgp-custom-fields').show();
                        }
                    }
                    $('#hgp_api_provider_select').change(toggleFields);
                    toggleFields(); // اجرا در لحظه لود
                });
            </script>
        </div>
        <?php
    }
}