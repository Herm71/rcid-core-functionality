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

add_action('wp_body_open', 'rcid_google_tag_manager');

function rcid_google_tag_manager()
{
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BLMEKZLMMC"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-BLMEKZLMMC');
    </script>
    <?php
}



