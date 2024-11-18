<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

if (!empty($arResult['ITEMS'])){
    foreach ($arResult['ITEMS'] as &$item){
        $item['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
            $item['PREVIEW_PICTURE']['ID'],
            Array("width"=> $arParams['PREVIEW_IMAGE_WIDTH'], "height"=>$arParams['PREVIEW_IMAGE_HEIGHT']),
            BX_RESIZE_IMAGE_EXACT,
            true
        );
    }
    unset($item);
}
