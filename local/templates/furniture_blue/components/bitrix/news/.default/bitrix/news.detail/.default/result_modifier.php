<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

if (!empty($arParams['ID_IBLOCK_CANONICAL']) && is_numeric($arParams['ID_IBLOCK_CANONICAL'])) {
    $arSelect = array( "ID", "IBLOCK_ID", "NAME", "PROPERTY_NEW");
    $arFilter = array(
        "IBLOCK_ID" => $arParams['ID_IBLOCK_CANONICAL'],
        "ACTIVE"=>"Y",
        "PROPERTY_NEW"=> $arResult['ID'],
    );

    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    if ($item = $res->Fetch()){
        $arResult['CANONICAL_LINK'] = $item['NAME'];
        $this->__component->setResultCacheKeys(array('CANONICAL_LINK'));
    }
}
