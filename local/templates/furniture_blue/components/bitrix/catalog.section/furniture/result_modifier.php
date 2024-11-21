<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['ITEMS'] as $key => $arItem)
{
	$arItem['PRICES']['PRICE']['PRINT_VALUE'] = number_format((float)$arItem['PRICES']['PRICE']['PRINT_VALUE'], 0, '.', ' ');
	$arItem['PRICES']['PRICE']['PRINT_VALUE'] .= ' '.$arItem['PROPERTIES']['PRICECURRENCY']['VALUE_ENUM'];

	$arResult['ITEMS'][$key] = $arItem;
}

$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_MATERIAL");
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$arGroupBy = array('PROPERTY_MATERIAL');
$res = CIBlockElement::GetList(Array(), $arFilter, $arGroupBy, false, $arSelect);

$materials = [];
while($ob = $res->Fetch())
{
    $materials[] = $ob;
}

$arResult['MATERIALS'] = $materials;
