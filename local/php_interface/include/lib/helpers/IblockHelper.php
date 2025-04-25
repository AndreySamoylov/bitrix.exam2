<?php

namespace helpers;

use Bitrix\Main\Loader;

class IblockHelper
{
    /**
     * Возвращает ID инфоблока по его CODE
     *
     * @param string $code
     * @return int|null
     */
    public static function getIdByCode($code): int|null
    {
        Loader::includeModule("iblock");

        try {
            $dbRes = \Bitrix\Iblock\IblockTable::getList([
                'select' => ['ID', 'CODE'],
                'filter' => ['CODE' => $code]
            ]);
        } catch (\Throwable $e) {
            return null;
        }

        while ($arRes = $dbRes->fetch()) {
            $id = $arRes['ID'];
        }

        return $id ?? null;
    }

}
