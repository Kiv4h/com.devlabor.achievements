/**
 * @author		Jeffrey 'Kiv' Reichardt
 * @copyright		2011-2012 devlabor.com
 * @package     		com.devlabor.achievements
 * @license		CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/legalcode>
 * @subpackage	js
 */

var isPlayed = false;
var sendAjaxRequest = true;
var visibleContainers = new Array();
 
Effect.Shine = function(element, object) {
	element = $(element);
	var height = $(element).getHeight();
	var width = $(element).getWidth();
	var shineLayer = Builder.node('div', { className: 'shineLayer', style: 'width:'+(width/5)+'px; height:'+(height+50)+'px; left: '+(width*-1)+'px; top: -40px;' });
	element.appendChild(shineLayer);

	/* \note y:0 sets top to value 0, but we need -40px. negative numbers are not allowed.
	return new Effect.Move(shineLayer, { 
							x: (width*1.2),
							y: 0,
							duration: 2, 
							mode: 'absolute',
							object: object,
							afterFinish: function(){ this.object.fadeEffect = new Effect.Fade(element, {duration: 4.0 }) }
						});*/
	return new Effect.Morph(shineLayer, { 
							style: 'left: '+(width*1.4)+'px;',
							duration: 2, 
							object: object,
							afterFinish: function(){ this.object.fadeEffect = new Effect.Fade(element, {duration: 3.0, afterFinish: function () { removeContainer() } }) }
						});					
						
};

// this must be global, because afterFinish handles scope wrong
function removeContainer() {
	for (i=visibleContainers.length;i>=0;i--) {
		var element = document.getElementById(visibleContainers[i]);
		
		if(!element) continue;	
		if(element.style.display == 'none')
			visibleContainers.splice(i, 1);
	}
	
	if(!visibleContainers.length) {
		var notificationContainer = document.getElementById("animationNotificationContainer");
		var mainContainer = document.getElementById("mainContainer")
		if(!notificationContainer || !mainContainer) return;
		mainContainer.removeChild(notificationContainer);
	}
}

var Achievement = Class.create({
	/**
	 * Inits Achievement.
	 */
	initialize : function() {
		this.fadeEffect = null,
		this.options = Object.extend({
			notificationID	: 0,
			duration		: 3,
			element			: null,
			useAjax			: true
		}, arguments[0] || { });
		
		if($(this.options.element)){
			visibleContainers.push($(this.options.element).readAttribute('id'));
			
			$(this.options.element).observe('mouseover', this.resetOpacity.bind(this));
			$(this.options.element).observe('mouseout', this.fadeOut.bind(this));
		}
	},
	
	resetOpacity: function(){
		if(this.fadeEffect)
			this.fadeEffect.cancel();
			
		Effect.Fade($(this.options.element), { from: 1, to: 1, duration: 0 });
	},
	
	fadeOut: function(){
		this.fadeEffect = new Effect.Fade($(this.options.element), { duration: 3.0, afterFinish: function(){ removeContainer() } });		
	},

	Shine: function(){
		this.fadeEffect = Effect.Shine($(this.options.element), this);
		if(!isPlayed && (ACHIEVEMENT_SYSTEM_AUDIO_NOTIFICATION == 1)) this.playSound();
			
		//send only first time	
		if(sendAjaxRequest){	
			this.ajaxRequest = new AjaxRequest();
			this.ajaxRequest.openPost('index.php?action=NotificationConfirm'+SID_ARG_2ND, 'ajax=1&t='+encodeURIComponent(SECURITY_TOKEN));
			
			sendAjaxRequest = false;
		}
	},
	
	playSound: function(){
		isPlayed = true;
		
		var audioElement = Builder.node('audio', { id: 'achievementAudio', autoplay: 'autoplay', volume: 0.5 });
		var sourceElement = Builder.node('source', { src: RELATIVE_WCF_DIR + 'audio/earnAchievement.ogg', type: 'audio/ogg' });
		audioElement.appendChild(sourceElement);
		audioElement.play();
	}
});