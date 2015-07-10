function CompetencyInstructionsManager(){
	var self = this;
	self.check_member_timer = {};

	this.init = function() {
		self.bind_handlers();
	};

	this.bind_handlers = function() {
		$('.checkbox_use_customized_instructions').on('change',self.toggle_inputs_enables);
	};

	this.toggle_inputs_enables = function() {
		var form_dom = $(this).closest('form');
		if ($(this).is(':checked')){
			form_dom.find('.input_custom_instruction').val('');
			form_dom.find('.input_custom_instruction').attr('placeholder', '');
			form_dom.find('.input_custom_instruction').removeAttr('disabled');
			form_dom.find('.input_custom_instruction').first().focus();
		}else{
			form_dom.find('.input_custom_instruction').val('');
			form_dom.find('.input_custom_instruction').attr('disabled','disabled');
			form_dom.find('.input_custom_instruction').each( function() {
				var original_default_placeholder = $(this).attr('data-default-placeholder');
				$(this).attr('placeholder', original_default_placeholder);
			});			 
		}
	};

}

var _CIM_ = new CompetencyInstructionsManager();
$(document).ready(_CIM_.init);