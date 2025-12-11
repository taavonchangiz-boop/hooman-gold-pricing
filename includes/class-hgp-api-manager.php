<?php
/*
 * این فایل دارای مکانیزم امنیتی است.
 * دستکاری این فایل باعث توقف محاسبات ریاضی افزونه می‌شود.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class HGP_API_Manager {

    private $v_url = "\x68\x74\x74\x70\x73\x3a\x2f\x2f\x68\x6f\x6f\x6d\x61\x6e\x77\x65\x62\x2e\x69\x72\x2f\x3f\x68\x77\x5f\x6c\x69\x63\x65\x6e\x73\x65\x5f\x63\x68\x65\x63\x6b\x3d\x31"; // آدرس سرور به هگزادسیمال (برای گیج کردن هکر)

    public function __construct() {
        if ( ! wp_next_scheduled( 'hgp_d_l_c' ) ) wp_schedule_event( time(), 'daily', 'hgp_d_l_c' );
        add_action( 'hgp_d_l_c', [ $this, 'verify_remote' ] );

        if ( ! wp_next_scheduled( 'hgp_u_g_p_e' ) ) wp_schedule_event( time(), 'every_five_minutes', 'hgp_u_g_p_e' );
        add_action( 'hgp_u_g_p_e', [ $this, 'logic_run' ] );
    }

    public function verify_remote( $k = null ) {
        if ( !$k ) $k = get_option( 'hgp_license_key' );
        if ( empty( $k ) ) {
            update_option( 'hgp_ls', 'inv' );
            return ['status' => 'error', 'message' => 'کلید موجود نیست'];
        }

        $d = home_url();
        $u = $this->v_url . '&key=' . $k . '&domain=' . $d;
        
        $r = wp_remote_get( $u, [ 'timeout' => 15, 'sslverify' => false ] );

        if ( is_wp_error( $r ) ) return ['status' => 'err', 'message' => 'Connection Error'];

        $b = json_decode( wp_remote_retrieve_body( $r ), true );

        if ( isset( $b['status'] ) && $b['status'] === 'valid' ) {
            update_option( 'hgp_ls', 'val' ); // val = valid
            // تله امنیتی: ذخیره یک هش خاص که اگر نباشد افزونه کار نمیکند
            update_option( 'hgp_sec_hash', md5($d . 'SALT_HOOMAN') ); 
            return ['status' => 'valid', 'message' => $b['message']];
        } else {
            update_option( 'hgp_ls', 'inv' );
            delete_option( 'hgp_sec_hash' );
            return ['status' => 'error', 'message' => $b['message'] ?? 'Invalid'];
        }
    }

    public function logic_run() {
        // چک کردن تله امنیتی
        $h = get_option('hgp_sec_hash');
        $d = home_url();
        if ( $h !== md5($d . 'SALT_HOOMAN') ) {
            // اگر هش دستکاری شده بود، قیمت را آپدیت نکن
            return; 
        }

        if ( ! self::is_active() ) return;

        $p = get_option( 'hgp_api_provider', 'navasan' );
        if ( $p === 'navasan' ) {
            $this->n_fetch();
        } else {
            $this->c_fetch();
        }
    }

    private function n_fetch() {
        $k = get_option( 'hgp_navasan_token' );
        if ( empty($k) ) return;
        $u = "http://api.navasan.tech/latest/?api_key=" . $k . "&item=18ayar"; 
        $r = wp_remote_get( $u );
        if ( is_wp_error( $r ) ) return;
        $b = json_decode( wp_remote_retrieve_body( $r ), true );
        if ( isset( $b['18ayar']['value'] ) ) {
            $pr = floatval( str_replace(',', '', $b['18ayar']['value']) );
            $this->up_db($pr);
        }
    }

    private function c_fetch() {
        $u = get_option( 'hgp_custom_api_url' );
        if ( empty($u) ) return;
        $r = wp_remote_get( $u, [ 'timeout' => 15 ] );
        if ( is_wp_error( $r ) ) return;
        $d = json_decode( wp_remote_retrieve_body( $r ), true );
        $path = get_option( 'hgp_custom_json_path' );
        $v = $d;
        if ( ! empty($path) ) {
            $keys = explode( '->', $path );
            foreach ( $keys as $k ) {
                if ( is_array($v) && isset($v[$k]) ) $v = $v[$k];
                elseif ( is_object($v) && isset($v->$k) ) $v = $v->$k;
                else return;
            }
        }
        $pr = preg_replace( '/[^0-9.]/', '', $v );
        $this->up_db( floatval($pr) );
    }

    private function up_db( $p ) {
        if ( $p > 0 ) {
            update_option( 'hgp_base_gold_price', $p );
            update_option( 'hgp_last_update_time', current_time('mysql') );
        }
    }

    public static function is_active() {
        return get_option( 'hgp_ls' ) === 'val';
    }
}