<?php
defined('TYPO3_MODE') or die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['linkData-PostProc']['Colorcube.SimulateStaticUrls'] = \Colorcube\SimulateStaticUrls\Hook\UrlRewritingHook::class . '->encodeUrl';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkAlternativeIdMethods-PostProc']['Colorcube.SimulateStaticUrls'] = \Colorcube\SimulateStaticUrls\Hook\UrlRewritingHook::class . '->decodeUrl';


