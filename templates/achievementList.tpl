{include file="documentHeader"}
<head>
	<title>{lang}wcf.achievement.list.all{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{assign var='allowSpidersToIndexThisPage' value=true}

	{include file='headInclude' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{include file='header' sandbox=false}

<div id="main">
    <div class="mainHeadline">
        <img src="{icon}achievementL.png{/icon}" alt="" title="{lang}wcf.achievement.list.all{/lang}" />
		<div class="headlineContainer">
			<h2>{lang}wcf.achievement.list.all{/lang}</h2>
			<p>{lang}wcf.achievement.list.all.description{/lang}</p>
		</div>
	</div>

	{if $userMessages|isset}{@$userMessages}{/if}

	{if $additionalTopContents|isset}{@$additionalTopContents}{/if}

	<div id="achievementMenu" class="tabMenu">
		<ul>
			<li{if !$categoryID} class="activeTabMenu"{/if}><a href="index.php?page=AchievementList{@SID_ARG_2ND}">{lang}wcf.achievement.list.all{/lang}</a></li>
			{if $achievementMenu->getMenuItems('')|count > 0}
				{foreach from=$achievementMenu->getMenuItems('') item=item}
					<li{if $item.menuItem|in_array:$achievementMenu->getActiveMenuItems()} class="activeTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" title="{lang}{@$item.languageCategory}.category.{@$item.menuItem}{/lang}"/> {/if}<span>{lang}{@$item.languageCategory}.category.{@$item.menuItem}{/lang}</span></a></li>
				{/foreach}
			{/if}				
		</ul>
	</div>	
	
	{if $achievementMenu->getMenuItems('')|count > 0}
		<div class="subTabMenu">
			<div class="containerHead">
				{assign var=activeMenuItem value=$achievementMenu->getActiveMenuItem()}
				{if $activeMenuItem && $achievementMenu->getMenuItems($activeMenuItem)|count}
					<ul>
						{foreach from=$achievementMenu->getMenuItems($activeMenuItem) item=item}
							<li{if $item.menuItem|in_array:$achievementMenu->getActiveMenuItems()} class="activeSubTabMenu"{/if}><a href="{@$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" title="{lang}{@$item.languageCategory}.category.{@$item.menuItem}{/lang}" /> {/if}<span>{lang}{@$item.languageCategory}.category.{@$item.menuItem}{/lang}</span></a></li>
						{/foreach}
					</ul>
				{else}
					<div> </div>
				{/if}
			</div>
		</div>
	{/if}
	
							
    <div class="border">
		<div class="layout-3">
			<div class="columnContainer">
				<div class="column first container-3">
					<div class="columnInner">
					{if $categoryInformation|count > 0}
						<div class="contentBox">
							<h3 class="subHeadline">{lang}wcf.achievement.stats{/lang}</h3>
								
							{foreach from=$categoryInformation item=info}
								<div class="formElement">
									<div class="formFieldLabel">
										<label>{@$info.title}</label>
									</div>
									<div class="formField">
										<p>{@$info.value}</p>
									</div>
								</div>
							{/foreach}
						</div>
					{/if}
					</div>
				</div>
			
			
				<div class="column second container-1">
					<div class="columnInner">
						{if !$categoryID}
							<div class="contentBox">
								<h3 class="subHeadline">{lang}wcf.achievement.list.latest{/lang} <span class="smallFont">({$latestAchievements|count})</span></h3>						
								{foreach from=$latestAchievements item=achievement}
									{assign var="showAchiever" value=true}
									{include file='achievementCard' sandbox=false}
								{foreachelse}
									<p class="light">{lang}wcf.achievement.list.noItems{/lang}</p>
								{/foreach}
							</div>
							
							<div class="contentBox">
								<h3 class="subHeadline">{if MODULE_MEMBERS_LIST}<a href="index.php?page=MembersList&amp;sortField=achievementPoints&amp;sortOrder=DESC{@SID_ARG_2ND}">{lang}wcf.achievement.list.ranking{/lang}</a>{else}{lang}wcf.achievement.list.ranking{/lang}{/if}</h3>						
								
								{if $ranking|count > 0}
									<div class="border">
										<table class="tableList">
											<thead>
												<tr class="tableHead">
													<th class="columnUsername" colspan="2"><div><a>{lang}wcf.achievement.list.ranking.username{/lang}</a></div></th>
													<th class="columnAchievementPoints"><div><a>{lang}wcf.achievement.list.ranking.points{/lang}</a></div></th>
												</tr>
											</thead>
											<tbody>
												{foreach from=$ranking item=rank}
												<tr class="container-{cycle values='1,2'}">
													<td class="columnRanking" style="width:20px;">{counter}</td>
													<td class="columnUsername"><a href="index.php?page=UserAchievementList&amp;userID={$rank->userID}{@SID_ARG_2ND}">{@$rank->username}</a></td>
													<td class="columnAchievementPoints">{#$rank->achievementPoints}</td>
												</tr>
												{/foreach}
											</tbody>
										</table>
									</div>
								{else}
									<p class="light">{lang}wcf.achievement.list.noItems{/lang}</p>
								{/if}								
							</div>
						{else}
							<h3 class="subHeadline">{lang}{@$activeCategory.languageCategory}.category.{@$activeCategory.categoryName}{/lang} <span class="smallFont">({$achievements|count})</span></h3>
							<p class="description">{lang}{@$activeCategory.languageCategory}.category.{@$activeCategory.categoryName}.description{/lang}</p>
							
							{foreach from=$achievements item=achievement}
								{include file='achievementCard' sandbox=false}
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
		</div>
    </div>
	
	<div class="border infoBox">
		<div class="container-1">
			<div class="containerIcon"><img src="{icon}pollM.png{/icon}" alt="" /></div>
			<div class="containerContent">
				<h3>{lang}wcf.achievement.list.stats{/lang}</h3> 
				<p class="smallFont">{lang}wcf.achievement.list.stats.detail{/lang}</p>
			</div>
		</div>
	</div>
	
	<div>
		<p class="pluginCopyright" style="font-size: 0.85em">{lang}wcf.achievement.copyright{/lang}</p>
	</div>
</div>	

{include file='footer' sandbox=false}

</body>
</html>