<?php
declare(strict_types=1);

namespace Colorcube\SimulateStaticUrls\Service;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UrlDecodingService extends AbstractUrlMapService implements SingletonInterface
{

    /**
     * @param string $pagePath
     * @return array|null
     */
    public function decodeFromPagePath(string $pagePath)
    {
        $parametersArray = null;

        // If there has been a redirect (basically; we arrived here otherwise than via "index.php" in the URL)
        // this can happen either due to a CGI-script or because of rewrite rule.
        // Additionally we check if '.html'/'.xml' is in the url.
        if ($pagePath && substr($pagePath, 0, 9) !== 'index.php' && (stripos($pagePath, '.html') || stripos($pagePath, '.xml'))) {
            $parametersArray = $this->getParametersFromPagePath($pagePath);
        }

        return $parametersArray;
    }


    /**
     * convert speaking URL into parameters which will be further processed by the TYPO3 frontend
     *
     * We extract the id and type from the name of that HTML-file.
     * The url must end with '.html' and the format must comply with either of these:
     * 1:      '[title].[id].[type].[L].html'  - title is just for easy recognition in the
     *                                       logfile!; no practical use of the title for TYPO3.
     * 2:      '[id].[type].html'          - above, but title is omitted; no practical use of
     *                                       the title for TYPO3.
     * 3:      '[id].html'                 - only id, type and L is set to the default, zero!
     * NOTE: In all case 'id' may be the uid-number
     *
     * @param    string $path
     * @return    array
     */
    public function getParametersFromPagePath(string $path)
    {
        $decodedUrlParameters = $this->decodePrameters($path);

        $uParts = parse_url($path);
        $fI = GeneralUtility::split_fileref($uParts['path']);

        if ($fI['filebody']) {
            $parts = explode('.', $fI['filebody']);

            // remove page title
            array_shift($parts);

            if ($parts) {
                $decodedUrlParameters['id'] = (int)array_shift($parts);
            }
            if ($parts) {
                $decodedUrlParameters['type'] = (int)array_shift($parts);
            }
            if ($parts) {
                $decodedUrlParameters['L'] = (int)array_shift($parts);
            }
        }

        return $decodedUrlParameters;
    }


    /**
     * search and process pEncoded parameter
     *
     * url is searched for '+' sign.
     * If the sign is present, all successive data is analysed.
     *
     * @param string $url
     * @return array
     */
    public function decodePrameters($url)
    {
        // Splitting the Id by a '+' sign
        $idParts = explode('+', $url, 2);
        if (isset($idParts[1])) {
            list($hash) = explode('.', $idParts[1]);
            return $this->decodePrametersHash($hash, $url);
        }
        return [];
    }


    /**
     * Analyzes the second part of a id-string (after the "+"), looking for B6 or M5 encoding
     * and if found it will resolve it
     *
     * @param string $hash : String to analyze
     * @param string|null $url
     * @return array
     */
    protected function decodePrametersHash($hash, $url=null)
    {
        # $this->debugLog(__METHOD__);

        $getVars = [];
        switch (substr($hash, 0, 2)) {
            case 'B6':
                # $this->debugLog('base64');
                $addParams = base64_decode(str_replace('_', '=', str_replace('-', '/', substr($hash, 2))));
                $getVars = static::queryStringToParametersArray($addParams);
                break;

            case 'M5':
                // remove prefix
                $queryHash = substr($hash, 2);
                # $this->debugLog('md5: ' . $queryHash);
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('params', 'cache_md5params', 'md5hash=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($queryHash, 'cache_md5params'));
                $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

                if($row) {
                    # $this->debugLog('md5 found: ' . $row['params']);
                    // update tstamp
                    $GLOBALS['TSFE']->updateMD5paramsRecord(substr($hash, 2));
                    $getVars = static::queryStringToParametersArray(urldecode($row['params']));
                } else {
                    // We log the missing hash but silently we ignore it so the page will be displayed but maybe with the wrong content
                    // todo: redirect to page with md5?
                    error_log('md5hash not found for ' . $url);
                }
                break;
        }

        return $getVars;
    }


    protected function debugLog($msg) {
        error_log($msg);
    }

}
