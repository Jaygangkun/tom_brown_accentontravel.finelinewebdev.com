var RSFirewall = {};

RSFirewall.$ = jQuery;

RSFirewall.Continents = {
    '--': ['A1', 'A2', 'O1'],
    'EU': ['AD', 'AL', 'AT', 'AX', 'BA', 'BE', 'BG', 'BY', 'CH', 'CZ', 'CY', 'DE', 'DK', 'EE', 'ES', 'EU', 'FI', 'FO', 'FR', 'GB', 'GG', 'GI', 'GR', 'HR', 'HU', 'IE', 'IM', 'IS', 'IT', 'JE', 'LI', 'LT', 'LU', 'LV', 'MC', 'MD', 'ME', 'MK', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'RS', 'RU', 'SE', 'SI', 'SJ', 'SK', 'SM', 'TR', 'UA', 'VA'],
    'AS': ['AE', 'AF', 'AM', 'AP', 'AZ', 'BD', 'BH', 'BN', 'BT', 'CC', 'CN', 'CX', 'GE', 'HK', 'ID', 'IL', 'IN', 'IO', 'IQ', 'IR', 'JO', 'JP', 'KG', 'KH', 'KP', 'KR', 'KW', 'KZ', 'LA', 'LB', 'LK', 'MM', 'MN', 'MO', 'MV', 'MY', 'NP', 'OM', 'PH', 'PK', 'PS', 'QA', 'SA', 'SG', 'SY', 'TH', 'TJ', 'TL', 'TM', 'TW', 'UZ', 'VN', 'YE'],
    'NA': ['AG', 'AI', 'AN', 'AW', 'BB', 'BM', 'BS', 'BZ', 'CA', 'CR', 'CU', 'DM', 'DO', 'GD', 'GL', 'GP', 'GT', 'HN', 'HT', 'JM', 'KN', 'KY', 'LC', 'MQ', 'MS', 'MX', 'NI', 'PA', 'PM', 'PR', 'SV', 'TC', 'TT', 'US', 'VC', 'VG', 'VI'],
    'AF': ['AO', 'BF', 'BI', 'BJ', 'BW', 'CD', 'CF', 'CG', 'CI', 'CM', 'CV', 'DJ', 'DZ', 'EG', 'EH', 'ER', 'ET', 'GA', 'GH', 'GM', 'GN', 'GQ', 'GW', 'KE', 'KM', 'LR', 'LS', 'LY', 'MA', 'MG', 'ML', 'MR', 'MU', 'MW', 'MZ', 'NA', 'NE', 'NG', 'RE', 'RW', 'SC', 'SD', 'SH', 'SL', 'SN', 'SO', 'ST', 'SZ', 'TD', 'TG', 'TN', 'TZ', 'UG', 'YT', 'ZA', 'ZM', 'ZW'],
    'AN': ['AQ', 'BV', 'GS', 'HM', 'TF'],
    'SA': ['AR', 'BO', 'BR', 'CL', 'CO', 'EC', 'FK', 'GF', 'GY', 'PE', 'PY', 'SR', 'UY', 'VE'],
    'OC': ['AS', 'AU', 'CK', 'FJ', 'FM', 'GU', 'KI', 'MH', 'MP', 'NC', 'NF', 'NR', 'NU', 'NZ', 'PF', 'PG', 'PN', 'PW', 'SB', 'TK', 'TO', 'TV', 'UM', 'VU', 'WF', 'WS']
};

RSFirewall.initCountryBlocking = function() {
    var RSFirewallFixCheckboxes = function() {
        RSFirewall.fixCheckAllCountries();
        RSFirewall.fixCheckContinents();
	};

    RSFirewallFixCheckboxes();

    jQuery(document.getElementsByName('jform[blocked_countries][]')).change(RSFirewallFixCheckboxes);
    jQuery(document.getElementsByName('jform[blocked_continents][]')).change(RSFirewallFixCheckboxes);
    jQuery(document.getElementsByName('jform[blocked_countries_checkall][]')).change(RSFirewallFixCheckboxes);
};

RSFirewall.checkCountries = function(el) {
    for (var continent in RSFirewall.Continents) {
        if (RSFirewall.Continents.hasOwnProperty(continent) && el.value === continent) {
            var countries = RSFirewall.Continents[continent];
            for (var i = 0; i < countries.length; i++) {
                jQuery('#jform_blocked_countries').find('[value="' + countries[i] + '"]').prop('checked', el.checked);
            }
        }
    }
};

RSFirewall.checkAllCountries = function(value) {
    var items = document.getElementsByName('jform[blocked_countries][]');
    for (var i = 0; i < items.length; i++) {
        items[i].checked = value;
    }
};

RSFirewall.fixCheckAllCountries = function() {
    var items 	= document.getElementsByName('jform[blocked_countries][]');
    var checked = 0;
    for (var i = 0; i < items.length; i++) {
        if (items[i].checked === true) {
            checked++;
        }
    }
    var checkAll = document.getElementsByName('jform[blocked_countries_checkall][]')[0];
    if (checked === 0 || checked < items.length) {
        checkAll.checked = false;
    } else if (checked === items.length) {
        checkAll.checked = true;
    }
};

RSFirewall.fixCheckContinents = function() {
    var continents = document.getElementsByName('jform[blocked_continents][]');
    for (var i = 0; i < continents.length; i++) {
        var countries = RSFirewall.Continents[continents[i].value];
        var checked	  = 0;
        for (var j = 0; j < countries.length; j++) {
            if (jQuery('#jform_blocked_countries').find('[value="' + countries[j] + '"]').prop('checked')) {
                checked++;
            }
        }
        continents[i].checked = checked === countries.length;
    }
};

RSFirewall.initCharts = function() {
    google.charts.load('current', {packages: ['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(RSFirewalldrawChart);
};

RSFirewall.parseJSON = function (data) {
	if (typeof data != 'object') {
		// parse invalid data:
		var match = data.match(/{.*}/);

		return jQuery.parseJSON(match[0]);
	}

	return jQuery.parseJSON(data);
};

RSFirewall.requestTimeOut = {};
RSFirewall.requestTimeOut.Seconds = 0;
RSFirewall.requestTimeOut.Milliseconds = function () {
	return parseFloat(RSFirewall.requestTimeOut.Seconds) * 1000;
};

/* loading helper */
RSFirewall.removeLoading = function () {
	jQuery('.com-rsfirewall-icon-16-loading').remove();
};
RSFirewall.addLoading = function (where, type) {
	var loader = '<span class="com-rsfirewall-icon-16-loading"></span>';
	if (typeof type === 'undefined') {
		jQuery(where).append(loader);
	} else {
		jQuery(where)[type](loader);
	}
};
RSFirewall.removeArrow = function () {
	jQuery('.com-rsfirewall-current-item').removeClass('com-rsfirewall-current-item');
};
RSFirewall.addArrow = function (item) {
	jQuery(item).addClass('com-rsfirewall-current-item');
};

jQuery(document).ready(function () {
	jQuery('#jform_blocked_countries input, #jform_blocked_continents input, #jform_blocked_countries_checkall input').on('click', function (element) {
		if ((element.target.value == 'US' || element.target.value == 'checkall' || element.target.value == 'NA') && element.target.checked) {
			jQuery('#us-country-blocked').removeClass('com-rsfirewall-hidden')
		} else if ((element.target.value == 'US' || element.target.value == 'checkall' || element.target.value == 'NA') && !element.target.checked) {
			jQuery('#us-country-blocked').addClass('com-rsfirewall-hidden');
		}
	});
});

/* Database */
RSFirewall.Database = {};
RSFirewall.Database.Check = {
	unhide     : function (item) {
		return jQuery(item).removeClass('com-rsfirewall-hidden');
	},
	ignored    : false,
	tables     : [],
	tablesNum  : 0,
	table      : '',
	content    : '',
	prefix     : '',
	startCheck : function () {
		this.table = jQuery('#' + this.prefix + '-table');
		this.content = jQuery('#' + this.prefix);
		if (!this.tables.length) {
			return false;
		}

		this.unhide(this.content);
		this.content.hide().show('fast', function () {
			RSFirewall.Database.Check.stepCheck(0);
		});
	},
	stopCheck  : function () {

	},
	setProgress: function (index) {
		if (jQuery('#' + this.prefix + '-progress .com-rsfirewall-bar').length > 0) {
			var currentProgress = (100 / this.tablesNum) * index;
			jQuery('#' + this.prefix + '-progress .com-rsfirewall-bar').css('width', currentProgress + '%').text(parseInt(currentProgress) + '%');
		}
	},
	stepCheck  : function (index) {
		this.setProgress(index);
		if (!this.tables || !this.tables.length) {
			this.stopCheck();
			return false;
		}

		this.unhide(this.table.find('tr')[index + 1]);

		var table = this.tables.pop();
		jQuery.ajax({
			type      : 'POST',
			url       : 'index.php?option=com_rsfirewall',
			data      : {
				task : 'dbcheck.optimize',
				table: table,
				sid  : Math.random()
			},
			beforeSend: function () {
				RSFirewall.addLoading(jQuery('#result' + index));
			},
			success   : function (data) {
				RSFirewall.removeLoading();
				jQuery('#result' + index).html(data);
				if (RSFirewall.requestTimeOut.Seconds != 0) {
					setTimeout(function () {
						RSFirewall.Database.Check.stepCheck(index + 1)
					}, RSFirewall.requestTimeOut.Milliseconds());
				}
				else {
					RSFirewall.Database.Check.stepCheck(index + 1);
				}
			}
		});
	}
};

/* System */
RSFirewall.System = {};
RSFirewall.Retries = 0;
RSFirewall.MaxRetries = 10;
RSFirewall.RetryTimeout = 10;
RSFirewall.System.Check = {
	unhide           : function (item) {
		return jQuery(item).removeClass('com-rsfirewall-hidden');
	},
	content          : null,
	table            : null,
	steps            : [],
	currentStep      : '',
	prefix           : '',
	fix              : function (step, currentButton) {
		var parent = jQuery(currentButton).parents('td');

		var data = {
			task: 'fix.' + step,
			sid : Math.random()
		};

		if (parent.find('.com-rsfirewall-loader-wrapper').length > 0) {
			var loaderWrapper = parent.find('.com-rsfirewall-loader-wrapper');
		} else {
			var loaderWrapper = jQuery('<span class="com-rsfirewall-loader-wrapper"></span>');
			loaderWrapper.insertAfter(currentButton);
		}

		loaderWrapper.removeClass('com-rsfirewall-ok com-rsfirewall-not-ok com-rsfirewall-error').empty();

		if (step == 'fixAdminUser') {
			data.username = jQuery('#com-rsfirewall-new-username').val();
		} else if (step == 'fixHashes') {
			data.files = [];
			jQuery('input[name="hashes[]"]:checked').each(function () {
				data.files.push(jQuery(this).val());
			});
			data.flags = [];
			jQuery('input[name="hash_flags[]"]').each(function () {
				data.flags.push(jQuery(this).val());
			});
		} else if (step == 'ignoreFiles') {
			data.files = [];
			jQuery('input[name="ignorefiles[]"]:checked').each(function () {
				data.files.push(jQuery(this).val());
			});
		} else if (step == 'fixFolderPermissions') {
			data.folders = [];
			// adjust the limit
			var limit = this.limit;
			if (jQuery('input[name="folders[]"]:checked').length < this.limit) {
				limit = jQuery('input[name="folders[]"]:checked').length;
			}
			// add the folders to the POST array
			for (var i = 0; i < limit; i++) {
				data.folders.push(jQuery(jQuery('input[name="folders[]"]:checked')[i]).val());
			}

			// how many items are left?
			loaderWrapper.text(Joomla.JText._('COM_RSFIREWALL_ITEMS_LEFT').replace('%d', jQuery('input[name="folders[]"]:checked').length));

			// stop if there are no folders to process
			if (data.folders.length == 0) {
				// show the message
				loaderWrapper.html(Joomla.JText._('COM_RSFIREWALL_FIX_FOLDER_PERMISSIONS_DONE'));
				loaderWrapper.addClass('com-rsfirewall-ok');
				jQuery(currentButton).remove();
				return;
			}
		} else if (step == 'fixFilePermissions') {
			data.files = [];
			// adjust the limit
			var limit = this.limit;
			if (jQuery('input[name="files[]"]:checked').length < this.limit) {
				limit = jQuery('input[name="files[]"]:checked').length;
			}
			// add the files to the POST array
			for (var i = 0; i < limit; i++) {
				data.files.push(jQuery(jQuery('input[name="files[]"]:checked')[i]).val());
			}

			// how many items are left?
			loaderWrapper.text(Joomla.JText._('COM_RSFIREWALL_ITEMS_LEFT').replace('%d', jQuery('input[name="files[]"]:checked').length));

			// stop if there are no files to process
			if (data.files.length == 0) {
				// show the message
				loaderWrapper.html(Joomla.JText._('COM_RSFIREWALL_FIX_FILE_PERMISSIONS_DONE'));
				loaderWrapper.addClass('com-rsfirewall-ok');
				jQuery(currentButton).remove();
				return;
			}
		}

		jQuery.ajax({
			converters: {
				"text json": RSFirewall.parseJSON
			},
			dataType  : 'json',
			type      : 'POST',
			url       : 'index.php?option=com_rsfirewall',
			data      : data,
			beforeSend: function () {
				RSFirewall.addLoading(loaderWrapper);
				jQuery(currentButton).hide();
			},
			error     : function (jqXHR, textStatus, errorThrown) {
				RSFirewall.removeLoading();
				jQuery(currentButton).show();

				loaderWrapper.addClass('com-rsfirewall-error');
				loaderWrapper.html(Joomla.JText._('COM_RSFIREWALL_ERROR_FIX') + jqXHR.status + ' ' + errorThrown);
			},
			success   : function (json) {
				RSFirewall.removeLoading();
				jQuery(currentButton).show();

				if (json.success == true) {
					if (RSFirewall.System.Check.parseFixDetails(step, json, loaderWrapper, currentButton)) {
						// returning true means that we need to skip what's below
						return;
					}

					if (typeof json.data.result != 'undefined') {
						if (json.data.result == true) {
							loaderWrapper.addClass('com-rsfirewall-ok');
							jQuery(currentButton).remove();
						} else {
							loaderWrapper.addClass('com-rsfirewall-not-ok');
						}
					}
					if (typeof json.data.message != 'undefined') {
						loaderWrapper.html(json.data.message);
					}

				} else {
					loaderWrapper.addClass('com-rsfirewall-error');
					if (typeof json.data.message != 'undefined') {
						loaderWrapper.html(json.data.message);
					}
				}
			}
		});
	},
	setProgress      : function (index) {
		if (jQuery('#' + this.prefix + '-progress .com-rsfirewall-bar').length > 0) {
			var currentProgress = (100 / this.steps.length) * index;
			jQuery('#' + this.prefix + '-progress .com-rsfirewall-bar').css('width', currentProgress + '%').text(parseInt(currentProgress) + '%');
		}
	},
	stopCheck        : function () {
		// overwritten
	},
	stepCheck        : function (index, more_data) {
		this.setProgress(index);
		if (typeof(this.steps[index]) == 'undefined') {
			if (typeof this.stopCheck == 'function') {
				this.stopCheck();
			}
			return;
		}

		trindex = index > 0 ? index * 2 : 0;

		var currentRow = jQuery(this.table.find('tbody tr.com-rsfirewall-table-row')[trindex]);
		var currentText = jQuery(currentRow.find('td span')[0]);
		var currentResult = jQuery(currentRow.find('td span')[1]);
		var currentDetailsRow = jQuery(this.table.find('tbody tr.com-rsfirewall-table-row')[trindex + 1]);
		var currentDetails = jQuery(currentDetailsRow.children('td')[0]);
		var currentDetailsButton = jQuery(this.table.find('.com-rsfirewall-details-button')[index]);
		var currentStep = this.steps[index];
		this.currentStep = currentStep;

		this.unhide(currentRow);

		default_data = {
			task: 'check.' + currentStep,
			sid : Math.random()
		};
		if (more_data) {
			for (var key in more_data)
				default_data[key] = more_data[key];
		}

		jQuery.ajax({
			converters: {
				"text json": RSFirewall.parseJSON
			},
			dataType  : 'json',
			type      : 'POST',
			url       : 'index.php?option=com_rsfirewall',
			data      : default_data,
			beforeSend: function () {
				RSFirewall.addArrow(currentText);
				RSFirewall.addLoading(currentResult);
			},
			error     : function (jqXHR, textStatus, errorThrown) {
				if (currentStep == 'checkSignatures')
				{
					// Retry after 10 seconds if the server firewall interfered
					// Max 10 retries
					if (RSFirewall.Retries < RSFirewall.MaxRetries)
					{
						RSFirewall.Retries++;
						currentResult.html(Joomla.JText._('COM_RSFIREWALL_ERROR_CHECK_RETRYING'));
						setTimeout(function () {
							if (typeof RSFirewall.next_file != 'undefined')
							{
								RSFirewall.System.Check.stepCheck(index, {'file': RSFirewall.next_file});
							}
							else
							{
								RSFirewall.System.Check.stepCheck(index);
							}
						}, parseFloat(RSFirewall.RetryTimeout * 1000));
						
						return;
					}
				}
				currentResult.addClass('com-rsfirewall-error');
				currentResult.html(Joomla.JText._('COM_RSFIREWALL_ERROR_CHECK') + jqXHR.status + ' ' + errorThrown);

				RSFirewall.removeArrow();
				if (RSFirewall.requestTimeOut.Seconds != 0) {
					setTimeout(function () {
						RSFirewall.System.Check.stepCheck(index + 1)
					}, RSFirewall.requestTimeOut.Milliseconds());
				}
				else {
					RSFirewall.System.Check.stepCheck(index + 1);
				}
			},
			success   : function (json) {
				RSFirewall.removeArrow();
				if (json.success == true) {
					if (typeof json.data.message != 'undefined') {
						currentResult.html(json.data.message);
					}
					if (typeof json.data.result != 'undefined') {
						currentResult.addClass(json.data.result == true ? 'com-rsfirewall-ok' : 'com-rsfirewall-not-ok');
						// show the button if we need to provide details
						if (json.data.result == false) {
							RSFirewall.System.Check.unhide(currentDetailsButton);
						}
					}

					// a little hack to stop going to the next step
					// if this step requires extra ajax calls
					if (RSFirewall.System.Check.parseCheckDetails(currentStep, json, currentDetails, currentResult, currentDetailsButton)) {
						return;
					}
				} else {
					if (typeof json.data.message != 'undefined') {
						if (currentStep == 'checkCoreFilesIntegrity') {
							currentResult.addClass('com-rsfirewall-not-ok');
						} else {
							currentResult.addClass('com-rsfirewall-error');
						}
						currentResult.html(Joomla.JText._('COM_RSFIREWALL_ERROR_CHECK') + json.data.message);
					}
				}
				if (RSFirewall.requestTimeOut.Seconds != 0) {
					setTimeout(function () {
						RSFirewall.System.Check.stepCheck(index + 1)
					}, RSFirewall.requestTimeOut.Milliseconds());
				}
				else {
					RSFirewall.System.Check.stepCheck(index + 1);
				}
			}
		});
	},
	startCheck       : function () {
		this.table = jQuery('#' + this.prefix + '-table');
		this.content = jQuery('#' + this.prefix);

		var currentTable = this.table;

		// make buttons clickable
		this.table.find('.com-rsfirewall-details-button').each(function (i, el) {
			jQuery(el).click(function () {
				var row = currentTable.find('tbody tr.com-rsfirewall-table-row')[i * 2 + 1];
				if (jQuery(row).hasClass('com-rsfirewall-hidden')) {
					RSFirewall.System.Check.unhide(row);
					jQuery(row).hide();
				}
				jQuery(row).toggle();
				jQuery(this).children('span').toggleClass(function (j, theClass) {
					jQuery(this).removeAttr('class');
					if (theClass == 'icon-arrow-down')
						return 'icon-arrow-up';
					return 'icon-arrow-down';
				});
			});
		});

		this.unhide(this.content);
		this.content.hide().show('fast', function () {
			RSFirewall.System.Check.stepCheck(0);
		});
	},
	/* custom details parsing rules */
	parseFixDetails  : function (step, json, wrapper, button) {
		if (step == 'fixPHP') {
			if (json.data.contents) {
				jQuery('#com-rsfirewall-php-ini').text(json.data.contents);
				this.unhide('#com-rsfirewall-php-ini-wrapper');
				jQuery('#com-rsfirewall-php-ini-wrapper').hide().fadeIn('slow');
			}
		} else if (step == 'fixFolderPermissions') {
			if (json.data.results) {
				for (var i = 0; i < json.data.results.length; i++) {
					var result = jQuery('<span>', {'class': json.data.results[i] == 1 ? 'com-rsfirewall-ok' : 'com-rsfirewall-not-ok'});
					jQuery(jQuery('input[name="folders[]"]:checked')[0]).replaceWith(result);
				}
				RSFirewall.System.Check.fix(step, button);
				return true;
			}
		} else if (step == 'fixFilePermissions') {
			if (json.data.results) {
				for (var i = 0; i < json.data.results.length; i++) {
					var result = jQuery('<span>', {'class': json.data.results[i] == 1 ? 'com-rsfirewall-ok' : 'com-rsfirewall-not-ok'});
					jQuery(jQuery('input[name="files[]"]:checked')[0]).replaceWith(result);
				}
				RSFirewall.System.Check.fix(step, button);
				return true;
			}
		}
	},
	parseCheckDetails: function (step, json, details, result, detailsButton) {

		if (step == 'checkCoreFilesIntegrity') {
			if (jQuery('#com-rsfirewall-hashes').length == 0) {
				// let's create the table
				var table = jQuery('<table>', {
					'class': 'com-rsfirewall-colored-table',
					'id'   : 'com-rsfirewall-hashes'
				});
				details.append(table);
			}

			var table = jQuery('#com-rsfirewall-hashes');

			if (json.data.ignored) {
				this.ignored = true;
			}

			if (json.data.files && json.data.files.length > 0) {
				var j = table.find('tr').length;

				for (var i = 0; i < json.data.files.length; i++) {
					var file = json.data.files[i];

					var tr = jQuery('<tr>', {
						'class': j % 2 ? 'blue' : 'yellow',
						'id'   : 'hash' + j

					});
					var td_checkbox = jQuery('<td>', {
						width : '1%',
						nowrap: 'nowrap'
					});
					var checkbox = jQuery('<input />', {
						type   : 'checkbox',
						checked: true,
						name   : 'hashes[]',
						value  : file.path,
						id     : 'checkboxHash' + j
					});
					var hidden = jQuery('<input />', {
						type: 'hidden',
						name: 'hash_flags[]'
					});
					var label = jQuery('<label>', {'for': 'checkboxHash' + j}).text(file.path);
					var $hid = 'hash' + j;

					var downloadBtn = jQuery('<button type="button" style="margin-left:10px; margin-right:10px;" class="rsfirewall-download-original com-rsfirewall-button" onclick="RSFirewall.diffs.download(\'' + file.path + '\', \'' + $hid + '\' , window.document)"></button>')
						.text(Joomla.JText._('COM_RSFIREWALL_DOWNLOAD_ORIGINAL'));

					if (file.type == 'wrong') {
						if (typeof file.time == 'string')
						{
							var td_type = jQuery('<td>').text(Joomla.JText._('COM_RSFIREWALL_FILE_HAS_BEEN_MODIFIED_AGO').replace('%s', file.time));
						}
						else
						{
							var td_type = jQuery('<td>').text(Joomla.JText._('COM_RSFIREWALL_FILE_HAS_BEEN_MODIFIED'));
						}
						var btn = jQuery('<button type="button" id="diff' + $hid + '" class="com-rsfirewall-button"></button>')
							.attr('href', 'index.php?option=com_rsfirewall&view=diff&tmpl=component&hid=hash' + j + '&file=' + encodeURIComponent(file.path))
							.text(Joomla.JText._('COM_RSFIREWALL_VIEW_DIFF'))
							.click(function () {
								window.open(jQuery(this).attr('href'), 'diffWindow', 'width=800, height=600, left=50%, location=0, menubar=0, resizable=1, scrollbars=1, toolbar=0, titlebar=1');
							});

						td_type.append(btn);
						td_type.append(downloadBtn);

					} else if (file.type == 'missing') {
						hidden.val('M');
						var td_type = jQuery('<td>').text(Joomla.JText._('COM_RSFIREWALL_FILE_IS_MISSING'));
						td_type.append(downloadBtn);
					}

					var td_path = jQuery('<td>').append(label);

					table.append(tr.append(td_checkbox.append(checkbox, hidden), td_path, td_type));
					j++;
				}

			}

			// we haven't reached the end of the file so do another ajax call
			if (json.data.fstart) {
				var stepIndex = this.steps.indexOf(this.currentStep);

				if (RSFirewall.requestTimeOut.Seconds != 0) {
					setTimeout(function () {
						RSFirewall.System.Check.stepCheck(stepIndex, {'fstart': json.data.fstart})
					}, RSFirewall.requestTimeOut.Milliseconds());
				}
				else {
					RSFirewall.System.Check.stepCheck(stepIndex, {'fstart': json.data.fstart});
				}
				// returning true means that this step hasn't finished and we don't need to go to the next step
				return true;
			} else {
				if (table.find('tr').length > 0) {
					var $html = Joomla.JText._('COM_RSFIREWALL_HASHES_INCORRECT').replace('%d', '<span id="hashCount">' + table.find('tr').length + '</span> ');
					result.html($html).addClass('com-rsfirewall-not-ok');
					RSFirewall.System.Check.unhide(detailsButton);
				} else {
					result.text(Joomla.JText._('COM_RSFIREWALL_HASHES_CORRECT')).addClass('com-rsfirewall-ok');
				}
				// returning false means that this step has finished and we need to go to the next step

				if (this.ignored) {
					jQuery('#com-rsfirewall-ignore-files-button').removeClass('com-rsfirewall-hidden');
				}
				return false;
			}

		} else if (step == 'checkConfigurationIntegrity') {
			// let's create the table
			var table = jQuery('<table>', {'class': 'com-rsfirewall-colored-table'});

			// and populate it
			if (json.data.details) {
				for (var i = 0; i < json.data.details.length; i++) {
					var detail = json.data.details[i];
					var tr = jQuery('<tr>', {'class': i % 2 ? 'blue' : 'yellow'});
					var td_line = jQuery('<td>').html(Joomla.JText._('COM_RSFIREWALL_CONFIGURATION_LINE').replace('%d', detail.line));
					var td_code = jQuery('<td>').text(detail.code);

					table.append(tr.append(td_line, td_code));
				}
			}

			details.append(table);
		} else if (step == 'checkTemporaryFiles') {
			if (json.data.details) {
				var p = jQuery('<p>');
				details.append(p.append(json.data.details.message));

				// let's create the table
				var table = jQuery('<table>', {'class': 'com-rsfirewall-colored-table'});
				var j = 0;
				var limit = 10;

				json.data.details.folders = jQuery.map(json.data.details.folders, function (el) {
					return el
				});
				json.data.details.files = jQuery.map(json.data.details.files, function (el) {
					return el
				});

				for (var i = 0; i < json.data.details.folders.length; i++) {
					var folder = json.data.details.folders[i];
					var tr = jQuery('<tr>', {'class': j % 2 ? 'blue' : 'yellow'});
					var td = jQuery('<td>').text('[' + folder + ']');

					table.append(tr.append(td));
					j++;
					if (i >= limit) {
						var tr = jQuery('<tr>', {'class': j % 2 ? 'blue' : 'yellow'});
						var td = jQuery('<td>').append(jQuery('<em>').text(Joomla.JText._('COM_RSFIREWALL_MORE_FOLDERS').replace('%d', (json.data.details.folders.length - limit))));
						table.append(tr.append(td));
						j++;
						break;
					}
				}
				for (var i = 0; i < json.data.details.files.length; i++) {
					var file = json.data.details.files[i];
					var tr = jQuery('<tr>', {'class': j % 2 ? 'blue' : 'yellow'});
					var td = jQuery('<td>').text(file);

					table.append(tr.append(td));
					j++;
					if (i >= limit) {
						var tr = jQuery('<tr>', {'class': j % 2 ? 'blue' : 'yellow'});
						var td = jQuery('<td>').append(jQuery('<em>').text(Joomla.JText._('COM_RSFIREWALL_MORE_FILES').replace('%d', (json.data.details.files.length - limit))));
						table.append(tr.append(td));
						j++;
						break;
					}
				}

				details.append(table);
			}
		} else if (step == 'checkDisableFunctions') {
			if (json.data.details) {
				var p = jQuery('<p>');
				details.append(p.append(json.data.details));
			}
			if (this.table.find('.com-rsfirewall-not-ok').length > 0) {
				this.unhide(jQuery('#com-rsfirewall-server-configuration-fix'));
			}
		} else if (step == 'checkFolderPermissions') {
			if (jQuery('#com-rsfirewall-folders').length == 0) {
				// let's create the table
				var table = jQuery('<table>', {
					'class': 'com-rsfirewall-colored-table',
					'id'   : 'com-rsfirewall-folders'
				});
				details.append(table);
			}

			var table = jQuery('#com-rsfirewall-folders');

			if (json.data.stop) {
				// stop scanning
				if (table.find('tr').length > 0) {
					result.text(Joomla.JText._('COM_RSFIREWALL_FOLDER_PERMISSIONS_INCORRECT').replace('%d', table.find('tr').length)).addClass('com-rsfirewall-not-ok');
					RSFirewall.System.Check.unhide(detailsButton);
				} else {
					result.text(Joomla.JText._('COM_RSFIREWALL_FOLDER_PERMISSIONS_CORRECT')).addClass('com-rsfirewall-ok');
				}

				// finished
				return false;
			} else {
				if (json.data.folders && json.data.folders.length > 0) {
					var j = table.find('tr').length;
					for (var i = 0; i < json.data.folders.length; i++) {
						var folder = json.data.folders[i];

						var tr = jQuery('<tr>', {'class': j % 2 ? 'blue' : 'yellow'});
						var td_checkbox = jQuery('<td>', {
							width : '1%',
							nowrap: 'nowrap'
						});
						var checkbox = jQuery('<input />', {
							type   : 'checkbox',
							checked: true,
							name   : 'folders[]',
							value  : folder.path,
							id     : 'checkboxFolder' + j
						});
						var label = jQuery('<label>', {'for': 'checkboxFolder' + j}).text(folder.path);
						var td_path = jQuery('<td>').append(label);
						var td_perms = jQuery('<td>').text(folder.perms);

						table.append(tr.append(td_checkbox.append(checkbox), td_path, td_perms));
						j++;
					}
				}

				if (json.data.next_folder) {
					var stepIndex = this.steps.indexOf(this.currentStep);

					var next_folder = json.data.next_folder;
					var next_folder_stripped = json.data.next_folder_stripped;
					result.text(Joomla.JText._('COM_RSFIREWALL_PLEASE_WAIT_WHILE_BUILDING_DIRECTORY_STRUCTURE').replace('%s', next_folder_stripped));

					if (RSFirewall.requestTimeOut.Seconds != 0) {
						setTimeout(function () {
							RSFirewall.System.Check.stepCheck(stepIndex, {'folder': next_folder})
						}, RSFirewall.requestTimeOut.Milliseconds());
					}
					else {
						RSFirewall.System.Check.stepCheck(stepIndex, {'folder': next_folder});
					}

					// not finished
					return true;
				}
			}
		} else if (step == 'checkFilePermissions') {
			if (jQuery('#com-rsfirewall-files').length == 0) {
				// let's create the table
				var table = jQuery('<table>', {
					'class': 'com-rsfirewall-colored-table',
					'id'   : 'com-rsfirewall-files'
				});
				details.append(table);
			}

			var table = jQuery('#com-rsfirewall-files');

			if (json.data.files && json.data.files.length > 0) {
				var j = table.find('tr').length;
				for (var i = 0; i < json.data.files.length; i++) {
					var file = json.data.files[i];

					var tr = jQuery('<tr>', {'class': j % 2 ? 'blue' : 'yellow'});
					var td_checkbox = jQuery('<td>', {
						width : '1%',
						nowrap: 'nowrap'
					});
					var checkbox = jQuery('<input />', {
						type   : 'checkbox',
						checked: true,
						name   : 'files[]',
						value  : file.path,
						id     : 'checkboxFile' + j
					});
					var label = jQuery('<label>', {'for': 'checkboxFile' + j}).text(file.path);
					var td_path = jQuery('<td>').append(label);
					var td_perms = jQuery('<td>').text(file.perms);

					table.append(tr.append(td_checkbox.append(checkbox), td_path, td_perms));
					j++;
				}
			}

			if (json.data.next_file) {
				var stepIndex = this.steps.indexOf(this.currentStep);

				var next_file = json.data.next_file;
				var next_file_stripped = json.data.next_file_stripped;
				result.text(Joomla.JText._('COM_RSFIREWALL_PLEASE_WAIT_WHILE_BUILDING_FILE_STRUCTURE').replace('%s', next_file_stripped));

				if (RSFirewall.requestTimeOut.Seconds != 0) {
					setTimeout(function () {
						RSFirewall.System.Check.stepCheck(stepIndex, {'file': next_file})
					}, RSFirewall.requestTimeOut.Milliseconds());
				}
				else {
					RSFirewall.System.Check.stepCheck(stepIndex, {'file': next_file});
				}

				// not finished
				return true;
			} else {
				if (json.data.stop) {
					// stop scanning
					if (table.find('tr').length > 0) {
						result.text(Joomla.JText._('COM_RSFIREWALL_FILE_PERMISSIONS_INCORRECT').replace('%d', table.find('tr').length)).addClass('com-rsfirewall-not-ok');
						RSFirewall.System.Check.unhide(detailsButton);
					} else {
						result.text(Joomla.JText._('COM_RSFIREWALL_FILE_PERMISSIONS_CORRECT')).addClass('com-rsfirewall-ok');
					}

					// finished
					return false;
				}
			}
		} else if (step == 'checkSignatures') {
			if (jQuery('#com-rsfirewall-signatures').length == 0) {
				// let's create the table
				var table = jQuery('<table>', {
					'class': 'com-rsfirewall-colored-table',
					'id'   : 'com-rsfirewall-signatures'
				});
				details.append(table);
			}

			var table = jQuery('#com-rsfirewall-signatures');

			if (json.data.files && json.data.files.length > 0) {
				var j = table.find('tr').length;
				for (var i = 0; i < json.data.files.length; i++) {
					var file = json.data.files[i];

					var tr = jQuery('<tr>', {'class': j % 2 ? 'blue' : 'yellow'});
					var td_checkbox = jQuery('<td>', {
						valign: 'top',
						width : '1%',
						nowrap: 'nowrap'
					});
					var checkbox = jQuery('<input />', {
						valign : 'top',
						width  : '19%',
						type   : 'checkbox',
						checked: true,
						name   : 'ignorefiles[]',
						value  : file.path,
						id     : 'checkboxFile' + j
					});
					var td_path = jQuery('<td valign="top" width="20%">').text(file.path);
					
					if (typeof file.time == 'string')
					{
						td_path.html(td_path.html() + '<br /><small>' + Joomla.JText._('COM_RSFIREWALL_FILE_HAS_BEEN_MODIFIED_AGO').replace('%s', file.time) + '</small>');
					}
					
					var td_reason = jQuery('<td valign="top" width="20%">', {'nowrap': 'nowrap'}).text(file.reason);
					var td_match = jQuery('<td valign="top" width="40%">').addClass('broken-word');
					if (file.match) {
						td_match = td_match.text(file.match.substring(0, 355));
					}

					var td_view = jQuery('<td valign="top" width="1%">').addClass('com-rsfirewall-default-font');

					var btn = jQuery('<button type="button" class="com-rsfirewall-button pull-right"></button>')
						.attr('href', 'index.php?option=com_rsfirewall&view=file&tmpl=component&file=' + encodeURIComponent(file.path))
						.text(Joomla.JText._('COM_RSFIREWALL_VIEW_FILE'))
						.click(function () {
							window.open(jQuery(this).attr('href'), 'fileWindow', 'width=800, height=600, left=50%, location=0, menubar=0, resizable=1, scrollbars=1, toolbar=0, titlebar=1');
						});

					td_view.append(btn);

					table.append(tr.append(td_checkbox.append(checkbox), td_path, td_reason, td_match, td_view));
					j++;
				}
			}

			if (json.data.next_file) {
				var stepIndex = this.steps.indexOf(this.currentStep);

				var next_file = json.data.next_file;
				var next_file_stripped = json.data.next_file_stripped;
				result.text(Joomla.JText._('COM_RSFIREWALL_PLEASE_WAIT_WHILE_BUILDING_FILE_STRUCTURE').replace('%s', next_file_stripped));
				
				RSFirewall.next_file = next_file;

				if (RSFirewall.requestTimeOut.Seconds != 0) {
					setTimeout(function () {
						RSFirewall.System.Check.stepCheck(stepIndex, {'file': next_file})
					}, RSFirewall.requestTimeOut.Milliseconds());
				}
				else {
					RSFirewall.System.Check.stepCheck(stepIndex, {'file': next_file});
				}

				// not finished
				return true;
			} else {
				if (json.data.stop) {
					// stop scanning
					if (table.find('tr').length > 0) {
						result.text(Joomla.JText._('COM_RSFIREWALL_MALWARE_PLEASE_REVIEW_FILES').replace('%d', table.find('tr').length)).addClass('com-rsfirewall-not-ok');
						RSFirewall.System.Check.unhide(detailsButton);
					} else {
						result.text(Joomla.JText._('COM_RSFIREWALL_NO_MALWARE_FOUND')).addClass('com-rsfirewall-ok');
					}

					// finished
					return false;
				}
			}
		} else {
			if (json.data.details) {
				var p = jQuery('<p>');
				details.append(p.append(json.data.details));
			}
		}
	}
};

RSFirewall.Grade = {
	create: function () {
		// compute the grade value
		// each failed step removes 2 from the total grade
		var grade = 100 - jQuery('.com-rsfirewall-count span.com-rsfirewall-not-ok').length * 2;
		var hasErrors = jQuery('.com-rsfirewall-error').length > 0;
		var gradeInput = jQuery('#com-rsfirewall-grade input');

		// If errors occured, grade is 0 and change the text.
		if (hasErrors) {
			grade = 0;
            jQuery('#com-rsfirewall-grade h2').addClass('com-rsfirewall-error').text(Joomla.JText._('COM_RSFIREWALL_GRADE_NOT_FINISHED'));
			jQuery('#com-rsfirewall-grade p').text(Joomla.JText._('COM_RSFIREWALL_GRADE_NOT_FINISHED_DESC'));

			gradeInput.remove();
		} else {
            gradeInput.val(grade);

            // green
            gradeInput.knob({
                'min'               : 0,
                'max'               : 100,
                'readOnly'          : true,
                'width'             : 90,
                'height'            : 90,
                'inputColor'        : '#000000',
                'dynamicDraw'       : true,
                'thickness'         : 0.3,
                'tickColorizeValues': true,
                'change'            : function (v) {
                    var grade = v;
                    var color;
                    if (grade <= 75) {
                        color = '#ED7A53';
                    } else if (grade <= 90) {
                        color = '#88BBC8';
                    } else if (grade <= 100) {
                        color = '#9FC569';
                    }
                    this.fgColor = color;
                }
            });

            this.save();
		}

        jQuery("#com-rsfirewall-grade").fadeIn('slow');
	},
	save  : function () {
		jQuery.ajax({
			type: 'POST',
			url : 'index.php?option=com_rsfirewall',
			data: {
				task : 'check.saveGrade',
				grade: jQuery('#com-rsfirewall-grade input').val(),
				sid  : Math.random()
			}
		});
	}
};

RSFirewall.Status = {
	errorContainer: '',

	Error: function (display, type, error) {
		this.errorContainer.empty();
		if (display && typeof error != 'undefined') {
			this.errorContainer.addClass('com-rsfirewall-log-' + type);
			var heading = jQuery('<h3>').append(Joomla.JText._('COM_RSFIREWALL_LOG_' + type.toUpperCase()));
			this.errorContainer.append(heading);
			this.errorContainer.append(error);
			this.errorContainer.show();
		} else {
			this.errorContainer.removeClass('com-rsfirewall-log-error', 'com-rsfirewall-log-warning');
			this.errorContainer.hide();
		}
	},

	Change: function (id, listId, type, element) {
		var data = {
			task: 'logs.' + type,
			id  : id
		};
		if (listId != null) {
			data.listId = listId;
		}

		jQuery.ajax({
			converters: {
				"text json": RSFirewall.parseJSON
			},
			dataType  : 'json',
			type      : 'POST',
			url       : 'index.php?option=com_rsfirewall&task',
			data      : data,
			beforeSend: function () {
				RSFirewall.addLoading(element, 'after');
				jQuery(element).hide();

				// remove previous errors
				RSFirewall.Status.Error(false);
			},
			error     : function (jqXHR, textStatus, errorThrown) {
				RSFirewall.removeLoading();
				jQuery(element).show();

				// set the error
				RSFirewall.Status.Error(true, 'error', jqXHR.status + ' ' + errorThrown);
			},
			success   : function (json) {
				RSFirewall.removeLoading();
				if (json.success) {
					if (json.data.result) {
						// all ok
						if (json.data.type) {
							// change the buttons for all other rows that contains the same ip
							RSFirewall.Status.ChangeSameIp(id, null, 0);
						} else {
							// change the buttons for all other rows that contains the same ip
							RSFirewall.Status.ChangeSameIp(id, json.data.listId, 1);
						}
						jQuery(element).remove();
					} else {
						// errors
						jQuery(element).show();

						// set the warning
						RSFirewall.Status.Error(true, 'warning', json.data.error);
					}
				}
			}
		});
	},

	MakeButton: function (element, id, listId, type) {
		var button, task, text,
			classes = ['btn', 'btn-small', 'btn-sm'];

		if (type)
		{
			task = 'unblockajax';
			classes.push('btn-secondary');
			text = Joomla.JText._('COM_RSFIREWALL_UNBLOCK');
		}
		else
		{
			task = 'blockajax';
			classes.push('btn-danger');
			text = Joomla.JText._('COM_RSFIREWALL_BLOCK');

			listId = null;
		}

		button = document.createElement('button');
		button.setAttribute('type', 'button');
		button.setAttribute('class', classes.join(' '));
		button.onclick = function() {
			RSFirewall.Status.Change(id, listId, task, this);
		};
		button.innerText = text;

		jQuery(element).after(button);
	},

	ChangeSameIp: function (id, listId, type) {
		// get the ip address that we need to change the button
		var ip = jQuery('#rsf-log-' + id).find('.rsf-ip-address').html().trim();

		// parse the table to find the same ip entries
		jQuery('.rsf-entry').each(function () {
            var ipFound = jQuery(this).find('.rsf-ip-address').html().trim();
            if (ipFound.length > 0 && ipFound === ip) {
                var element = jQuery(this).find('.rsf-status > button');

                RSFirewall.Status.MakeButton(element, id, listId, type);
                jQuery(element).remove();
            }
		});
	}
};

/**
 * Function to download the original file from the remote server
 * and overwrite the one stored locally.
 *
 * @type {{download: RSFirewall.diffs.download}}
 */
RSFirewall.diffs = {
	download: function ($local, $hid, $window) {

		if (!confirm(Joomla.JText._('COM_RSFIREWALL_CONFIRM_OVERWRITE_LOCAL_FILE'))) {
			return false;
		}

		jQuery.ajax({
			type      : 'POST',
			dataType  : 'JSON',
			url       : 'index.php?option=com_rsfirewall',
			data      : {
				task     : 'diff.download',
				localFile: $local
			},
			beforeSend: function () {
				var $buttons = [];
				var $counter = jQuery('#' + $hid, $window).find('td').last();
				var $button = $counter.find('.rsfirewall-download-original');
				var $optional = jQuery('#replace-original');

				$buttons.push($button);
				if ($optional.length) {
					$buttons.push($optional);
				}

				jQuery.each($buttons, function () {
					jQuery(this).attr('disabled', 'true').addClass('btn-processing');
					jQuery(this).html('<span class="icon-refresh"></span> ' + Joomla.JText._("COM_RSFIREWALL_BUTTON_PROCESSING"));
				});
			},
			success   : function (result) {
				var $hashCount = jQuery('#hashCount', $window);
				var $parent = $hashCount.parents('.com-rsfirewall-table-row.alt-row');
				var $counter = jQuery('#' + $hid, $window).find('td').last();
				var $oldValue = parseInt(jQuery('#hashCount', $window).html());
				var $button = $counter.find('.rsfirewall-download-original');
				var $optional = jQuery('#replace-original');
				var $diffButton = jQuery('#diff' + $hid, $window);

				var $buttons = [];

				$buttons.push($button);
				if ($optional.length) {
					$buttons.push($optional);
				}

				if (result.status == true) {
					$diffButton.remove();
					jQuery.each($buttons, function () {
						jQuery(this).removeClass('btn-processing').addClass('btn-success');
						jQuery(this).html('<span class="icon-checkmark-2"></span> ' + Joomla.JText._("COM_RSFIREWALL_BUTTON_SUCCESS"));
					});


					if ($oldValue == 1) {
						$parent.find('.com-rsfirewall-not-ok').removeClass('com-rsfirewall-not-ok').addClass('com-rsfirewall-ok');
						$parent.find('.com-rsfirewall-ok').empty().append('<span>' + Joomla.JText._('COM_RSFIREWALL_HASHES_CORRECT') + '</span>');
					} else {
						$hashCount.html($oldValue - 1);
					}

				} else {
					jQuery.each($buttons, function () {
						jQuery(this).removeClass('btn-processing').addClass('btn-failed');
						jQuery(this).html('<span class="icon-cancel-circle"></span> ' + Joomla.JText._("COM_RSFIREWALL_BUTTON_FAILED"));
					});
				}

				if ($optional.length) {
					jQuery('.rsfirewall-replace-original').append('<div class="alert alert-info">' + result.message + '</div>');
				}

				if ($counter.find('#' + $hid + '-message', $window).length) {
					jQuery('#' + $hid + '-message', $window).remove();
				}

				$counter.append('<span id="' + $hid + '-message">' + result.message + '</span>');
			}
		});
	}
};

RSFirewall.ignore = {
	remove: function ($id) {
		if (!confirm(Joomla.JText._('COM_RSFIREWALL_CONFIRM_UNIGNORE'))) {
			return false;
		}
		jQuery.ajax({
			type      : 'POST',
			dataType  : 'JSON',
			url       : 'index.php?option=com_rsfirewall',
			data      : {
				task         : 'ignored.removeFromIgnored',
				ignoredFileId: $id
			},
			beforeSend: function () {
				$button = jQuery('#removeIgnored' + $id);
				$button.attr('disabled', 'true').addClass('btn-processing');
				$button.html('<span class="icon-refresh"></span> ' + Joomla.JText._("COM_RSFIREWALL_BUTTON_PROCESSING"));
			},
			success   : function (result) {
				$button = jQuery('#removeIgnored' + $id);

				if (result.status == true) {
					$button.removeClass('btn-processing').addClass('btn-success');
					$button.html('<span class="icon-checkmark-2"></span> ' + Joomla.JText._("COM_RSFIREWALL_BUTTON_SUCCESS"));
					$button.parents('tr').hide('fast');

				} else {
					$button.removeClass('btn-processing').addClass('btn-failed');
					$button.html('<span class="icon-cancel-circle"></span> ' + Joomla.JText._("COM_RSFIREWALL_BUTTON_FAILED"));
				}
			}
		})
	}
};

RSFirewall.vmap = {
	/**
	 * Holds the LOCATION:THREATS object
	 */
	data         : null,
	/**
	 * The map DOM selector
	 */
	selector     : null,
	/**
	 * Constructor
	 *
	 * @param $id
	 */
	init         : function ($id) {
		/**
		 * Set the DOM selector
		 */
		this.selector = $id;
		/**
		 * Load the data using AJAX
		 */
		this.loadData();
	},
	/**
	 * Helper function
	 *
	 * Call action here:
	 *  - rsfirewall\admin\controllers\logs.php
	 *     - rsfirewall\admin\models\logs.php
	 *
	 *     returns an object ( success: bool | data: object( country code : encounters ) )
	 */
	loadData     : function () {
		var self = this;
		jQuery.ajax({
			type      : 'POST',
			dataType  : 'JSON',
			url       : 'index.php?option=com_rsfirewall',
			data      : {
				task: 'logs.getStatistics'
			},
			beforeSend: self.loadPreloader(self.selector),
			success   : function (result) {
				if (result.success) {
					jQuery('#rsf-overlay-region, #rsf-overlay-cube').fadeOut('fast', function () {
						jQuery('#rsf-overlay-region, #rsf-overlay-cube').remove();
					});
					self.data = result.data;
					self.renderMap();
				}
			}
		})
	},
	/**
	 * Create a pre
	 */
	loadPreloader: function (selector) {
		jQuery(selector).prepend('<div class="tpl-overlay" id="rsf-overlay-region"></div>');
		jQuery(selector).append('<div class="sk-folding-cube" id="rsf-overlay-cube"><div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div>');
	},
	/**
	 * Render the jQuery Vector Map
	 *
	 * Triggered only if the AJAX request was successful
	 */
	renderMap    : function () {
		var self = this;
		jQuery(this.selector).remove('.tpl-overlay, sk-folding-cube');
		/**
		 * Draw the map
		 */
		jQuery(this.selector).vectorMap(
			{
				map              : 'world_en',
				backgroundColor  : null,
				color            : '#ffffff',
				hoverOpacity     : 0.7,
				selectedColor    : '#666666',
				enableZoom       : true,
				values           : self.data,
				showTooltip      : true,
				scaleColors      : ['#F8C3C4', '#e8363a'],
				normalizeFunction: 'polynomial'
			}
		);
		/**
		 * Initiate the tooltips
		 */
		jQuery(this.selector).bind('labelShow.jqvmap',
			function (event, label, code) {
				var text = label.text();
				if (typeof self.data[code] != 'undefined') {
					label.text(text + ' : ' + self.data[code]);
				}
			}
		);
	}
};

RSFirewall.GeoIPDownload = function (element) {
	jQuery(element).attr('disabled', 'disabled');
	
	var GeoIPDownloadErrorHandler = function(message, url) {
		var $parent = jQuery(element).parent();

		$parent.children('.geoip-error').remove();

		$parent.append('<p class="geoip-error"><strong class="text-error">' + message + '</strong></p>').hide().fadeIn();

        if (typeof url === 'string')
        {
            $parent.append('<p class="geoip-error"><strong>' + Joomla.JText._('COM_RSFIREWALL_GEOIP_DB_TRY_TO_DOWNLOAD_MANUALLY').replace(/%s/g, url) + '</strong></p>');
        }

		$parent.append('<p class="geoip-error">' + Joomla.JText._('COM_RSFIREWALL_GEOIP_DB_CANNOT_DOWNLOAD') + '<br><input type="file" name="jform[geoip_upload][]"></p><p>' + Joomla.JText._('COM_RSFIREWALL_GEOIP_DB_CANNOT_DOWNLOAD_CONTINUED') + '</p>').hide().fadeIn();

		jQuery(element).removeAttr('disabled');
	};
	
	jQuery.ajax({
		type    : 'POST',
		url     : 'index.php?option=com_rsfirewall',
		dataType: 'json',
		data    : {
			'task': 'configuration.downloadGeoIPDatabase',
			'license_key': document.getElementById('jform_maxmind_license_key').value
		},
		success: function (data, textStatus, jqXHR) {
			if (!data.success) {
				GeoIPDownloadErrorHandler(data.message, data.url);
			} else {
				if (!jQuery('.rsfirewall-geoip-works').length) {
					jQuery('#country_block .com-rsfirewall-tooltip').after('<div class="alert alert-success rsfirewall-geoip-works"><p>' + data.message + '</p></div>');
				}
				jQuery(document.getElementsByName('jform[blocked_continents][]')).removeAttr('disabled');
				jQuery(document.getElementsByName('jform[blocked_countries][]')).removeAttr('disabled');
				jQuery(document.getElementsByName('jform[blocked_countries_checkall][]')).removeAttr('disabled');
				
				jQuery(element).parents('.alert').remove();
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {			
			GeoIPDownloadErrorHandler(Joomla.JText._('COM_RSFIREWALL_DOWNLOAD_GEOIP_SERVER_ERROR').replace('%s', errorThrown));
		}
	})
};