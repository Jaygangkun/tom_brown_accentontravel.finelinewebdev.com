<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>
<div class="dashboard-info">
	<div><?php echo JHtml::_('image', 'com_rsfirewall/rsfirewall.png', 'RSFirewall!', array(), true); ?></div>
	<ul>
		<li><strong><?php echo JText::_('COM_RSFIREWALL_PRODUCT_VERSION') ?>:</strong> <?php echo $this->version; ?></li>
		<li><strong><?php echo JText::_('COM_RSFIREWALL_COPYRIGHT_NAME') ?>:</strong> &copy; 2009 &mdash; 2020 <a href="https://www.rsjoomla.com" target="_blank">RSJoomla!</a></li>
		<li><strong><?php echo JText::_('COM_RSFIREWALL_LICENSE_NAME') ?>:</strong> <a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GNU/GPL</a></li>
		<li><strong><?php echo JText::_('COM_RSFIREWALL_UPDATE_CODE') ?>:</strong><br />
			<?php if (strlen($this->code) == 20) { ?>
				<span class="correct-code"><?php echo $this->escape($this->code); ?></span>
			<?php } elseif ($this->code) { ?>
				<span class="incorrect-code"><?php echo $this->escape($this->code); ?></span>
			<?php } else { ?>
				<span class="missing-code"><a href="<?php echo JRoute::_('index.php?option=com_rsfirewall&view=configuration'); ?>"><?php echo JText::_('COM_RSFIREWALL_PLEASE_ENTER_YOUR_CODE_IN_THE_CONFIGURATION'); ?></a></span>
			<?php } ?></li>
	</ul>
</div>