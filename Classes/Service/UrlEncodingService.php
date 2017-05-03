<?php
declare(strict_types=1);

namespace Colorcube\SimulateStaticUrls\Service;

use Colorcube\SimulateStaticUrls\Model\Configuration;
use Colorcube\SimulateStaticUrls\Model\Url;
use Colorcube\SimulateStaticUrls\Utility\StringUtility;
use Doctrine\Common\Util\Debug;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;


/**
 * Build an url like /en/service/about-us.3.0.1.html
 * during FE rendering
 */
class UrlEncodingService extends AbstractUrlMapService implements SingletonInterface
{

    /*
     config {
        simulateStaticUrls = 1
        simulateStaticUrls {

            language = 1
            language {
                0 = de
                1 = en/gb
            }

            path = 1
            pathSegmentMaxLength =
            pathSegmentSmartTruncate =
            pathSegmentReplacementChar = _
            pathFormat = lowercase

            titleMaxLength = 40
            titleSmartTruncate =
            titleReplacementChar = _
            titleFormat = lowercase
        
            // if you want some parameters shorter
            parameterEncodingType = md5
            // list of parameters to be encoded others will be excluded
            parameterEncodingInclude = cHash, print, tx_ttnews[backPid], tx_ttnews[tt_news], tx_ttnews[pS], tx_ttnews[pL], tx_ttnews[arc], tx_ttnews[cat], tx_ttnews[pointer], tx_ttnews[swords]
            // list of parameters to be excluded from encoding
            parameterEncodingExclude =

        }

        // disable the other ones
        tx_realurl_enable = 0
        simulateStaticDocuments = 0
    }
     */


    /**
     * @var Configuration
     */
    protected $config;


    public function __construct(array $config)
    {
        $this->config = new Configuration((array)$config['simulateStaticUrls.']);
        $this->config->setValue('absRefPrefix', $config['absRefPrefix']);
    }


    /**
     * Here we go and build the url
     *
     * @param string $queryString
     * @return string
     */
    public function encodeFromQueryString(string $queryString): string
    {
        $url = new Url();

        // for now add all parameters to the url
        // some parameters will be removed later so we might end up to have an empty parameter list
        $url->parameters = self::queryStringToParametersArray($queryString);

        $this->handleLanguage($url);

        $this->handlePage($url);

        return $this->buildStaticUrl($url);
    }


    /**
     * Processes the requested language an change $url accordingly
     *
     * @param Url $url
     * @return void
     */
    protected function handleLanguage(Url $url)
    {
        $url->languageUid = isset($url->parameters['L']) ? (int)$url->parameters['L'] : 0;

        if ($this->config->isEnabled('language')) {

            if ($this->config->has('language.' . $url->languageUid)) {
                $url->pathSegments[] = $this->config->getValue('language.' . $url->languageUid);

            } elseif (($url->languageUid > 0) && LanguageService::hasLanguage($url->languageUid)) {
                // add iso code to path segment
                $url->pathSegments[] = LanguageService::getIsoCode($url->languageUid);
            }
        }
        unset($url->parameters['L']);
    }


    /**
     * Processes the requested page an change $url accordingly
     * Build the path if configured
     *
     * @param Url $url
     * @return void
     */
    protected function handlePage(Url $url)
    {
        if (isset($url->parameters['id'])) {
            $url->pid = (int)$url->parameters['id'];
            unset($url->parameters['id']);
            $pageRecord = PageService::getPage($url->pid, $url->languageUid);

            if ($pageRecord === null) {
                // this makes no sense, the page isn't there and it should not be possible to get here
                // nevertheless add a pseudo page title
                $url->title = 'p';
                return;
            }

            $pagePathSegments = null;
            if ($this->config->isEnabled('path')) {
                $pagePathSegments = $this->getPathForPageRecord($pageRecord, $url->languageUid);
            }

            if ($pagePathSegments) {
                // remove last segment - it's the page
                array_pop($pagePathSegments);

                foreach ($pagePathSegments as $pagePathSegment) {
                    $pagePathSegment = StringUtility::convertStringToAscii(
                        $pagePathSegment,
                        $this->config->getValue('pathSegmentReplacementChar'),
                        $this->config->getValue('pathSegmentMaxLength'),
                        $this->config->getValue('pathSegmentSmartTruncate'),
                        $this->config->getValue('pathSegmentFormat')
                    );

                    $url->pathSegments[] = $pagePathSegment;
                }
            }

            $url->title = $pageRecord['nav_title'] ?: $pageRecord['title'];
        }
    }


    /**
     * Finally we put the url all together
     *
     * @param $url
     * @return string
     */
    public function buildStaticUrl($url) :string
    {
        $prefix = $encodedPath = $this->config->getValue('absRefPrefix') ?? '/';

        $path = implode('/', $url->pathSegments);

        $simulatedFileName = $this->buildFilename($url);

        $parametersString = $this->buildParameterString($url);

        return rtrim($prefix . $path, '/') . '/' . $simulatedFileName . $parametersString;
    }


    /**
     * @param Url $url
     * @return string
     */
    protected function buildFilename(Url $url) :string
    {
        $staticPageTitle = StringUtility::convertStringToAscii(
            $url->title,
            $this->config->getValue('titleReplacementChar'),
            $this->config->getValue('titleMaxLength'),
            $this->config->getValue('titleSmartTruncate'),
            $this->config->getValue('titleFormat')
        );

        $enc = $this->encodeParameter($url);

        return $staticPageTitle . '.' . $url->pid . $enc . '.' . $url->type . '.' . $url->languageUid . '.html';
    }


    /**
     * Here we put together parameters that are left to be added to the url with ?
     *
     * @param Url $url
     * @return string
     */
    protected function buildParameterString(Url $url) :string
    {
        $parametersString = '';

        // remove cHash if it's the last parameter
        if (count($url->parameters) === 1 && isset($url->parameters['cHash'])) {
            unset($url->parameters['cHash']);
        }
        if (count($url->parameters)) {
            $parametersString = '?' . self::parametersArrayToQueryString($url->parameters);
        }

        return $parametersString;
    }


    /**
     * Encode parameters if configured
     * This will transform parameters in another form to make them less ugly?!
     * Parameters that are encoded are removed from the parameters list
     *
     * @param Url $url
     * @return string
     */
    protected function encodeParameter(Url $url) :string
    {
        $enc = '';

        if ($url->parameters && !$url->parameters['no_cache']) {

            $parameters = $url->parameters;

            if (is_array($this->config->getValue('pEncodingAllowedParamNames'))) {
                foreach ($parameters as $parameterName => $parameter) {
                    if (!in_array($parameterName, $this->config->getValue('pEncodingAllowedParamNames'), true)) {
                        unset($parameters[$parameterName]);
                    }
                }
            }
            if (is_array($this->config->getValue('pEncodingExcludedParamNames'))) {
                foreach ($parameters as $parameterName => $parameter) {
                    if (in_array($parameterName, $this->config->getValue('pEncodingExcludedParamNames'), true)) {
                        unset($parameters[$parameterName]);
                    }
                }
            }

            // remove the parameters which will be encoded from the remaining parameters which will be appended to the url
            if ($parameters) {
                foreach ($parameters as $parameterName => $parameter) {
                    unset($url->parameters[$parameterName]);
                }
            }

            if ($parameters) {
                $addParams = self::parametersArrayToQueryString($parameters);
                switch ($this->config->getValue('parameterEncodingType')) {
                    case
                    Configuration::ParameterEncodingTypeMd5:
                        $md5 = substr(md5($addParams), 0, 10);
                        $enc = '+M5' . $md5;

                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                            'md5hash',
                            'cache_md5params',
                            'md5hash=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($md5, 'cache_md5params')
                        );
                        if (!$GLOBALS['TYPO3_DB']->sql_num_rows($res)) {
                            $insertFields = array(
                                'md5hash' => $md5,
                                'tstamp' => $GLOBALS['EXEC_TIME'],
                                'type' => 1,
                                'params' => $addParams
                            );

                            $GLOBALS['TYPO3_DB']->exec_INSERTquery('cache_md5params', $insertFields);
                        }
                        $GLOBALS['TYPO3_DB']->sql_free_result($res);
                        break;
                    case Configuration::ParameterEncodingTypeBase64:
                        $enc = '+B6' . str_replace('=', '_', str_replace('/', '-', base64_encode($addParams)));
                        break;
                }
            }
        }

        return $enc;
    }


    /**
     * Build the path from the rootline
     *
     * @param array $targetPage
     * @param int $languageUid
     * @return null|array
     */
    protected function getPathForPageRecord(array $targetPage, int $languageUid)
    {
        // TODO test other doktypes and see what would happen if we remove the check
        if (!in_array((int)$targetPage['doktype'], PageService::SUPPORTED_DOKTYPES, false)) {
            return null;
        }
        $rootline = PageService::getRootline($targetPage['uid']);
        if ($languageUid > 0) {
            $rootline = FrontendControllerService::getPageRepository()->getPagesOverlay($rootline, $languageUid);
        }
        $pathSegments = [];
        foreach ($rootline as $rootlinePage) {
            if ($rootlinePage['is_siteroot']) {
                break;
            }
            $slugField = '';
            foreach (['nav_title', 'title', 'uid'] as $possibleSlugField) {
                if (!empty($rootlinePage[$possibleSlugField])) {
                    $slugField = $possibleSlugField;
                    break;
                }
            }
            $pathSegments[] = $rootlinePage[$slugField];
        }
        return array_reverse($pathSegments);
    }

}
