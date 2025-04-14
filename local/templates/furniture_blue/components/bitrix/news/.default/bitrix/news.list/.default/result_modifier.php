<?php

if ($arParams['SPECIAL_DATE'] === 'Y') {
    $arResult['DATE_FIRST_NEWS'] = $arResult['ITEMS'][0]['ACTIVE_FROM'];
    $this->__component->SetResultCachekeys(array('DATE_FIRST_NEWS'));
}
