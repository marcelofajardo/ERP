let getById = (id, parent) => parent ? parent.getElementById(id) : getById(id, document);
let getByClass = (className, parent) => parent ? parent.getElementsByClassName(className) : getByClass(className, document);

const DOM =  {
	chatListArea: getById("chat-list-area"),
	messageArea: getById("message-area"),
	inputArea: getById("input-area"),
	chatList: getById("chat-list"),
	messages: getById("messages"),
	chatListItem: getByClass("chat-list-item"),
	messageAreaName: getById("name", this.messageArea),
	messageAreaPic: getById("pic", this.messageArea),
	messageAreaNavbar: getById("navbar", this.messageArea),
	messageAreaDetails: getById("details", this.messageAreaNavbar),
	messageAreaOverlay: getByClass("overlay", this.messageArea)[0],
	messageInput: getById("input"),
	profileSettings: getById("profile-settings"),
	profilePic: getById("profile-pic"),
	profilePicInput: getById("profile-pic-input"),
	inputName: getById("input-name"),
	username: getById("username"),
	displayPic: getById("display-pic"),
};

let mClassList = (element) => {
	return {
		add: (className) => {
			element.classList.add(className);
			return mClassList(element);
		},
		remove: (className) => {
			element.classList.remove(className);
			return mClassList(element);
		},
		contains: (className, callback) => {
			if (element.classList.contains(className))
				callback(mClassList(element));
		}
	};
};

// 'areaSwapped' is used to keep track of the swapping
// of the main area between chatListArea and messageArea
// in mobile-view
let areaSwapped = false;

// 'chat' is used to store the current chat
// which is being opened in the message area
let chat = null;

// this will contain all the chats that is to be viewed
// in the chatListArea
let chatList = [];

// this will be used to store the date of the last message
// in the message area
let lastDate = "";
let firstDate = "";

// check sending request already then do not send another request
let isLoading = false;

// set interval time 
let intervalTime = 5000; 

// 'populateChatList' will generate the chat list
// based on the 'messages' in the datastore
let populateChatList = () => {
	chatList = [];

	// 'present' will keep track of the chats
	// that are already included in chatList
	// in short, 'present' is a Map DS
	let present = {};
	//console.log('contactList',contactList);

	MessageUtils.getMessages()
	.sort((a, b) => mDate(a.time).subtract(b.time))
	.forEach((msg) => {
		let chat = {};
		
		chat.isGroup = msg.recvIsGroup;
		chat.msg = msg;

		if (msg.recvIsGroup) {
			chat.group = groupList.find((group) => (group.id === msg.recvId));
			chat.name = chat.group.name;
		} else {
			chat.contact = contactList.find((contact) => (msg.sender !== user.id) ? (contact.id === msg.sender) : (contact.id === msg.recvId));
			//console.log('CHAT',chat);
			chat.name = (chat.contact) ? chat.contact.name : "";
		}

		chat.unread = (msg.sender !== user.id && msg.status <= 2) ? 1: 0;

		if (present[chat.name] !== undefined) {
			chatList[present[chat.name]].msg = msg;
			chatList[present[chat.name]].unread += chat.unread;
		} else {
			present[chat.name] = chatList.length;
			chatList.push(chat);
		}
	});
};

let viewChatList = () => {
	DOM.chatList.innerHTML = "";
	chatList
	.sort((a, b) => mDate(b.msg.time).subtract(a.msg.time))
	.forEach((elem, index) => {
		let statusClass = elem.msg.status <= 5 ? "far" : "fas";
		let unreadClass = elem.unread ? "unread" : "";
		let unApproved  = (elem.msg.approved != 1);
		DOM.chatList.innerHTML += `
		<div class="chat-list-item d-flex flex-row w-100 p-2 border-bottom ${unreadClass}" id="user-list-${elem.msg.recvId}">
			<div class="w-50" onclick="generateMessageArea(this, ${index})">
				<div class="name">${elem.name}</div>
				<div class="small last-message">${elem.isGroup ? contactList.find(contact => contact.id === elem.msg.sender).number + ": " : ""}${elem.msg.sender === user.id ? "<i class=\"" + statusClass + " fa-check-circle mr-1\"></i>" : ""}
					${(elem.msg.has_media == true) ? '<i class="fas fa-file-image"></i> Media' : elem.msg.body}
				</div>
			</div>
			<div class="flex-grow-1 text-right">
				<div class="small time">${mDate(elem.msg.time).chatListFormat()}</div>
				<div data-i="${elem.msg.recvId}" class="badge badge-danger badge-pill small block-from-list" onClick="blockEvent('`+elem.msg.recvId+`');">Block</div>
				${elem.unread ? "<div class=\"badge badge-success badge-pill small\" id=\"unread-count\">" + elem.unread + "</div>" : ""}
			</div>
		</div>
		`;
	});
};

let generateChatList = () => {
	populateChatList();
	viewChatList();
};

let addDateToMessageArea = (date,append) => {
	var body = `
	<div class="mx-auto my-2 bg-primary text-white small py-1 px-2 rounded">
		${date}
	</div>
	`;

	if (append == true) {
		DOM.messages.append($(body)[0]);
	}else {
		DOM.messages.prepend($(body)[0]);
	}

};

let addMediaToMessageArray = (msg,append) => {
	let sendStatus = `<i class="${msg.status <= 5 ? "far" : "fas"} fa-check-circle"></i>`;
	let MediaHtml = '<div class="_1b0ym">'
		msg.media.forEach((media,key) => {
			var classDis = "";
			if(key > 3 ) {
				classDis = "dis-none";
			}

			var dropMenu = ``;
			if(media.product_id > 0 && chat.contact.type == "customer") {
				dropMenu = `
				<div class="options dropup" style="z-index:1000; top:auto;bottom:0;">
					<a class="dropdown-toggle-chat" data-toggle="dropdown"><i class="fas fa-angle-down text-muted px-2"></i></a>
					<div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(-122px, 40px, 0px); top: 0px; left: 0px; z-index:1200">
						<a data-case="send_dimension" data-p="${media.product_id}" data-i="${msg.id}" class="dropdown-item dropdown-item-message" href="javascript:;">Send Dimensions</a>
						<a data-case="send_detail" data-p="${media.product_id}" data-i="${msg.id}" class="dropdown-item dropdown-item-message" href="javascript:;">Send Detail</a>
					</div>
				</div>`;
			}

			MediaHtml += 
				`<div class="_36Yqt `+classDis+`">
				    <div class="_1b8RS">
				        <div class="_3SaET xCzoD">
				        	${(key == 3 && msg.media.length > 4) ? '<div class="_3Ms7M">' : ''}
				            <div>
				                <div class="_3mdDl ${(key == 3 && msg.media.length > 4) ? '_1cdQD' : ''}" style="width: 165px; height: 168px;">
				                   <img data-src="${media.url}" data-type="${media.type}" src="${(media.type == "pdf") ? "/images/pdf_icon.png" : media.url}" class="_18vxA" style="height: 100%;">
				                   <div class="_3TrQs"></div>
				                </div>
				            </div>
				            ${(dropMenu != '') ? dropMenu : ''}
				            ${(key == 3 && msg.media.length > 4) ? '<span class="_1XSxP _1drsQ">+'+(msg.media.length - 4)+'</span></div>' : ''}
				         </div>
				      </div>
				</div>`;
		});
		MediaHtml += '</div>';
		let unApprovedcls = (msg.approved != 1 && !msg.isSender) ?  "unapproved-msg" : "";
		var body =  
			`<div data-cn="${msg.id}" class="align-self-${msg.isSender ? "end self" : "start"} p-1 my-1 mx-3 rounded bg-white shadow-sm message-item ${msg.isLast == true ? 'last-block-message' : ''} ${unApprovedcls}">
				<div class="options dropdown" style="z-index:1200">
					<a class="dropdown-toggle-chat" data-toggle="dropdown"><i class="fas fa-angle-down text-muted px-2"></i></a>
					<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-122px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
						<a data-case="delete" data-i="${msg.id}" class="dropdown-item dropdown-item-message" href="javascript:;">Delete</a>
						${(msg.approved != 1 && (user.is_hod_crm || user.is_admin) && !msg.isSender) ? '<a data-case="approve" data-i="'+msg.id+'" class="dropdown-item dropdown-item-message" href="javascript:;">Approve</a>' : '' } 
						${(msg.approved) ? '<a data-case="resend" data-i="'+msg.id+'" class="dropdown-item dropdown-item-message" href="javascript:;">Resend</a>' : '' }
					</div>
				</div>
				<div class="d-flex flex-row media-row">
					`+MediaHtml+`
				</div>
			</div>`;

		if(append == true) {
			DOM.messages.append($(body)[0])
		}else{
			DOM.messages.prepend($(body)[0])
		};

}

let addMessageToMessageArea = (msg,append) => {
	let msgDate = mDate(msg.time).getDate();
	chat.lastMsg = msg.id;
	if(append == true && msg.isFirst == true && msgDate != firstDate) {
		firstDate = msgDate;
		addDateToMessageArea(msgDate,append);
	}else if (lastDate != msgDate && append != true) {
		lastDate = msgDate;
		addDateToMessageArea(msgDate,append);
	}

	/*let htmlForGroup = `
	<div class="small font-weight-bold text-primary top-c-t-m">
		${contactList.find(contact => contact.id === msg.recvId).number}
	</div>
	`;*/

	let sendStatus = `<i class="${msg.status <= 5 ? "far" : "fas"} fa-check-circle"></i>`;
	let unApprovedcls = (msg.approved != 1 && !msg.isSender) ?  "unapproved-msg" : "";
	if(msg.has_media == false || msg.body != '') {
		var body = `<div data-cn="${msg.id}" class="align-self-${msg.isSender ? "end self" : "start"} p-1 my-1 mx-3 rounded bg-white shadow-sm message-item ${msg.isLast == true ? 'last-block-message' : ''} ${unApprovedcls}">
			<div class="options dropdown" style="z-index:1200">
				<a class="dropdown-toggle-chat" data-toggle="dropdown"><i class="fas fa-angle-down text-muted px-2"></i></a>
				<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-122px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
					<a data-case="delete" data-i="${msg.id}" class="dropdown-item dropdown-item-message" href="javascript:;">Delete</a>
					${(msg.approved != 1 && (user.is_hod_crm || user.is_admin) && !msg.isSender) ? '<a data-case="approve" data-i="'+msg.id+'" class="dropdown-item dropdown-item-message" href="javascript:;">Approve</a>' : '' } 
					${(msg.approved) ? '<a data-case="resend" data-i="'+msg.id+'" class="dropdown-item dropdown-item-message" href="javascript:;">Resend</a>' : '' }
					${(msg.status == 0) ? '<a data-case="mark_as_read" data-i="'+msg.id+'" class="dropdown-item dropdown-item-message" href="javascript:;">Mark as Read</a>' : ''}
					${(msg.status == 0 || msg.status == 5) ? '<a data-case="mark_as_replied" data-i="'+msg.id+'" class="dropdown-item dropdown-item-message" href="javascript:;">Mark as Replied</a>' : ''}
				</div>
			</div>	
			${chat.isGroup ? htmlForGroup : ""}
			<div class="d-flex flex-row">
				<div class="body m-1 mr-2">${msg.body}</div>
				<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted" style="width:75px;">
					${mDate(msg.time).getTime()}
					${(msg.sender === user.id) ? sendStatus : ""}
				</div>
			</div>
		</div>`;
		if(append == true) {
			DOM.messages.append($(body)[0]);
		}else{
			DOM.messages.prepend($(body)[0]);
		}
	}


	if(msg.has_media == true && msg.media.length > 0) {
		addMediaToMessageArray(msg,append)
	}

	if(append == true || chat.currentPage == 1) {
		DOM.messages.scrollTo(0, DOM.messages.scrollHeight);
	}

};

let generateMessageArea = (elem, chatIndex) => {
	
	lastDate = "";
	startDate = "";

	chatList[chatIndex].currentPage = 1;
	chatList[chatIndex].lastPageWas = 1;
	chat = chatList[chatIndex];

	mClassList(DOM.inputArea).contains("d-none", (elem) => elem.remove("d-none").add("d-flex"));
	mClassList(DOM.messageAreaOverlay).add("d-none");

	[...DOM.chatListItem].forEach((elem) => mClassList(elem).remove("active"));

	mClassList(elem).contains("unread", () => {
		 MessageUtils.changeStatusById({
			isGroup: chat.isGroup,
			id: chat.isGroup ? chat.group.id : chat.contact.id
		});
		mClassList(elem).remove("unread");
		mClassList(elem.querySelector("#unread-count")).add("d-none");
	});

	if (window.innerWidth <= 575) {
		mClassList(DOM.chatListArea).remove("d-flex").add("d-none");
		mClassList(DOM.messageArea).remove("d-none").add("d-flex");
		areaSwapped = true;
	} else {
		mClassList(elem).add("active");
	}

	DOM.messageAreaName.innerHTML = chat.name;
	DOM.messageAreaPic.src = chat.isGroup ? chat.group.pic : chat.contact.pic;
	
	document.getElementById("attach-files-user").href = "/attachImages/"+chat.contact.type+"/"+chat.contact.real_id+"/1";
	
	DOM.messageAreaDetails.innerHTML = `Created At ${mDate(chat.contact.lastSeen).lastSeenFormat()}`;
	
	DOM.messages.innerHTML = "";

	chat.currentPage = 1;
	sendRequestForMoreMessages(chat , 1);
	
};

let sendRequestForMoreMessages = (chat, pageNo) => {
	
	if((isLoading || chat.lastPageWas == chat.currentPage) &&  chat.currentPage != 1) {
		return false;
	}

	var body = `<svg id="spinner-container" class="spinner-container" viewBox="0 0 44 44">
        <circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle>
	</svg>`;

	DOM.messages.prepend($(body)[0]);

	isLoading = true;
	fetch("/web-message/message-list/"+chat.contact.id+"?" + new URLSearchParams({
	    lastMsg: $(".message-item").first().data("cn"),
	    previous: true
	}), { headers: { "Content-Type": "application/json; charset=utf-8" }})
    .then(res => res.json()) // parse response as JSON (can be res.text() for plain response)
    .then(response => {
    	var spinner = $("#spinner-container");
    	if(spinner.length > 0) {
    		spinner.remove();
		}

    	isLoading = false;
    	if(pageNo == 1) {
    		firstDate = mDate(response.msgs[0].time).getDate();
    		response.msgs[0].isFirst = true;
    	}

        // here you do what you want with response
    	response.msgs
		//.sort((a, b) => mDate(a.time).subtract(b.time))
		.forEach((msg) => addMessageToMessageArea(msg,(msg.isFirst == 0)));
		//fireScrollEvent();
		//chat.lastPageWas = pageNo;
    })
    .catch(err => {
        console.log(err)
        alert("sorry, there are no results for your search")
    });
};

let getNewMessages = () => {
	//isLoading = true;
	fetch("/web-message/status/?" + new URLSearchParams({
	    lastMsg: $(".message-item").last().data("cn"),
	    next: true,
	    ac: (chat) ? chat.contact.id : 0
	}), { headers: { "Content-Type": "application/json; charset=utf-8" }})
    .then(res => res.json()) // parse response as JSON (can be res.text() for plain response)
    .then(response => {
    	contactList = response.data.jsonCustomer;
		messages = response.data.jsonMessage;
		if(chat && chat.contact.id != '') {
			response.data.msgs.forEach((msg) => {
				msg.isFirst = true;	
				addMessageToMessageArea(msg,true);
			});
		}
		generateChatList();
		setTimeout(getNewMessages, intervalTime);
    })
    .catch(err => {
        console.log(err)
        //alert("sorry, there are no results for your search")
        //setTimeout(getNewMessages, intervalTime);
    });
};

let showChatList = () => {
	if (areaSwapped) {
		mClassList(DOM.chatListArea).remove("d-none").add("d-flex");
		mClassList(DOM.messageArea).remove("d-flex").add("d-none");
		areaSwapped = false;
	}
};

let sendMessage = () => {
	let value = DOM.messageInput.value;
	DOM.messageInput.value = "";
	if (value === "") return;
	let msg = {
		sender: user.id,
		body: value,
		time: mDate().toString(),
		status: 1,
		recvId: chat.contact.id,
		recvIsGroup: chat.isGroup,
		isFirst : true
	};
	
	//$("#user-list-"+chat.contact.id).click();
	$.ajax({
		type: 'POST',
	    url: "/web-message/send",
	    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	    data: msg
	}).done(function(response) {
		msg.id = response.message.id;
		addMessageToMessageArea(msg,true);
		MessageUtils.addMessage(msg);
		generateChatList();
	}).fail(function(response) {
	    console.log("Oops, something went wrong request failed :" , msg)
	});
};

let showProfileSettings = () => {
	DOM.profileSettings.style.left = 0;
	DOM.profilePic.src = user.pic;
	DOM.inputName.value = user.name;
};

let hideProfileSettings = () => {
	DOM.profileSettings.style.left = "-110%";
	DOM.username.innerHTML = user.name;
};

window.addEventListener("resize", e => {
	if (window.innerWidth > 575) showChatList();
});


let fireScrollEvent = () => {
	$("#messages").on("scroll",function(e) {
	   if ($(this).scrollTop() <= 0 && $(this).find(".message-item").length > 0){
	   	chat.currentPage++;
	   	sendRequestForMoreMessages(chat)	
   	   }
	});
};

let messageAction = (ele) => {
	
	let action = {
		id : ele.data("i"),
		case : ele.data("case"),
		p : ele.data("p")
	}
	
	$.ajax({
		type: 'POST',
	    url: "/web-message/action",
	    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	    data: action
	}).done(function(response) {
		if(response.code == 200) {
			if(action.case == "delete") {
				ele.closest(".message-item").remove();
			}
			if(action.case == "approve") {
				ele.closest(".message-item").removeClass("unapproved-msg");
			}
		}
	}).fail(function(response) {
	    console.log("Oops, something went wrong request failed :" , response)
	});

}; 

let blockEvent = (id) => {
	let action = {
		id : id,
		case : "block"
	}
	$.ajax({
		type: 'POST',
	    url: "/web-message/user-action",
	    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	    data: action
	}).done(function(response) {
		if(response.code == 200) {
			
		}
	}).fail(function(response) {
	    console.log("Oops, something went wrong request failed :" , response)
	});
};

let init = () => {
	DOM.username.innerHTML = user.name;
	DOM.displayPic.src = user.pic;
	//DOM.profilePic.src = user.pic;
	//DOM.profilePic.addEventListener("click", () => DOM.profilePicInput.click());
	//DOM.profilePicInput.addEventListener("change", () => console.log(DOM.profilePicInput.files[0]));
	//DOM.inputName.addEventListener("blur", (e) => user.name = e.target.value);
	generateChatList();
	fireScrollEvent();
	setTimeout(getNewMessages, intervalTime);

	$(document).on("click",".dropdown-item-message",function(e){
		e.preventDefault();
		messageAction($(this));
	});

	$(document).on("click","._36Yqt",function(e){
		if(e.target.tagName.toLowerCase() !== "a") {
			var images  = $(this).closest(".media-row").find("img");
			var items = [];
				$.each(images,function(k,v){
					var ele = $(v);
					items.push({
						"src" : ele.attr("data-src"),
						"type" : (ele.attr("data-type") == "pdf") ? "iframe" : "image"
					})
				});

			$.magnificPopup.open({
			 	items:items,
			  	gallery: {enabled: true},
		    	type: 'image'
			});
		}
	});

	$(document).on("keypress",".input-message",function(event){
		var keycode = (event.keyCode ? event.keyCode : event.which);
	    if(keycode == '13'){
	        sendMessage();
	    }
	});
	

	$( ".input-message" ).easyAutocomplete({
		data : autoSuggest,
		list: {
			match: {
				enabled: true
			}
		}
	});
};

init();