(function() {
	var device;
	var currentConnection;
	var notifs = [];
	// var applicationSid = "AP3219d6e242854380b4fa67e6cb7e2305";
	var remotePhoneNumber = "";

	var defaultNotifOpts = {
		"delay": 3000
	};
	var longNotifOpts = {
		"delay": 9000000
	};
	var dialerCallTimeout = (1000) * 1;
	var callerId = null;
	var mainCallerId = null;
	var bMute = false;
	var currentCallSid = null;
	var numberCallFrom = null;
	let flagAboutAnswer = true;

	const createCard = (id,target,buttonName,body)=>{
		let string = '<div class="card card-in-modal"><div class="card-header" '
		if (id){
			string += 'id="'+id+'"'
		}
		string += '><h5 class="mb-0"><button class="btn btn-link leads__button" type="button" data-toggle="collapse" '
		if (target){
			string += 'data-target="#'+target+'" aria-expanded="false" aria-controls="collapseOne">'
		}
		string += buttonName+'</button></h5></div><div id="'
		string += target+'" class="collapse" aria-labelledby="headingOne" data-parent="#accordionTables"><div class="card-body">'
		string += body+' </div></div></div>'
		return string
	}

	const createTable = (head,body,bodyElements)=>{
		let string = '<div class="table-responsive mt-3"><table class="table table-bordered" ><thead><tr>'
		if (head.length){
			for (let i in head){
				string += '<th>'+head[i]+'</th>'
			}
		}
		string += '</tr></thead><tbody>'
		if (body.length){
			for (let i in body){
				string += '<tr>'
				for (let j in bodyElements){
					string += '<td>'
					string += body[i][bodyElements[j]] ? body[i][bodyElements[j]] : 'N/A'
					string += '</td>';
				}
				string += '</tr>'
			}
		}
		string += '</tbody></table></div>'
		return string
	}

	const get_leads_table_data = (leads,total) => {
		if (leads.length){
			let head = ['#','Status','Customer','Brand','Brand Segment','Category','Color','Size']
			let bodyElements = ['id','status_name','customer_name','brand_name','brand_segment','cat_title','color','size']
			let table = createTable(head,leads,bodyElements)
			return createCard('leads__card__id','leads__target','Leads <span>('+total+')</span>	',table)
		}
		return ''
	}

	const get_orders_table_data = (orders,total) => {
		if (orders.length){
			let head = ['Id','Date','Site Name','Brands','Order Status']
			let bodyElements = ['id','order_date','storeWebsite','brand_name_list','status']
			let table = createTable(head,orders,bodyElements)
			return createCard('orders__card__id','orders__target','Orders <span>('+total+')</span>	',table)
		}
		return ''
	}

	const get_exchanges_return_table_data = (data,total) => {
		if (data.length){
			let head = ['Id','Customer Name','Product Name','Type','Refund amount',
				'Reason for refund','Status','Pickuo Address','Remarks','Created At']
			let bodyElements = ['id','customer_name','name','type','refund_amount',
			'reason_for_refund','status_name','pickup_address','remarks','created_at']
			let table = createTable(head,data,bodyElements)
			return createCard('exchanges_return__card__id','exchanges_return__target','return/exchange <span>('+total+')</span>	',table)
		}
		return ''
	}

	function cleanup() {
		showError("Call ended");
		bMute = false;
	}

	function loadTwilioDevice(token,agent) {
		const $confirmModal = $('#receive-call-popup');

		console.log("Token : "+token);
		console.log("Agent : "+agent);
		device = new Twilio.Device(token, {debug: true, allowIncomingWhileBusy: true, audioConstraints: {
			mandatory: { 
				googAutoGainControl: false 
			} 
		} });
		// Twilio.Device.setup(token, {debug: true});
		device.on('ready', function () {
			$("*[data-twilio-call]").each(function () {
				var number = $(this).text();
				if ($(this).is(":input")) {
					number = $(this).val();
				}
				var context = $(this).attr("data-context");
				var id = $(this).attr("data-id");

				var call = $("<button class='btn btn-primary' type='button' id='twillio_call_button'>Call</button>");
				call.click(function () {
					var numberToCall = number;
					if (!numberToCall.startsWith("+")) {
						numberToCall = "+" + number;
					}
					callNumber(numberToCall, context, id);
				});
				call.insertAfter(this);
			});

			$(document).on('change', '.call-twilio', function () {

				var id = $(this).data('id');
				var numberToCall = $(this).data('phone');
				var context = $(this).data('context');
				var numberCallFrom = $(this).children("option:selected").val();
				var auth_id = $(this).data('auth-id');
				$('#show' + id).hide();
				console.log(id);
				console.log(numberToCall);
				console.log(context);
				console.log(numberCallFrom);
				console.log(auth_id);

				if (!numberToCall.toString().startsWith("+")) {
					numberToCall = "+" + $(this).data('phone').toString();
				}
				callNumber(numberCallFrom, numberToCall, context, id, auth_id);
			});

			$(document).on('click', '.conference-twilio', function () {
				var id = 1;
				var numberToCall = $('#vendors-conference').val();
				var context = $('#context').val();
				var numberCallFrom = $('#conference-number-selected').children("option:selected").val();
				alert('Conferecen Call Initilizing');
				callConference(numberCallFrom, numberToCall, context, id);
			});
		});

		device.on('error', function (error) {
			console.error("twilio device error ", error);
			showError("Error in Twilio Device");
		});

		device.on('connect', function (conn) {
			console.log("twilio device connected ", conn);
			currentCallSid = conn.parameters.CallSid;
			showNotifTimer("Call with " + remotePhoneNumber);
			currentConnection = conn;
		});

		device.on('disconnect', function (conn) {

			var auth_id = $('.call-twilio ').attr('data-auth-id');

			$.ajax({
				url: '/twilio/change_agent_status',
				type: 'POST',
				dataType: 'json',
				data: {
					_token: "{{ csrf_token() }}",
					authid : auth_id,
					status: 0,
				},
			})

			cleanup();
		});

		device.on('incoming', function (conn) {

			console.log("------------incoming------------");

			$.getJSON("/twilio/getLeadByNumber?number=" + encodeURIComponent(conn.parameters.From), function (data) {

				const $buttonForAnswer = $('.call__answer'),
					$buttonForCancelledCall = $('.call__canceled'),
					$accordionTables = $('#accordionTables');

				if (data.found) {
					let information = data.data
					let name = information.customer.name;
					let number = information.customer.phone;
					let email = information.customer.email ;
					let accordion_data = '';
					if (information.leads_total){
						accordion_data = get_leads_table_data(information.leads,information.leads_total)
					}
					if (information.orders_total){
						accordion_data += get_orders_table_data(information.orders,information.orders_total)
					}
					if (information.exchanges_return_total){
						accordion_data += get_exchanges_return_table_data(information.exchanges_return,information.exchanges_return_total)
					}
					let string = "Name :<span style='color: #2f2fe7'> " + name + " </span><br>Phone :<span style='color: #3939e2'>" + conn.parameters.From + "</span>"
					if (email){
						string += "<br>Email :<span style='color: #3939e2'>"+email+"</span>"
					}
					$('#receive-call-popup .modal-body').html(string)
					$('.call__to').html(conn.customParameters.get('phone'))
					$accordionTables.html(accordion_data)

				} else {
					$('#receive-call-popup .modal-body').html("Incoming call from: <span style='color:#2727b8;'>" + number + "</span> would you like to answer call?")
					$('.call__to').html(conn.customParameters.get('phone'))
				}

				$confirmModal.modal('show');
				$confirmModal.modal({
					backdrop: 'static',
					keyboard: false
				});

				$buttonForAnswer.off().one('click', function () {

					$confirmModal.modal('hide');

					sendTwilioEvents({
						status: 'answered',
						From:  conn.parameters.From,
						To: conn.customParameters.get('phone'),
						CallSid: conn.parameters.CallSid,
						AccountSid: conn.parameters.AccountSid
					})
					flagAboutAnswer = false;

					var auth_id = $('.call-twilio ').attr('data-auth-id');

					$.ajax({
						url: '/twilio/change_agent_call_status',
						type: 'POST',
						dataType: 'json',
						data: {
							_token: "{{ csrf_token() }}",
							authid : auth_id,
							status: 1,
						},
					})

					conn.accept();
				});

				$buttonForCancelledCall.off().one('click', function () {

					$confirmModal.modal('hide');
					sendTwilioEvents({
						status: 'busy',
						From:  conn.parameters.From,
						To: conn.customParameters.get('phone'),
						CallSid: conn.parameters.CallSid,
						AccountSid: conn.parameters.AccountSid
					})
					flagAboutAnswer = false

					var auth_id = $('.call-twilio ').attr('data-auth-id');

					$.ajax({
						url: '/twilio/change_agent_status',
						type: 'POST',
						dataType: 'json',
						data: {
							_token: "{{ csrf_token() }}",
							authid : auth_id,
							status: 0,
						},
					})
					conn.reject();
				});
			});
		});

		device.on('offline', function (conn) {

		});

		device.on('cancel', function (conn) {

			var agent_id = 'client:'+agent;

			if(agent_id == conn.parameters.To)
			{
				sendTwilioEvents({
					status: 'no-answer',
					From:  conn.parameters.From,
					To: conn.customParameters.get('phone'),
					CallSid: conn.parameters.CallSid,
					AccountSid: conn.parameters.AccountSid
				});
				$confirmModal.modal('hide');
				showError("Call Cancelled");

				var auth_id = $('.call-twilio ').attr('data-auth-id');

				$.ajax({
					url: '/twilio/change_agent_status',
					type: 'POST',
					dataType: 'json',
					data: {
						_token: "{{ csrf_token() }}",
						authid : auth_id,
						status: 0,
					},
				})
			}
		});
	}

	function initializeTwilio() {
		$.getJSON("/twilio/token", function (result) {
			if (!result.empty) {
				console.log("Received Twilio Token - agent " + result.agent);
				for (var i in result.twilio_tokens) if (result.twilio_tokens.hasOwnProperty(i)) {
					loadTwilioDevice(result.twilio_tokens[i],result.agent);
				}
			} else {
				console.log("Not Twilio Token - agent or auth user");
			}
		});
	}

	function callerHangup() {
		if (currentConnection) currentConnection.disconnect();

		var auth_id = $('.call-twilio ').attr('data-auth-id');

		$.ajax({
			url: '/twilio/change_agent_status',
			type: 'POST',
			dataType: 'json',
			data: {
				_token: "{{ csrf_token() }}",
				authid : auth_id,
				status: 0,
			},
		})

		device.disconnectAll();
	}

	function callerMute(number) {
		var conn = device.activeConnection();
		var el = $(".muter");
		Mute = !bMute;

		if (bMute) {
			conn.mute(true);
			el.text("Unmute");
		} else {
			conn.mute(false);
			el.text("Mute");
		}
	}

	function callNumber(numberCallFrom, number, context, id, auth_id) {
		var conn = device.activeConnection();
		if (conn) {
			alert("Please hangup current call before dialing new number..");
			return;
		}

		remotePhoneNumber = number;
		$.notifyClose();
		var callingText = "<h5>Calling " + remotePhoneNumber + "</h5>";
		callingText += "<br/><button class='btn btn-danger' onclick='callerHangup()'>Hangup</button>";

		showWarning(callingText, longNotifOpts);
		var params = {"CallNumber": numberCallFrom, "PhoneNumber": number, "context": context, "internalId": id, "AuthId": auth_id};
		console.log("Dialer_StartCall call params", params);
		device.connect(params);
	}

	function closeNotifs(dontClose) {
		if (notifs.length > 0 && !dontClose) {
			notifs.forEach(function (notif) {
				notif.close();
			});
		}

	}

	function showNotif(settings, opts, dontClose) {
		closeNotifs(dontClose);
		opts['delay'] = opts['delay'] || 99999999;
		var notif = $.notify(settings, opts);
		notifs.push(notif);
		return notif;
	}

	function showWarning(message, opts) {
		opts = opts || defaultNotifOpts;
		opts['type'] = "warning";
		showNotif({message: message}, opts);
	}

	function showSuccess(message, opts) {
		opts = opts || defaultNotifOpts;
		opts['type'] = "success";
		showNotif({message: message}, opts);
	}

	function showError(message, opts) {
		opts = opts || defaultNotifOpts;
		opts['type'] = "danger";
		showNotif({message: message}, opts);
	}

	function callConference(numberCallFrom, numbers, context, id) {
		var conn = device.activeConnection();
		if (conn) {
			alert("Please hangup current call before dialing new number..");
			return;
		}

		$.notifyClose();

		$.ajax({
			url: '/api/twilio-conference',
			type: 'POST',
			dataType: 'json',
			data: {
				_token: "{{ csrf_token() }}",
				numbersFrom: numberCallFrom,
				numbers: numbers,
				context: context,
				id: id,
			},
		}).done(function (response) {
			for (var i = 1; i < response.length; i++) {
				var callingText = "<h5>Calling " + response[i]['number'] + "</h5>";
				callingText += "<br/><button class='btn btn-danger' onclick='callerHangup(" + response[i]['number'] + ")'>Hangup</button>";
				showWarning(callingText, longNotifOpts);
			}

		})
			.fail(function () {
				console.log("error");
			});
	}

	function callerConferenceHangup(number) {
		$.ajax({
			url: '/api/twilio-conference',
			type: 'POST',
			dataType: 'json',
			data: {
				_token: "{{ csrf_token() }}",
				sid: number,
			},
		}).done(function (response) {
			console.log(response.length)
			showError("Caller Removed From Conferece");
		})
			.fail(function () {
				console.log("error");
			});
	}

	function showNotifTimer(message) {
		var timerInterval = 1000;
		var totalSeconds = 0;
		var iTime = new Date;
		var myNotif = showNotif({
			message: ""
		}, {
			//onClosed: sipHangUp,
			type: "info",
			delay: 9999999
		});
		var main = $("<div></div>");
		var center = $("<center class='c2c-in-call'></center>");
		center.appendTo(main);
		var content = $("<div class='content'></div>").appendTo(center);
		$("<span><h2>" + message + "</h2></span>").appendTo(content);
		//timer
		$("<span><h1 class='timer'></h1></span>").appendTo(content);
		$("<hr></hr>").appendTo(center);
		//call control
		var buttons = $("<ul style='list-style: none !important; ' class='buttons'><h4>Call Control</h4></ul>").appendTo(center);
		$("<li><button class='btn btn-danger hangup' onclick='callerHangup()'>Hangup</button></li>").appendTo(buttons);
		$("<li><button class='btn btn-primary muter' onclick='callerMute()'></button></li>").appendTo(buttons);

		function calculateTime() {
			++totalSeconds;
			return pad(parseInt(totalSeconds / 60)) + ":" + pad(totalSeconds % 60);
		}

		function pad(val) {
			var valString = val + "";
			if (valString.length < 2) {
				return "0" + valString;
			} else {
				return valString;
			}
		}

		function onInterval() {
			var newTime = calculateTime();
			var muteText = "", holdText = "";
			if (bMute) {
				muteText = "Unmute";
			} else {
				muteText = "Mute";
			}
			center.find(".timer").text(newTime);
			center.find(".muter").text(muteText);
			center.find(".muter").click(callerMute);
			center.find(".hangup").click(callerHangup);

			myNotif.update({
				'message': main.html(),
				'type': 'info'
			});
		}

		callInterval = setInterval(onInterval, 1000);
		return myNotif;
	}

	window['initializeTwilio'] = initializeTwilio;
	window['callerHangup'] = callerHangup;
	window['callerMute'] = callerMute;
	window['showNotifTimer'] = showNotifTimer;

	const sendTwilioEvents = (event) => {
		$.ajax({
			url: '/twilio/eventsFromFront',
			type: 'POST',
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN':$('meta[name=csrf-token]').val()
			},
			data: event,
		})
	}
})();
