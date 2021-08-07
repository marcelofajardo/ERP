<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/png" href="/favicon/@yield ('favicon')" />
    <title>@yield ('title', 'ERP') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">
	<link rel="stylesheet" href="/css/magnific-popup.css">
	<link rel="stylesheet" href="/css/easy-autocomplete.min.css">
	<link rel="stylesheet" href="/css/easy-autocomplete.themes.min.css">
	<link rel="stylesheet" href="/css/web-message.css">

	<style type="text/css">
		.dis-show{
			display: inline-block; !improtant;
		}
		.dis-none{
			display: none;
		}
		.unapproved-msg {
			border: 1px solid red;
		}
		.easy-autocomplete {
			width: 100% !important;
		}
		.easy-autocomplete .input-message {
			width: 100%;
		}
	</style>
</head>
<body>
	
	<div class="container-fluid" id="main-container">
		<div class="row h-100">
			<div class="col-12 col-sm-3 col-md-3 d-flex flex-column" id="chat-list-area" style="position:relative;">
				<!-- Navbar -->
				<div class="row d-flex flex-row align-items-center p-2" id="navbar">
					<img alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px; cursor:pointer;" id="display-pic">
					<div class="text-white font-weight-bold" id="username"></div>
				</div>
				<!-- Chat List -->
				<div class="row" id="chat-list" style="overflow-y:scroll;height: 550px;"></div>
			</div>

			<!-- Message Area -->
			<div class="d-none d-sm-flex flex-column col-12 col-sm-8 col-md-9 p-0 h-100" id="message-area">
				<div class="w-100 h-100 overlay"></div>

				<!-- Navbar -->
				<div class="row d-flex flex-row align-items-center p-2 m-0 w-100" id="navbar">
					<div class="d-block d-sm-none">
						<i class="fas fa-arrow-left p-2 mr-2 text-white" style="font-size: 1.5rem; cursor: pointer;" onclick="showChatList()"></i>
					</div>
					<a href="#"><img src="https://via.placeholder.com/400x400" alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px;" id="pic"></a>
					<div class="d-flex flex-column">
						<div class="text-white font-weight-bold" id="name"></div>
						<div class="text-white small" id="details"></div>
					</div>
					<div class="d-flex flex-row align-items-center ml-auto">
						<!-- <a href="#"><i class="fas fa-search mx-3 text-white d-none d-md-block"></i></a> -->
						<a id="attach-files-user" target="_blank" href="#"><i class="fas fa-paperclip mx-3 text-white d-none d-md-block"></i></a>
						<!-- <a href="#"><i class="fas fa-ellipsis-v mr-2 mx-sm-3 text-white"></i></a> -->
					</div>
				</div>

				<!-- Messages -->
				<div class="d-flex flex-column" id="messages"></div>

				<!-- Input -->
				<div class="d-none justify-self-end align-items-center flex-row" id="input-area">
					<a href="#"><i class="fa fa-history text-muted px-3" style="font-size:1.5rem;"></i></a>
					<input type="text" name="message" id="input" placeholder="Type a message" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm input-message">
					<i class="fas fa-paper-plane text-muted px-3" style="cursor:pointer;" onclick="sendMessage()"></i>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var user  = JSON.parse('<?php echo addslashes(json_encode($jsonUser)); ?>');
		var contactList = JSON.parse('<?php echo addslashes(json_encode($jsonCustomer)) ?>');
		var messages = JSON.parse('<?php echo addslashes(json_encode($jsonMessage)) ?>');
		var autoSuggest = JSON.parse('<?php echo addslashes(json_encode($jsonAutoSuggest)) ?>');
	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
	<script src="/js/jquery.magnific-popup.min.js"></script>
	<script src="/js/jquery.easy-autocomplete.min.js"></script>
	<script src="/js/web-message/datastore.js"></script>
	<script src="/js/web-message/date-utils.js"></script>
	<script src="/js/web-message/script.js"></script>
</body>

</html>