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
        } else if ($arFields['IBLOCK_ID'] == PRODUCTS_IBLOCK_ID && !empty($arFields['ACTIVE']) && $arFields['ACTIVE'] === 'N') {
            $product = CIBlockElement::GetByID($arFields['ID'])->Fetch();

            // Если количество просмотров товара больше 2
            if (!empty($product['SHOW_COUNTER']) && is_numeric($product['SHOW_COUNTER']) && $product['SHOW_COUNTER'] > MAX_SHOW_COUNTER) {
                global $APPLICATION;
                $APPLICATION->throwException(
                    getMessage('EVENTS_EXCEPTION_UPDATE_MAX_COUNTER',
                        array(
                            '#COUNT#' => $product['SHOW_COUNTER'],
                            '#MAX_COUNT#' => MAX_SHOW_COUNTER,
                        )
                    )
                );
                return false;
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
AddEventHandler("main", "OnEpilog", array('CMainHandler', 'OnEpilogHandler'));
AddEventHandler("main", "OnBeforeEventAdd", array("CMainHandler", "OnBeforeEventAddHandler"));
AddEventHandler("main", "OnBuildGlobalMenu", array("CMainHandler", "OnBuildGlobalMenuHandler"));

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

    static public function OnEpilogHandler()
    {
        if (defined('ERROR_404') && ERROR_404 === 'Y') {
            global $APPLICATION;
            // Сбрасываем буфер
            $APPLICATION->RestartBuffer();
            // Подключаем файл для отображения 404 страницы, если страница не найдена
            include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/header.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/404.php";
            include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/footer.php";
            // Логирование в журнал событий
            CEventLog::Add(
                array(
                    "SEVERITY" => "INFO",
                    "AUDIT_TYPE_ID" => "ERROR_404",
                    "MODULE_ID" => "main",
                    "DESCRIPTION" => $APPLICATION->GetCurPage(),
                )
            );
        }
    }

    static function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        if (!empty($event) && $event === 'FEEDBACK_FORM') {
            global $USER;
            if ($USER->IsAuthorized()) {
                $arFields['AUTHOR'] = getMessage(
                    'EVENTS_USER_AUTHORIZED',
                    array(
                        '#USER_ID#' => $USER->GetID(),
                        '#USER_LOGIN#' => $USER->GetLogin(),
                        '#USER_NAME#' => $USER->GetFullName(),
                        '#NAME#' => $arFields['AUTHOR'],
                    )
                );
            } else {
                $arFields['AUTHOR'] = getMessage(
                    'EVENTS_USER_UNAUTHORIZED',
                    array(
                        '#NAME#' => $arFields['AUTHOR'],
                    )
                );
            }

            CEventLog::Add(array(
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => "MY_OWN_TYPE",
                "MODULE_ID" => "main",
                "ITEM_ID" => $event,
                "DESCRIPTION" => getMessage(
                    'EVENTS_FEEDBACK_FORM_LOG',
                    array(
                        '#AUTHOR#' => $arFields['AUTHOR']
                    )
                ),
            ));
        }
    }

    static function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu) {
        $isAdmin = false;
        $isContentEditor = false;

        // Получить группы пользователя
        global $USER;
        $groups = $USER->GetUserGroupArray();
        $contentGroupID = CGroup::GetList(
            $by="c_sort",
            $order="desc",
            array(
                'STRING_ID' => CONTENT_EDITORS_GROUP_CODE
            )
        )->Fetch()['ID'];

        // Пользователь админ
        if (in_array(ADMIN_GROUP_ID, $groups)) {
            $isAdmin = true;
        }

        // Пользователь контент-редактор
        if (in_array($contentGroupID, $groups)) {
            $isContentEditor = true;
        }

        if (!$isAdmin && $isContentEditor) {
            foreach ($aModuleMenu as $key => $item) {
                if ($item['items_id'] === "menu_iblock_/news") {
                    $aModuleMenu = [$item];

                    break;
                }
            }
            $aGlobalMenu = ['global_menu_content' => $aGlobalMenu['global_menu_content']];
        }
    }
}

?>