<!DOCTYPE html>
<html lang="en">
	<head>

		<title>Spreedly UI</title>
		<link rel="shortcut icon" href="/favicon.ico" />

		<link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="/css/font-awesome.css" rel="stylesheet" />
		<link href="/css/spreedlyui.css" rel="stylesheet" type="text/css" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="/css/font-awesome-ie7.css">
		<![endif]-->

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
					<a href="#" class="brand">Spreedly UI</a>
					<ul class="nav">
						<li><a href="/gateways"><i class="icon-shopping-cart"></i> Payment Gateways</a></li>
						<li><a href="/payment_methods"><i class="icon-credit-card"></i> Payment Methods</a></li>
					</ul>
					<?php if (isset($_SESSION['login'])): ?>
					<ul class="nav pull-right">
						<li><a href="/logout"><i class="icon-power-off"></i> Log out</a></li>
					</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="container" style="margin: 40px auto 20px auto">
			<div class="row">
				<div class="span12">
					<?php echo $view_content; ?>
				</div>
			</div>
		</div>

		<div id="footer">
			<div class="container">
				<div class="row">
					<div class="span12">
						<b>Spreedly UI</b> for <a href="https://spreedly.com/">Spreedly Core</a> 
						was created by <a href="http://www.dangrossman.info">Dan Grossman</a> 
						while building <a href="http://www.improvely.com">Improvely</a>. 
						Get the latest code from  
						<a href="https://github.com/dangrossman/spreedly-ui">GitHub</a>. 
					</div>
				</div>
			</div>
		</div>

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
				</ul>
		        <div class="tab-content">
					<div class="tab-pane active" id="xml"></div>
				</div>
			</div>
		</div>

	</body>
</html>