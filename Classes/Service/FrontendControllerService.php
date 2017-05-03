<?php
declare(strict_types=1);

namespace Colorcube\SimulateStaticUrls\Service;

use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageRepository;

abstract class FrontendControllerService
{

    /**
     * @return PageRepository
     */
    public static function getPageRepository(): PageRepository
    {
        return self::getController()->sys_page;
    }

    /**
     * @return TemplateService
     */
    public static function getTemplateService(): TemplateService
    {
        return self::getController()->tmpl;
    }

    /**
     * @return TypoScriptFrontendController
     */
    public static function getController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }


}
