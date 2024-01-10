<?php
/**
 * Google Tag Manager
 *
 * This file contains the functions necessary to add the RCID Google Analytics and Tag Manager snippets to the site.
 *
 * @package   Core_Functionality
 * @since     1.1.0
 * @link      https://github.com/Herm71/rcid-core-functionality.git
 * @author    Jason Chafin
 * @copyright Copyright (c) 2011, Jason Chafin
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

add_action('wp_head', 'rcid_google_tag_manager_head' );
add_action('wp_body_open', 'rcid_google_tag_manager_body' );

function rcid_google_tag_manager_head()
{
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5K5NV59S');</script>
    <!-- End Google Tag Manager -->
    <?php

}

function rcid_google_tag_manager_body()
{
    ?>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5K5NV59S"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php
}



