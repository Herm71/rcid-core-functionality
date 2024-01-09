<?php

/**
 * Google Tag Manager

 * This file contains the functions necessary to add the RCID Google Analytics and Tag Manager snippets to the site.
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



