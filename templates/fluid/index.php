<?php
/**
 * @package	 Joomla.Administrator
 * @subpackage  Templates.fluid
 * 
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license	 GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

///////// Google Tag Manager ///////////
$googleTagManagerId = "GTM-XXXXXXX";

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
$this->setGenerator(null);

$isMobile = $app->getUserState('cmobile.ismobile', false);
if($isMobile) {
	$deviceTypeBodyClass = " device-mobile ";
} else {
	$deviceTypeBodyClass = " device-desktop ";
}

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view	 = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task	 = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

// Add JavaScript Frameworks
JHtml::_('jquery.framework');
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', 'text/javascript', true, false);
JHtml::_('bootstrap.framework');
$doc->addScript('templates/' . $this->template . '/js/fluid.js', 'text/javascript', true, false);
$doc->addScript('templates/' . $this->template . '/js/bootstrap-sub-dropdown.js', 'text/javascript', true, false);
if(!$isMobile) {
	$doc->addScript('templates/' . $this->template . '/js/bootstrap-hover-dropdown.js', 'text/javascript');
}
$doc->addScript('templates/' . $this->template . '/js/jquery.modal.min.js', 'text/javascript');
$doc->addScript('https://code.jquery.com/ui/1.12.0/jquery-ui.min.js', 'text/javascript');

// Add Stylesheets
JHtmlBootstrap::loadCss();
$doc->addStyleSheet('templates/'.$this->template.'/css/jquery.modal.min.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/glyphicons.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/layout.css');
$doc->addStyleSheet('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
  
// Calculate main content area col size
$spanUsage = ($this->countModules('content-right') ? 4 : 0) + ($this->countModules('content-left') ? 4 : 0);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= $this->language; ?>" lang="<?= $this->language; ?>" dir="<?= $this->direction; ?>" itemscope="" itemtype="http://schema.org/WebPage">
	<head>
		{flschema-meta}
		{flschema-twitter-og}
		
		<jdoc:include type="head" />
		
		<!-- Google Fonts -->
		<link href="//fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
		
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo $googleTagManagerId;?>');</script>
	</head>

	<body class="site <?= $option
		. ' view-' . $view
	  	. ($layout ? ' layout-' . $layout : ' no-layout')
	  	. ($task ? ' task-' . $task : ' no-task')
	  	. ($itemid ? ' itemid-' . $itemid : '')
	  	. $deviceTypeBodyClass; ?>" role="document">
	  	
	  	<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $googleTagManagerId;?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

		<? if ($this->countModules('header-top') || $this->countModules('logo') || $this->countModules('header') || $this->countModules('header-bottom')) { ?>
			<header id="wrapper-header">
				<? if ($this->countModules('header-top')) { ?>
					<!-- Header-Top -->
					<div id="header-top">
						<div class="container">
							<div class="row">
								<jdoc:include type="modules" name="header-top" style="FLuid" />
							</div>
						</div>
					</div>
					<!-- /Header-Top -->
				<? } ?>
				<? if ($this->countModules('logo') || $this->countModules('header')) { ?>
					<!-- Header -->
					<div id="header">
						<div class="container">
							<? if ($this->countModules('logo')) { ?>
								<div class="row">
									<jdoc:include type="modules" name="logo" style="FLuid" />
								</div>
							<? } ?>
							<? if ($this->countModules('header')) { ?>
								<div class="row">
									<jdoc:include type="modules" name="header" style="FLuid" />
								</div>
							<? } ?>
						</div>
					</div>
					<!-- /Header -->
				<? } ?>
				
				<? if ($this->countModules('header-bottom')) { ?>
					<!-- Header-Bottom -->
					<div id="header-bottom">
						<div class="container">
							<div class="row">
								<jdoc:include type="modules" name="header-bottom" style="FLuid" />
							</div>
						</div>
					</div>
					<!-- /Header-Bottom -->
				<? } ?>
			</header>
		<? } ?>
		
		<?php if ($this->countModules('main-menu') || $this->countModules('main-menu-fixed') || $this->countModules('main-menu-affix')) { ?>
			<!-- ================================ Main Navigation ================================ -->
			<?php if($this->countModules('main-menu-fixed')) { ?>
				<nav id="mainmenu" class="navbar navbar-expand-md fixed-top" role="navigation">
			<?php } else if($this->countModules('main-menu-affix')) { ?>
				<nav id="mainmenu" class="navbar navbar-expand-md sticky-top" role="navigation">
			<?php } else { ?>
				<nav id="mainmenu" class="navbar navbar-expand-md" role="navigation">
			<?php } ?>
				<div class="container">
					<?php if ($this->countModules('navbar-brand')) { ?>
						<a class="navbar-brand" href="/">
							<div class="brand pull-left">
								<jdoc:include type="modules" name="navbar-brand"  />
							</div>
						</a>
					<? } ?>
					<?php if ($this->countModules('navbar-message')) { ?>
				  		<div class="navbar-message">
							<jdoc:include type="modules" name="navbar-message" />
					  	</div>
					<? } ?>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
				  	</button>
					
					<jdoc:include type="modules" name="main-menu" style="none" />
					<jdoc:include type="modules" name="main-menu-fixed" style="FLuidFull" />
					<jdoc:include type="modules" name="main-menu-affix" style="FLuidFull" />
				</div>
			</nav>
			<!-- ================================ /Main Navigation ================================ -->
	 	<? } ?>
	 	
		<? if ($this->countModules('banner-full')) { ?>
			<!-- =================================== Banner-Full =================================== -->
			<section id="banner-full" role="banner">
				<? if($this->countModules('banner-message')) { ?>
					<div id="banner-message">
						<jdoc:include type="modules" name="banner-message" style="FLuidFull" />
					</div>
				<? } ?>
				<jdoc:include type="modules" name="banner-full" />
			</section>
			<!-- =================================== /Banner-Full =================================== -->
		<? } ?>
		<!-- =================================== Content Area  ================================== -->
		<? if ($this->countModules('top-section')) { ?>
			<!-- Top Section -->
			<section id="top-section">
						<jdoc:include type="modules" name="top-section" style="FLuid" />
			</section>
			<!-- /Top Section -->
		<? } ?>
		<div id="wrapper-content">
			<div class="container">
				<div id="page-content" class="content-padding"  role="article">
					<div class="row">
						<?php if ($this->countModules('content-left')) { ?>
							<!-- Content Left -->
							<div id="content-left" class="col-lg-4" role="complementary">
								<div class="row">
									<jdoc:include type="modules" name="content-left" style="FLuid" />
								</div>
							</div>
							<!-- /Content Left -->
						<? } ?>
						<!--======== Main Content ========-->
						<section id="content" class="col-lg-<?= 12-$spanUsage;?>" itemprop="mainContentOfPage">
							<? if ($this->countModules('banner')) { ?>
								<!--- =================================== Banner =================================== -->
								<div id="banner" class="carousel slide" role="banner">
									<? if($this->countModules('banner-message')) { ?>
									<div id="banner-message">
										<jdoc:include type="modules" name="banner-message" />
									</div>
									<? } ?>
									<jdoc:include type="modules" name="banner" />
								</div>
								<!-- =================================== /Banner =================================== -->
							<? } ?>
							<div class="main-content" role="main">
								<? if ($this->countModules('breadcrumbs')) { ?>
									<div id="breadcrumb-wrapper">
										<div class="row">
											<jdoc:include type="modules" name="breadcrumbs" style="FLuid" />
										</div>
									</div>
								<? } ?>
								<jdoc:include type="message" />
								<? if($this->countModules('above-content')) { ?>
									<!-- Above Content -->
									<div id="above-content">
										<div class="row">
											<jdoc:include type="modules" name="above-content" style="FLuid" />
										</div>
									</div>
									<!-- /Above Content -->
								<? } ?>
								<jdoc:include type="component" />
								<? if($this->countModules('below-content')) { ?>
									<!-- Below Content -->
									<div id="below-content">
										<div class="row">
											<jdoc:include type="modules" name="below-content" style="FLuid" />
									  	</div>
									</div>
									<!-- /Below Content -->
								<? } ?>
							</div>
						</section>
						<!--======== /Main Content ========-->
						<?php if ($this->countModules('content-right')) { ?>
							<!-- Content Right -->
							<div id="content-right" class="col-lg-4" role="complementary">
								<div class="row">
									<jdoc:include type="modules" name="content-right" style="FLuid" />
								</div>
							</div>
							<!-- /Content Right -->
						<? } ?>
					</div>
				</div>
			</div>
		</div>
		<!-- /Wrapper-Content -->
		<? if ($this->countModules('bottom-section')) { ?>
			<!-- Bottom Section-->
			<section id="bottom-section">
				<div class="container">
					<div class="row">
						<jdoc:include type="modules" name="bottom-section" style="FLuid" />
					</div>
				</div>
			</section>
			<!-- /Bottom Section -->
		<? } ?>
		<!-- =================================== /Content Area  ================================== -->
		<!-- =================================== Footer ================================== -->
		<footer id="wrapper-footer" class="content-padding">
			<? if ($this->countModules('footer-top')) { ?>
				<!-- Footer Top -->
				<div id="footer-top">
					<div class="container">
						<div class="row">
							<jdoc:include type="modules" name="footer-top" style="FLuid" />
						</div>
					</div>
				</div>
				<!-- /Footer Top -->
			<? } ?>
			<? if ($this->countModules('footer')) { ?>
				<!-- Footer -->
				<div id="footer">
					<div class="container">
						<div class="row">
							<jdoc:include type="modules" name="footer" style="FLuid" />
						</div>
					</div>
				</div>
				<!-- /Footer -->
			<? } ?>
			<? if ($this->countModules('footer-bottom')) { ?>
				<!-- Footer Bottom -->
				<div id="footer-bottom">
					<div class="container">
						<div class="row">
							<jdoc:include type="modules" name="footer-bottom" style="FLuid" />
						</div>
			  		</div>
				</div>
				<!-- /Footer Bottom -->
			<? } ?>
			<div id="credits" class="hidden-print">
				<div class="container">
					<div class="disclaimer">
						<? if ($this->countModules('copyright')) { ?>
							<jdoc:include type="modules" name="copyright" />
						<? } else { ?>
							<div class="copyright">
								&copy;<?= date('Y') . " " . $sitename; ?>. All Rights Reserved.
							</div>
						<? } ?>
					</div>
					<div id="sitebyfineline">
						site by <a title="Web site design by Fine Line Websites & IT Consulting" target="_blank" href="https://www.finelinewebsites.com"><span class="flFine">Fine</span> <span class="flLine">Line</span> Websites</a>
					</div>
				</div>
			</div>
		</footer>
		<!-- =================================== /Footer ================================== -->
		<?php
		// Scripts to handle fixed/affix menus
		if($this->countModules('main-menu-fixed')) { ?>
			<script>
				function recalculateFixedTopPadding() {
					jQuery("#wrapper-content").css("padding-top", jQuery("body").css("height"));
				}
				jQuery(document).ready(function() {
					recalculateFixedTopPadding();
				});
				jQuery(window).load(function() {
					recalculateFixedTopPadding();
				});
				jQuery(window).resize(function() {
					recalculateFixedTopPadding();
				});
			</script>
		<?php }?>
		
		{flschema-json}
		
		<jdoc:include type="modules" name="debug" style="none" />
	</body>
</html>