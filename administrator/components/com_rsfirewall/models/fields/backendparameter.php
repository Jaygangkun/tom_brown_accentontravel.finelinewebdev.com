<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldBackendParameter extends JFormFieldText
{
	protected function getInput()
	{
		$html = str_replace('type="text"', 'type="text" oninput="this.value = this.value.replace(/\s+|\?|\&/g, \'_\'); document.getElementById(\'backend_password_placeholder\').innerText = this.value.length > 0 ? this.value + \'=\' : \'\';"', parent::getInput());

		$parameter = RSFirewallConfig::getInstance()->get('backend_password_parameter');

		if (strlen($parameter))
		{
			$parameter .= '=';
		}

		$html .= '<br /><p><small>' . JText::sprintf('COM_RSFIREWALL_BACKEND_PASSWORD_EXAMPLE', $this->escape(JUri::root() . 'administrator/?') . '<span id="backend_password_placeholder">' . $this->escape($parameter) . '</span>') . '</small></p>';

		return $html;
	}

	protected function escape($string)
	{
		return htmlentities($string, ENT_COMPAT, 'utf-8');
	}
}