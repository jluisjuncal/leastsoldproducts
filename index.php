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

require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
dol_include_once('/custom/leastsoldproducts/class/leastsoldproducts.class.php');
dol_include_once('/custom/leastsoldproducts/lib/leastsoldproducts.lib.php');

// Security check
if (!$user->rights->leastsoldproducts->read) accessforbidden();

$langs->loadLangs(array("leastsoldproducts@leastsoldproducts", "companies", "products"));

// Parameters
$page = GETPOSTISSET('pageplusone') ? (GETPOST('pageplusone') - 1) : GETPOST("page", 'int');
if (empty($page) || $page == -1) $page = 0;
$limit = GETPOST('limit', 'int') ? GETPOST('limit', 'int') : $conf->liste_limit;
$offset = $limit * $page;

// Get period for analysis
$search_period = GETPOST('search_period', 'int') ? GETPOST('search_period', 'int') : getAnalysisPeriod();

$form = new Form($db);
$leastSoldProducts = new LeastSoldProducts($db);

/*
 * View
 */

llxHeader('', $langs->trans('LeastSoldProducts'));

print load_fiche_titre($langs->trans("LeastSoldProducts"));

// Filter form
print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
print '<input type="hidden" name="token" value="'.newToken().'">';

print '<div class="justify-content-end">';
print $langs->trans("Period").' ';
print '<input type="text" name="search_period" value="'.$search_period.'" size="3"> '.$langs->trans("Months");
print ' ';
print '<input type="submit" name="button_search_x" value="'.$langs->trans("Refresh").'" class="button">';
print '</div>';

print '</form>';

// Get and display products
$products = $leastSoldProducts->getLeastSoldProducts($limit, $offset, $search_period);

if ($products !== false) {
    print '<div class="div-table-responsive">';
    print '<table class="tagtable liste">'."\n";

    print '<tr class="liste_titre">';
    print '<th>'.$langs->trans("Ref").'</th>';
    print '<th>'.$langs->trans("Label").'</th>';
    print '<th>'.$langs->trans("Description").'</th>';
    print '<th class="right">'.$langs->trans("QuantitySold").'</th>';
    print '</tr>';

    if (count($products) > 0) {
        foreach ($products as $product) {
            print '<tr class="oddeven">';
            print '<td><a href="'.DOL_URL_ROOT.'/product/card.php?id='.$product->rowid.'">'.$product->ref.'</a></td>';
            print '<td>'.$product->label.'</td>';
            print '<td>'.dol_trunc($product->description, 50).'</td>';
            print '<td class="right">'.price($product->total_qty).'</td>';
            print '</tr>';
        }
    } else {
        print '<tr><td colspan="4"><span class="opacitymedium">'.$langs->trans("NoRecordFound").'</span></td></tr>';
    }

    print '</table>';
    print '</div>';
} else {
    dol_print_error($db);
}

llxFooter();
$db->close();