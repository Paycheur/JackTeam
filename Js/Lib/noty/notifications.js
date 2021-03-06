$(document).ready(function() {
	$.noty.defaults = {
			  layout: 'topLeft',
			  theme: 'default',
			  text: '',
			  dismissQueue: true, // If you want to use queue feature set this true
			  template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
			  animation: {
			    open: {height: 'toggle'},
			    close: {height: 'toggle'},
			    easing: 'swing',
			    speed: 500 // opening & closing animation speed
			  },
			  timeout: false, // delay for closing event. Set false for sticky notifications
			  force: false, // adds notification to the beginning of queue when set to true
			  modal: false,
			  closeWith: ['click'], // ['click', 'button', 'hover']
			  callback: {
			    onShow: function() {},
			    afterShow: function() {},
			    onClose: function() {},
			    afterClose: function() {}
			  },
			  buttons: false // an array of buttons
			};
			   
	
	var erreur=$('#error').text();
	if(erreur!='')
	{
		$('#error').css('display', 'none');
		var n = noty({text: erreur});
		n.setType('error');
	}
	
	var confirmation=$('#confirmation').text();
	if(confirmation!='')
	{
		$('#confirmation').css('display', 'none');
		var n = noty({text: confirmation});
		n.setType('success');
	}
});