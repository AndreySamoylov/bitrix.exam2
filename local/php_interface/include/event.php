<?php

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("CIBLockHandler", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", Array("CIBLockHandler", "OnBeforeIBlockElementDeleteHandler"));
class CIBLockHandler
{
    // создаем обработчик события "OnBeforeIBlockElementUpdate"
    public static function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == NEWS_IBLOCK_ID) {
            if (!empty($arFields['ACTIVE']) && $arFields['ACTIVE'] != 'Y') {
                $res = CIBlockElement::GetByID($arFields['ID']);
                if ($ar_res = $res->GetNext()) {
                    $createDateTime = $ar_res['DATE_CREATE'];
                    $createTimeStamp = MakeTimeStamp($createDateTime, "DD.MM.YYYY HH:MI:SS");
                    $currentDateTime = ConvertTimeStamp(type: "FULL");
                    $currentTimeStamp = MakeTimeStamp($currentDateTime, "DD.MM.YYYY HH:MI:SS");
                    $diffTimeStamp = $currentTimeStamp - $createTimeStamp;
                    echo '<pre>';
                    if ($diffTimeStamp > 0 && $diffTimeStamp / ONE_DAY <= 3){
                        global $APPLICATION;
                        $APPLICATION->throwException("Вы пытаетесь деактивировать свежую новость!");
                        return false;
                    }
                }
            }
        }
    }

    public static function OnBeforeIBlockElementDeleteHandler($ID) {
        $res = CIBlockElement::GetByID($ID);
        if($ar_res = $res->GetNext()){
            if ($ar_res['IBLOCK_ID'] == PRODUCTS_IBLOCK_ID  && !empty($ar_res['SHOW_COUNTER']) && $ar_res['SHOW_COUNTER'] > 0) {
                global $APPLICATION;
                $APPLICATION->throwException("Нельзя удалять товраы, у которых есть просмотры. Просмотров: " . $ar_res['SHOW_COUNTER']);
                return false;
            }
        }
    }
}
AddEventHandler("main", "OnBeforeUserUpdate", Array("CMainHandler", "OnBeforeUserUpdateHandler"));

class CMainHandler {
    static public function OnBeforeUserUpdateHandler($arFields)
    {

        // Текущие группы пользователя
        $currentUserGroups = CUser::GetUserGroup($arFields['ID']);
        // Обновленные группы пользователя
        $updatedUserGroups = array_column($arFields['GROUP_ID'], 'GROUP_ID');

        // Если пользователя добавили в группу контект редакторы
        if (!in_array(CONTENT_EDITOR_GROUP_ID, $currentUserGroups) && in_array(CONTENT_EDITOR_GROUP_ID, $updatedUserGroups)){
            AddMessage2Log("Пользователя {$arFields['NAME']} добавили в группу контект-редакторы");
        }
    }
}

?>