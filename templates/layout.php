<!DOCTYPE html>
<html lang="en">
	<head>

		<title>Spreedly Dashboard</title>
		<link rel="shortcut icon" href="/favicon.ico" />

		<link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="/css/font-awesome.css" rel="stylesheet" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="/css/font-awesome-ie7.css">
		<![endif]-->

		<style type="text/css">
			h1 {
				font-size: 24px;
			}
			.table td, .table th {
				white-space: nowrap;
				padding: 8px 12px;
				vertical-align: middle;
			}
			.table td {
				font-size: 12px;
			}
			#status {
				font-size: 14px;
			}
			thead th {
				cursor: pointer;
				background: #eee;
			}
			thead th.first {
				background: #fff;
			}
			thead .headerSortUp:after {
				display: inline-block;
				width: 0;
				height: 0;
				vertical-align: middle;
				border-top: 4px solid #000000;
				border-right: 4px solid transparent;
				border-left: 4px solid transparent;
				content: "";
				opacity: 0.5;
				filter: alpha(opacity=50);
				margin-left: 5px;
			}
			thead .headerSortDown:after {
				display: inline-block;
				width: 0;
				height: 0;
				vertical-align: middle;
				border-bottom: 4px solid #000000;
				border-right: 4px solid transparent;
				border-left: 4px solid transparent;
				content: "";
				opacity: 0.5;
				filter: alpha(opacity=50);
				margin-left: 5px;
			}
			#nav .navbar-inner {
				background: #1e95f9;
			}
			#nav .nav li a:link, #nav .nav li a:visited {
				color: #fff;
				border-right: 1px solid #067bde;
				border-left: 1px solid #50acfa;
				font-weight: bold;
			}
			#nav .nav li a:hover {
				color: #e1f3fd;
				background: #056ec5;
				border-left: 1px solid #056ec5;
			}
			#nav a.brand {
				color: #fff;
				font-style: italic;
				font-weight: bold;
			}
			#nav i {
				padding-right: 3px;
			}
		</style>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="/js/jquery.tablesorter.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/moment.min.js"></script>
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	</head>
	<body>

		<div class="navbar navbar-inverse navbar-fixed-top" id="nav">
			<div class="navbar-inner">
				<div class="container">
					<a href="#" class="brand">Spreedly Dashboard</a>
					<ul class="nav">
						<li><a href="/gateways"><i class="icon-shopping-cart"></i> Payment Gateways</a></li>
						<li><a href="/payment_methods"><i class="icon-credit-card"></i> Payment Methods</a></li>
					</ul>
					<ul class="nav pull-right">
						<li><a href="/logout"><i class="icon-power-off"></i> Log out</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container" style="margin-top: 40px">
			<div class="row">
				<div class="span12">
					<?php echo $view_content; ?>
				</div>
			</div>
		</div>

	</body>
</html>