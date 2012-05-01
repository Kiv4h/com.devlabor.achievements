<div class="border achievementCard">
	<div class="{if $achievement->isAchieved()}container-3 achieved{else}container-{cycle values='1,2'}{/if}">
		<div class="containerIcon">
			{if $achievement->icon}
				<div class="smallButtons">
					<ul>
						<li><a><img src="{@$achievement->getLargeIcon()}" title="{lang}{@$achievement->languageCategory}.{@$achievement->achievementName}{/lang}" alt="" /></a></li>
					</ul>
				</div>
			{/if}
		</div>
		<div class="containerContent">
			<h3 class="subHeadline{if !$achievement->isAchieved()} light{/if}">{if $achievement->isAchieved()}<a href="index.php?page=Achievement&amp;achievementID={@$achievement->achievementID}{@SID_ARG_2ND}"><strong>{lang}{@$achievement->languageCategory}.{@$achievement->achievementName}{/lang}</strong></a>{else}{lang}{@$achievement->languageCategory}.{@$achievement->achievementName}{/lang}{/if}{if $achievement->hasAchieved()}<span class="achievementCardOptions"><img src="{icon}successS.png{/icon}" alt="{lang}wcf.achievement.achieved.user{/lang}" title="{lang}wcf.achievement.achieved.user{/lang}" /></span>{/if}</h3>
			<p class="smallFont {if !$achievement->isAchieved()} light{/if}">{lang}{@$achievement->languageCategory}.{@$achievement->achievementName}.description{/lang}</p>

			{if !$achievement->hasAchieved() && $this->user->userID > 0}
				{if $achievement->objectQuantity > 1 && $achievement->getObject()->showProgress}
					<div class="progressBarContainer">
						<div class="progressBar" title="100%">
							<div style="width: {$achievement->getProgressWidth()}%;"></div>
						</div>
						<span class="smallFont">{#$achievement->getProgress()}/{#$achievement->objectQuantity}</span>
					</div>
				{/if}
			{else}
				{if $showAchiever|isset}
					{if $showAchiever}
						<p class="smallFont light">{lang userID=$achievement->user->userID username=$achievement->user->username time=$achievement->time count=$achievement->getReceivers()|count}wcf.achievement.achievedBy{/lang}</p>
					{/if}
				{/if}
			{/if}
			
			{if $achievement->getChilds()|count > 0}
			<div class="smallButtons" style="display:table;margin: 0 auto;">
				<ul class="metaList">
					{foreach from=$achievement->getChilds() item=child}
						<li><a{if $child->isAchieved()} class="achieved"{/if} href="index.php?page=Achievement&amp;achievementID={@$child->achievementID}{@SID_ARG_2ND}"><img src="{icon}{@$child->icon}{/icon}" title="{lang}{$child->languageCategory}.{$child->achievementName}{/lang}" alt="" /></a></li>
					{/foreach}
				</ul>
			</div>
			{/if}
			
			{if $achievement->getReward()}
				<div class="reward">
					<p class="smallFont"><strong>{lang}wcf.achievement.reward.title{/lang}</strong></p>
					<p class="smallFont">{lang}wcf.achievement.reward.type.{$achievement->getReward()->rewardType|strtolower}{/lang} &raquo;{lang}{$achievement->languageCategory}.reward.{$achievement->getReward()->rewardName|strtolower}{/lang}&laquo;</p>
				</div>
			{/if}
		</div>
		<div class="containerPoints">
			<div class="smallButtons">
				<ul>
					<li>
						<a{if $achievement->isAchieved()} title="{lang userID=$achievement->getFirstAchiever()->userID username=$achievement->getFirstAchiever()->username time=$achievement->getFirstTime() count=$achievement->getReceivers()|count}wcf.achievement.achievedBy.first.title{/lang}"{/if}>
							<span style="font-size: 1.9em; text-shadow: 0 2px 3px #BBBBBB; display:block; text-align:center;" {if !$achievement->isAchieved()} class="light"{/if}>{@$achievement->points}</span>
							{if $achievement->isAchieved()}<span class="smallFont light">{@"%d.%m.%Y"|strftime:$achievement->getFirstTime()}</span>{/if}
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>