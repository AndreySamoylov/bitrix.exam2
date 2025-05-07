<?php

use Bitrix\Main\Loader;
use	Bitrix\Main\Localization\Loc;
use	Bitrix\Iblock;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

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


//iblock elements
$arSelectElems = array (
    "ID",
    "IBLOCK_ID",
    "NAME",
);
$arFilterElems = array (
    "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
    "ACTIVE" => "Y"
);
$arSortElems = array (
    "NAME" => "ASC"
);

$arResult["ELEMENTS"] = array();
$rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
while($arElement = $rsElements->GetNext())
{
    $arResult["ELEMENTS"][] = $arElement;
}



//iblock sections
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

$arResult["SECTIONS"] = array();
$rsSections = CIBlockSection::GetList($arSortSect, $arFilterSect, false, $arSelectSect, false);
while($arSection = $rsSections->GetNext())
{
    $arResult["SECTIONS"][] = $arSection;
}
/*
// user
$arOrderUser = array("id");
$sortOrder = "asc";+
$arFilterUser = array(
    "ACTIVE" => "Y"
);

$arResult["USERS"] = array();
$rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser); // выбираем пользователей
while($arUser = $rsUsers->GetNext())
{
    $arResult["USERS"][] = $arUser;
}	*/

$this->includeComponentTemplate();	
