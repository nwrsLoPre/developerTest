<?php
/**
 * Created on 08.04.19
 */


namespace App;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\Localization\Loc;

class IblockWorker
{
    public function __construct()
    {
        $this->checkModules();
    }

    private function checkModules()
    {
        if (!Loader::IncludeModule("iblock")) {
            throw new LoaderException(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        }
    }

    public function getList(
        $select = ['*'],
        $filter = null,
        $group = null,
        $order = null,
        $limit = null,
        $offset = null,
        $runtime = null)
    {
        $cacheTime = 3600;
		$cacheId = __CLASS__ . '_' .__FUNCTION__;
        $result = null;

		$isParamsPassed = false;
        if (!is_null($filter) OR !is_null($filter) OR !is_null($group) OR !is_null($order)
            OR !is_null($limit) OR !is_null($offset) OR !is_null($runtime) OR $select != ['*'])
        {
            $isParamsPassed = true;
        }

        $cache = Bitrix\Main\Data\Cache::createInstance();
        if ($cache->initCache($cacheTime, $cacheId) AND !$isParamsPassed)
        {
            $result = $cache->getVars();
        }
        elseif ($cache->startDataCache())
        {

            $res = Element::getList([
                'select'   => $select,
                'filter'   => $filter,
                'group'    => $group,
                'order'    => $order,
                'limit'    => $limit,
                'offset'   => $offset,
                'runtime'  => $runtime,
            ]);
            $result = $res->fetchAll();

            $cache->endDataCache($result);
        }

        return $result;
    }
}
