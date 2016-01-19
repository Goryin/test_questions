<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
if($USER->IsAuthorized()) {
	if (defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER']))
	   $GLOBALS['CACHE_MANAGER']->ClearByTag('user_like_names');

	if(isset($_REQUEST["ITEM_ID"]) && !empty($_REQUEST["ITEM_ID"])) {
		CModule::IncludeModule("iblock");
		
		$itemId = htmlspecialcharsbx($_REQUEST["ITEM_ID"]);
		$itemId = intval($itemId);

		$userLikeRes = CIBlockElement::GetProperty(
			  NEWS_IBLOCK_ID,
			  $itemId,
			  array("value_id"=>"asc"),
			  array("ID"=>USER_LIKE_PROPERTY_ID)
			);
		while($userLike = $userLikeRes->GetNext()) {
			$arUserLikeId[$userLike["PROPERTY_VALUE_ID"]] = $userLike["VALUE"];
			$arUserLikeValueId[$userLike["VALUE"]] = $userLike["PROPERTY_VALUE_ID"];
		}

		if(is_array($arUserLikeId) && !in_array($USER->GetID(), $arUserLikeId)) {
			$arUserLikeId[$newUserLikeId] = $USER->GetID();
			$out["answer"] = "N";
		} else {
			unset($arUserLikeId[$arUserLikeValueId[$USER->GetID()]]);
			$out["answer"] = "Y";
		}

		CIBlockElement::SetPropertyValues($itemId, NEWS_IBLOCK_ID, $arUserLikeId, USER_LIKE_PROPERTY_ID);

		$out["userNames"] = array();
		if(count($arUserLikeId)) {
			$rsUsers = CUser::GetList(
				$by = "name",
				$order = "asc", 
				array('ID' => implode('|', $arUserLikeId)),
				array('ID','LOGIN')
			);
			while($arUser = $rsUsers->GetNext()) {
				$out["userNames"][] = $arUser["NAME"];
			}
		}

		echo json_encode($out);	
		
	}
}

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
