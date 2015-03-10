// EMAIL LIST SIGN-UP FORM 
function validate_email_list_form(thisform){
  with (thisform){
	if(validate_required(user_name,"Please enter your full name!")==false){
	  user_name.focus();return false;
	}
    if(validate_email(user_email,"Please enter your valid email address!")==false){
	  user_email.focus();return false;
	}
  }
}

// CONTACT FORM
function validate_contact_form(thisform){
  with (thisform){
	if(validate_required(user_name,"Please enter your name!")==false){
	  user_name.focus();return false;
	}
	if(validate_required(user_subject,"Please enter a subject!")==false){
	  user_subject.focus();return false;
	}
	if(validate_email(user_email,"Please enter your valid email address!")==false){
	  user_email.focus();return false;
	}
    if(validate_required(user_message,"Please enter your message!")==false){
	  user_message.focus();return false;
	}
  }
}

// REVIEW FORM
function validate_review_form(thisform){
  with (thisform){
	if(validate_required(user_name,"Please enter your name!")==false){
	  user_name.focus();return false;
	}
	if(validate_email(user_email,"Please enter your valid email address!")==false){
	  user_email.focus();return false;
	}
	// RADIO BUTTONS
	radio_selected = -1;
	for (i=thisform.user_rating.length-1; i > -1; i--) {
		if (thisform.user_rating[i].checked) {
		radio_selected = i; i = -1;
		}
	}
	if (radio_selected == -1) {
	alert("Please select a star rating!");
	return false;
	}
    if(validate_required(user_review,"Please enter your review!")==false){
	  user_review.focus();return false;
	}
  }
}
// REVIEW FORM MOBILE
function validate_review_form_mobile(thisform){
  with (thisform){
	if(validate_required(user_name,"Please enter your name!")==false){
	  user_name.focus();return false;
	}
	if(validate_email(user_email,"Please enter your valid email address!")==false){
	  user_email.focus();return false;
	}
	if(validate_required(user_rating,"Please select a rating!")==false){
	  user_rating.focus();return false;
	}
    if(validate_required(user_review,"Please enter your review!")==false){
	  user_review.focus();return false;
	}
  }
}

// FAQ FORM
function validate_faq_form(thisform){
  with (thisform){
	if(validate_required(user_name,"Please enter your name!")==false){
	  user_name.focus();return false;
	}
	if(validate_email(user_email,"Please enter your valid email address!")==false){
	  user_email.focus();return false;
	}
    if(validate_required(user_question,"Please enter your question!")==false){
	  user_question.focus();return false;
	}
  }
}

// QUOTE FORM
function validate_quote_form(thisform){
  with (thisform){
	if(validate_required(dealer_company_name,"Please enter a company name!")==false){
	  dealer_company_name.focus();return false;
	}
	if(validate_required(contact_name,"Please enter a contact name!")==false){
	  contact_name.focus();return false;
	}
	if(validate_required(phone,"Please enter a business phone number!")==false){
	  phone.focus();return false;
	}
	if(validate_required(email,"Please enter a business email!")==false){
	  email.focus();return false;
	}
	if(validate_required(address,"Please enter a mailing address!")==false){
	  address.focus();return false;
	}
	if(validate_required(city,"Please enter a mailing city!")==false){
	  city.focus();return false;
	}
	if(validate_required(state_province,"Please enter a mailing state/province!")==false){
	  state_province.focus();return false;
	}
	if(validate_required(zip_postal,"Please enter a mailing zip/postal code!")==false){
	  zip_postal.focus();return false;
	}
	if(validate_required(years_in_business,"Please enter a number of years in business!")==false){
	  years_in_business.focus();return false;
	}
	if(validate_required(business_type,"Please select a business type!")==false){
	  business_type.focus();return false;
	}
	if(validate_required(invoice_method,"Please select a desired invoice method!")==false){
	  invoice_method.focus();return false;
	}
	if(validate_required(financial_name,"Please enter an institution name!")==false){
	  financial_name.focus();return false;
	}
	if(validate_required(financial_contact,"Please enter a contact name!")==false){
	  financial_contact.focus();return false;
	}
	if(validate_required(account_number,"Please enter an account number!")==false){
	  account_number.focus();return false;
	}
	if(validate_required(financial_phone,"Please enter a institution phone number!")==false){
	  financial_phone.focus();return false;
	}
	if(validate_required(financial_city,"Please enter an institution city!")==false){
	  financial_city.focus();return false;
	}
    if (thisform.accuracy_confirmation.checked == false ){
	    alert ( "Please ensure that you have confirmed the accuracy of all the information provided!" );
	    return false;
	} 
  }
}

// CAREER FORM
function validate_career_form(thisform){
  with (thisform){
	if(validate_required(user_name,"Please enter your name!")==false){
	  user_name.focus();return false;
	}
	if(validate_email(user_email,"Please enter your valid email address!")==false){
	  user_email.focus();return false;
	}
    if(validate_required(user_phone,"Please enter your phone number!")==false){
	  user_phone.focus();return false;
	}
	if(validate_required(image,"Please upload your resume!")==false){
	  image.focus();return false;
	}
	if(validate_required(user_message,"Please enter your cover letter/message!")==false){
	  user_message.focus();return false;
	}
  }
}

// PRODUCT SEARCH FORM 
function validate_search_form(thisform){
  with (thisform){
	if(validate_required(product_search,"Please enter a search!")==false){
	  product_search.focus();return false;
	}
  }
}
// PRODUCT SEARCH FORM 
function validate_location_form(thisform){
  with (thisform){
	if(validate_required(province,"Please select a Province/State!")==false){
	  province.focus();return false;
	}
  }
}

// VALADATION FUNCTIONS

// Required Field Validator - checks to make sure user did not leave a required field blank.
function validate_required(field,alerttxt){with (field){var text_check = value.replace(/ /g,"");if (text_check==null||text_check==""||text_check==" "){alert(alerttxt);return false;}else{return true;}}}

// Email validator - checks to make sure the user entered a valid email format
function validate_email(field,alerttxt){with (field){apos=value.indexOf("@");dotpos=value.lastIndexOf(".");if(apos<1||dotpos-apos<2){alert(alerttxt);return false;}else{return true;}}}

// Length validator - ensures that the exact length of a field was entered correctly (i.e. 3 digits for an area code)
function validate_length(field,alerttxt,maxsize){with (field){if(value.length!=maxsize){alert(alerttxt);return false;}else{return true;}}}

// Numbers only - prevents certain keys (i.e. letters) from working in a numbers only field
function IsNumeric(evt){var keyCode = evt.which ? evt.which : evt.keyCode;if (keyCode == 37 || keyCode == 39 || keyCode == 8 || keyCode == 9 || keyCode == 46 || (keyCode >= 48 && keyCode <= 57)){if (keyCode == 46){var sCurrVal = $get(evt.srcElement.id).value;if (sCurrVal.indexOf(".") > -1 ){return false;}}return true;}else{return false;}}

function IsFloat(evt){var keyCode = evt.which ? evt.which : evt.keyCode;if (keyCode == 190 || keyCode == 37 || keyCode == 39 || keyCode == 8 || keyCode == 9 || keyCode == 46 || (keyCode >= 48 && keyCode <= 57)){if (keyCode == 46){var sCurrVal = $get(evt.srcElement.id).value;if (sCurrVal.indexOf(".") > -1 ){return false;}}return true;}else{return false;}}

//Confirms Deleting 
function confirm_delete(){return confirm("Are you sure you want to delete?")}

//Confirms Approval 
function confirm_approve(){return confirm("Are you sure you want to approve?")}

// Birthdate Validator - checks for proper month/day relationships including leap years
function validate_birth(month,day,year,alerttxt){with(month){var month_is = month.value*1;}with(day){var day_is = day.value*1;}with(year){var year_is = year.value*1;}var max_days;if(month_is==1||month_is==3||month_is==5||month_is==7||month_is==8||month_is==10||month_is==12){max_days = 31;}else if(month_is==4||month_is==6||month_is==9||month_is==11){max_days = 30;}else{if((year_is%4==0&&year_is%100!=0)||year_is%400==0){max_days = 29;}else{max_days = 28;}}if(day_is > max_days){alert(alerttxt);return false;}else{return true;}} 

// Birthdate Validator - checks to make sure user entered something other than default values
function validate_dates(field,alerttxt){with (field){if (value=="MM"||value=="DD"||value=="YYYY"){alert(alerttxt);return false;}else{return true;}}}

function check_passwords(thisform){with(thisform){if(pass1.value.length < 6){ alert("The minimum password length is 6 characters.");pass1.focus();return false;}if(pass2.value.length < 6){ alert("The minimum password length is 6 characters.");pass2.focus();return false;}if(validate_required(pass1,"Please enter a valid password in field 1")==false){pass1.focus();return false;}if(validate_required(pass2,"Please enter a valid password in field 2!")==false){pass2.focus();return false;}if(pass1.value != pass2.value){alert("The passwords do not match");pass1.focus();return false;}}}

// Admin Adding & Editing - checks username and passwords when adding a new user in the control panel 
function admin_login(thisform){with(thisform){if(validate_required(adminname,"Please enter a valid name")==false){adminname.focus();return false;}if(validate_required(username,"Please enter a valid username")==false){username.focus();return false;}if(pass1.value.length < 6){ alert("The minimum password length is 6 characters.");pass1.focus();return false;}if(pass2.value.length < 6){ alert("The minimum password length is 6 characters.");pass2.focus();return false;}if(validate_required(pass1,"Please enter a valid password in field 1")==false){pass1.focus();return false;}if(validate_required(pass2,"Please enter a valid password in field 2!")==false){pass2.focus();return false;}if(pass1.value != pass2.value){alert("The passwords do not match");pass1.focus();return false;}}}

function admin_login_edit(thisform){with(thisform){if(validate_required(adminname,"Please enter a valid name")==false){adminname.focus();return false;}if(validate_required(username,"Please enter a valid username")==false){username.focus();return false;}if(pass1.value != pass2.value){alert("The passwords do not match");pass1.focus();return false;}}}

// Retail User Adding & Editing - checks username and passwords when adding a new user in the control panel 
function retail_user_add(thisform){with(thisform){if(validate_required(username,"Please enter a valid username")==false){username.focus();return false;}if(pass1.value.length < 6){ alert("The minimum password length is 6 characters.");pass1.focus();return false;}if(pass2.value.length < 6){ alert("The minimum password length is 6 characters.");pass2.focus();return false;}if(validate_required(pass1,"Please enter a valid password in field 1")==false){pass1.focus();return false;}if(validate_required(pass2,"Please enter a valid password in field 2!")==false){pass2.focus();return false;}if(pass1.value != pass2.value){alert("The passwords do not match");pass1.focus();return false;}}}

function retail_user_edit(thisform){with(thisform){if(validate_required(username,"Please enter a valid username")==false){username.focus();return false;}if(pass1.value != pass2.value){alert("The passwords do not match");pass1.focus();return false;}}}