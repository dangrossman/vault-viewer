<h1>Payment Gateways</h1>

<div class="navbar">
	<div class="navbar-inner">
		<div class="container">

			<form class="navbar-search pull-left">
			  	<input type="text" class="search-query" placeholder="Filter">
			</form>

			<ul class="nav pull-right">
				<li>
					<p class="navbar-text" id="status">
						<i class="icon-spinner icon-spin icon-large"></i> &nbsp; Loaded <span>0</span> payment gateways...
					</p>
				</li>
			</ul>

		</div>
	</div>
</div>

<table class="table" id="gateways" style="display: none">
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

	$('#gateways tbody').html('');
	for (var i = 0; i < list.length; i++) {
		var gw = list[i];
		var html = '<tr>';
		html += '<td>';
		html += '<a href="#' + gw.token + '" title="Redact" class="btn btn-small btn-danger redact"><i class="icon-remove"></i></a> ';
		html += '<a href="#' + gw.token + '" title="View XML" class="btn btn-small zoom"><i class="icon-zoom-in"></i></a>';
		html += '</td>';
		html += '<td>' + gw.name + '</td>';
		html += '<td>' + gw.gateway_type + '</td>';
		html += '<td>' + moment(gw.created_at).format('YYYY-MM-DD') + '</td>';
		html += '<td>' + moment(gw.updated_at).format('YYYY-MM-DD') + '</td>';
		html += '<td>' + gw.state + '</td>';
		html += '<td style="width: 100%">' + gw.token + '</td>';
		html += '</tr>';
		$('#gateways tbody').append(html);
	}

	$('a').tooltip();

}

function get_items(since) {
	var url = '/gateways.json';
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
			$('#status').html('Displaying ' + items.length + ' payment gateways');
			update();

		    $("#gateways").show().tablesorter({ 
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
		$('#gateways').trigger("update");
		$('#gateways').trigger("sorton",[[[3,1]]]);
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
		$('#gateways').trigger("update");
		$('#gateways').trigger("sorton",[[[3,1]]]);
	}

}

$(document).ready(function() {

	get_items();

	$('.search-query').keypress(function() {
		search($(this).val());
	});

	$('.search-query').blur(function() {
		search($(this).val());
	});

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

	$(document).on('click', 'a.redact', function() {

		var selector = $(this).attr('href').replace('#', '');

		var sure = confirm('Are you sure you want to redact this payment gateway?');
		if (sure) {
			$.getJSON('/gateway/' + selector + '/redact.json', function(data) {
				window.location = '/gateways';
			});
		}

	});

});
</script>