<?php

function leastsoldproductsAdminPrepareHead()
{
    global $langs, $conf;

    $langs->load("leastsoldproducts@leastsoldproducts");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/custom/leastsoldproducts/admin/setup.php", 1);
    $head[$h][1] = $langs->trans("Settings");
    $head[$h][2] = 'settings';
    $h++;

    return $head;
}

function getAnalysisPeriod()
{
    global $conf;
    return !empty($conf->global->LEASTSOLD_ANALYSIS_PERIOD) ? $conf->global->LEASTSOLD_ANALYSIS_PERIOD : 3;
}