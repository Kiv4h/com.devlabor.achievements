{if $achievements|count > 0}
<div class="contentBox">
	<h3 class="subHeadline"><a href="index.php?page=UserAchievementList&amp;userID={@$user->userID}{@SID_ARG_2ND}">{lang}wcf.user.profile.lastAchievements{/lang}</a> <span>({#$achievementsCount})</span></h3>

    {if $achievements|count > 0}
	<ul class="dataList">
		{foreach from=$achievements item=achievement}
			<li class="{cycle values='container-1,container-2'}">
				<div class="containerIcon">
					{if $achievement->icon}<img src="{icon}{@$achievement->icon}{/icon}" title="{lang}{@$achievement->languageCategory}.{$achievement->achievementName}{/lang}" />{/if}
				</div>
				<div class="containerContent">
					<h4><a href="index.php?page=Achievement&amp;achievementID={@$achievement->achievementID}{@SID_ARG_2ND}#user-{@$user->userID}">{lang}{@$achievement->languageCategory}.{$achievement->achievementName}{/lang}</a></h4>
					<p class="firstPost smallFont light">{lang}{@$achievement->languageCategory}.{$achievement->achievementName}.description{/lang}</p>
					<p class="firstPost smallFont light">{@$achievement->time|shorttime}</p>
				</div>
			</li>
		{/foreach}
	</ul>
    {else}
        <p class="light">{lang}wcf.user.achievement.list.noItems{/lang}</p>
    {/if}

	<div class="buttonBar">
		<div class="smallButtons">
			<ul>
				<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
				<li><a href="index.php?page=UserAchievementList&amp;userID={@$user->userID}{@SID_ARG_2ND}" title="{lang}wcf.user.profile.allAchievements{/lang}"><img src="{icon}achievementS.png{/icon}" alt="" /> <span>{lang}wcf.user.profile.allAchievements{/lang}</span></a></li>
			</ul>
		</div>
	</div>
</div>
{/if}