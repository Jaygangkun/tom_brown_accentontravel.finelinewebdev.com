<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

JText::script('COM_RSFIREWALL_LEVEL_LOW');
JText::script('COM_RSFIREWALL_LEVEL_MEDIUM');
JText::script('COM_RSFIREWALL_LEVEL_HIGH');
JText::script('COM_RSFIREWALL_LEVEL_CRITICAL');

$this->document->addScript('https://www.gstatic.com/charts/loader.js');
$this->document->addScriptDeclaration('jQuery(RSFirewall.initCharts);');
?>
<h2><?php echo JText::_('COM_RSFIREWALL_ATTACKS_BLOCKED_PAST_MONTH'); ?></h2>
<div id="com-rsfirewall-logs-chart" style="height: 400px;"></div>

<script type="text/javascript">
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function RSFirewalldrawChart() {
	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('date', 		'');
	data.addColumn('number', 	Joomla.JText._('COM_RSFIREWALL_LEVEL_LOW'));
	data.addColumn('number', 	Joomla.JText._('COM_RSFIREWALL_LEVEL_MEDIUM'));
	data.addColumn('number', 	Joomla.JText._('COM_RSFIREWALL_LEVEL_HIGH'));
	data.addColumn('number', 	Joomla.JText._('COM_RSFIREWALL_LEVEL_CRITICAL'));

	<?php foreach ($this->lastMonthLogs as $date => $item) { ?>
	data.addRow([new Date(<?php echo $date; ?>), <?php echo $item['low']; ?>, <?php echo $item['medium']; ?>, <?php echo $item['high']; ?>, <?php echo $item['critical']; ?>]);
	<?php } ?>
	
	// Set chart options	
	var options = {
		'colors': ['green', 'orange', 'red', 'black'],
		'backgroundColor': 'transparent', // try to make it transparent
		'legend': {'position': 'top'},
		'chartArea': {
			'left': 0,
			'width': '100%'
		}
	};
	
	var chart = new google.visualization.LineChart(document.getElementById('com-rsfirewall-logs-chart'));
	chart.draw(data, options);
	
	jQuery(window).resize(function() {
		chart.draw(data, options);
	});
}
</script>