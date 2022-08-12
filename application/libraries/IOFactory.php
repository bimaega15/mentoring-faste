<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once('assets_js/PHPExcel-1.8.1/PHPExcel/IOFactory.php');

class IOFactory extends PHPExcel_IOFactory
{
    public function __construct()
    {
        parent::__construct();
    }
}
