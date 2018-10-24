<?php
declare(strict_types=1);

$partialsPath = APPLICATION_ROOT_DIRECTORY . '/templates/admin/partials/';
$listPartialsPath = 'partials/';

require $partialsPath . 'header.php';
require $listPartialsPath . 'endMain.php';
require $partialsPath . 'footer.php';

$htmlHeadCss = '<link href="/css/adminFlexList.css" rel="stylesheet" type="text/css">';
$htmlBodyContent = $header;
$htmlBodyContent .= $listTemplate->getStartMain();
$htmlBodyContent .= $listTemplate->getResultsRows();
$htmlBodyContent .= $endMain;
$htmlBodyContent .= $footer;
$htmlBodyJs = '<script type="text/javascript" src="/js/uiHelper.js"></script><script type="text/javascript" src="/js/sortTable.js"></script>';

require APPLICATION_ROOT_DIRECTORY . '/templates/base.php';
