<?php

use Bitrix\Main\Loader;
use	Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @var array $arParams */
/** @var array $arResult */

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_97_IBLOCK_MODULE_NONE"));
	return;
}

# START Обработка входных параметров
if (empty($arParams["IBLOCK_ID"]) || !is_numeric($arParams["IBLOCK_ID"])) {
    ShowError(Loc::getMessage("SIMPLECOMP_EXAM2_97_UNFILLED_PARAMETER", array("#PARAMETER_NAME#" => 'IBLOCK_ID')));
    return;
} else {
    $arParams["IBLOCK_ID"] = (int) $arParams["IBLOCK_ID"];
}

if (empty($arParams["AUTHOR_PROPERTY_CODE"])) {
    ShowError(Loc::getMessage("SIMPLECOMP_EXAM2_97_UNFILLED_PARAMETER", array("#PARAMETER_NAME#" => 'AUTHOR_PROPERTY_CODE')));
    return;
}

if (empty($arParams["AUTHOR_TYPE_CODE"])) {
    ShowError(Loc::getMessage("SIMPLECOMP_EXAM2_97_UNFILLED_PARAMETER", array("#PARAMETER_NAME#" => 'AUTHOR_TYPE_CODE')));
    return;
}

if (empty($arParams['CACHE_TYPE'])) {
    $arParams['CACHE_TYPE'] = 'A';
}

if (empty($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TYPE'] = '36000000';
}

if (empty($arParams['CACHE_GROUPS'])) {
    $arParams['CACHE_GROUPS'] = 'Y';
}
# END Обработка входных параметров

// Проверка авторизирован ли пользователь
if (!$USER->IsAuthorized()) {
    ShowError(Loc::getMessage("SIMPLECOMP_EXAM2_97_UNAUTHORIZED"));
    return;
}

$currentUserID = $USER->GetID();
$currentAuthorType = null;
if ($user = CUser::GetByID($currentUserID)->Fetch()) {
    if (!empty($user[$arParams['AUTHOR_TYPE_CODE']])) {
        $currentAuthorType = $user[$arParams['AUTHOR_TYPE_CODE']];
    }
}


if ($this->startResultCache(false, array($currentUserID, $currentAuthorType)))
{
    $result = CUser::GetList(
        'login',
        'asc',
        array(
            $arParams["AUTHOR_TYPE_CODE"] => $currentAuthorType,
            "!ID" => $USER->GetID(),
        ),
    );

    $users = [];
    while ($user = $result->Fetch()) {
        $users[$user['ID']] = $user['ID'];
        $arResult['USERS'][$user['ID']] = array(
            'ID' => $user['ID'],
            'LOGIN' => $user['LOGIN'],
            'NEWS' => [],
        );
    }

    if (empty($users)) {
        return;
    }


    $result = CIBlockElement::GetList(
        array(),
        array(
            $arParams["AUTHOR_PROPERTY_CODE"] => $users
        ),
        false,
        false,
        array(
            '*',
            $arParams["AUTHOR_PROPERTY_CODE"]
        )
    );

    while ($new = $result->Fetch()) {
        $arResult['USERS'][$new['PROPERTY_AUTHOR_VALUE']]['NEWS'][$new['ID']] = array(
            'ID' => $new['ID'],
            'NAME' => $new['NAME'],
        );
    }

    $news = [];
    foreach ($arResult['USERS'] as $arUser) {
        if (is_array($arUser['NEWS']) && count($arUser['NEWS']) > 0) {
            foreach ($arUser['NEWS'] as $id => $new) {
                $news[$id] = $id;
            }
        }
    }
    $arResult['NEWS_COUNT'] = is_array($news) && count($news) > 0 ? count($news) : 0;

    $this->setResultCacheKeys(array(
        "NEWS_COUNT",
    ));
    $this->includeComponentTemplate();

} else {
    $this->abortResultCache();
}

if (!empty($arResult['NEWS_COUNT'])) {
    $APPLICATION->setTitle(Loc::getMessage('SIMPLECOMP_EXAM2_97_TITLE', array("#COUNT#" => $arResult['NEWS_COUNT'])));
}
