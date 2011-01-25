$.tools.validator.localize("en", {
  '[required]':'This form field is required.',
  '[min]':'Arvon on oltava suurempi, kuin $1',
});
$.tools.validator.fn("[date]", "Please enter a valid date and time. (i.e. 6/19/2010 at 10:06 am)", function(input) {
  var value = input.attr('date');
  switch(value) {
    case 'mm/dd/yyyy':
      return input.val().match(/\d{1,2}\/\d{1,2}\/\d{4} \d{1,2}:\d{2} (am|pm|AM|PM)/) != null;
      break;
  }
  return true;
});
$.tools.validator.fn("[check-name]", "This name is already in use.", function(input) {
  $.ajax({
            url: '/child/check',
            type: 'POST',
            cache: true,
            async: false,
            dataType: 'json',
            data: {value: input.val()},
            error: function() {
              ret = false;
            },
            success: function(response) {
              ret = response.message;
            }
          });
  return ret;
});
$.tools.validator.fn("[check-domain]", "Please enter only alphabets, numbers and -.", function(input) {
  return input.val().match(/^[a-zA-Z0-9-]+$/) != null;
});
$.tools.validator.fn("[data-equals]", "Does not match $1.", function(input) {
	var name = input.attr("data-equals"),
		 field = this.getInputs().filter("[name=" + name + "]"); 
	return input.val() == field.val() ? true : [name];
});
