{include file="documentHeader"}
<head>
	<title>{lang}wcf.achievement.detail{/lang} - {lang}{@$achievement->languageCategory}.{$achievement->achievementName}{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
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
		{if $achievement->icon}
			<img src="{@$achievement->getLargeIcon()}" title="{$achievement->getTitle()}" alt="" />
		{else}
			<img src="{icon}achievement{if $achievement->isAchieved()}Earned{/if}L.png{/icon}" alt="" title="{lang}wcf.achievement.{$achievement->achievementName}{/lang}" />
		{/if}
		<div class="headlineContainer">
			<h2>{$achievement->getTitle()}</h2>
			<p>{$achievement->getDescription()}</p>
		</div>
	</div>

	{if $userMessages|isset}{@$userMessages}{/if}

	{if $additionalTopContents|isset}{@$additionalTopContents}{/if}

    <div class="border content">
		<div class="layout-2">
			<div class="columnContainer">	
				<div class="container-1 column first">
					<div class="columnInner">
						{if $receivers|count > 0}
							<p>{lang userID=$achievement->getFirstAchiever()->userID username=$achievement->getFirstAchiever()->username time=$achievement->getFirstTime() count=$receivers|count}wcf.achievement.achievedBy.first{/lang}</p>
						{/if}		
						
						<div class="contentBox">
							<h3 class="subHeadline">{lang}wcf.achievement.receivedBy{/lang} ({#$receivers|count})</h3>
							{if $receivers|count > 0}
								<p>{implode from=$receivers item=user}<a href="index.php?page=UserAchievementList&amp;userID={@$user.userID}{@SID_ARG_2ND}#achievement-{$achievement->achievementID}">{if $user.userID|in_array:$buddies}<span class="buddy">{@$user.username}</span>{else}{@$user.username}{/if}</a> <span class="light smallFont">({@$user.time|shorttime})</span>{/implode}</p>
							{else}
								<p>{lang}wcf.achievement.list.noItems{/lang}</p>
							{/if}
							<div class="buttonBar">
								<div class="smallButtons">
									<ul>
										<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
									</ul>
								</div>
							</div>							
						</div>
					</div>
				</div>
				<div class="container-3 column second sidebar profileSidebar">
					<div class="columnInner">
						{if $additionalBoxes1|isset}{@$additionalBoxes1}{/if}
						
						<div class="contentBox">
							<div class="border">
								<div class="containerHead">
									<h3>{lang}wcf.achievement.information{/lang}</h3>
								</div>
								
								<ul class="dataList">
									<li class="{cycle values='container-1,container-2'}">
                                        <div class="containerIcon"><img src="{icon}userRankM.png{/icon}" title="{lang}wcf.achievement.points{/lang}" alt="" /></div>
                                        <div class="containerContent">
                                            <h4 class="smallFont">{lang}wcf.achievement.points{/lang}</h4>
                                            <p>{$achievement->points}</p>
                                        </div>
                                    </li>	
									{if $achievement->getRelatedAchievements()|count > 0}
										<li class="{cycle values='container-1,container-2'}">
											<div class="containerIcon"><img src="{icon}relatedAchievementM.png{/icon}" title="{lang}wcf.achievement.level{/lang}" alt="" /></div>
											<div class="containerContent">
												<h4 class="smallFont">{lang}wcf.achievement.level{/lang}</h4>
												<p>{#$achievement->getLevel()}/{#$maxLevels}</p>
											</div>
										</li>	
									{/if}									
									{if ACHIEVEMENT_SYSTEM_ENABLE_ACTIVITY_POINTS && $achievement->activityPoints > 0}
										<li class="{cycle values='container-1,container-2'}">
											<div class="containerIcon"></div>
											<div class="containerContent">
												<h4 class="smallFont">{lang}wcf.achievement.activityPoints{/lang}</h4>
												<p>{$achievement->activityPoints}</p>
											</div>
										</li>
									{/if}									
								</ul>
							</div>
						</div>
						
						{if $additionalBoxes2|isset}{@$additionalBoxes2}{/if}
						
						{if $achievement->getReward()}
							<div class="contentBox">
								<div class="border">
									<div class="containerHead">
										<h3>{lang}wcf.achievement.reward.title{/lang}</h3>
									</div>
									
									<ul class="dataList">
										<li class="{cycle values='container-1,container-2'}">
											<div class="containerIcon">
												<img src="{icon}achievementRewardM.png{/icon}" alt="" title="{lang}wcf.achievement.publication.bbcode{/lang}" />
											</div>
											<div class="containerContent">
												<h4 class="smallFont">{lang}wcf.achievement.reward.type.{$achievement->getReward()->rewardType|strtolower}{/lang}</h4>
												<p>{lang}{$achievement->languageCategory}.reward.{$achievement->getReward()->getName()}{/lang}</p>
											</div>
										</li>								
									</ul>
								</div>
							</div>
						{/if}
						
						<div class="contentBox">
							<div class="border">
								<div class="containerHead">
									<h3>{lang}wcf.achievement.publication{/lang}</h3>
								</div>
								
								<ul class="dataList">
									<li class="{cycle values='container-1,container-2'}">
                                        <div class="containerIcon">
                                            <img src="{icon}wysiwyg/insertCodeM.png{/icon}" onclick="document.getElementById('entryBBCode').select()" alt="" title="{lang}wcf.achievement.publication.bbcode{/lang}" />
                                        </div>
                                        <div class="containerContent">
                                            <h4 class="smallFont">{lang}wcf.achievement.publication.bbcode{/lang}</h4>
                                            <p><input type="text" class="inputText" onclick="document.getElementById('entryBBCode').select()" id="entryBBCode" value="[achievement]{$achievement->achievementID}[/achievement]" /></p>
                                        </div>
                                    </li>								
									<li class="{cycle values='container-1,container-2'}">
                                        <div class="containerIcon">
                                            <img src="{icon}wysiwyg/linkInsertM.png{/icon}" onclick="document.getElementById('entryLink').select()" alt="" title="{lang}wcf.achievement.publication.link{/lang}" />
                                        </div>
                                        <div class="containerContent">
                                            <h4 class="smallFont">{lang}wcf.achievement.publication.link{/lang}</h4>
                                            <p><input type="text" class="inputText" onclick="document.getElementById('entryLink').select()" id="entryLink" value="{PAGE_URL}/index.php?page=Achievement&amp;achievementID={$achievement->achievementID}" /></p>
                                        </div>
                                    </li>
								</ul>
							</div>
						</div>
						
						{if $additionalBoxes3|isset}{@$additionalBoxes3}{/if}
					</div>
				</div>
			</div>
		</div>
    </div>
	{if (MODULE_SOCIAL_BOOKMARK && $socialBookmarks|isset) || ($achievement->getRelatedAchievements()|count > 0)}
		<div class="border infoBox">
			{if $achievement->getRelatedAchievements()|count > 0}
				<div class="container-{cycle values="1,2"}">
					<div class="containerIcon">
						<img alt="" src="{icon}relatedAchievementM.png{/icon}">
					</div>
					<div class="containerContent">
						<h3>{lang}wcf.achievement.related{/lang}</h3>
						<ul class="relatedAchievements">
							{foreach from=$achievement->getRelatedAchievements() item=related}
								<li>
									<p>{if $related.icon}<img src="{icon}{@$related.icon}{/icon}" title="{lang}{$related.languageCategory}.{@$related.achievementName}{/lang}" />{/if} <a href="index.php?page=Achievement&amp;achievementID={@$related.achievementID}{@SID_ARG_2ND}" title="{lang}{$related.languageCategory}.{$related.achievementName}.description{/lang}">{lang}{$related.languageCategory}.{$related.achievementName}{/lang}</a> <span class="light">({$related.points} {lang}wcf.achievement.points{/lang})</span></p>
								</li>
							{/foreach}
						</ul>
					</div>
				</div>
			{/if}
			
			{if MODULE_SOCIAL_BOOKMARK && $socialBookmarks|isset}
			<div class="container-{cycle values="1,2"}">
				<div class="containerIcon">
					<img src="{icon}socialBookmarkProviderM.png{/icon}" alt="" title="" />
				</div>
				<div class="containerContent">
					<h3>{lang}wcf.achievement.social.bookmarks{/lang}</h3>
					{@$socialBookmarks}
				</div>
				<div style="clear: both;"></div>
			</div>
			{/if}
		</div>
	{/if}
	
	<div>
		<p class="pluginCopyright" style="font-size: 0.85em">{lang}wcf.achievement.copyright{/lang}</p>
	</div>
</div>	
{include file='footer' sandbox=false}

</body>
</html>