function CompetencyGradingManager(){
	var self = this;
	self.check_member_timer = {};

	this.init = function() {
		self.bind_handlers();
	};

	this.bind_handlers = function() {
		$('#enable_default_grading').on('change',self.toggle_inputs_enables);
	};

	this.toggle_inputs_enables = function() {
		if ($('#enable_default_grading').is(':checked')){
			$('.input_custom_grade').val('');
			$('.input_custom_grade').attr('disabled','disabled');
		}else{
			$('.input_custom_grade').removeAttr('disabled');
		}
	};

	this.set_name_from_link = function(e) {
		e.preventDefault();
		var name = $('#button_to_set_this_name').text();
		$('#new_member_name').val(name);
		self.hide_autocomplete_values();
	};

	this.check_member_exists = function() {
		self.show_checking_name_text();
		clearTimeout(self.check_member_timer);
		if ($('#new_member_email').val().length==0){
			self.hide_all_checking_messages();
			return false;
		}
    self.check_member_timer = setTimeout(function() {
  		var ws = {
	  		type:'POST',
	  		dataType:'json',
	  		data:{email: $('#new_member_email').val()},
	  		url: base_url + 'user/check_member_email',
	  		complete: self.check_member_email_ok
	  	}

	  	$.ajax(ws);
    }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s
	};

	this.show_checking_name_text = function() {
		$('#checking_email_member').removeClass('not-global');
	};

	this.hide_all_checking_messages = function() {
		$('#checking_email_member').addClass('not-global');
	};

	this.check_member_email_ok = function(data) {
		self.hide_all_checking_messages();
		var response = data['responseText'];
    try{
    	response = $.parseJSON( response );
    }catch(e){
    	console.log(response);
    }
    if (response['result']=='ok'){
	    if (response['exist']=='yes'){
	    	self.show_autocomplete_values(response['user_data']);
	    }else{
	    	self.hide_autocomplete_values();
	    }
    }else{
    	console.log(response);
    }
	};

	this.show_autocomplete_values = function(user_data) {
		$('#button_to_set_this_name').text(user_data['name']);
		$('#checking_email_member_match_found').removeClass('not-global');
	};

	this.hide_autocomplete_values = function() {
		$('#checking_email_member_match_found').addClass('not-global');
	};
}

var _CGM_ = new CompetencyGradingManager();
$(document).ready(_CGM_.init);