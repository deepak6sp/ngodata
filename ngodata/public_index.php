<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../classes/NGOData.class.php';
//require_once ('');

// what is displayed for the public


// should be in database
$text1 = 'Welcome to the <b>new</b> Tasmanian designed, owned, and operated Hosted Data Management site.';
$text2 = 'We are a company of professional database programmers with over twenty years experience in government and the private sector.  We provide you with peace of mind in the knowledge that your data is always accessable and secure.  This site is designed with not-for-profit organisations in mind, but the services offered are suited to any small business looking for professional, secure, and affordable data storage and management.';
$text3 = 'Please explore the site, and contact us with any questions - ask us.';


$html = '';
$html .= NGOData::displayPageStart();
//$html .= NGOData::displayPageStart('../Private/css/');
$html .= NGOData::displayMessage();

$html .= NGOData::startIndent();
$html .= NGOData::header3('Welcome!');

$html .= NGOData::para($text1);
$html .= NGOData::para($text2);
$html .= NGOData::para($text3);
$html .= 'dirname: '.dirname(dirname(__FILE__));
$html .= 'dirname: '.(dirname(__FILE__));
$html .= '<p> The Server: '.print_r($_SERVER).'</p>';
$html .= NGOData::closeProperty();
$html .= '<div><a href="#">Testing links...</a>';
$html .= NGOData::displayPageEnd();

print $html;
?>