<?php
// if (($this->error->getCode()) == '404') {
// header('Location: /index.php?option=com_content&view=article&id=10');
// exit;
// }
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="abstract" content="ChangeMe" />
<meta name="author" content="Fine Line Websites &amp; IT Consulting" />
<meta name="copyright" content="Copyright (c) 2008-<? echo date('Y'); ?> by Fine Line Websites &amp; IT Consulting" />
<meta name="language" content="en-us" >
<meta name="distribution" content="Global">
<meta name="geo.placename" content="United States">
<meta name="geo.position" content="39.105008,-75.551602">
<meta name="geo.region" content="US-DE">
<meta charset="UTF-8">
<jdoc:include type="head" />
<!--[if lt IE 9]>
    <script src="/media/jui/js/html5.js"></script>
  <![endif]-->
<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/templates/fluid/images/ico/apple-touch-icon-144.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/templates/fluid/images/ico/apple-touch-icon-114.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/templates/fluid/images/ico/apple-touch-icon-72.png">
<link rel="apple-touch-icon-precomposed" href="/templates/fluid/images/ico/apple-touch-icon-57.png">
<link rel="shortcut icon" href="/templates/fluid/images/ico/favicon.png">
<link rel="stylesheet" href="/templates/fluid/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="/templates/fluid/css/template.css" type="text/css" />
  <link rel="stylesheet" href="/templates/fluid/css/layout.css" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
</head>
<script type="application/ld+json">
{
	"@context" : "http://schema.org",
	"@type" : "LocalBusiness",
	"name" : "COMPANY NAME",
	"logo" : "http://www.urltogohere.com/companylogo.png",
	"telephone" : "000-000-0000",
	"faxNumber" : "000-000-0000",
	"description" : "A BREIF STATEMENT ABOUT THE COMPANY, SERVICE"
	"address" : {
		"@type" : "PostalAddress",
		"streetAddress" : "COMPANY ADDRESS",
		"addressLocality" : "CITY",
		"addressRegion" : "Delaware",
		"addressCountry" : "United States",
		"postalCode" : "ZIPCODE" },
	"geo" : {
		"@type" : "GeoCoordinates",
		"latitude" : "39.105008",
		"longitude" : "-75.551602"},
	"url" : "SITE URL"
	"map" : "URL LINK TO MAP"
}
</script>
<body class="site-error">
<div id="wrapper-outer-body">
  <div id="wrapper-middle-body">
    <div id="wrapper-inner-body">
       
        <!-- Header -->
        <div id="wrapper-header" class="clearfix">
        <div id="wrapper-header-container" class="container">
          <a href="/" id="logo"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/layout/spacer.gif"  alt="ChangeMe" title="ChangeMe" /></a>
          </div>
		   
        </div>
        <!-- /Header --> 
       
        <div id="wrapper-content">
        <div class="container">
        <div id="page-content" class="row-fluid">
		
<h3>404: Not Found</h3>
<p>Sorry, but the content you requested could not be found.<br>
Please visit our <a href="/">home page</a> and use the navigation.</p>
        </div>
        <!-- /Row-Fluid --> 
        </div>
        </div>
    </div>
    <!-- /wrapper-inner-body --> 
  </div>
  <!-- /wrapper-middle-body --> 
</div>
<!-- /wrapper-outer-body --> 

<!-- Footer -->
<div id="wrapper-outer-footer">
  <div id="wrapper-middle-footer">
    <div id="wrapper-inner-footer" class="container">
	<div id="footer-bottom" class="clearfix">

      </div>
      <!-- /Footer Bottom -->
     
    </div>
    <div id="credits" class="clearfix container">
      <div id="sitebyfineline">site by <a title="Web site design by Fine Line Websites & IT Consulting" target="_blank" href="http://www.finelinewebsites.com"><span class="flFine">Fine</span> <span class="flLine">Line</span> Websites</a></div>
    </div>
  </div>
</div>
<!-- /Footer --> 
<script type="text/javascript">

  var _gaq = _gaq || [];
  var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
  _gaq.push(['_require', 'inpage_linkid', pluginUrl]);
  _gaq.push(['_setAccount', 'UA-XXXXX-X']);
  _gaq.push(['_trackEvent', 'Error', '404', 'page: ' + document.location.pathname + document.location.search + ' ref: ' + document.referrer ]);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>