function validateForm(f)
{
	jQuery(f).find(':input').each(function(){
		var $elem = jQuery(this);
		if ($elem.is(":submit, :reset, :button, :image")) return true;
		if (($elem.is('.required') && jQuery.trim($elem.val()) == '') || ($elem.is('.email') && !isEmailValid($elem.val())) || ($elem.is('.numeric') && isNaN($elem.val())))
		{
			$elem.addClass('validate_error');
			isOK = false;
		}
		else
			$elem.removeClass('validate_error');
	});

	if (jQuery('.validate_error').length == 0) return true;

	jQuery(f).find('.validate_error:first').focus();
	return false;
}

function post(f)
{
	var $f = jQuery(f);
    makeRequest($f.attr('method'), $f.attr('action'), serializeForm(f), $f.attr("divResult"), $f.attr("divWorking"), f);
}

function serializeForm(f)
{
	var params = jQuery(f).serialize();
	params += "&nonce=" + getNonce();

	return params;
}

function getNonce()
{
	return jQuery("#rdetectorNonce").val();
}

function toggleForm(f, enabled)
{
	jQuery(f).find(':submit, :reset, :button, :image').attr('disabled', !enabled);
}