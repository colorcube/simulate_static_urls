<?php
declare(strict_types=1);
namespace Colorcube\SimulateStaticUrls\Hook;

use Colorcube\SimulateStaticUrls\Service\FrontendControllerService;
use Colorcube\SimulateStaticUrls\Service\UrlDecodingService;
use Colorcube\SimulateStaticUrls\Service\UrlEncodingService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class UrlRewritingHook
{

    /**
     * @param array $params
     */
    public function encodeUrl(array &$params)
    {
        if (!FrontendControllerService::getTemplateService()->setup['config.']['simulateStaticUrls']) {
            return;
        }

        $queryString = parse_url($params['LD']['totalURL'])['query'];
        $params['LD']['totalURL'] = GeneralUtility::makeInstance(
            UrlEncodingService::class,
            (array)FrontendControllerService::getTemplateService()->setup['config.']
        )->encodeFromQueryString($queryString);
    }

    /**
     * @param array $params
     */
    public function decodeUrl(array $params)
    {
        // TODO check if we're activated?

        /** @var TypoScriptFrontendController $typoscriptFrontendController */
        $typoscriptFrontendController = &$params['pObj'];
        $pagePath = $typoscriptFrontendController->siteScript;
        $decodedUrlParameters = GeneralUtility::makeInstance(UrlDecodingService::class)->decodeFromPagePath($pagePath);
        if ($decodedUrlParameters === null) {
            return;
        }
        $_SERVER['QUERY_STRING'] = UrlDecodingService::parametersArrayToQueryString($decodedUrlParameters);
        $typoscriptFrontendController->mergingWithGetVars($decodedUrlParameters);
        $typoscriptFrontendController->id = (int)$decodedUrlParameters['id'];
    }

}
