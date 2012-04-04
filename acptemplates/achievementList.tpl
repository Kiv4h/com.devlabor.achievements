{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/achievementL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.menu.link.user.achievements.list{/lang}</h2>
		<p>{lang}wcf.acp.menu.link.user.achievements.list.description{/lang}</p>
	</div>
</div>

{if $objects|count}
	<div class="border content">
		<div class="container-1">
		{foreach from=$objects item=object}
			<ul id="objectList{$object.objectID}" class="itemList" style="list-style-type: none;">
				<li class="deletable" id="item_1">
					<div class="buttons">
						<a title="" href=""><img alt="" src="{@RELATIVE_WCF_DIR}icon/addS.png"></a>
						<a class="deleteButton" title="" href=""><img longdesc="Wollen Sie dieses Forum wirklich löschen?" alt="" src="{@RELATIVE_WCF_DIR}icon/deleteS.png"></a>
					</div>
					<h3 class="subHeadline">{lang}{$object.objectName}{/lang}</h3>
					
					{if $object.hasChild}
						<ol id="achievementList{$object.objectID}" class="itemList">
							{foreach from=$achievements item=achievement}
								{if $achievement.objectName == $object.objectName}
									<li class="deletable" id="item_1">
										<div class="buttons">
											<a href=""><img title="" alt="" src="{@RELATIVE_WCF_DIR}icon/editS.png"></a>
											<a title="" href="#"><img alt="" src="{@RELATIVE_WCF_DIR}icon/enabledS.png"></a>
											<a class="deleteButton" title="" href=""><img longdesc="" alt="" src="{@RELATIVE_WCF_DIR}icon/deleteS.png"></a>
										</div>
										<h3 class="itemListTitle"><a href="">{lang}{$achievement.languageCategory}.{$achievement.achievementName}{/lang}</a> ({#$achievement.objectQuantity})</h3>
									</li>
								{/if}
							{/foreach}
						</ol>
					{/if}
				</li>
			</ul>
		{/foreach}
		</div>
	</div>
{else}
	<div class="message content">
		<div class="messageInner container-1">
			<p>{lang}wcf.acp.achievement.list.noItems{/lang}</p>
		</div>
	</div>
{/if}

{include file='footer'}