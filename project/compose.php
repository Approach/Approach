<?php

require_once('layout.php');
require_once( '../../approach/core.php');

$InstallPath='http://portal.localhost/approachfoundation.org/';
$RunPath = 'C:/Serve/WT-NMP/WWW/approachfoundation.org/';

$PostOptions=array();
$PostOptions['template_path']=$RunPath . 'Templates/featured.xml';
$PostOptions['featured']['ALL']['target'] = 'featured';

$DisplayArea['Stage']->children[]=$Featured=new Smart('ul', $PostOptions);
$Featured->classes[]='Feature';


?>