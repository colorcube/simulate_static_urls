<?php
declare(strict_types = 1);
namespace Colorcube\SimulateStaticUrls\Model;


/**
 * This stores all data needed to build the url.
 * The data might be modified untile the final url is generated
 */
class Url
{
    public $title = '';

    public $languageUid = 0;
    public $pid = 0;
    public $type = 0;


    public $languageSegment = '';
    public $locationSegment = '';

    public $pathSegments = [];

    public $parameters = [];

}
