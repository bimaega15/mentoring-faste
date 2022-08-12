
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once('assets_js/PHPExcel-1.8.1/src/PHPExcel.php');

class Excel extends PHPExcel
{
    public function __construct()
    {
        parent::__construct();
    }
}
?>