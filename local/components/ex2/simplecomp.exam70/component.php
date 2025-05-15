<?php

use Bitrix\Main\Loader;
use	Bitrix\Main\Localization\Loc;
use	Bitrix\Iblock;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

/** @global CMain $APPLICATION */
/** @var array $arParams */

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

# START Обработка входных параметров
if (empty($arParams['PRODUCTS_IBLOCK_ID_PROPERTY'])) {
    ShowError(
        Loc::getMessage(
            "SIMPLECOMP_EXAM2_UNFILLED_PARAMETER",
            array(
                "#PARAMETER_NAME#" => 'PRODUCTS_IBLOCK_ID_PROPERTY'
            )
        )
    );
    return;
}

if (!empty($arParams["PRODUCTS_IBLOCK_ID"]) && !is_numeric($arParams["PRODUCTS_IBLOCK_ID"])) {
    ShowError(
        Loc::getMessage(
            "SIMPLECOMP_EXAM2_UNFILLED_PARAMETER",
            array(
                "#PARAMETER_NAME#" => 'PRODUCTS_IBLOCK_ID'
            )
        )
    );
    return;
} else {
    $arParams["PRODUCTS_IBLOCK_ID"] = (int) $arParams["PRODUCTS_IBLOCK_ID"];
}

if (!empty($arParams["NEWS_IBLOCK_ID"]) && !is_numeric($arParams["NEWS_IBLOCK_ID"])) {
    ShowError(
        Loc::getMessage(
            "SIMPLECOMP_EXAM2_UNFILLED_PARAMETER",
            array(
                "#PARAMETER_NAME#" => 'NEWS_IBLOCK_ID'
            )
        )
    );
    return;
} else {
    $arParams["NEWS_IBLOCK_ID"] = (int) $arParams["NEWS_IBLOCK_ID"];
}
# END Обработка входных параметров


if ($this->startResultCache()) {
    // START Получение продуктов
    $productsSection = [];
    
    $arSelectElems = array (
        "ID",
        "IBLOCK_ID",
        "NAME",
        "IBLOCK_SECTION_ID",
        "PROPERTY_MATERIAL_VALUE" => "PROPERTY_MATERIAL",
        "PROPERTY_ARTNUMBER",
        "PROPERTY_PRICE",
    );
    $arFilterElems = array (
        "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
        "ACTIVE" => "Y"
    );
    $arSortElems = array (
        "NAME" => "ASC"
    );
    
    $rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
    
    $productCount = 0;
    while($arElement = $rsElements->Fetch())
    {
        $productCount++;

        $sectionId = $arElement["IBLOCK_SECTION_ID"];
        // Группировка по ID раздела
        $productsSection[$sectionId]['IBLOCK_SECTION_ID'] = $sectionId;
        $productsSection[$sectionId]['ITEMS'][] = $arElement;
    }
    $arResult['PRODUCTS_COUNT'] = $productCount;
    // END Получение продуктов

    // START Получение разделов продуктов
    $arSelectSect = array (
        "ID",
        "IBLOCK_ID",
        "NAME",
        $arParams['PRODUCTS_IBLOCK_ID_PROPERTY'],
    );
    $arFilterSect = array (
        "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
        "ACTIVE" => "Y"
    );
    $arSortSect = array (
        "NAME" => "ASC"
    );


    $rsSections = CIBlockSection::GetList($arSortSect, $arFilterSect, false, $arSelectSect, false);
    while($arSection = $rsSections->Fetch())
    {
        $productsSection[$arSection['ID']]['NAME'] = $arSection['NAME'];
        $productsSection[$arSection['ID']]['UF_NEWS_LINK'] = $arSection['UF_NEWS_LINK'];
    }
    // END Получение разделов продуктов

    // START Получение новостей
    $arSelectElems = array (
        "ID",
        "IBLOCK_ID",
        "NAME",
        "ACTIVE_FROM",
    );
    $arFilterElems = array (
        "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
        "ACTIVE" => "Y"
    );
    $arSortElems = array (
        "NAME" => "ASC"
    );

    $arResult["NEWS"] = array();
    $rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
    while($arElement = $rsElements->Fetch())
    {
        $arResult["NEWS"][$arElement['ID']]['NEW'] = $arElement;
        foreach ($productsSection as $arSection) {
            if (in_array($arElement['ID'], $arSection['UF_NEWS_LINK'])) {
                $arResult["NEWS"][$arElement['ID']]['PRODUCT_SECTIONS_NAME'][] = $arSection['NAME'];

                if (is_array($arResult["NEWS"][$arElement['ID']]['ITEMS']) && count($arResult["NEWS"][$arElement['ID']]['ITEMS']) > 0) {
                    $arResult["NEWS"][$arElement['ID']]['PRODUCTS'] = array_merge($arSection['ITEMS'], $arResult["NEWS"][$arElement['ID']]['ITEMS']);
                } else {
                    $arResult["NEWS"][$arElement['ID']]['PRODUCTS'] = $arSection['ITEMS'];
                }
            }
        }
    }
    // END Получение новостей

    $this->setResultCacheKeys(array(
        "PRODUCTS_COUNT",
    ));
    $this->includeComponentTemplate();

} else {
    $this->abortResultCache();
}

if (!empty($arResult['PRODUCTS_COUNT'])) {
    $APPLICATION->setTitle( Loc::getMessage('SIMPLECOMP_EXAM2_BROWSER_TITLE', array("#COUNT#" => $arResult['PRODUCTS_COUNT'])));
}
