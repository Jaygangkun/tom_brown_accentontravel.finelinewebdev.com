<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title><?php echo JText::_('COM_RSFIREWALL_PROTECTED_AREA'); ?></title>
	<style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #224c8f;
      }

      .form-signin {
        max-width: 370px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
	  
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
        text-align: center;
      }

    </style>
	<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
		document.getElementsByName('rsf_backend_password')[0].focus();
	});
	</script>
</head>
<body>
	<div class="container">
		<?php if ($this->password_sent) { ?>
			<div class="alert alert-danger" role="alert">
				<h4><?php echo JText::_('COM_RSFIREWALL_ERROR'); ?></h4>
				<?php echo JText::_('COM_RSFIREWALL_PASSWORD_INCORRECT'); ?>
			</div>
		<?php } ?>
		<form method="post" action="index.php" class="form-signin text-center">
			<p><?php echo JHtml::_('image', 'com_rsfirewall/icon-48-rsfirewall.png', 'RSFirewall!', array(), true); ?></p>
			<h3><?php echo JText::_('COM_RSFIREWALL_PLEASE_LOGIN_TO_CONTINUE'); ?></h3>
			<input type="password" class="input-block-level" name="rsf_backend_password" placeholder="<?php echo $this->escape(JText::_('COM_RSFIREWALL_PASSWORD')); ?>" />
			<button class="btn btn-primary btn-lg" type="submit"><?php echo JText::_('COM_RSFIREWALL_LOGIN'); ?></button>
		</form>
	</div>
</body>
</html>