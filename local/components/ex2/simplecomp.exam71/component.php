<?php

use Bitrix\Main\Loader;
use	Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

/** @global CMain $APPLICATION */
/** @var array $arParams */
/** @var array $arResult */

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_71_IBLOCK_MODULE_NONE"));
	return;
}

# START Обработка входных параметров
if (empty($arParams['PRODUCTS_IBLOCK_ID_PROPERTY'])) {
    ShowError(Loc::getMessage("SIMPLECOMP_EXAM2_71_UNFILLED_PARAMETER", array("#PARAMETER_NAME#" => 'PRODUCTS_IBLOCK_ID_PROPERTY')));
    return;
}

if (empty($arParams["PRODUCTS_IBLOCK_ID"]) || !is_numeric($arParams["PRODUCTS_IBLOCK_ID"])) {
    ShowError(Loc::getMessage("SIMPLECOMP_EXAM2_71_UNFILLED_PARAMETER", array("#PARAMETER_NAME#" => 'PRODUCTS_IBLOCK_ID')));
    return;
} else {
    $arParams["PRODUCTS_IBLOCK_ID"] = (int) $arParams["PRODUCTS_IBLOCK_ID"];
}

if (empty($arParams["COMPANY_MAKER_IBLOCK_ID"]) || !is_numeric($arParams["COMPANY_MAKER_IBLOCK_ID"])) {
    ShowError(Loc::getMessage("SIMPLECOMP_EXAM2_71_UNFILLED_PARAMETER", array("#PARAMETER_NAME#" => 'COMPANY_MAKER_IBLOCK_ID' )));
    return;
} else {
    $arParams["COMPANY_MAKER_IBLOCK_ID"] = (int) $arParams["COMPANY_MAKER_IBLOCK_ID"];
}
# END Обработка входных параметров


if ($this->startResultCache())
{
    // START Получение фирм производителей
    $arSelect = Array(
        "ID",
        "NAME",
        "CODE",
    );
    $arFilter = Array(
        "IBLOCK_ID" => $arParams['COMPANY_MAKER_IBLOCK_ID'],
        "ACTIVE_DATE"=>"Y",
        "ACTIVE"=>"Y",
    );
    $result = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($company = $result->Fetch())
    {
        $arResult['COMPANIES'][$company['ID']] = $company;
    }

    if (is_array($arResult['COMPANIES']) && count($arResult['COMPANIES']) > 0) {
        $arResult['COMPANIES_COUNT'] = count($arResult['COMPANIES']);
    }
    // END Получение фирм производителей

    // START Получение товаров
    $arSelect = Array(
        "ID",
        "NAME",
        "DATE_ACTIVE_FROM",
        "DETAIL_PAGE_URL",
        "PROPERTY_" . $arParams['PRODUCTS_IBLOCK_ID_PROPERTY'],
        "PROPERTY_PRICE",
        "PROPERTY_MATERIAL",
        "PROPERTY_ARTNUMBER",
    );
    $arFilter = Array(
        "IBLOCK_ID" => $arParams['PRODUCTS_IBLOCK_ID'],
        "ACTIVE_DATE"=>"Y",
        "ACTIVE"=>"Y",
        "!PROPERTY_" . $arParams['PRODUCTS_IBLOCK_ID_PROPERTY'] => false,
    );
    $result = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($product = $result->GetNext())
    {
        if (!empty($product['PROPERTY_COMPANY_MAKER_VALUE'])) {
            $arResult['COMPANIES'][$product['PROPERTY_COMPANY_MAKER_VALUE']]['PRODUCTS'][] = $product;
        }
    }
    // END Получение товаров

    $this->setResultCacheKeys(array(
        "COMPANIES_COUNT",
    ));
    $this->includeComponentTemplate();

} else {
    $this->abortResultCache();
}

if (!empty($arResult['COMPANIES_COUNT'])) {
    $APPLICATION->setTitle( Loc::getMessage('SIMPLECOMP_EXAM2_71_BROWSER_TITLE', array("#COUNT#" => $arResult['COMPANIES_COUNT'])));
}
