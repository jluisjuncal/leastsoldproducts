<?php

include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

class modLeastSoldProducts extends DolibarrModules
{
    public function __construct($db)
    {
        global $langs, $conf;

        $this->db = $db;
        $this->numero = 500000;
        $this->rights_class = 'leastsoldproducts';
        $this->family = "products";
        $this->module_position = '90';
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->description = "Track and analyze least sold products";
        $this->descriptionlong = "Module to identify and analyze products with lowest sales performance";
        $this->editor_name = 'Your Company';
        $this->version = '1.0';
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        $this->picto = 'product';
        $this->module_parts = array(
            'triggers' => 0,
            'login' => 0,
            'substitutions' => 0,
            'menus' => 1,
            'theme' => 0,
            'tpl' => 0,
            'barcode' => 0,
            'models' => 0,
            'css' => array(),
            'js' => array(),
            'hooks' => array('productcard')
        );

        $this->dirs = array("/leastsoldproducts/temp");
        $this->config_page_url = array("setup.php@leastsoldproducts");
        $this->depends = array("modProduct");
        $this->requiredby = array();
        $this->conflictwith = array();
        $this->langfiles = array("leastsoldproducts@leastsoldproducts");
        $this->phpmin = array(7, 0);
        $this->need_dolibarr_version = array(13, 0);
        $this->warnings_activation = array();
        $this->warnings_activation_ext = array();

        $this->const = array(
            1 => array(
                'LEASTSOLD_ANALYSIS_PERIOD',
                'chaine',
                '3',
                'Default analysis period in months',
                0,
                'current',
                1
            )
        );

        $this->rights = array();
        $r = 0;

        $this->rights[$r][0] = $this->numero . sprintf("%02d", $r + 1);
        $this->rights[$r][1] = 'View least sold products analysis';
        $this->rights[$r][4] = 'read';
        $this->rights[$r][5] = 1;
        $r++;

        $this->menu = array();
        $r = 0;

        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=products',
            'type' => 'left',
            'titre' => 'Least Sold Products',
            'prefix' => img_picto('', $this->picto, 'class="paddingright pictofixedwidth valignmiddle"'),
            'mainmenu' => 'products',
            'leftmenu' => 'leastsoldproducts',
            'url' => '/custom/leastsoldproducts/index.php',
            'langs' => 'leastsoldproducts@leastsoldproducts',
            'position' => 100,
            'enabled' => '1',
            'perms' => '$user->rights->leastsoldproducts->read',
            'target' => '',
            'user' => 0
        );
        $r++;
    }

    public function init($options = '')
    {
        $sql = array();
        return $this->_init($sql, $options);
    }

    public function remove($options = '')
    {
        $sql = array();
        return $this->_remove($sql, $options);
    }
}