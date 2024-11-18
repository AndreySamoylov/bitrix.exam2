<?

function AgentCheckDates()
{
    if(CModule::IncludeModule("iblock")) {
        $arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_TO");
        $arFilter = array("IBLOCK_ID" => IntVal(STOCK_IBLOCK_ID), "<=DATE_ACTIVE_TO" => ConvertTimeStamp(false, "FULL"));
        $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $items = [];
        while ($item = $res->Fetch()) {
            $items[] = $item;
        }

        if (is_array($items) && count($items) > 0) {
            $arEventFields = array(
                "COUNT" => count($items),
            );

            CEvent::Send("WARMING_USER", "s1", $arEventFields);

            CEventLog::Add(array(
                "SEVERITY" => "SECURITY",
                "AUDIT_TYPE_ID" => "MY_OWN_TYPE",
                "MODULE_ID" => "main",
                "DESCRIPTION" => "Акций с истекшей датой активности = " . count($items),
            ));
        }
    }

    return "AgentCheckDates();";
}
