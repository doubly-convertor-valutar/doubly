@extends('app')

@section('scripts') 
    {!! HTML::script('js/datatables.js') !!}
@stop

@section('styles')  
    {!! HTML::style('css/datatables.css') !!}
@stop

@section('content')
    {!! Form::open(['url'=>'currency-by-country','id' =>'currency-by-country']) !!}
        <div class="section">
            <div class="layout-content">
                <div class="layout-content__sidebar layout-content__first">
                    <div class="section-summary">
                        <h1>Assign Currency to Country</h1>
                        <p>If a user visits your website from a specific Country, you can select which currency will automatically be selected for him.</p>
                    </div>
                </div>
                <div class="layout-content__main">
                    <div class="next-card">
                        <div class="next-card__section">
                            By default each country's default currency is set. In case the currency isn't in your Currency Switcher <a href="javascript:void(0)" onclick="toggleCurrencies()" id="toggleCurrencies">(view)</a>, the default currency ({{ $defaultCurrency }}) will be selected for your customers.
                        </div>
                        <div class="next-card__section datatable-section" id="selected-currencies" style="display:none;">
                            <ul class="unstyled">
                                @foreach ($currency_items as $currency)
                                <li class="reportcard order_reports">
                                    <a href="javascript:void(0)" class="reportcard-link">
                                      <div class="reportcard-content-wrapper pb">
                                        <div class="reportcard-content">
                                          <ul class="unstyled">
                                            <li class="reportcard-name tc">{{ $currency }}</li>
                                          </ul>
                                        </div>
                                      </div>
                                    </a>                  
                                </li>
                                @endforeach
                                <li class="reportcard order_reports">
                                    <a href="{!! url('settings') !!}" target="_blank" class="reportcard-link">
                                      <div class="reportcard-content-wrapper pb">
                                        <div class="reportcard-content">
                                          <ul class="unstyled">
                                            <li class="reportcard-name tc link">Add New Currency</li>
                                          </ul>
                                        </div>
                                      </div>
                                    </a>                  
                                </li>
                            </ul>
                        </div>
                        <div class="next-card__section datatable-section">
                            <table class="table-hover expanded" id="users-table">
                            	<thead>
                            	<tr>
                                    <th>Country Code</th>
                            		<th><span>Country</span></th>
                            		<th><span>Currency</span></th>
                            	</tr>
                            	</thead>
                            </table>
                            <input type="hidden" name="json_data" id="json_data">
                            <script>
                                var currencyCode = [];
                                <?php foreach ($currency_list as $key => $value): ?>
                                     currencyCode['<?php echo $value; ?>'] = '<?php echo $key; ?>';
                                <?php endforeach; ?>

                                $(function() {

                                    //Initialize data table
                                    window.table = $('#users-table').DataTable({
                                        ajax: '{!! url("country/load-all") !!}',
                                        columns: [
                                            { data: 'country_code', className: 'country_code' },
                                            { data: 'name', className: 'name' },
                                            { data: 'currency_code', className: 'currency_code' }
                                        ],
                                        columnDefs: [
                                            { orderable: false, "targets": [0] },
                                            { searchable: false, "targets": [0] }
                                        ],
                                        oLanguage: { 
                                            sZeroRecords: 'No matching currencies or countries found.' 
                                        },
                                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                        order: [1, 'asc'],

                                        fnInitComplete: function() {
                                            $('#dt-search').attr('placeholder','Start typing to search for countries or currencies...');
                                            
                                            //search field
                                            $('#dt-search').focus(function(){
                                                $(this).removeAttr('placeholder');
                                                $(this).parent().addClass('next-input--is-focused');
                                            });
                                            $('#dt-search').focusout(function(){
                                                $(this).attr('placeholder','Start typing to search for countries or currencies...');
                                                $(this).parent().removeClass('next-input--is-focused');
                                            });
                                            $('#users-table_length .next-select__wrapper').append('<svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg>');
                                            
                                            //tooltips
                                            addTooltips();
                                        },
                                        //Call back function to insert select
                                        fnDrawCallback: function() {
                                            var select_opt;
                                            //Draw selectbox
                                            $('#users-table tr td.currency_code').each(function(){
                                                var opt = '';
                                                var currency_name;

                                                if($(this).find('select').length !=0) {
                                                    currency_name = $(this).find('select').val();
                                                } else {
                                                    currency_name =  $(this).text();                    
                                                }

                                                <?php foreach ($currency_list as $key => $value): ?>
                                                    var value = "<?php echo $value; ?>";
                                                    var key = "<?php echo $key; ?>";
                                                    if(currency_name==value || currency_name==key){
                                                        opt += '<option value="<?php echo $key; ?>" selected="selected" ><?php echo $value; ?></option>';
                                                    }else{
                                                        opt += '<option value="<?php echo $key; ?>"><?php echo $value; ?></option>';
                                                    }
                                                <?php endforeach; ?>

                                                //Remove select to and replace them with plain td
                                                select_opt = '<div class="next-select__wrapper"><select class="select_currency next-select">'+opt+'</select><svg class="next-icon next-icon--size-12"><use xlink:href="#next-chevron-down"/></svg></div>';
                                                $(this).html(select_opt);
                                            });
                                            $('.country_code').hide();
                                            
                                            //tooltips
                                            addTooltips();
                                        }

                                    });
                                });

                                /*beforeSave()
                                 function reads the table rows and pushes all data into JSON container which is sent to server
                                 for further processing
                                 */
                                function beforeSave(){
                                	var array = [];
                                	var currency = [];
                                	var country = [];

                                    table.column(0).nodes().each(function (node, index, dt) {
                                        country[index] = $(table.cell(node).node()).find('.country_code').context.innerHTML;
                                    });
                                	table.column(2).nodes().each(function (node, index, dt) {
                                		currency[index] = (typeof $(table.cell(node).node()).find('.select_currency').val() !== 'undefined') ?  $(table.cell(node).node()).find('.select_currency').val() : currencyCode[$(table.cell(node).node()).find('.select_currency').context.innerHTML];
                                	});

                                	var obj = [];
                                	table.rows().iterator( 'row', function ( context, index ) {
                                		obj = this.row( index ).data();
                                		obj.currency = currency[index];
                                		obj.country_code = country[index];
                                		array[index] = obj;
                                	});

                                	$('#json_data').val(JSON.stringify(array));
                                }

                                function toggleCurrencies() {
                                    if ($('#toggleCurrencies').text()=='(view)') {
                                        $('#selected-currencies').show();
                                        $('#toggleCurrencies').text('(hide)');
                                    } else {
                                        $('#selected-currencies').hide();
                                        $('#toggleCurrencies').text('(view)');
                                    }    
                                }

                                /* Validate Settings Form */
                                function submitForm(){              
                                    ShopifyApp.Bar.loadingOn();
                                    beforeSave();

                                    $('#currency-by-country').submit();
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
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none;" refresh-always="true" refresh="global-icon-symbols" id="global-icon-symbols">
            <svg xmlns="http://www.w3.org/2000/svg">
                <symbol id="next-chevron-down" class="icon-symbol--loaded"><svg enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 5.176l-9.086 9.353-8.914-9.353-2.314 2.471 11.314 11.735 11.314-11.735-2.314-2.471z"/></svg></symbol>
                <symbol id="next-search-16" class="icon-symbol--loaded"><svg enable-background="new 0 0 16 16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M0 5.667c0 3.125 2.542 5.667 5.667 5.667 1.202 0 2.315-.38 3.233-1.02l.455.456c-.07.5.082 1.025.466 1.41l3.334 3.332c.326.325.753.488 1.18.488.425 0 .852-.163 1.177-.488.652-.65.652-1.706 0-2.357L12.18 9.822c-.384-.384-.91-.536-1.41-.466l-.454-.456c.64-.918 1.02-2.03 1.02-3.233C11.333 2.542 8.79 0 5.666 0S0 2.542 0 5.667zm2 0C2 3.645 3.645 2 5.667 2s3.667 1.645 3.667 3.667-1.646 3.666-3.667 3.666S2 7.688 2 5.667z"/></svg></symbol>
            </svg>
        </div>
    {!! Form::close() !!}
@stop