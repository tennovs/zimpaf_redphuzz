<?php
/**
 * Email template
 * 
 * */
 if( ! defined("ABSPATH" ) )
        die("Not Allewed");

// style Email
$tmp_container = "-webkit-box-shadow:0 0 0 3px rgba(0,0,0,0.025) !important;
       box-shadow:0 0 0 3px rgba(0,0,0,0.025) !important;
       -webkit-border-radius:6px !important;
       border-radius:6px !important;
       background-color: #fafafa;
       border-radius:6px !important;";
$tmp_header = "background-color: #63b1bb;
        color: #f1f1f1;
        font-family:Arial;
        font-weight:bold;
        line-height:100%;
        vertical-align:middle;";
$tmp_email_logo = "margin: 0;
        padding: 8px 0;
        font-size: 30px;
        font-weight: bold;
        text-align: center;
        line-height: 150%;
        color: #ffffff;
        font-family: serif !important;
        font-variant: petite-caps;";
$tmp_body = "color: #4e4c4c;
        font-family:Arial;
        font-size: 14px;
        line-height:150%;
        text-align:left;";
$tmp_footer = "border-top:1px solid #E2E2E2;
        background: #a29c891a;
        -webkit-border-radius:0px 0px 6px 6px;
        -o-border-radius:0px 0px 6px 6px;
        -moz-border-radius:0px 0px 6px 6px;
        border-radius:0px 0px 6px 6px;";
$tmp_footer_inner = "border: 0;
        border: 0;
        color: #777;
        font-family: Arial;
        font-size: 11px;
        line-height: 0%;
        text-align: center;
        padding: 0px;";
        
$site_name = get_bloginfo('name');
 ?>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
    <tbody>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="776" id="template_container" style="<?php echo esc_attr($tmp_container); ?>" class="wpr_template_container">
                    <tbody style="background-color: #f7e8bc40;border: 2px solid #82a8c51a;
                                        display: block;">
                        <tr>
                            <td align="center" valign="top">
                                <!-- Header -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="<?php echo esc_attr($tmp_header); ?>">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h1 style="<?php echo esc_attr($tmp_email_logo); ?>" id="logo">
                                                    <a style="color: #f1f1f1;text-decoration: none;" title="<?php echo $site_name; ?>" target="_self"><?php echo $site_name; ?></a>
                                               </h1>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- End Header -->
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">
                                <!-- Body -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="background-color: #ffffffc7;" id="mailtpl_body_bg">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top">
                                                                <div style="<?php echo esc_attr($tmp_body); ?>" id="mailtpl_body" >

                                                                    <p>%WPFM_EMAIL_CONTENT%</p>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- End Body -->
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top">
                            <!-- Footer -->
                               <table border="0" cellpadding="10" cellspacing="0" width="100%"  style="<?php echo esc_attr($tmp_footer); ?>" id="template_footer">
                                    <tbody>
                                        <tr>
                                            <td valign="top">
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2" valign="middle" style="<?php echo esc_attr($tmp_footer_inner); ?>" id="credit"><?php echo $site_name; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- End Footer -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>