{include file='header'}
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/achievementL.png" alt="" title="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.menu.link.user.achievements.award{/lang}</h2>
		<p>{lang}wcf.acp.menu.link.user.achievements.award.description{/lang}</p>
	</div>
</div>

{*
{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}
*}

<p class="info">{lang}wcf.acp.achievement.award.description{/lang}</p>

<form method="post" action="index.php?form=AchievementAward">
	<div class="border content">
		<div class="container-1">			
			{* \todo add radio buttons and user select
			
			\todo erfolgs selektion *}
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		<input type="hidden" name="action" value="award" />
 	</div>
</form>

{include file='footer'}