<?php
/*
Plugin Name: Vimeo Fancybox Video Gallery
Plugin URI: https://github.com/toscano/vimeo-fancybox-video-galery
Description: Its an wordpress plugin that allow users to embed Vimeo videos on pages and posts by entering shortcode [vimeo video_id='video_id' caption='your video label']
Version: 1.1
Author: Marcelo Toscano
Author URI: http://toscano.com.br
*/

function vfvg_get($url) {
    if (function_exists(curl_init)) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($curl);
        curl_close($curl);
        return $return;
    }    
}

function getVimeo($attr) {
    // You may want to use oEmbed discovery instead of hard-coding the oEmbed endpoint.
    $oembed_endpoint = 'http://vimeo.com/api/oembed';
    // Grab the video url from the url, or use default
    $video_url = ($_GET['url']) ? $_GET['url'] : 'http://vimeo.com/'.$attr['video_id'];
    // Create the URLs
    $json_url = $oembed_endpoint . '.json?url=' . rawurlencode($video_url) . '&width=640';
    vfvg_get($json_url);//get all information from vimeo
    $oembed = json_decode(vfvg_get($json_url)); //decode json from vimeo
    echo html_entity_decode("<a class='fancybox-media' href='http://vimeo.com/".$attr['video_id']."'><img class='vimeo_video' src='".$oembed->thumbnail_url."' width='170' height='112' /><p class='caption'>".$attr['caption']." </p></a>");
    ?>
    <?php 
}

function vimeoFeedHeader(){   
?>
    <!-- Vimeo Fancybox Video Gallery -->
    <script type="text/javascript" src="<?=plugins_url('fancybox/lib/jquery-1.9.0.min.js', __FILE__); ?>"></script>
    <script type="text/javascript" src="<?=plugins_url('fancybox/lib/jquery.mousewheel-3.0.6.pack.js', __FILE__); ?>"></script>
    <script type="text/javascript" src="<?=plugins_url('fancybox/source/jquery.fancybox.js?v=2.1.4', __FILE__); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?=plugins_url('fancybox/source/jquery.fancybox.css?v=2.1.4', __FILE__); ?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?=plugins_url('fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5', __FILE__) ?>" />
    <script type="text/javascript" src="<?=plugins_url('fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5', __FILE__); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?=plugins_url('fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7', __FILE__) ?> " />
    <script type="text/javascript" src="<?=plugins_url('fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7', __FILE__); ?>"></script>
    <script type="text/javascript" src="<?=plugins_url('/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5', __FILE__); ?> "></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.fancybox-media')
                .fancybox({
                    openEffect : 'none',
                    closeEffect : 'none',
                    prevEffect : 'none',
                    nextEffect : 'none',
                    arrows : false,
                    helpers : {
                        media : {},
                        buttons : {}
                    }
                });
        });
    </script>
    <style type="text/css">
        .fancybox-custom .fancybox-skin { box-shadow: 0 0 50px #222; }
        .fancybox-media { float: left; margin-right:10px; }        
        .caption { text-align: center; font-size: 12px; }
    </style>
    <!-- End - Vimeo Fancybox Video Gallery -->
<?php
}
add_action('wp_head','vimeoFeedHeader');
add_shortcode("vimeo","getVimeo");
?>