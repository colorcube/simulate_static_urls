<?php
declare(strict_types=1);

namespace Colorcube\SimulateStaticUrls\Service;


use TYPO3\CMS\Backend\Utility\BackendUtility;

abstract class LanguageService
{
    protected static $systemLanguages = [];

    protected static function getLanguage(int $languageUid)
    {
        if (isset(self::$systemLanguages[$languageUid])) {
            $languageRecord = self::$systemLanguages[$languageUid];
        } else {
            $languageRecord = BackendUtility::getRecord('sys_language', $languageUid);
        }
        if (is_array($languageRecord)) {
            return $languageRecord;
        }
        return null;
    }

    public static function hasLanguage(int $languageUid)
    {
        return (self::getLanguage($languageUid) !== null);
    }

    public static function getIsoCode(int $languageUid)
    {
        if ($languageRecord = self::getLanguage($languageUid)) {
            return $languageRecord['language_isocode'];
        }
        return null;
    }
}
