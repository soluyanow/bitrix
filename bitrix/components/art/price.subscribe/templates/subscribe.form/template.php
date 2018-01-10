<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="showFormBtn" onclick='showForm("subscribeForm")'> Подписаться </div>

<div class="subscribeForm" id="subscribeForm" style="display:none;">
	<h2 class="subscribeHead"> Подписка на снижение цены </h2>

	<div class="subscribeFormInner">
		<form method="post" id="sendForm">
			<input type="text" id="emailInput" name="email" value="<?=$arParams['USER_EMAIL']?>" />
			<input type="text" id="usernameInput" name="username" value="<?=$USER->GetLogin()?>"  style="display:none;" />
			<input type="text" id="ibidInput" name="ibid" value="<?=$arParams['IBLOCK_ID']?>"  style="display:none;" />
			<input type="text" id="productidInput" name="productid" value="<?=$arParams['PRODUCT_ID']?>"  style="display:none;" />
			<input type="text" id="productibidInput" name="productibid" value="<?=$arParams['PRODUCT_IBLOCK_ID']?>" style="display:none;" />												
			<input type="submit" id="sendSubscribe" value="OK"/>
		</form>	
	</div>
	
	<div id="formResult"> </div>
	
</div>

<script type="text/javascript" src='/bitrix/components/art/price.subscribe/templates/subscribe.form/script.js'> </script>


