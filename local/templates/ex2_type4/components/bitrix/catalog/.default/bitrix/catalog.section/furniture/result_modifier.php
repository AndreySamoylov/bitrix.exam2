<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

/**
 * @param CUser $APPLICATION
 * @param array $arResult
 * @param array $arParams
 */

// Статус
$arSelect = array(
    "ID",
    "NAME",
    "CODE",
);
$arFilter = array(
    "IBLOCK_ID"=> \helpers\IblockHelper::getIdByCode(STATUS_IBLOCK_CODE),
    "CODE" => STATUS_PUBLISH_CODE,
    "ACTIVE"=>"Y"
);
$publishStatus = CIBlockElement::GetList(
    array(),
    $arFilter,
    false,
    false,
    $arSelect
)->Fetch();


// Группа авторы рецензий
$reviewAuthorGroup = CGroup::GetList(
    "c_sort",
    'desc',
    array(
        'STRING_ID' => REVIEWS_AUTHORS_GROUP_CODE
    )
)->Fetch();

// Пользователи
$filter = [];
$rsUsers = CUser::GetList(
    ($by="personal_country"),
    ($order="desc"),
    array(
        'GROUPS_ID' => $reviewAuthorGroup['ID'],
        'UF_AUTHOR_STATUS' => $publishStatus['ID']
    ),
    array(
        "SELECT" => array(
            "UF_AUTHOR_STATUS",
        ),
        "FIELDS" => array(
            'ID',
            'LOGIN',
            'EMAIL',
        )
    )
);

$users = [];
while($user = $rsUsers->Fetch()) {
//    if (in_array($reviewAuthorGroup['ID'] , CUser::GetUserGroup($user['ID']))) {
        $users[$user['ID']] = $user;
//    }
}

// Рецензии
$arSelect = array(
    "ID",
    "NAME",
    "PROPERTY_AUTHOR",
    "PROPERTY_PRODUCT",
);
$arFilter = array(
    "IBLOCK_ID"=> \helpers\IblockHelper::getIdByCode(REVIEWS_IBLOCK_CODE),
    "PROPERTY_AUTHOR" => array_column($users, 'ID'),
    "PROPERTY_PRODUCT" => array_column($arResult['ITEMS'], 'ID'),
    "ACTIVE_DATE"=>"Y",
    "ACTIVE"=>"Y"
);
$result = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

$productReviews = [];
while($review = $result->Fetch())
{
    $productReviews[$review['PROPERTY_PRODUCT_VALUE']]['REVIEWS'][] = $review;
}


foreach ($arResult['ITEMS'] as $key => $arItem)
{
    $arItem['PRICES']['PRICE']['PRINT_VALUE'] = number_format((float)$arItem['PRICES']['PRICE']['PRINT_VALUE'], 0, '.', ' ');
    $arItem['PRICES']['PRICE']['PRINT_VALUE'] .= ' '.$arItem['PROPERTIES']['PRICECURRENCY']['VALUE_ENUM'];

    if (!empty($productReviews[$arItem['ID']])) {
        $arItem['REVIEWS'] = $productReviews[$arItem['ID']]['REVIEWS'];
    }

    $arResult['ITEMS'][$key] = $arItem;
}


$ex2Meta = $APPLICATION->GetProperty('ex2_meta');
$ex2Meta = str_replace('#count#', 123123, $ex2Meta);
$APPLICATION->SetDirProperty('ex2_meta', $ex2Meta);
var_dump($APPLICATION->GetProperty('ex2_meta'));
