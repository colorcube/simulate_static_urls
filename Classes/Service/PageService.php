<?php
declare(strict_types=1);

namespace Colorcube\SimulateStaticUrls\Service;


use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

abstract class PageService
{
    const SUPPORTED_DOKTYPES = [
        PageRepository::DOKTYPE_DEFAULT,
        PageRepository::DOKTYPE_SHORTCUT,
    ];

    protected static $pagesCache = [];

    public static function getPage(int $pageUid, int $languageUid)
    {
        $pageRecord = self::getPageUnresolvedWithOverlay($pageUid, $languageUid);

        switch ((int)$pageRecord['doktype']) {
            case PageRepository::DOKTYPE_SHORTCUT:
                $pageRecord = self::handleShortcutDokType($pageRecord);
                break;
            default:
        }

        return $pageRecord;
    }


    protected static function getPageUnresolvedWithOverlay(int $pageUid, int $languageUid)
    {
        $pageRecord = null;

        if (isset(self::$pagesCache[$languageUid][$pageUid])) {
            $pageRecord = self::$pagesCache[$languageUid][$pageUid];

        } else {

            if (isset(self::$pagesCache[0][$pageUid])) {
                $pageRecord = self::$pagesCache[0][$pageUid];
            } else {
                $pageRecord = BackendUtility::getRecord('pages', $pageUid);
                self::$pagesCache[0][$pageUid] = $pageRecord;
            }

            if ($pageRecord) {
                if ($languageUid > 0) {
                    $doktype = $pageRecord['doktype'];
                    $pageRecord = FrontendControllerService::getPageRepository()->getPageOverlay($pageRecord, $languageUid);
                    // doktype could be '0' by accident so we handle that
                    $pageRecord['doktype'] = $pageRecord['doktype'] ? $pageRecord['doktype'] : $doktype;
                }
                self::$pagesCache[$languageUid][$pageUid] = $pageRecord;
            }
        }

        return $pageRecord;
    }


    protected static function handleShortcutDokType($pageRecord)
    {
        if (
            (int)$pageRecord['doktype'] === PageRepository::DOKTYPE_SHORTCUT
            && (int)$pageRecord['shortcut_mode'] === PageRepository::SHORTCUT_MODE_NONE
        ) {
            try {
                $pageRecord = FrontendControllerService::getController()->getPageShortcut(
                    $pageRecord['shortcut'],
                    $pageRecord['shortcut_mode'],
                    $pageRecord['uid']
                );
            } catch (PageNotFoundException $e) {
                error_log($e->getMessage());
                // this might happen with wrong data in pages_overlay.shortcut_mode != pages.shortcut_mode
                return null;
            }
        }

        return $pageRecord;
    }

    /**
     * Get the rootline directly from RootlineUtility instead of TSFE->sys_page to circumvent the rootline cache
     *
     * @param int $id
     * @return array
     */
    public static function getRootline(int $id): array
    {
        static $rootlines = [];
        if (!isset($rootlines[$id])) {
            $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $id, '', FrontendControllerService::getPageRepository());
            $rootline->purgeCaches();
            GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_rootline')->flush();
            $rootlines[$id] = $rootline->get();
        }
        return $rootlines[$id];
    }
}
