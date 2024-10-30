jQuery(document).ready(function($) {

	$wcklpgSettingsForm 		 	 = $('#wcklpg-settings-form'),
	$wcklpgSettingsFormInputs 		 = $('#wcklpg-settings-form input,#wcklpg-settings-form textarea,#wcklpg-settings-form select'),
	$wcklpgSettingsSubmitBtn  		 = $('.wcklpg-settings-submit'),
	$wcklpgSettingsAlert		 	 = $('.wcklpg-settings-alert.success'),
	$wcklpgChangeAlert		 		 = $('.wcklpg-settings-alert.warning'),
	$wcklpgSettingsMenuItem	 		 = $('.wcklpg-settings-menu-item')
	$wcklpgSettingsLoadingIndicator  = $('.throbber-loader'),
	$wcklpgURLParameterAddButton     = $('.url-parameter-add-button'),
	$wcklpgURLParameterRemoveButton  = $('.url-parameter-remove-button');
	
	
	$wcklpgSettingsSubmitBtn.on('click', function(e){
		e.preventDefault();
		doSettingsAjax();
	});

	$wcklpgSettingsFormInputs.on('keypress change', function(e){
		hideSuccessAlert();
		showChangeAlert();
	});

	$wcklpgSettingsMenuItem.on('click', function(e){
		e.preventDefault();
		toggleTabs($(this).attr('data-tab-id'), $(this));
	});

	$wcklpgURLParameterAddButton.on('click', function(e){
		e.preventDefault();
		var numRows 			= $('.url-parameter-table-row').length;
		$('.url-parameter-table-row.prototype')
			.clone(true)
			.appendTo('.url-parameter-table')
			.removeClass('hidden prototype')
			.find('input')
				.val('')
				.attr('id','WC_KLPG_option[url_parameter_'+(numRows)+']')
				.attr('name','WC_KLPG_option[url_parameter_'+(numRows)+']')
				.end()
			.find('select')
				.val('')
				.attr('id','WC_KLPG_option[url_parameter_'+(numRows)+'_operator]')
				.attr('name','WC_KLPG_option[url_parameter_'+(numRows)+'_operator]');
	});

	$wcklpgURLParameterRemoveButton.on('click', function(e){
		e.preventDefault();
		var $target = $(this);
		$target.closest('tr')
			.addClass('hidden')
			.find('input')
				.val('')
			.end()
			.find('select')
				.val('');
		showChangeAlert();
	});

	function toggleTabs(tabId, $newActiveButton){
		var $newActiveTab = $('.settings-tab-content[data-tab-id="'+tabId+'"');
		$('.settings-tab-content').addClass('hidden');
		$newActiveTab.removeClass('hidden');

		$('.wcklpg-settings-menu-item').removeClass('active');
		$newActiveButton.addClass('active');

		Cookies.set('activeTab', tabId);
	}

	function doSettingsAjax(){
		showLoadingSpinner();
		$.ajax({
				type: "POST",
				url: $wcklpgSettingsForm.attr("action"),
				data: $wcklpgSettingsForm.serializeArray(),
				showMask: true,
				dataType: "text",
				timeout: 300000
			}).done(function(data){
				showSuccessAlert();
				hideChangeAlert();
			}).fail(function(data){
				
			});
		 
	}

	function doWPAjaxCall(action){
		$.ajax({
			type: "POST",
			url : "admin-ajax.php",
			data :{
				action: action	
			}
		}).done(function(data){
			if(!action ==='flush_rewrite_rules'){
				showSuccessAlert();
			}
		}).fail(function(data){

		});
	}

	function showSuccessAlert(){
		$wcklpgSettingsAlert
			.removeClass('hidden')
				.fadeTo(500, .7)
			 		.delay(3000)
			 			.fadeTo(500,0)
			 			.addClass('hidden');
		hideLoadingSpinner();
	}

	function showChangeAlert(){
		$wcklpgSettingsAlert.addClass('hidden');
		$wcklpgChangeAlert
			.removeClass('hidden')
				.fadeTo(500, .7);
	}

	function hideChangeAlert(){
		$wcklpgChangeAlert
				.fadeTo(500, 0)
				.addClass('hidden');
	}

	function hideSuccessAlert(){
		$wcklpgSettingsAlert.addClass('hidden');
	}

	function showLoadingSpinner(){
		$wcklpgSettingsSubmitBtn
			.attr('disabled', 'disabled')
			.addClass('disabled');
		$wcklpgSettingsLoadingIndicator
			.removeClass('hidden')
				.fadeTo(500, .9);
	}

	function hideLoadingSpinner(){
		$wcklpgSettingsLoadingIndicator
			.fadeTo(10, 0)			
			.addClass('hidden');
		$wcklpgSettingsLoadingIndicator.promise().done(function() { 
			$wcklpgSettingsSubmitBtn
				.attr('disabled', false)
				.removeClass('disabled'); 
		});	
	}
	
});