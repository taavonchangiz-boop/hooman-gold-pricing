<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class HGP_Topbar {

    public function __construct() {
        // اتصال به هوک ابتدای بادی سایت
        // اگر قالب شما استاندارد باشد از wp_body_open پشتیبانی می‌کند
        add_action( 'wp_body_open', [ $this, 'render_topbar' ] );
        
        // اگر قالب قدیمی است و wp_body_open ندارد، با فوتر و CSS فیکسش می‌کنیم
        add_action( 'wp_footer', [ $this, 'render_topbar_fallback' ] );
        
        add_action( 'wp_head', [ $this, 'render_css' ] );
    }

    public function render_topbar() {
        $this->output_html();
    }

    public function render_topbar_fallback() {
        // فقط اگر بادی اوپن کار نکرد، این تابع محتوا را اینجکت می‌کند
        // با جاوااسکریپت چک نمی‌کنیم، صرفا با CSS هندل می‌کنیم
    }

    private function output_html() {
        // جلوگیری از تکرار (یک بار لود شود)
        if ( defined('HGP_TOPBAR_LOADED') ) return;
        define('HGP_TOPBAR_LOADED', true);

        if ( class_exists('HGP_Calculator') ) {
            $price = HGP_Calculator::get_live_gold_price();
        } else {
            $price = 0;
        }

        ?>
        <div id="hgp-gold-topbar">
            <div class="hgp-topbar-content">
                <span class="hgp-topbar-icon">💎</span>
                <span class="hgp-topbar-title">قیمت لحظه‌ای هر گرم طلا:</span>
                <span class="hgp-topbar-price"><?php echo number_format($price); ?> <small>تومان</small></span>
                <span class="hgp-separator">|</span>
                <span class="hgp-topbar-update">بروزرسانی قیمت دقیق و لحظه‌ای</span>
            </div>
        </div>
        <?php
    }

    public function render_css() {
        ?>
        <style>
            /* ایجاد فضای خالی بالای سایت تا نوار روی منو نیفتد */
            body {
                margin-top: 40px !important; /* ارتفاع نوار */
            }

            #hgp-gold-topbar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 40px;
                background-color: #222; /* رنگ پس زمینه مشکی شیک */
                color: #fff;
                z-index: 99999; /* بالاتر از همه چیز */
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                font-family: inherit; /* ارث‌بری فونت قالب */
                direction: rtl;
            }

            .hgp-topbar-content {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 13px;
            }

            .hgp-topbar-title {
                color: #ccc;
            }

            .hgp-topbar-price {
                color: #d4af37; /* رنگ طلایی */
                font-weight: bold;
                font-size: 15px;
                background: rgba(212, 175, 55, 0.1);
                padding: 2px 8px;
                border-radius: 4px;
                border: 1px solid rgba(212, 175, 55, 0.3);
            }
            
            .hgp-topbar-price small {
                font-size: 10px;
                color: #d4af37;
            }

            .hgp-separator {
                color: #444;
                margin: 0 5px;
            }

            .hgp-topbar-update {
                color: #2ecc71; /* سبز */
                font-size: 11px;
                animation: hgp-pulse 2s infinite;
            }

            @keyframes hgp-pulse {
                0% { opacity: 0.8; }
                50% { opacity: 1; text-shadow: 0 0 5px #2ecc71; }
                100% { opacity: 0.8; }
            }

            /* ریسپانسیو برای موبایل */
            @media (max-width: 600px) {
                #hgp-gold-topbar {
                    height: auto;
                    padding: 5px 0;
                }
                body {
                    margin-top: 60px !important;
                }
                .hgp-topbar-content {
                    flex-wrap: wrap;
                    justify-content: center;
                }
                .hgp-separator, .hgp-topbar-update {
                    display: none; /* در موبایل متن طولانی حذف شود */
                }
            }
        </style>
        <?php
    }
}