<?php
$isPlaylist = false;
$url = $params->get('url', "");

if(strpos($url, ",") !== false) {
    $isPlaylist = true;
    $playlist = explode(",", $url);
}

$containment = $params->get('containment','body');
$loop = $params->get('loop',"true");
$mute = $params->get('mute',"true");
$autoPlay = $params->get('autoPlay',"true");
$showYTLogo= $params->get('showYTLogo',"false");
$showControls = $params->get('showControls',"false");
$stopMovieOnBlur = $params->get('stopMovieOnBlur',"false");

if($isPlaylist) {
    foreach($playlist as $url) {
        $options = "videoURL:'".$url."',";
        $options .= "containment:'".$containment."',";
        $options .= "autoPlay:".$autoPlay.",";
        $options .= "mute:".$mute.",";
        $options .= "loop:".$loop.",";
        $options .= "showYTLogo:".$showYTLogo.",";
        $options .= "stopMovieOnBlur:".$stopMovieOnBlur.",";
        $options .= "showControls:".$showControls."";
        $videos[] = "{".$options."}";
    }
} else {
    $options = "videoURL:'".$url."',";
    $options .= "containment:'".$containment."',";
    $options .= "autoPlay:".$autoPlay.",";
    $options .= "mute:".$mute.",";
    $options .= "loop:".$loop.",";
    $options .= "showYTLogo:".$showYTLogo.",";
    $options .= "stopMovieOnBlur:".$stopMovieOnBlur.",";
    $options .= "showControls:".$showControls."";
}
?>
<div id="bgndVideo" class="player" data-property="{<?php print $options; ?>}"></div>
<script>
    jQuery(document).ready(function() {
        <?php if($isPlaylist) { ?>
            var videos = <?php print "[".implode(",", $videos)."]"; ?>;
            jQuery("#bgndVideo").YTPlaylist(videos, false);
        <?php } else { ?>        
            jQuery("#bgndVideo").YTPlayer();
        <?php } ?>        
    });
</script>