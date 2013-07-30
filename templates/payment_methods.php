<h1>Payment Methods</h1>

<div class="navbar">
	<div class="navbar-inner">
		<div class="container">

			<form class="navbar-search pull-left">
			  	<input type="text" class="search-query" placeholder="Filter">
			</form>

			<ul class="nav pull-right">
				<li>
					<p class="navbar-text" id="status">
						<i class="icon-spinner icon-spin icon-large"></i> &nbsp; Loaded <span>0</span> payment methods...
					</p>
				</li>
			</ul>

		</div>
	</div>
</div>

<table class="table" id="payment_methods" style="display: none">
	<thead>
		<tr>
			<th class="first"></th>
			<th>Name</th>
			<th>Type</th>
			<th>Created</th>
			<th>Last Updated</th>
			<th>State</th>
			<th style="width: 100%">Token</th>
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

	$('#payment_methods tbody').html('');
	for (var i = 0; i < list.length; i++) {
		var pm = list[i];
		var html = '<tr>';
		html += '<td>';
		html += '<a href="#" title="Redact" class="btn btn-small btn-danger"><i class="icon-remove"></i></a> ';
		html += '<a href="#' + pm.token + '" title="View Details" class="btn btn-small zoom"><i class="icon-zoom-in"></i></a>';
		html += '</td>';
		html += '<td>' + pm.full_name + '</td>';
		html += '<td>' + pm.payment_method_type + '</td>';
		html += '<td>' + moment(pm.created_at).format('YYYY-MM-DD') + '</td>';
		html += '<td>' + moment(pm.updated_at).format('YYYY-MM-DD') + '</td>';
		html += '<td>' + pm.storage_state + '</td>';
		html += '<td style="width: 100%">' + pm.token + '</td>';
		html += '</tr>';
		$('#payment_methods tbody').append(html);
	}

	$('a').tooltip();

}

function get_items(since) {
	var url = '/payment_methods.json';
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
			$('#status').html('Displaying ' + items.length + ' payment methods');
			update();

		    $("#payment_methods").show().tablesorter({ 
		        textExtraction: function(node) { 
		            return node.innerHTML.replace(/<\/?[^>]+>/gi, '').replace(',', '');
		        },
		        sortList: [[3,1]]
		    });	

		}
	});
}

function search(query) {

	if (query.length == 0) {
		update();
		$('#payment_methods').trigger("update");
		$('#payment_methods').trigger("sorton",[[[3,1]]]);
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
		$('#payment_methods').trigger("sorton",[[[3,1]]]);
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
		$('#modal #transactions').html('<i class="icon-spinner icon-spin icon-large"></i>');
		$('#modal').modal({'show': true });

		$.getJSON('payment_method/' + selector + '/transactions.json', function(data) {
			if (data.length == 0) return;
			var html = '<table class="table"><thead><tr><th>Created At</th><th>Transaction Type</th><th>Amount</th><th>Succeeded</th></tr></thead><tbody>';
			for (var i = data.length - 1; i >= 0; i--) {
				var item = data[i];
				html += '<tr>';
				html += '<td>' + moment(item.created_at).format('YYYY-MM-DD') + '</td>';
				html += '<td>' + item.transaction_type + '</td>';
				html += '<td>' + (item.hasOwnProperty('amount') ? item.amount / 100 : 'N/A') + '</td>';
				html += '<td>' + item.succeeded + '</td>';
				html += '</tr>';
				//html += '<pre>' + data[i].xml.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre>';
			}
			html += '</tbody></table>';
			$('#modal #transactions').html(html);
		});

	});

	$('.search-query').keypress(function() {
		search($(this).val());
	});

	$('.search-query').blur(function() {
		search($(this).val());
	});	

});
</script>

<div class="modal hide fade" id="modal" style="width: 800px; margin-left: -400px">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4></h4>
	</div>
	<div class="modal-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#xml" data-toggle="tab">XML</a>
			</li>
			<li>
				<a href="#transactions" data-toggle="tab">Transactions</a>
			</li>
		</ul>
        <div class="tab-content">
			<div class="tab-pane active" id="xml"></div>
			<div class="tab-pane" id="transactions"></div>
		</div>
	</div>
</div>