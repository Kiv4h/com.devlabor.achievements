{* --- special styles --- *}
{capture append="specialStyles"}
	<style type="text/css">
        table.achievementList td.columnTime, table.achievementList td.columnPoints { text-align:center; }
        table.achievementList td.columnIcon { width: 30px; }
        table.achievementList td.columnIcon img{ max-width: 24px; }
    </style>
{/capture}
{* --- end --- *}

{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.profile.title{/lang} - {lang}wcf.user.profile.members{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.user.profile.search.query{/lang}'}
{capture assign=searchHiddenFields}
	<input type="hidden" name="userID" value="{@$user->userID}" />
{/capture}
{* --- end --- *}
{include file='header' sandbox=false}

<div id="main">
	{include file="userProfileHeader"}
	
	<div class="border {if $this|method_exists:'getUserProfileMenu' && $this->getUserProfileMenu()->getMenuItems('')|count > 1}tabMenuContent{else}content{/if}">
		<div class="layout-2">
			<div class="columnContainer">	
				<div class="container-1 column first">
					<div class="columnInner">
						{if $additionalContent1|isset}{@$additionalContent1}{/if}
						
						<h3 class="subHeadline">{lang}wcf.user.profile.achievements{/lang} <span>({#$user->getEarnedAchievements()|count})</span></h3>
				
						<div class="contentHeader">
							{pages print=true assign=pagesOutput link="index.php?page=UserAchievementList&userID=$userID&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"|concat:SID_ARG_2ND_NOT_ENCODED}
						</div>
						
                        {if $achievements|count > 0}
						<div class="border">    
							<table class="tableList achievementList">
								<thead>
									<tr class="tableHead">
										<th colspan="2" class="columnName {if $sortField == 'achievementName'} active{/if}"><div><a href="index.php?page=UserAchievementList&amp;userID={@$userID}&amp;pageNo={@$pageNo}&amp;sortField=achievementName&amp;sortOrder={if $sortField == 'achievementName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">{lang}wcf.user.achievement.name{/lang}{if $sortField == 'achievementName'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></div></th>
										<th class="columnPoints {if $sortField == 'points'} active{/if}"><div><a href="index.php?page=UserAchievementList&amp;userID={@$userID}&amp;pageNo={@$pageNo}&amp;sortField=points&amp;sortOrder={if $sortField == 'points' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">{lang}wcf.user.achievement.points{/lang}{if $sortField == 'points'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></div></th>
										<th class="columnTime {if $sortField == 'time'} active{/if}"><div><a href="index.php?page=UserAchievementList&amp;userID={@$userID}&amp;pageNo={@$pageNo}&amp;sortField=time&amp;sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">{lang}wcf.user.achievement.time{/lang}{if $sortField == 'time'} <img src="{icon}sort{@$sortOrder}S.png{/icon}" alt="" />{/if}</a></div></th>
									</tr>
								</thead>
								<tbody>
									{foreach from=$achievements item=achievement}
										<tr class="container-{cycle values='1,2'}">
											<td class="columnIcon">{if $achievement->icon}<img src="{icon}{@$achievement->icon}{/icon}" title="{lang}{@$achievement->languageCategory}.{@$achievement->achievementName}{/lang}" alt=""/>{/if}</td>
											<td class="columnName"><a id="achievement-{@$achievement->achievementID}" href="index.php?page=Achievement&amp;achievementID={@$achievement->achievementID}{@SID_ARG_2ND}">{lang}{@$achievement->languageCategory}.{@$achievement->achievementName}{/lang}</a>
												<p class="smallFont light">{lang}{@$achievement->languageCategory}.{@$achievement->achievementName}.description{/lang}</p>
											</td>
											<td class="columnPoints">{#$achievement->points}</td>
											<td class="columnTime">{@$achievement->time|shorttime}</td>
										</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
                        {else}
                            <p class="light">{lang}wcf.user.achievement.list.noItems{/lang}</p>
                        {/if}
						<div class="buttonBar">
							<div class="smallButtons">
								<ul id="achievmentsSmallButtons">
									<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
									<li><a title="{lang}wcf.user.achievement.list.all{/lang}" href="index.php?page=AchievementList{@SID_ARG_2ND}"><img alt="" src="{icon}achievementS.png{/icon}" /> <span>{lang}wcf.user.achievement.list.all{/lang}</span></a></li>
									
									{if $additionalAlbumSmallButtons|isset}{@$additionalAlbumSmallButtons}{/if}
								</ul>
							</div>
						</div>
						
						{if $additionalContent2|isset}{@$additionalContent2}{/if}
						
						{if $additionalContent3|isset}{@$additionalContent3}{/if}
				</div>
				<div class="contentFooter">
					{@$pagesOutput}
				</div>
			</div>
			<div class="container-3 column second sidebar profileSidebar">
				<div class="columnInner">
					{if $additionalBoxes1|isset}{@$additionalBoxes1}{/if}
					
					<div class="contentBox">
						<div class="border">
							<div class="containerHead">
								<h3>{lang}wcf.user.profile.achievements{/lang}</h3>
							</div>
							
							<ul class="dataList">
								<li class="container-1">
									<div class="containerIcon">
										<img src="{icon}userRankM.png{/icon}" alt="" title="{lang}wcf.user.achievement.points{/lang}" />
									</div>
									<div class="containerContent">
										<h4 class="smallFont">{lang}wcf.user.achievement.points{/lang}</h4>
										<p>{#$user->achievementPoints}</p>
									</div>
								</li>
								<li class="container-2">
									<div class="containerIcon">
										<img src="{icon}updateM.png{/icon}" alt="" title="{lang}wcf.user.achievement.progress{/lang}" />
									</div>
									<div class="containerContent">
										<h4 class="smallFont">{lang}wcf.user.achievement.progress{/lang} ({#$user->achievementPoints}/{$allAchievements->countPoints()})</h4>
										<div class="border" style="padding:1px; height: 12px; width: 50%;">
											<div style="width: {$progressBarWidth}%; border-radius: 2px;" class="pollOptionBar pollBarColor1"></div>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
					{if $additionalBoxes2|isset}{@$additionalBoxes2}{/if}
					
					{if $additionalBoxes3|isset}{@$additionalBoxes3}{/if}
				</div>
			</div>
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