<?php
elgg_register_event_handler('init', 'system', 'drop_box_backup_init');

function drop_box_backup_init() {
 $period = elgg_get_plugin_setting('dropboxbk_period', 'drop_box_backup');
 switch ($period) {
		case 'weekly':
		case 'monthly' :
		case 'yearly' :
			break;
		default:
			$period = 'weekly';
	} 
 elgg_register_plugin_hook_handler('cron', $period, 'drop_box_backup_handler');
}

function drop_box_backup_handler($hook, $period, $return, $params) {
	$start_time = elgg_extract('time', $params);
    $resulttext = elgg_echo("Backup Started at $start_time\n");
	$resulttext .= dropbox_backup();
    $end_time = elgg_extract('time', $params);
	$resulttext .= elgg_echo("Backup ended at $end_time\n");
	
	echo $resulttext;
}

function dropbox_backup(){
// Initializing Parameters

ini_set("display_errors",1);
ini_set('max_execution_time', 9000);
ini_set('memory_limit','-1');

require_once elgg_get_plugins_path() . "drop_box_backup/vendor/dropbox-sdk/Dropbox/autoload.php";

$dropbox_config = array(
    'key'    => elgg_get_plugin_setting('dropbox_key', 'drop_box_backup'),
    'secret' => elgg_get_plugin_setting('dropbox_secret', 'drop_box_backup')
);

$appInfo = Dropbox\AppInfo::loadFromJson($dropbox_config);
$webAuth = new Dropbox\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

$accessToken = elgg_get_plugin_setting('dropbox_api', 'drop_box_backup');
$tmpDir = elgg_get_plugin_setting('dropbox_path_temp', 'drop_box_backup');
$dropbox_path = elgg_get_plugin_setting('dropbox_path', 'drop_box_backup');

$dbxClient = new Dropbox\Client($accessToken, "PHP-Example/1.0");
$accountInfo = $dbxClient->getAccountInfo();
//print_r($accountInfo);
echo ("\n");

// Database Backup
$option = elgg_get_plugin_setting('dropboxbk_db', 'drop_box_backup');

if($option == "yes"){
$sqlFile = $tmpDir."DB_".date('Y_m_d').".sql";
$sourceFile = "DB_".date('Y_m_d').".tgz";
$sqlbackupFile = $tmpDir.$sourceFile;
echo "Ziping $sqlFile start\n";
$result = exec("mysqldump -h ".$CONFIG->dbhost." -u ".$CONFIG->dbuser." --password=".$CONFIG->dbpass." ".$CONFIG->dbname." --> $sqlFile");
$result = exec("tar cvzf $sqlbackupFile $sqlFile");
echo "Ziping $sqlFile end\n";

echo("Uploading $sqlbackupFile to Dropbox\n");
$f = fopen($sqlbackupFile, "rb");
print_r($f);
echo ("\n");
$result = $dbxClient->uploadFile($dropbox_path.$sourceFile, Dropbox\WriteMode::add(), $f);
print_r($result);
echo ("\n");
fclose($f);

// Delete the temporary files
echo "\n Deleting temporary files from Server\n\n";
unlink($sqlFile);
unlink($sqlbackupFile);

echo "\n Deleting Old files from Dropbox\n\n";
// Deleting any old file
$ydate = date('Y_m_d', strtotime('-7 days'));
var_dump($dbxClient->delete("/DB_".$ydate.".tgz"));
echo "\n";
}

// Upload Folder Backup
$option = elgg_get_plugin_setting('dropboxbk_upload', 'drop_box_backup');

if($option == "yes"){
$uploadFolder = elgg_get_data_path();
$sourceFile = "Upload_".date('Y_m_d').".zip";
$uploadbackupFolder = $tmpDir.$sourceFile;
echo "Ziping $uploadFolder start\n";
exec("zip -r $uploadbackupFolder $uploadFolder");
echo "Ziping $uploadFolder end\n";

echo("Uploading $uploadbackupFolder to Dropbox\n");
$f = fopen($uploadbackupFolder, "r");
print_r($f);
echo ("\n");
$result = $dbxClient->uploadFile($dropbox_path.$sourceFile, Dropbox\WriteMode::add(), $f);
print_r($result);
echo ("\n");
fclose($f);

// Delete the temporary files
echo "\n Deleting temporary files from Server\n\n";
unlink($uploadbackupFolder);

echo "\n Deleting Old files from Dropbox\n\n";
// Deleting any old file
$ydate = date('Y_m_d', strtotime('-7 days'));
var_dump($dbxClient->delete("/Upload_".$ydate.".zip"));
echo "\n";
}


// Source Folder Backup
$option = elgg_get_plugin_setting('dropboxbk_source', 'drop_box_backup');

if($option == "yes"){
$sourceFolder = elgg_get_root_path();
$sourceFile = "Source_".date('Y_m_d').".zip";
$sourcebackupFolder = $tmpDir.$sourceFile;
echo "Ziping $sourceFolder start\n";
exec("zip -r $sourcebackupFolder $sourceFolder");
echo "Ziping $sourceFolder end\n";

echo("Uploading $sourcebackupFolder to Dropbox\n");
$f = fopen($sourcebackupFolder, "r");
print_r($f);
echo ("\n");
$result = $dbxClient->uploadFile($dropbox_path.$sourceFile, Dropbox\WriteMode::add(), $f);
print_r($result);
echo ("\n");
fclose($f);

// Delete the temporary files
echo "\n Deleting temporary files from Server\n\n";
unlink($sourcebackupFolder);

echo "\n Deleting Old files from Dropbox\n\n";
// Deleting any old file
$ydate = date('Y_m_d', strtotime('-7 days'));
var_dump($dbxClient->delete("/Source_".$ydate.".zip"));
echo "\n";
}
}