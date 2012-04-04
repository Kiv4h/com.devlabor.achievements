{include file="documentHeader"}
<head>
	<title>{lang}wcf.achievement.plugin.information{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{assign var='allowSpidersToIndexThisPage' value=true}

	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
        <li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
        <li><a href="index.php?page=AchievementList{@SID_ARG_2ND}"><img src="{icon}achievementS.png{/icon}" alt="" /> <span>{lang}wcf.achievement.list{/lang}</span></a> &raquo;</li>
    </ul>

    <div class="mainHeadline">
		<img src="{icon}packageL.png{/icon}" title="{lang}wcf.achievement.plugin.information{/lang}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.achievement.plugin.information{/lang}</h2>
		</div>
	</div>

	{if $userMessages|isset}{@$userMessages}{/if}

	{if $additionalTopContents|isset}{@$additionalTopContents}{/if}

    <div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}{$package->packageName}{/lang}</legend>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.author{/lang}</label>
					</div>
					<div class="formField">
						<p>{@$package->author}</p>
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.website{/lang}</label>
					</div>
					<div class="formField">
						<p><a href="{@$package->authorURL}" title="{$package->authorURL}" class="externalURL">{$package->authorURL}</a></p>
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.version{/lang}</label>
					</div>
					<div class="formField">
						<p>{$package->packageVersion}</p>
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.license{/lang}</label>
					</div>
					<div class="formField">
						<p><a href="http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode">CC BY-NC-SA 3.0</a></p>
					</div>
				</div>
			</fieldset>
			
			<fieldset>
				<legend>{lang}wcf.achievement.plugin.icons{/lang}</legend>
				<p class="light small">Trophy-Icon</p>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.author{/lang}</label>
					</div>
					<div class="formField">
						<p>Icons-Land</p>
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.website{/lang}</label>
					</div>
					<div class="formField">
						<p><a href="http://www.icons-land.com/" title="http://www.icons-land.com/" class="externalURL">http://www.icons-land.com/</a></p>
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.license{/lang}</label>
					</div>
					<div class="formField">
						<p>Commercial usage: Not allowed</p>
					</div>
				</div>
				
				<p class="light small">Holiday-Icons</p>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.author{/lang}</label>
					</div>
					<div class="formField">
						<p>IconDrawer</p>
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.website{/lang}</label>
					</div>
					<div class="formField">
						<p><a href="http://icondrawer.com/free.php" title="http://icondrawer.com/free.php" class="externalURL">http://icondrawer.com/free.php</a></p>
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						<label>{lang}wcf.achievement.plugin.license{/lang}</label>
					</div>
					<div class="formField">
						<p>Free</p>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</div>

{include file='footer' sandbox=false}

</body>
</html>