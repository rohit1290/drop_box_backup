<?php

$op1 = elgg_get_plugin_setting('dropbox_api', 'drop_box_backup');
$op2 = elgg_get_plugin_setting('dropbox_key', 'drop_box_backup');
$op3 = elgg_get_plugin_setting('dropbox_secret', 'drop_box_backup');

if($op1 <> "" && $op2 <> "" && $op3 <> ""){
echo "<h4>Validating the Settings</h4><br>If all the parameters are correct then you would be able to see your drop box profile below";
require_once elgg_get_plugins_path() . "drop_box_backup/vendor/dropbox-sdk/Dropbox/autoload.php";
$dropbox_config = array(
    'key'    => elgg_get_plugin_setting('dropbox_key', 'drop_box_backup'),
    'secret' => elgg_get_plugin_setting('dropbox_secret', 'drop_box_backup')
);
$appInfo = Dropbox\AppInfo::loadFromJson($dropbox_config);
$webAuth = new Dropbox\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");
$accessToken = elgg_get_plugin_setting('dropbox_api', 'drop_box_backup');
$tmpDir = elgg_get_plugin_setting('dropbox_path_temp', 'drop_box_backup');
$dbxClient = new Dropbox\Client($accessToken, "PHP-Example/1.0");
$accountInfo = $dbxClient->getAccountInfo();
print_r($accountInfo);
}
?>