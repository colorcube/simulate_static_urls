<?php
declare(strict_types=1);
namespace Colorcube\SimulateStaticUrls\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractUrlMapService
{
    /**
     * @param string $queryString
     * @return array
     */
    public static function queryStringToParametersArray(string $queryString):array
    {
        parse_str($queryString, $urlParameters);
        return $urlParameters;
    }

    /**
     * @param array $urlParameters
     * @return string
     */
    public static function parametersArrayToQueryString(array $urlParameters):string
    {
        return http_build_query($urlParameters);
    }

}
