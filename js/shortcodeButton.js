jQuery(document).ready(function($) {

	var $wcklpgButton 			 = $('#wcklpg-button'),
		$wcklpgModal  			 = $('.wcklpg-shortcode-modal'),
		$wcklpgActiveModal 		 = $('.wcklpg-shortcode-modal.active'),
		$wcklpgShortcodeAddBtn   = $('.wcklpg-modal-add'),
		$wcklpgModalCloseBtn 	 = $('.wcklpg-modal-close'),
		$wcklpgModalBuildBtn 	 = $('.wcklpg-modal-build'),
		$wcklpgRenderBySelect    = $('.wcklpg-shortcode-template'),
		$wcklpgFullPageBtn		 = $('.wcklpg-modal-full-product'),
		$wcklpgGridProductBtn 	 = $('.wcklpg-modal-grid-product'),
		$parameters				 = [],
		currentContentType;
	
	$(window).on('resize', setShortCodeParametersContainerHeight );

	$wcklpgModalCloseBtn.on('click', closeDPGShortcodeModal);
		
	$wcklpgModalBuildBtn.on('click', function(e){
		var error = false;
		error = WC_KLPG_utilities.checkRequiredShortCodeParameters();
		if( !error && $('.wcklpg-advanced-shortcode-table').length !== 0 ){
			addToShortCodeDisplay();
			WC_KLPG_utilities.enableButton($('.wcklpg-modal-add'));
		}
	});
	
	$wcklpgShortcodeAddBtn.on('click', function(e){
		addShortCodeToPost();
		closeDPGShortcodeModal();
	});

	$wcklpgModal.on('click', function(e){
		$target = $(e.target);
		isModal = $target.closest('.wcklpg-shortcode-modal-content').length ;
		if(isModal === 0 ){
			closeDPGShortcodeModal();
			wcklpg_advanced_options.toggleAdvancedOptions(0);
		}
	});

	$('.wcklpg-shortcode-content-type').on('change', function(e){
		currentContentType =  $(this).val();
		$('.wcklpg-shortcode-template').trigger('change');
	});

	$('.wcklpg-shortcode-template').on('change', function(e){
		var	currentRenderByValue = $(this).val();
		if(currentRenderByValue === 'shortcode' && currentContentType !== undefined){
			WC_KLPG_shortcode_selection.enableShortCodeSelection();
		}else{
			WC_KLPG_shortcode_selection.disableShortCodeSelection();
		}
	});
	
	$('body').delegate( '#wcklpg-button', 'click', function(event){
		event.preventDefault();
		var $wcklpgModalActive   = $('.wcklpg-shortcode-modal.active');
		openModal = $wcklpgModalActive.length;
		if(openModal < 1){
			$wcklpgModal.clone(true)
				.appendTo('body')
				.addClass('active')
				.removeClass('hidden');

			WC_KLPG_utilities.disableButton($('.wcklpg-modal-add'));
			setShortCodeParametersContainerHeight();
		}
	});

	$wcklpgFullPageBtn.on('click', addFullPageProductShortcodeToPage);
	$wcklpgGridProductBtn.on('click', addGridProductShortcodeToPage);

	function addFullPageProductShortcodeToPage (event) {
		var shortCodeOpen  				= '[wcklpg',
			shortCodeClose 				= ']',
			shortCodeValue;

			shortCodeValue = shortCodeOpen + ' content_type="product" render_by="shortcode" shortcode_name="product_page" ' + shortCodeClose;
			window.send_to_editor(shortCodeValue); 
			closeDPGShortcodeModal();
	}

	function addGridProductShortcodeToPage (event) {
		var shortCodeOpen  				= '[wcklpg',
			shortCodeClose 				= ']',
			shortCodeValue;

			shortCodeValue = shortCodeOpen + ' content_type="product" render_by="shortcode" shortcode_name="product" ' + shortCodeClose;
			window.send_to_editor(shortCodeValue);
			closeDPGShortcodeModal();
	}
	
	function openDPGShortcodeModal(){
		var $wcklpgModalActive   = $('.wcklpg-shortcode-modal.active');
		openModal = $wcklpgModalActive.length;
		if(openModal < 1){
			$wcklpgModal.clone(true)
				.appendTo('body')
				.addClass('active')
				.removeClass('hidden');

			WC_KLPG_utilities.disableButton($('.wcklpg-modal-add'));
			setShortCodeParametersContainerHeight();
		}
	}

	function setShortCodeParametersContainerHeight(){
		var windowHeight 		= $(window).height(),
			containerHeight 	= (windowHeight * .85) - 200;
		$('.wcklpg-shortcode-parameters-container')
			.css({
				'max-height': containerHeight,
				'overflow-y': 'auto'
			});
		// fakescroll disabled for now
		//$('.wcklpg-fakescroll-container').fakeScroll();
	}

	function addShortCodeToPost( ){
	    var shortCodeValue = $('textarea.wcklpg-shortcode-display').val();
	    window.send_to_editor(shortCodeValue);
	    wcklpg_advanced_options.toggleAdvancedOptions(0);
	    WC_KLPG_utilities.disableButton( $('.wcklpg-modal-advanced') );
	}

	
	function closeDPGShortcodeModal(){
		
		var $wcklpgShortCodeDisplay	= $('textarea.wcklpg-shortcode-display'),
			$wcklpgModalActive   	= $('.wcklpg-shortcode-modal.active');
		
		$wcklpgShortCodeDisplay.val('');
		$wcklpgModalActive.remove();
		WC_KLPG_utilities.disableButton( $('.wcklpg-modal-advanced') );

	}

	function addToShortCodeDisplay(){
		var $inputs 					= $('.wcklpg-shortcode-modal.active').find('.wcklpg-shortcode-select:visible'),
			error						= false,
			shortCodeParameters 		= '',
			$wcklpgShortCodeDisplay		= $('textarea.wcklpg-shortcode-display'),
			shortCodeOpen  				= '[wcklpg',
			shortCodeClose 				= ']',
			wcklpgShortCodeCurrentValue	= $wcklpgShortCodeDisplay.val().replace(/\[wcklpg/g, ''),
			wcklpgShortCodeCurrentValue	= wcklpgShortCodeCurrentValue.replace(/\]/g, '');
		
		$.each($inputs, function(){
			var key 			= $(this).attr('data-shortcode-parameter'),
				selectedOption 	= $(this).val();

				key = key.trim();
			
			if(selectedOption !== '' && selectedOption !== null){
				shortCodeParameters += ' ' + key + '='+ '"' + selectedOption + '"';
			}
		});

		if( !$('.wcklpg-shortcode-advanced-options').hasClass('hidden') ){
			shortCodeParameters =  getAdvancedOptions(shortCodeParameters);
		}

		shortCodeValue = shortCodeOpen + shortCodeParameters + shortCodeClose ;
		$wcklpgShortCodeDisplay.val(shortCodeValue);		
	}

	function getAdvancedOptions(shortCodeParameters){

		var advancedTermsValue 		= $('.wcklpg-terms-option.active').find('textarea').val(),
			advancedTermOptionKey 	= $('.wcklpg-terms-option.active').find('textarea').attr('data-terms-key'),
			advancedTermsOptionParameters;

			if(advancedTermsValue == '' || advancedTermOptionKey === undefined)
				return shortCodeParameters;

		advancedTermsOptionParameters = ' ' + advancedTermOptionKey + '=' + '"' + advancedTermsValue + '"';

		shortCodeParameters += advancedTermsOptionParameters;

		return shortCodeParameters;

	}	

	var WC_KLPG_shortcode_selection = ( function(){
		
		var self = {};
		var numShortcodes;
		
		self.enableShortCodeSelection = function(){
			self.extractRegisteredShortcodes();
			if(numShortcodes===0)
				return;

			var $shortcodeSelectionContainer  = $('.shortcode-selection-container');
				$shortcodeSelectionContainer
					.find('select')
						.attr('disabled', false)
						.addClass('required')
					.end()
					.find('.alert')
						.removeClass('hidden');
					
				self.disableFeaturedImageSelection();
		};

		self.enableFeaturedImageSelection = function(){
			var $featuredImageSelectionContainer = $('.wcklpg-shortcode-featured-img-container');
			$featuredImageSelectionContainer
					.find('select')
						.attr('disabled', false);
		};

		self.extractRegisteredShortcodes = function(){
			numShortcodes = 0;
			$.each( WC_KLPG_shortcode_button_vars.WC_KLPG_options , function(option, value){
				if( WC_KLPG_utilities.endsWith(option, 'shortcode') && WC_KLPG_utilities.startsWith(option, currentContentType) ){
					var shortcodeArray = value.split(',');
					WC_KLPG_shortcode_selection.populateShortCodeSelection(shortcodeArray);
					numShortcodes++;
				}
			});
		};

		self.populateShortCodeSelection = function(shortcodeArray){
			var $shortcodeSelectionContainer = $('.shortcode-selection-container'),
				$shortCodeSelection 		 = $shortcodeSelectionContainer.find('select');
			self.emptyShortCodeSelection();
			$shortCodeSelection.append('<option value="">Choose...</option>');
			$.each(shortcodeArray, function(index, value){
				value = value.trim();
				$shortCodeSelection.append('<option value="'+value+'">'+value+'</option>');
			});
		};

		self.emptyShortCodeSelection = function(){
			var $shortcodeSelectionContainer = $('.shortcode-selection-container'),
				$shortCodeSelection 		 = $shortcodeSelectionContainer.find('select');
			$shortCodeSelection.find('option').each(function(i,v){
				if( $(this).attr('value') !== undefined )
					$(this).remove();
			});
		},

		self.disableFeaturedImageSelection = function(){
			var $featuredImageSelectionContainer = $('.wcklpg-shortcode-featured-img-container');
			$featuredImageSelectionContainer
					.find('select')
						.attr('disabled', 'disabled')
						.val('');
		};
		
		self.disableShortCodeSelection = function(){
			var $shortcodeSelectionContainer = $('.shortcode-selection-container');
			$shortcodeSelectionContainer
				.find('select')
					.attr('disabled', 'disabled')
					.removeClass('required')
				.end()
				.find('.alert')
					.addClass('hidden');

			self.emptyShortCodeSelection();
			self.enableFeaturedImageSelection();
		};

		return{
			disableShortCodeSelection : self.disableShortCodeSelection,
			enableShortCodeSelection : self.enableShortCodeSelection,
			populateShortCodeSelection : self.populateShortCodeSelection
		};

	}());



	var WC_KLPG_utilities = {
		
		enableButton : function ($button){
			$button
				.removeClass('disabled')
				.attr('disabled', false);
		},

		disableButton: function ($button){
			$button
				.addClass('disabled')
				.attr('disabled', true);
		},

		endsWith : function(str, suffix) {
	    	return str.indexOf(suffix, str.length - suffix.length) !== -1;
		},
		
		startsWith: function(str, prefix){
			position = position || 0;
		    return str.indexOf(prefix, position) !== -1;
		},
		
		checkRequiredShortCodeParameters: function(){

			var $wcklpgActiveModal 	= $('.wcklpg-shortcode-modal.active'),
				$errorMsg 			= $('.error-msg'),
				error = false;

				$('.wcklpg-shortcode-modal.active select.required').removeClass('has-error');
				$errorMsg.addClass('hidden');
				
			$('.wcklpg-shortcode-modal.active select.required').each(function(){
				if($(this).val() == ''){
					$(this).addClass('has-error');
					$errorMsg.removeClass('hidden');
					error = true;
				}
			});

			if(error === true){
				return true;
			}else{
				return false;
			}
			
		}
	};

	var wcklpg_advanced_options = ( function(){

		var self = {};			

		self.init = function(){
			
			if($('.wcklpg-modal-advanced').length === 0)
				return;

			self.bindEvents();

		};

		self.bindEvents = function(){

			$('.wcklpg-shortcode-content-type').on('change', function(){
				if(currentContentType !==''){
					WC_KLPG_utilities.enableButton( $('.wcklpg-modal-advanced') );
					
				}else{
					WC_KLPG_utilities.disableButton( $('.wcklpg-modal-advanced') );
					$('.wcklpg-modal-advanced').text('Show Advanced Options');
					self.clearAdvancedOptions();
				}
			});

			$('.wcklpg-modal-advanced').on('click', function(e){
				self.toggleAdvancedOptions();
			});	

			$('.wcklpg-radio').on('click', function(e){
				self.toggleAdvancedSelection(e);
			});	

		};

		self.toggleAdvancedSelection = function(event){

			var $target 	= $(event.target),
				$siblings	= $('.wcklpg-radio');
				
				$siblings.removeClass('active');
				$target.addClass('active');

				$('.wcklpg-terms-option')
					.addClass('hidden')
					.removeClass('active');

				$target
					.next('.wcklpg-terms-option')
						.removeClass('hidden')
						.addClass('active')
						.find('textarea')
							.focus();				
		};

		self.toggleAdvancedOptions = function(toggle){
			if(toggle === 0){
				$('.wcklpg-shortcode-advanced-options').addClass('hidden');
				$('.wcklpg-modal-advanced').text('Show Advanced Options');
				return;
			}
			if( $('.wcklpg-shortcode-advanced-options').hasClass('hidden') ){				
				$('.wcklpg-shortcode-advanced-options').removeClass('hidden');
				$('.wcklpg-modal-advanced').text('Hide Advanced Options');
			}else{
				$('.wcklpg-shortcode-advanced-options').addClass('hidden');
				$('.wcklpg-modal-advanced').text('Show Advanced Options');
			}
		};

		self.clearAdvancedOptions = function(){
			$('.wcklpg-advanced-option').val('');
			$('.wcklpg-shortcode-advanced-options').addClass('hidden');
		};

		self.init();

		return{
			toggleAdvancedOptions : self.toggleAdvancedOptions
		};

	}());

});