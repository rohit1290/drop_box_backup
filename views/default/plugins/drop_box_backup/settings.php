<?php
$insert_view = elgg_view('dropbox/extend');

$dropbox_api = elgg_echo('Enter DropBox Access token');
$dropbox_api_view = elgg_view('input/text', array(
	'name' => 'params[dropbox_api]',
	'value' => $vars['entity']->dropbox_api,
	'class' => 'text_input',
	'required' => 'yes',
));

$dropbox_key = elgg_echo('Enter DropBox Key');
$dropbox_key_view = elgg_view('input/text', array(
	'name' => 'params[dropbox_key]',
	'value' => $vars['entity']->dropbox_key,
	'class' => 'text_input',
	'required' => 'yes',
));

$dropbox_secret = elgg_echo('Enter DropBox Secret');
$dropbox_secret_view = elgg_view('input/text', array(
	'name' => 'params[dropbox_secret]',
	'value' => $vars['entity']->dropbox_secret,
	'class' => 'text_input',
	'required' => 'yes',
));

$dropbox_path = elgg_echo('Enter DropBox path to store the files (Should always start and end with "/")');
$dropbox_path_view = elgg_view('input/text', array(
	'name' => 'params[dropbox_path]',
	'value' => $vars['entity']->dropbox_path,
	'class' => 'text_input',
	'required' => 'yes',
));

$dropbox_path_temp = elgg_echo('Enter path to a temporary folder on your server');
$dropbox_path_temp_view = elgg_view('input/text', array(
	'name' => 'params[dropbox_path_temp]',
	'value' => $vars['entity']->dropbox_path_temp,
	'class' => 'text_input',
	'required' => 'yes',
));

// Yes/No

$dropboxbk_source = elgg_echo('Backup Source Code');
$dropboxbk_source_view = elgg_view('input/select', array(
	'name' => 'params[dropboxbk_source]',
	'value' => $vars['entity']->dropboxbk_source,
	'options_values' => array(
		'yes' => elgg_echo('Yes'),
		'no' => elgg_echo('No')
	)
));

$dropboxbk_upload = elgg_echo('Backup Upload Folder');
$dropboxbk_upload_view = elgg_view('input/select', array(
	'name' => 'params[dropboxbk_upload]',
	'value' => $vars['entity']->dropboxbk_upload,
	'options_values' => array(
		'yes' => elgg_echo('Yes'),
		'no' => elgg_echo('No')
	)
));

$dropboxbk_db = elgg_echo('Backup Database');
$dropboxbk_db_view = elgg_view('input/select', array(
	'name' => 'params[dropboxbk_db]',
	'value' => $vars['entity']->dropboxbk_db,
	'options_values' => array(
		'yes' => elgg_echo('Yes'),
		'no' => elgg_echo('No')
	)
));

$dropboxbk_period = elgg_echo('When do you want to run the backup');
$dropboxbk_period_view = elgg_view('input/select', array(
	'name' => 'params[dropboxbk_period]',
	'value' => $vars['entity']->dropboxbk_period,
	'options_values' => array(
		'weekly' => elgg_echo('Weekly'),
		'monthly' => elgg_echo('Monthly'),
		'yearly' => elgg_echo('Yearly'),
	)
));

$settings = <<<__HTML
<div>$insert_view</div>
<div>$dropbox_api $dropbox_api_view</div>
<div>$dropbox_key $dropbox_key_view</div>
<div>$dropbox_secret $dropbox_secret_view</div>
<div>$dropbox_path $dropbox_path_view</div>
<div>$dropbox_path_temp $dropbox_path_temp_view</div>
<div>$dropboxbk_source $dropboxbk_source_view</div>
<div>$dropboxbk_upload $dropboxbk_upload_view</div>
<div>$dropboxbk_db $dropboxbk_db_view</div>
<div>$dropboxbk_period $dropboxbk_period_view</div>
__HTML;

echo $settings;
