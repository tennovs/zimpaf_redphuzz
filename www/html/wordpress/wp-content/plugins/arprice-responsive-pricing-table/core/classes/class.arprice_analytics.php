<?php

class arpricelite_analytics {

    function __construct() {
        add_shortcode('ARPLite', array($this, 'arplite_Shortcode'));
        add_action('wp_ajax_arp_get_template_chart', array($this, 'arp_get_template_charts'));
        add_action('wp_ajax_arplite_insert_plan_id', array($this, 'arp_insert_plan_id'));
        add_action('wp_ajax_nopriv_arplite_insert_plan_id', array($this, 'arp_insert_plan_id'));
        add_action('wp_ajax_arplite_analytic_table', array($this, 'arp_analytic_table'));
        add_action('wp_ajax_arplite_get_basic_area_weekly', array($this, 'arp_get_basic_area_weekly'));
        add_action('wp_ajax_arplite_get_basic_area_daily', array($this, 'arp_get_basic_area_daily'));
        add_action('init',array($this,'arplite_init_session'),10);
    }

    function arplite_init_session(){

        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            if( ( function_exists('session_status') && session_status() == PHP_SESSION_NONE && !is_admin() ) ) {
                @session_start(
                    array(
                        'read_and_close' => false
                    )
                );
            }
        } else if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            if ( ( function_exists('session_status') && session_status() == PHP_SESSION_NONE && !is_admin()  ) ) {
                @session_start();
            }
        } else {
            if( ( session_id() == '' && !is_admin() ) ) {
                @session_start();
            }
        }

    }

    function arplite_Shortcode($atts) {

        global $wpdb, $arpricelite_analytics;

        extract(
            shortcode_atts(
                array(
                    'id' => '1'
                ),
                $atts
            )
        );

        $table_id = $atts['id'];

        if ($table_id == "") {
            $table_id = 1;
        }

        $result = $wpdb->get_row($wpdb->prepare("select * from " . $wpdb->prefix . "arplite_arprice where ID=%d", $table_id));
        $pricetable_name = isset($result) ? $result->table_name : '';
        if ($pricetable_name == "") {
            return esc_html__("Please Select Valid Pricing Table","arprice-responsive-pricing-table");
        } else if ($result->status != 'published') {
            return esc_html__("Please Select Valid Pricing Table","arprice-responsive-pricing-table");
        } else if ($result->is_template == 1) {
            return "";
        }


        $country_name = "";

        $d = date("Y/m/d H:i:s");

        $brow = $_SERVER['HTTP_USER_AGENT'];

        $pageurl = $_SERVER['REQUEST_URI'];

        $ref = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $ip = $_SERVER['REMOTE_ADDR'];

        $ses_id = session_id();

        $browser = $arpricelite_analytics->getBrowser($brow);


        $sel = $wpdb->get_row($wpdb->prepare("SELECT tracking_id, session_id FROM " . $wpdb->prefix . "arplite_arprice_analytics WHERE pricing_table_id = %d AND session_id = %s", $table_id, $ses_id));

        if ($sel) {
            require_once ARPLITE_PRICINGTABLE_DIR . '/core/views/arprice_front.php';

            $contents = arplite_get_pricing_table_string($table_id);

            $contents = apply_filters('arplite_predisplay_pricingtable', $contents, $table_id);

            return $contents;
        }

        $res = $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "arplite_arprice_analytics (pricing_table_id,browser_name,browser_version,page_url,ip_address,country_name,session_id,added_date ) VALUES (%d,%s,%s,%s,%s,%s,%s,%s)", $table_id, sanitize_text_field($browser['browser_name']), sanitize_text_field($browser['version']), esc_url_raw($pageurl), sanitize_text_field($ip), sanitize_text_field($country_name), $ses_id, $d));


        require_once ARPLITE_PRICINGTABLE_DIR . '/core/views/arprice_front.php';

        $contents = arplite_get_pricing_table_string($table_id);

        $contents = apply_filters('arplite_predisplay_pricingtable', $contents, $table_id);

        return $contents;
    }

    function getBrowser($user_agent) {
        $u_agent = $user_agent;
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        } elseif (preg_match('/Android/i', $u_agent)){
            $platform = 'android';
        } elseif (preg_match('/iphone|ipad/i', $u_agent)) {
            $platform = 'iOS';
        }

        if ($platform != 'Unknown') {

            if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
            } elseif (preg_match('/Firefox/i', $u_agent)) {
                $bname = 'Mozilla Firefox';
                $ub = "Firefox";
            } elseif (preg_match('/Opera/i', $u_agent)) {
                $bname = 'Opera';
                $ub = "Opera";
            } elseif (preg_match('/Netscape/i', $u_agent)) {
                $bname = 'Netscape';
                $ub = "Netscape";
            } elseif (strpos($user_agent, 'Trident') !== FALSE) {
                $bname = 'Internet Explorer';
                $ub = "Trident";
            } else if (preg_match('/Edge/i', $u_agent)) {
                $bname = 'Microsoft Edge';
                $ub = "Edge";
            } else if (preg_match('/OPR/i', $u_agent)) {
                $bname = 'Opera';
                $ub = "Opera";
            } elseif (preg_match('/Chrome/i', $u_agent)) {
                $bname = 'Google Chrome';
                $ub = "Chrome";
            } elseif (preg_match('/Safari/i', $u_agent)) {
                $bname = 'Apple Safari';
                $ub = "Safari";
            }
        }

        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            
        }


        $i = count($matches['browser']);
        if ($i != 1) {

            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = isset($matches['version'][1]) ? $matches['version'][1] : "";
            }
        } else {
            $version = $matches['version'][0];
        }

        if ($ub == "Trident") {
            $version = "11";
        }


        if ($version == null || $version == "") {
            $version = "?";
        }
        return array('browser_name' => $bname, 'version' => $version);
    }

    function arp_retrieve_template_views($template_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'arplite_arprice_analytics';

        $counter = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' AND is_click='0'");

        return $counter;
    }

    function arp_get_template_charts() {
        global $wpdb, $arpricelite_analytics_chart, $arpricelite_analytics;
        $template_id = isset( $_POST['template_id'] ) ? intval( $_POST['template_id'] ) : '';
        $start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
        $end_date = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';
        $table = $wpdb->prefix . 'arplite_arprice_analytics';

        $browser_chart = array();
        $country_chart = array();

        $data = array();
        $get_country_list = $wpdb->get_results("SELECT country_name FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and is_click='0'");

        foreach ($get_country_list as $key => $country) {
            $country_name = $country->country_name;
            $country_counter = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and country_name = '" . $country_name . "' and is_click='0'");

            $data['country'][$country_name] = $country_counter;
        }

        $get_browser_list = $wpdb->get_results("SELECT DISTINCT browser_name FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "'");
        foreach ($get_browser_list as $key => $browser) {
            $browser_name = $browser->browser_name;
            $browser_counter = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and browser_name = '" . $browser_name . "'");
            $data['browser'][$browser_name] = $browser_counter;
        }

        $browser_chart['chart'] = array(
            'type' => "area",
        );
        $browser_chart['colors'] = array(
            '#49c5f8', '#c9ffdf'
        );

        $browser_chart['title'] = array(
            'text' => ''
        );

        $dt = new DateTime($start_date, new DateTimeZone('UTC'));
        $browser_chart['xAxis'] = array(
            'title' => array(
                'text' => esc_html__('Time Period',"arprice-responsive-pricing-table")
            ),
            'pointStart' => $dt->getTimestamp() * 1000,
            'type' => 'datetime',
            'minTickInterval' => 24 * 3600 * 1000 * 7,
            'labels' => array(
                'rotation' => -70,
            ),
            'tickPixelInterval' => 10
        );

        $browser_chart['yAxis'] = array(
            'title' => array(
                'text' => esc_html__('Clicks And Visits',"arprice-responsive-pricing-table"),
                'style' => array(
                ),
            ),
        );
        $dt = new DateTime($start_date, new DateTimeZone('UTC'));
        $browser_chart['plotOptions'] = array(
            'area' => array(
                'pointStart' => $dt->getTimestamp() * 1000,
                'pointInterval' => 24 * 3600 * 1000,
                'marker' => array(
                    'enabled' => false,
                    'symbol' => 'circle',
                    'radius' => 2,
                    'states' => array(
                        'hover' => array(
                            'enabled' => true,
                        )
                    )
                ),
            ),
        );

        $browser_chart['no_of_views'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($start_date))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($end_date))) . "' and is_click='0'");
        $browser_chart['no_of_clicks'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($start_date))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($end_date))) . "' and is_click='1'");
        if ($browser_chart['no_of_clicks'] != 0) {
            $browser_chart['conversion'] = ($browser_chart['no_of_clicks'] * 100) / $browser_chart['no_of_views'];
            $browser_chart['conversion'] = number_format((float) $browser_chart['conversion'], 2);
        } else {
            $browser_chart['conversion'] = 0;
        }

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = new DateInterval('P1D');
        $start->setTime(0, 0);
        $end->setTime(0, 0)->add($interval);
        $periods = new DatePeriod($start, $interval, $end);

        $view_data = array();
        $click_data = array();
        if (!empty($periods)) {
            foreach ($periods as $key => $interval_date) {

                $final_views['views'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($interval_date->format('Y-m-d')))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($interval_date->format('Y-m-d')))) . "' and is_click='0'");
                $final_views['clicks'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($interval_date->format('Y-m-d')))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($interval_date->format('Y-m-d')))) . "' and is_click='1'");

                array_push($view_data, (int) $final_views['views']);
                array_push($click_data, (int) $final_views['clicks']);
            }
        }

        $series = array(
            array(
                'name' => esc_html__("Visits","arprice-responsive-pricing-table"),
                'data' => $view_data,
            ),
            array(
                'name' => esc_html__("Clicks","arprice-responsive-pricing-table"),
                'data' => $click_data,
            ),
        );


        $browser_chart['series'] = $series;

        $browser_chart['legend'] = array(
            'symbolHeight' => 10,
            'symbolWidth' => 10,
            'symbolRadius' => 5,
        );

        $country_chart['chart'] = array(
            'type' => 'pie',
        );

        $country_chart['title'] = array(
            'text' => ''
        );

        $country_chart['tooltip'] = array(
            'tooltip' => array(
                'pointFormat' => '<b>{point.name}:{point.y}</b>'
            )
        );

        $country_chart['legend'] = array(
            'align' => 'right',
            'verticalAlign' => 'top',
            'layout' => 'vertical',
            'x' => 0,
            'y' => 100,
            'symbolHeight' => 10,
            'symbolWidth' => 10,
            'symbolRadius' => 5
        );

        $country_chart['plotOptions'] = array(
            'pie' => array(
                'depth' => 100,
                'innerSize' => 70,
                'showInLegend' => true,
            ),
        );

        $country_chart_color['colors'] = array('#6ec1e9', '#01d7cb', '#56d456', '#bad709', '#f9d900', '#ffa801', '#ef6565', '#f471cf', '#9b4e96', '#51dd89');
        $color_counter = count($country_chart_color['colors']);
        $data_chart_c = array();
        $data_color = array();
        $series_data_country = array();
        arsort($data['country']);
        $other_entry = 0;
        if (!empty($data['country'])) {
            $i = 0;
            $y = 0;

            foreach ($data['country'] as $country => $counter) {
                if ($y <= 8 && $country != '') {
                    $data_chart_c['name'] = $country;
                    $data_chart_c['y'] = (int) $counter;
                    $data_chart_c['color'] = $country_chart_color['colors'][$i];
                    $i++;
                    $y++;
                    array_push($series_data_country, $data_chart_c);
                } else {
                    $other_entry = 1;
                    $other_country = 0;
                    $other_country = $other_country + $counter;
                }
            }
            if ($other_entry == 1) {
                $data_chart_c['name'] = esc_html__('Others',"arprice-responsive-pricing-table");
                $data_chart_c['y'] = $other_country;
                $data_chart_c['color'] = $country_chart_color['colors'][$color_counter - 1];
                array_push($series_data_country, $data_chart_c);
            }
        }

        $country_series = array(
            array(
                'name' => 'Countries',
                'data' => $series_data_country
            )
        );

        $country_chart['series'] = $country_series;

        $final_data['browser'] = $browser_chart;
        $final_data['country'] = $country_chart;
        $final_data['arp_table'] = $arpricelite_analytics->arp_analytic_table($template_id);
        echo wp_json_encode($final_data);
        die();
    }

    function arp_analytic_table($template_id) {
        global $wpdb;
        $arp_table = array();

        $table = $wpdb->prefix . 'arplite_arprice_analytics';
        $sel = $wpdb->get_results("SELECT * FROM $table WHERE pricing_table_id = '" . $template_id . "' AND is_click='0'");
        $arp_table_row_data = array();
        $arp_row_data = array();
        $i = 0;
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        foreach ($sel as $key => $value) {

            $arp_row_data['date'] = date($date_format . ' ' . $time_format, strtotime($value->added_date));
            $arp_row_data['browser'] = $value->browser_name . ' (' . $value->browser_version . ')';
            $arp_row_data['ip_address'] = $value->ip_address;
            $arp_row_data['country'] = $value->country_name;
            $sele = $wpdb->get_results("SELECT plan_id FROM $table WHERE pricing_table_id = '" . $template_id . "' AND is_click='1' AND ip_address='" . $value->ip_address . "' AND session_id='" . $value->session_id . "'");

            if (empty($sele[0])) {
                $i = $i + 1;
                $arp_row_data['click'] = esc_html__('No',"arprice-responsive-pricing-table");
                $arp_row_data['sr_no'] = $i;
                array_push($arp_table_row_data, $arp_row_data);
            } else {
                foreach ($sele as $obj_id => $plan_id) {
                    $i = $i + 1;
                    $plan_id = explode("_", $plan_id->plan_id);
                    $arp_row_data['click'] = 'Yes (Plan ' . $plan_id[2] . ')';
                    $arp_row_data['sr_no'] = $i;
                    array_push($arp_table_row_data, $arp_row_data);
                }
            }
        }

        return $arp_table_row_data;
    }

    function arp_insert_plan_id() {

        global $wpdb, $arpricelite_analytics;

        $arp_plan_id = isset($_POST['arp_plan_id']) ? sanitize_text_field( $_POST['arp_plan_id'] ) : '';

        $arp_template_id = isset($_POST['arp_template_id']) ? sanitize_text_field( $_POST['arp_template_id'] ) : '';

        $caption_column = isset( $_POST['caption_column'] ) ? intval( $_POST['caption_column'] ) : 0;

        if ($caption_column == 0) {
            $plan_id = explode("_", $arp_plan_id);
            $plan_id[2] = $plan_id[2] + 1;
            $arp_plan_id = 'plan_id_' . $plan_id[2];
        }
        if ($caption_column == 1) {
            $plan_id = explode("_", $arp_plan_id);
            $arp_plan_id = 'plan_id_' . $plan_id[2];
        }

        $country_name = "";

        $d = date("Y/m/d H:i:s");

        $brow = $_SERVER['HTTP_USER_AGENT'];

        $pageurl = $_SERVER['REQUEST_URI'];

        $ref = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';

        $ip = $_SERVER['REMOTE_ADDR'];

        $ses_id = session_id();

        $browser = $arpricelite_analytics->getBrowser($brow);

        $sel = $wpdb->get_row($wpdb->prepare("SELECT plan_id,is_click,tracking_id FROM " . $wpdb->prefix . "arplite_arprice_analytics WHERE pricing_table_id = " . $arp_template_id . " AND ip_address ='" . $ip . "' AND is_click='1' AND plan_id='" . $arp_plan_id . "' AND session_id = %s", $ses_id));

        if ($sel) {
            
        } else {
            $is_click = 1;
            $res = $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "arplite_arprice_analytics (pricing_table_id,browser_name,browser_version,page_url,ip_address,country_name,session_id,added_date,is_click,plan_id ) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%d,%s)", $arp_template_id, sanitize_text_field($browser['browser_name']), sanitize_text_field($browser['version']), esc_url_raw($pageurl), sanitize_text_field($ip), sanitize_text_field($country_name), $ses_id, $d, $is_click, $arp_plan_id));
        }

        die();
    }

    function arp_get_basic_area_weekly() {
        global $wpdb, $arpricelite_analytics_chart, $arpricelite_analytics;
        $template_id = isset($_POST['template_id']) ? intval($_POST['template_id']) : '';
        $start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
        $end_date = isset( $_POST['end_date'] ) ? sanitize_text_field($_POST['end_date']) : '';
        $table = $wpdb->prefix . 'arplite_arprice_analytics';

        $browser_chart['no_of_views'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($start_date))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($end_date))) . "' and is_click='0'");
        $browser_chart['no_of_clicks'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($start_date))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($end_date))) . "' and is_click='1'");
        if ($browser_chart['no_of_clicks'] != 0) {
            $browser_chart['conversion'] = ($browser_chart['no_of_clicks'] * 100) / $browser_chart['no_of_views'];
            $browser_chart['conversion'] = number_format((float) $browser_chart['conversion'], 2);
        } else {
            $browser_chart['conversion'] = 0;
        }

        $browser_chart['colors'] = array(
            '#49c5f8', '#c9ffdf'
        );

        $browser_chart['chart'] = array(
            'type' => "area",
        );

        $browser_chart['title'] = array(
            'text' => ''
        );

        $browser_chart['xAxis'] = array(
            'title' => array(
                'text' => 'Time Period'
            ),
            'type' => 'datetime',
            'tickInterval' => 7 * 24 * 3600 * 1000,
            'labels' => array(
                'rotation' => -70,
            ),
        );

        $browser_chart['yAxis'] = array(
            'title' => array(
                'text' => 'Clicks And Visits'
            ),
        );

        $browser_chart['legend'] = array(
            'symbolHeight' => 10,
            'symbolWidth' => 10,
            'symbolRadius' => 5,
        );

        $dt = new DateTime($start_date, new DateTimeZone('UTC'));
        $browser_chart['plotOptions'] = array(
            'area' => array(
                'pointStart' => $dt->getTimestamp() * 1000,
                'pointInterval' => 24 * 3600 * 1000,
                'marker' => array(
                    'enabled' => false,
                    'symbol' => 'circle',
                    'radius' => 2,
                    'states' => array(
                        'hover' => array(
                            'enabled' => true,
                        )
                    )
                ),
            ),
        );

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = new DateInterval('P1D');
        $start->setTime(0, 0);
        $end->setTime(0, 0)->add($interval);
        $periods = new DatePeriod($start, $interval, $end);

        $view_data = array();
        $click_data = array();
        if (!empty($periods)) {
            foreach ($periods as $key => $interval_date) {
                $final_views['views'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($interval_date->format('Y-m-d')))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($interval_date->format('Y-m-d')))) . "' and is_click='0'");
                $final_views['clicks'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($interval_date->format('Y-m-d')))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($interval_date->format('Y-m-d')))) . "' and is_click='1'");

                array_push($view_data, (int) $final_views['views']);
                array_push($click_data, (int) $final_views['clicks']);
            }
        }

        $series = array(
            array(
                'name' => esc_html__("Visits","arprice-responsive-pricing-table"),
                'data' => $view_data,
            ),
            array(
                'name' => esc_html__("Clicks","arprice-responsive-pricing-table"),
                'data' => $click_data,
            ),
        );


        $browser_chart['series'] = $series;


        $final_data['browser'] = $browser_chart;

        echo wp_json_encode($final_data);

        die();
    }

    function arp_get_basic_area_daily() {
        global $wpdb, $arpricelite_analytics_chart, $arpricelite_analytics;
        $template_id = isset( $_POST['template_id'] ) ? intval( $_POST['template_id'] ) : '';
        $start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
        $end_date = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';
        $table = $wpdb->prefix . 'arplite_arprice_analytics';

        $browser_chart['no_of_views'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($start_date))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($end_date))) . "' and is_click='0'");
        $browser_chart['no_of_clicks'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($start_date))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($end_date))) . "' and is_click='1'");
        if ($browser_chart['no_of_clicks'] != 0) {
            $browser_chart['conversion'] = ($browser_chart['no_of_clicks'] * 100) / $browser_chart['no_of_views'];
            $browser_chart['conversion'] = number_format((float) $browser_chart['conversion'], 2);
        } else {
            $browser_chart['conversion'] = 0;
        }

        $browser_chart['chart'] = array(
            'type' => "area",
        );

        $browser_chart['colors'] = array(
            '#49c5f8', '#c9ffdf'
        );

        $browser_chart['title'] = array(
            'text' => ''
        );

        $browser_chart['xAxis'] = array(
            'title' => array(
                'text' => esc_html__('Time Period',"arprice-responsive-pricing-table")
            ),
            'type' => 'datetime',
            'tickInterval' => 24 * 3600 * 1000,
            'labels' => array(
                'rotation' => -70,
            ),
        );

        $browser_chart['yAxis'] = array(
            'title' => array(
                'text' => esc_html__('Clicks And Visits',"arprice-responsive-pricing-table")
            ),
        );
        $dt = new DateTime($start_date, new DateTimeZone('UTC'));
        $browser_chart['plotOptions'] = array(
            'area' => array(
                'pointStart' => $dt->getTimestamp() * 1000,
                'pointInterval' => 24 * 3600 * 1000,
                'marker' => array(
                    'enabled' => false,
                    'symbol' => 'circle',
                    'radius' => 2,
                    'states' => array(
                        'hover' => array(
                            'enabled' => true,
                        )
                    )
                ),
            ),
        );

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = new DateInterval('P1D');
        $start->setTime(0, 0);
        $end->setTime(0, 0)->add($interval);
        $periods = new DatePeriod($start, $interval, $end);

        $view_data = array();
        $click_data = array();
        if (!empty($periods)) {
            foreach ($periods as $key => $interval_date) {
                $final_views['views'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($interval_date->format('Y-m-d')))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($interval_date->format('Y-m-d')))) . "' and is_click='0'");
                $final_views['clicks'] = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . " WHERE pricing_table_id = '" . $template_id . "' and added_date BETWEEN '" . (date('Y-m-d 00:00:00', strtotime($interval_date->format('Y-m-d')))) . "' AND '" . (date('Y-m-d 23:59:59', strtotime($interval_date->format('Y-m-d')))) . "' and is_click='1'");

                array_push($view_data, (int) $final_views['views']);
                array_push($click_data, (int) $final_views['clicks']);
            }
        }

        $series = array(
            array(
                'name' => esc_html__("Visits","arprice-responsive-pricing-table"),
                'data' => $view_data,
            ),
            array(
                'name' => esc_html__("Clicks","arprice-responsive-pricing-table"),
                'data' => $click_data,
            ),
        );
        $browser_chart['legend'] = array(
            'symbolHeight' => 10,
            'symbolWidth' => 10,
            'symbolRadius' => 5,
        );

        $browser_chart['series'] = $series;

        $final_data['browser'] = $browser_chart;

        echo wp_json_encode($final_data);

        die();
    }

}

?>