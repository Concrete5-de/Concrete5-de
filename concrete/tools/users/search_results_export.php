<?php  
defined('C5_EXECUTE') or die("Access Denied.");
ini_set('memory_limit', -1);

$tp = new TaskPermission();
if (!$tp->canAccessUserSearch()) { 
	die(t("You have no access to users."));
}

// no limit
$_REQUEST['numResults'] = -1;

$u = new User();
$cnt = Loader::controller('/dashboard/users/search');
$userList = $cnt->getRequestedSearchResults();
$users = $userList->getPage();

header("Content-Type: application/vnd.ms-excel");
header("Cache-control: private");
header("Pragma: public");
$date = date('Ymd');
header("Content-Disposition: inline; filename=user_report_{$date}.xls"); 
header("Content-Title: User Report - Run on {$date}");

echo("<table><tr>");
echo("<td><b>".t('Username')."</b></td>");
echo("<td><b>".t('Email Address')."</b></td>");
echo("<td><b>".t('Registered')."</b></td>");
echo("<td><b>".t('# Logins')."</b></td>");
$attribs = UserAttributeKey::getList();
foreach($attribs as $ak) {
	echo("<td><b>" . $ak->getKeyName() . "</b></td>");
}
echo("</tr>");
foreach($users as $ui) { 
	echo("<tr>");
	echo("<td>{$ui->getUserName()}</td>");
	echo("<td>{$ui->getUserEmail()}</td>");
	echo("<td>{$ui->getUserDateAdded()}</td>");
	echo("<td>{$ui->getNumLogins()}</td>");
	foreach($attribs as $ak) {
		echo("<td>" . $ui->getAttribute($ak, 'display') . "</td>");
	}
	echo("</tr>");
	unset($ui);
	unset($ak);
}
echo("</table>");
exit;