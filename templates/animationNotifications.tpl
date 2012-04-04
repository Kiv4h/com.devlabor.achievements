{if $animNotifications|count}
	<script type="text/javascript">
		//<![CDATA[
		var ACHIEVEMENT_SYSTEM_AUDIO_NOTIFICATION = '{@ACHIEVEMENT_SYSTEM_AUDIO_NOTIFICATION}';
		//]]>
	</script>
	
	<div id="animationNotificationContainer">
		{foreach from=$animNotifications item=animNotification}	
			<div id="animationNotification-{$animNotification->notificationID}" class="animationNotification">
				{include file='achievementCard' achievement=$animNotification->additionalData.achievement}
			</div>
			<script type="text/javascript">
				//<![CDATA[
				var achievement = new Achievement({ duration: 3, element: 'animationNotification-{$animNotification->notificationID}' });
				achievement.Shine();
				//]]>
			</script>
		{/foreach}
	</div>
{/if}