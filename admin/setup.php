<?php

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) { $i--; $j--; }
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) $res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) $res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) $res = @include "../main.inc.php";
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
dol_include_once('/custom/leastsoldproducts/lib/leastsoldproducts.lib.php');

$langs->loadLangs(array("admin", "leastsoldproducts@leastsoldproducts"));

if (!$user->admin) accessforbidden();

$action = GETPOST('action', 'aZ09');

if ($action == 'update') {
    $analysis_period = GETPOST('analysis_period', 'int');
    dolibarr_set_const($db, "LEASTSOLD_ANALYSIS_PERIOD", $analysis_period, 'chaine', 0, '', $conf->entity);
    setEventMessages($langs->trans("SetupSaved"), null, 'mesgs');
}

/*
 * View
 */

llxHeader('', $langs->trans('LeastSoldProductsSetup'));

$linkback = '<a href="'.DOL_URL_ROOT.'/admin/modules.php?restore_lastsearch_values=1">'.$langs->trans("BackToModuleList").'</a>';
print load_fiche_titre($langs->trans('LeastSoldProductsSetup'), $linkback, 'title_setup');

$head = leastsoldproductsAdminPrepareHead();
print dol_get_fiche_head($head, 'settings', $langs->trans("Module500000Name"), -1, "product");

$form = new Form($db);

print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="update">';

print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameters").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print '</tr>';

// Analysis period setting
print '<tr class="oddeven">';
print '<td>'.$langs->trans("AnalysisPeriod").'</td>';
print '<td>';
print '<input type="text" name="analysis_period" value="'.getAnalysisPeriod().'" size="3"> '.$langs->trans("Months");
print '</td>';
print '</tr>';

print '</table>';

print '<div class="center">';
print '<input type="submit" class="button" value="'.$langs->trans("Save").'">';
print '</div>';

print '</form>';

print dol_get_fiche_end();

llxFooter();
$db->close();