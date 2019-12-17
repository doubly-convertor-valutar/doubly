@section('scripts')	
	{!! HTML::script('js/datatables.js') !!}
@stop

@section('styles')	
	{!! HTML::style('css/datatables.css') !!}
@stop
<div class="next-card__section dt-buttons">
	<div class="default-currency">Your shop's main currency is <b>{{ $shop_currency }}</b></div>
	<ul class="segmented" id="currency-dt-buttons">
		<li><button id="addCurrency" class="btn"  type="button">Add Currency</button></li>
		<li><button id="addAllCurrencies" class="btn"  type="button">Add All Currencies</button></li>
		<li><button id="removeAllCurrencies" class="btn"  type="button">Remove All Currencies</button></li>
	</ul>
</div>

<table id="users-table" class="table-hover expanded">
	<thead>
	<tr>
		<th>#</th>
		<th>Name</th>
		<th>ID</th>
		<th>Action</th>
	</tr>
	</thead>
</table>
<input type="hidden" name="json_data" id="json_data">
<input type="hidden" name="remove_currency_ids" id="remove_currency_ids">

<script>
	var selectedValues = {};
	var currencyCode = [];
	var currencyName = [];
	<?php foreach ($currency_list as $key => $value): ?>
		 currencyCode['<?php echo $value; ?>'] = '<?php echo $key; ?>';
		 currencyName['<?php echo $key; ?>'] = '<?php echo $value; ?>';
	<?php endforeach; ?>

	$(function() {
		//Initialize data table
		window.table = $('#users-table').DataTable({
			ajax: '{!! url("currency/load-all") !!}',
			columns: [
				{ data: 'position', className: 'index' },
				{ data: 'currency', className: 'name' },
				{ data: 'id',className: 'primary_id'},
				{ data: '', className: 'action' }
			],
			columnDefs: [
				{ orderable: false, targets: [0,1,2,3] },
				{ searchable: false, "targets": [0] },
				{ "targets": -1,
				  "data": null,
				  "defaultContent": ""
				},
				{ "orderDataType": "dom-select", "targets": 3 }
			],
			oLanguage: { 
				sEmptyTable: 'No currencies added.',
				sZeroRecords: 'No matching currencies found.' 
			},
			lengthMenu: [[10, 20, 35, -1], [10, 20, 35, "All"]],
			rowReorder: {
				dataSrc: 'position',
				selector:".move"
			},
			fnInitComplete: function() {
				<?php foreach ($currencies as $key => $currency): ?>
					selectedValues[<?php echo $currency->id; ?>] = '<?php echo $currency->currency; ?>';
				<?php endforeach; ?>

				$('#dt-search').attr('placeholder','Start typing to search for currencies...');
				
				//search field
				$('#dt-search').focus(function(){
					$(this).removeAttr('placeholder');
					$(this).parent().addClass('next-input--is-focused');
				});
				$('#dt-search').focusout(function(){
					$(this).attr('placeholder','Start typing to search for currencies...');
					$(this).parent().removeClass('next-input--is-focused');
				});
				$('#users-table_length .next-select__wrapper').append('<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg>');

				//tooltips
				addTooltips();
			},
			fnDrawCallback: function() {
				if ($('#remove_currency_ids').val()!='') {
					var removeCurrencyIds = $('#remove_currency_ids').val().split(',');
					for (i = 0; i < removeCurrencyIds.length; i++) { 
						$('#row'+removeCurrencyIds[i]).remove();
					}
				}

				var select_opt;
				//Draw selectbox
				$('#users-table tr td.name').each(function(){
					var opt = '';
					var currency_name;
					var primaryId = $(this).closest('tr').find('.primary_id').text();

					if (selectedValues[primaryId]!=null) {
						currency_name = selectedValues[primaryId];
					} else if($(this).find('select').length !=0) {
						currency_name = $(this).find('select').val();
						selectedValues[primaryId] = currency_name;
					} else {
						currency_name =  $(this).text();					
					}

					<?php foreach ($currency_list as $key => $value): ?>
						var value = "<?php echo $value; ?>";
						var key = "<?php echo $key; ?>";
						if(currency_name==value || currency_name==key){
							opt += '<option value="<?php echo $key; ?>" selected="selected" ><?php echo $value; ?></option>';
							if(currency_name==value) {
								selectedValues[primaryId] = key;
							}
						}else{
							opt += '<option value="<?php echo $key; ?>"><?php echo $value; ?></option>';
						}
					<?php endforeach; ?>

					//Remove select to and replace them with plain td
					select_opt = '<div class="next-select__wrapper"><select class="select_currency next-select">'+opt+'</select><svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>';
					$(this).html(select_opt);
				});
				
				$('#users-table tr td:nth-child(3)').each(function(){
					var new_id = $(this).text().split('-');
					if(new_id[0]=='n')
					{
						$(this).next('td').html('<button class="move btn btn--icon tooltip tooltip-bottom"><span class="tooltip-container"><span class="tooltip-label">Move</span></span><div class="move-icon"></div></button> <button type="button" class="remove btn btn--icon tooltip tooltip-bottom"><span class="tooltip-container"><span class="tooltip-label">Delete currency</span></span><i class="ico ico-14-svg ico-delete"></i></button>');
					}else{
						$(this).next('td').html('<button class="move btn btn--icon tooltip tooltip-bottom"><span class="tooltip-container"><span class="tooltip-label">Move</span></span><div class="move-icon"></div></button> <button type="button" onclick="removeCurrency('+new_id+', this)" class="btn btn--icon tooltip tooltip-bottom"><span class="tooltip-container"><span class="tooltip-label">Delete currency</span></span><i class="ico ico-14-svg ico-delete"></i></button>');
					}
				});

				$('.primary_id').hide();
				$('.index').hide();

				addTooltips();
				disableMove(this.api());
			}


		});

		window.new_index   = 1;

		//Add new row by making sure that it is movable too
		$('#addCurrency').on( 'click', function () {
			addNewCurrency();
		});

		//Add new row by making sure that it is movable too
		$('#addAllCurrencies').on( 'click', function () {
			addAllCurrencies();
		});

		//Upon click on move button, currency select box should only have selected values to avoid issues with the view
		$( "#users-table" ).delegate("tr td .move", "mousedown", function() {
			$('.tooltip').unbind();
			$('.tooltip').removeClass('is-active');
			$(".select_currency option[selected!=selected]").remove();
		});


		//Upon changing the currency, make sure it has not been selected before
		var old_selected;
		var changeRunning;
		$(document).on('focus','.select_currency',function(){
			if (!changeRunning) {
				old_selected =  $(this).val();
			}
		}).on('change','.select_currency',function(){
			changeRunning = true;

			var primaryId;
			var currency = $(this).val();
			$(this).find('option').each(function(){
				if($(this).attr('value') === currency ){
					$(this).prop({selected: true});
					$(this).attr('selected','selected');
					primaryId = $(this).closest('tr').find('.primary_id').text();
				}else{
					$(this).prop({selected: false});
					$(this).removeAttr('selected');
				}
			});

			var inArray = false;
			$.each(selectedValues, function(index, value){
				if (currency==value) {
					inArray = true;
				}
			});
			if(inArray && currency !== old_selected ){
				ShopifyApp.Modal.alert({
				  title: "Currency already exists",
				  message: "The selected currency has already been added to your Currency Switcher.",
				  okButton: "Ok"
				});
				$(this).find('option').each(function(){
					if($(this).attr('value')===old_selected)
					{
						$(this).prop({selected: true});
						$(this).attr('selected','selected');
					} else {
						$(this).prop({selected: false});
						$(this).removeAttr('selected');
					}
				});
				changeRunning = false;
				return false;
			}

			old_selected =  currency;
			selectedValues[primaryId] = currency;
			changeRunning = false;
		});

		//Remove data row
		$(document).on('click','.remove',function(){
			var element = $(this);
			ShopifyApp.Modal.confirm({
			  title: "Are you sure you want to delete this currency?",
			  message: "Please confirm.",
			  okButton: "Yes",
			  cancelButton: "No",
			  style: "danger"
			}, function(result){
				if(result){
					var primary_id = element.parent().prev().text();
					delete selectedValues[primary_id]; 

					var currentPage = table.page();
					if ((table.rows().data().length-1)%table.page.len()==0 && currentPage!=0) {
						currentPage = currentPage-1;
					}
					table.row( element.parents('tr') ).remove().draw();
					table.page(currentPage).draw( 'page' );
			  	}
			});
		});

		$('#removeAllCurrencies').click(function() {
			ShopifyApp.Modal.confirm({
			  title: "Are you sure you want to delete all the currencies?",
			  message: "Please confirm.",
			  okButton: "Yes",
			  cancelButton: "No",
			  style: "danger"
			}, function(result){
				if(result){
			  		removeAllCurrencies();
			  	}
			});
		});    
	});

	function addNewCurrency() {
		if ({{ count($currency_list) }}==table.rows().data().length) {
			ShopifyApp.Modal.alert({
			  title: "All currencies have already been added",
			  message: "You can't add any new currency. All the currencies have already been added.",
			  okButton: "Ok"
			});
			return false;
		}
		$.each(selectedValues, function(index, value){
			console.log( index + ": " + value );
		});
		
		//get select value
		var selectCurrency;
		$('#users-table tr:last td.name').find('option').each(function(){
			var inArray = false;
			var optionValue = $(this).attr('value');
			$.each(selectedValues, function(index, value){
				if (optionValue==value) {
					inArray = true;
					return false;
				}
			});
			if(!inArray)
			{
				selectCurrency = optionValue;
				return false;
			}
		});

		if (selectCurrency==undefined) {
			selectCurrency = '{{ $default_currency }}';
		} else {
			selectCurrency = currencyName[selectCurrency];
		}

		var counter = (table.rows().data().length!=0) ? table.row(table.rows().data().length-1).data().position+1 : 0;
		var primaryId = 'n-' +  window.new_index;

		table.rows.add([{
			"position":    counter,
			"currency":   selectCurrency,
			"id"      :  primaryId,
			""        :     ""
		}]).draw();

		table.page('last').draw( 'page' );
		$('#users-table tr:last td:last').html('<button class="move btn btn--icon tooltip tooltip-bottom"><span class="tooltip-container"><span class="tooltip-label">Move</span></span><div class="move-icon"></div></button> <button type="button" class="remove btn btn--icon tooltip tooltip-bottom"><span class="tooltip-container"><span class="tooltip-label">Delete currency</span></span><i class="ico ico-14-svg ico-delete"></i></button>');
		window.new_index++;

		//select first not used currency
		$('#users-table tr:last td.name').find('option').each(function(){
			if ($(this).attr('value')==selectCurrency) {
				selectedValues[primaryId] = currencyCode[selectCurrency];
				$(this).prop({selected: true});
				$(this).attr('selected','selected');
			}
		});

		addTooltips();
		disableMove(table);

		checkChangeTheme();

		return false;
	}

	function removeCurrency(id, element) {
		ShopifyApp.Modal.confirm({
		  title: "Are you sure you want to delete this currency?",
		  message: "Please confirm.",
		  okButton: "Yes",
		  cancelButton: "No",
		  style: "danger"
		}, function(result){
			if(result){
		  		var removeCurrencyIds = $('#remove_currency_ids').val();
				if (removeCurrencyIds!='') {
					removeCurrencyIds += ','+id;
				} else {
					removeCurrencyIds = id;
				}
				$('#remove_currency_ids').val(removeCurrencyIds);

				var currentPage = table.page();
				if ((table.rows().data().length-1)%table.page.len()==0 && currentPage!=0) {
					currentPage = currentPage-1;
				}
				table.row( $(element).closest('tr') ).remove().draw();
				table.page(currentPage).draw( 'page' );

				delete selectedValues[id]; 
		  	}
		});
	}

	function removeAllCurrencies() {
		selectedValues = {};
		var removeCurrencyIds = $('#remove_currency_ids').val();
		table.column(2).data().each(function (value, index) {
			if (!isNaN(value)) {
				if (removeCurrencyIds!='') {
					removeCurrencyIds += ','+value;
				} else {
					removeCurrencyIds = value;
				}
			}
	    });
		$('#remove_currency_ids').val(removeCurrencyIds);

		table.clear().draw();
	}

	function addAllCurrencies() {
		removeAllCurrencies();
		window.new_index = 1;
		@foreach ($currency_list as $key => $currency)
			var selectCurrency = '{{ $currency }}';
			var counter = window.new_index-1;
			var primaryId = 'n-' +  window.new_index;

			table.rows.add([{
				"position":    counter,
				"currency":   selectCurrency,
				// "selected":     "",
				"id"      :  primaryId,
				""        :     ""
			}]);

			selectedValues[primaryId] = selectCurrency;

			window.new_index++;
		@endforeach
		table.draw();

		checkChangeTheme();
	}

	function disableMove(table) {	
		//disable move if more than 20 on a page or if filtering
		if ($.trim($('#dt-search').val())!='' || ((table.page.len()==-1 || table.page.len()>20) && table.rows().data().length>20)) {
			$('.move').addClass('disabled');
			$('.move').addClass('move-disabled').removeClass('move');
			$('.move').prop("disabled", true);
		} else {
			$('.move').removeClass('disabled');
			$('.move').addClass('move').removeClass('move-disabled');
			$('.move').prop("disabled", false);
		}
	}

	function addTooltips() {
		$('.tooltip').each(function(){
			var tooltipButton = $(this);
			$(this).hover(function(){
				tooltipButton.addClass('is-active');
			},function(){
				tooltipButton.removeClass('is-active');
			});
		});
	}

	//change to flags theme if more than 5 currencies exist
	function checkChangeTheme() {
		if (table.rows().data().length>5) {
			// $('#currency_switcher_theme').val(2);
			if ($('#currency_switcher_theme').val()!='flags_theme' && $('#currency_switcher_theme').val()!='no_theme') {
				$('#currency_switcher_theme>option:eq(1)').prop({selected: true});
				$('#currency_switcher_theme>option:eq(1)').attr('selected','selected');
			
				$('.custom_options').css('display','none');
				$('#flags_theme_options').css('display','block');

				ShopifyApp.Modal.alert({
				  title: "Changing to Flags theme",
				  message: "Only the \"Flags theme\" or \"No theme\" support more than 5 currencies. Because you have "+table.rows().data().length+" currencies, we're automatically changing the Currency Switcher Theme to the \"Flags theme\".",
				  okButton: "Ok"
				});
			}
		}
	}

	/*beforeSave()
	function reads the table rows and pushes all data into JSON container which is sent to server
	for further processing
	*/
	function beforeSave(){
		console.log('before_save_start');
		var new_array = [];
		var new_currency = [];
		table.column(1).nodes().each(function (node, index, dt) {
			if (typeof $(table.cell(node).node()).find('.select_currency').val() !== 'undefined') {
				new_currency[index] = $(table.cell(node).node()).find('.select_currency').val();
			} else if ($(table.cell(node).node()).text().length==3) {
				new_currency[index] = $(table.cell(node).node()).text();
			} else {
				new_currency[index] = currencyCode[$(table.cell(node).node()).text()];
			}
		});

		var obj = [];
		table.rows().iterator( 'row', function ( context, index ) {
			obj = this.row( index ).data();
			obj.position = (index+1);
			obj.currency = new_currency[index];
			new_array[index] = obj;
		});

		$('#json_data').val(JSON.stringify(new_array));
		console.log('before_save_end');
	}
</script>
<div style="display: none;" refresh-always="true" refresh="global-icon-symbols" id="global-icon-symbols">
	<svg xmlns="http://www.w3.org/2000/svg">
		<symbol id="next-search-16" class="icon-symbol--loaded"><svg enable-background="new 0 0 16 16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M0 5.667c0 3.125 2.542 5.667 5.667 5.667 1.202 0 2.315-.38 3.233-1.02l.455.456c-.07.5.082 1.025.466 1.41l3.334 3.332c.326.325.753.488 1.18.488.425 0 .852-.163 1.177-.488.652-.65.652-1.706 0-2.357L12.18 9.822c-.384-.384-.91-.536-1.41-.466l-.454-.456c.64-.918 1.02-2.03 1.02-3.233C11.333 2.542 8.79 0 5.666 0S0 2.542 0 5.667zm2 0C2 3.645 3.645 2 5.667 2s3.667 1.645 3.667 3.667-1.646 3.666-3.667 3.666S2 7.688 2 5.667z"/></svg></symbol>
		<symbol id="next-remove" class="icon-symbol--loaded"><svg enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19.5 22c-.2 0-.5-.1-.7-.3L12 14.9l-6.8 6.8c-.2.2-.4.3-.7.3-.2 0-.5-.1-.7-.3l-1.6-1.6c-.1-.2-.2-.4-.2-.6 0-.2.1-.5.3-.7L9.1 12 2.3 5.2C2.1 5 2 4.8 2 4.5c0-.2.1-.5.3-.7l1.6-1.6c.2-.1.4-.2.6-.2.3 0 .5.1.7.3L12 9.1l6.8-6.8c.2-.2.4-.3.7-.3.2 0 .5.1.7.3l1.6 1.6c.1.2.2.4.2.6 0 .2-.1.5-.3.7L14.9 12l6.8 6.8c.2.2.3.4.3.7 0 .2-.1.5-.3.7l-1.6 1.6c-.2.1-.4.2-.6.2z"/></svg></symbol>
	</svg>
</div>