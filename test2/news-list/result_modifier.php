<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!=true) die();

if (defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER']))
{
   $cp = $this->__component;
   if (strlen($cp->getCachePath()))
   {      
      $GLOBALS['CACHE_MANAGER']->RegisterTag('user_like_names');
   }
}

foreach($arResult["ITEMS"] as $key=>$arItem) {
	if(is_array($arItem["PROPERTIES"]["USER_LIKE"]["VALUE"])) {
		$rsUsers = CUser::GetList(
				$by = "name",
				$order = "asc", 
				array('ID' => implode('|', $arItem["PROPERTIES"]["USER_LIKE"]["VALUE"])),
				array('ID','LOGIN')
			);
		$arUserLike = array();
		while($arUser = $rsUsers->GetNext()) {
			$arUserLike[] = $arUser["NAME"];
		}
		if(is_array($arUserLike) && count($arUserLike)>0) {
			$arResult["ITEMS"][$key]["USER_LIKE_NAMES"] = $arUserLike;
		}
	}	

}
?>

