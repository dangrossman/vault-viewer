<h1>Transactions</h1>

<div class="navbar">
	<div class="navbar-inner">
		<div class="container">

			<form class="navbar-search pull-left">
			  	<input type="text" class="search-query" placeholder="Filter">
			</form>

			<ul class="nav pull-right">
				<li>
					<p class="navbar-text" id="status">
						<i class="icon-spinner icon-spin icon-large"></i> &nbsp; Loaded <span>0</span> transactions...
					</p>
				</li>
			</ul>

		</div>
	</div>
</div>

<table class="table" id="transactions" style="display: none">
	<thead>
		<tr>
			<th class="first"></th>
			<th>Created At</th>
			<th>Transaction Type</th>
			<th>Amount</th>
			<th>Succeeded</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript">
var items = [];

function update(filtered) {

	var list = items;
	if ($.isArray(filtered))
		list = filtered;

	$('#transactions tbody').html('');
	for (var i = 0; i < list.length; i++) {
		var item = list[i];
		var html = '<tr>';
		html += '<td>';
		html += '<a href="#' + item.token + '" title="View XML" class="btn btn-small zoom"><i class="icon-zoom-in"></i></a> ';
		html += '</td>';
		html += '<td>' + moment(item.created_at).format('YYYY-MM-DD') + '</td>';
		html += '<td>' + item.transaction_type + '</td>';
		html += '<td>' + (item.hasOwnProperty('amount') ? item.amount / 100 : 'N/A') + '</td>';
		html += '<td>' + item.succeeded + '</td>';
		html += '</tr>';
		$('#transactions tbody').append(html);
	}

	$('a').tooltip();

}

function get_items(since) {
	var url = '/payment_method/<?php echo $payment_method_token; ?>/transactions.json';
	if (typeof since != 'undefined')
		url += '/' + since;
	$.getJSON(url, function(data) {
		if (data.length > 0) {
			for (var i = 0; i < data.length; i++) {
				items.push(data[i]);
			}
			$('#status span').html(items.length);	
			update();
			get_items(data[data.length - 1].token);
		} else {
			$('#status').html('Displaying ' + items.length + ' transactions');
			update();

		    $("#transactions").show().tablesorter({ 
		        textExtraction: function(node) { 
		            return node.innerHTML.replace(/<\/?[^>]+>/gi, '').replace(',', '');
		        },
		        sortList: [[1,1]]
		    });	

		}
	});
}

function search(query) {

	if (query.length == 0) {
		update();
		$('#payment_methods').trigger("update");
		$('#payment_methods').trigger("sorton",[[[1,1]]]);
		return;
	}

	var filtered = [];
	for (var i = 0; i < items.length; i++) {
		var item = items[i];
		if (item.xml.indexOf(query) > -1)
			filtered.push(item);
	}

	update(filtered);

	if (filtered.length > 0) {
		$('#payment_methods').trigger("update");
		$('#payment_methods').trigger("sorton",[[[1,1]]]);
	}

}

$(document).ready(function() {

	get_items();

	$(document).on('click', 'a.zoom', function() {
		var selector = $(this).attr('href').replace('#', '');

		var item = items[0];
		for (var i = 0; i < items.length; i++) {
			if (items[i].token == selector) {
				item = items[i];
				break;
			}
		}
		
		var xml = '<pre>' + item.xml.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre>';

		$('#modal h4').html(selector);
		$('#modal #xml').html(xml);
		$('#modal').modal({'show': true });

	});

	$('.search-query').keypress(function() {
		search($(this).val());
	});

	$('.search-query').blur(function() {
		search($(this).val());
	});	

});
</script>