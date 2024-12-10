<?php
use Bitrix\Main\Loader;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
$module_id = "ciat.company";

Loader::includeModule($module_id);

$MOD_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($MOD_RIGHT < "W") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

if($_SERVER["REQUEST_METHOD"] == "POST" && check_bitrix_sessid())
{
    COption::SetOptionString($module_id, "API_URL", $_POST["API_URL"]);
    COption::SetOptionString($module_id, "API_KEY", $_POST["API_KEY"]);
    COption::SetOptionString($module_id, "SYNC_INTERVAL", $_POST["SYNC_INTERVAL"]);
}

$API_URL = COption::GetOptionString($module_id, "API_URL");
$API_KEY = COption::GetOptionString($module_id, "API_KEY");
$SYNC_INTERVAL = COption::GetOptionString($module_id, "SYNC_INTERVAL");

$APPLICATION->SetTitle(GetMessage("CIAT_COMPANY_OPTIONS_TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<form method="post" action="<?=$APPLICATION->GetCurPage()?>?lang=<?=LANGUAGE_ID?>&mid=<?=urlencode($module_id)?>">
    <?=bitrix_sessid_post()?>
    <table class="adm-detail-content-table edit-table">
        <tr>
            <td width="40%"><label for="API_URL"><?=GetMessage("CIAT_COMPANY_API_URL")?>:</label></td>
            <td width="60%"><input type="text" name="API_URL" value="<?=htmlspecialcharsbx($API_URL)?>" size="50"></td>
        </tr>
        <tr>
            <td><label for="API_KEY"><?=GetMessage("CIAT_COMPANY_API_KEY")?>:</label></td>
            <td><input type="text" name="API_KEY" value="<?=htmlspecialcharsbx($API_KEY)?>" size="50"></td>
        </tr>
        <tr>
            <td><label for="SYNC_INTERVAL"><?=GetMessage("CIAT_COMPANY_SYNC_INTERVAL")?> (часов):</label></td>
            <td><input type="text" name="SYNC_INTERVAL" value="<?=htmlspecialcharsbx($SYNC_INTERVAL)?>" size="5"></td>
        </tr>
    </table>
    <input type="submit" name="save" value="<?=GetMessage("MAIN_SAVE")?>">
</form>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
