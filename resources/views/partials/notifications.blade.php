@if(auth()->user()->checkPermission('crm'))
<li class="nav-item dropdown notification-dropdown">
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" aria-haspopup="true"
       aria-expanded="false" v-pre>
        Notifications<span class="caret"></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <li>
            <hr>
        </li>
        @foreach($notifications as $notification)

            <li class="dropdown-item {{ $notification->isread ? 'isread' : '' }}">
                <div class="row">
                    <div class="col-10">
                        <a class="notification-link"
                           href="{{ $notification->sale_id ? route('sales.edit',$notification->sale_id) : route('products.show',$notification->product_id)  }}">
                            <p>{{ $notification->uname }} {{ str_limit($notification->message,50,'...') }}
                                {{$notification->pname ? $notification->pname : $notification->sku }}
                                at {{ Carbon\Carbon::parse($notification->created_at)->format('d-m H:i') }}
                            </p>
                        </a>
                    </div>

                    <div class="col-2">
                        <button class="btn btn-notify" data-id="{{ $notification->id }}"></button>
                        {{-- &#10003 --}}
                    </div>
                </div>
            </li>
            <li class=""></li>

        @endforeach
        <div class="notify-drop-footer text-center">
            <a href="{{ route('notifications') }}">See All</a>
        </div>
    </ul>
</li>
@endif



<script>
    var interval = 1000 * 30;  // 1000 = 1 second
    var notificationShowCount = 15;

    var allUsers = {!! json_encode( \App\Helpers::getUserArray( \App\User::all() ) ) !!};
    var current_userid = '{{ Auth::id() }}';
    var current_username = '{{ Auth::user()->name }}';
    var is_admin = "{{ Auth::user()->hasRole('Admin') }}";

    'use strict';

    var _createClass = function () {
        function defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        return function (Constructor, protoProps, staticProps) {
            if (protoProps) defineProperties(Constructor.prototype, protoProps);
            if (staticProps) defineProperties(Constructor, staticProps);
            return Constructor;
        };
    }();

    function _classCallCheck(instance, Constructor) {
        if (!(instance instanceof Constructor)) {
            throw new TypeError("Cannot call a class as a function");
        }
    }

    // Queue class

    var Queue = function () {
        // Array is used to implement a Queue
        function Queue() {
            _classCallCheck(this, Queue);

            this.items = [];
            this.isFirst = true;
            this.count = 0;
            this.taskcount = 0;
            this.ordercount = 0;
            this.leadcount = 0;
            this.messagecount = 0;
        }

        // Functions to be implemented
        // enqueue function


        _createClass(Queue, [{
            key: 'enqueue',
            value: function enqueue(element) {

                // adding element to the queue
                var i = void 0;
                var itemsLength = this.items.length;

                for (i = 0; i < this.items.length; i++) {

                    if (this.items[i].id === element.id) {
                        break;
                    }

                    if (this.items[i].message === element.message) {
                        this.items.splice(i, 1);
                    }
                }

                if (i === itemsLength) {
                    element['isShown'] = false;
                    this.items.push(element);
                }

                this.notificationCount();
            }

            // dequeue function

        }, {
            key: 'dequeue',
            value: function dequeue() {
                // removing element from the queue
                // returns underflow when called
                // on empty queue
                if (this.isEmpty()) return false;
                // return "Underflow";
                var result = this.items.shift();

                this.notificationCount();
                return result;
            }
        }, {
            key: 'dequeueWithId',
            value: function dequeueWithId(notificationId) {

                if (this.isEmpty()) return false;

                for (var i = 0; i < this.items.length; i++) {

                    if (this.items[i].id === notificationId) {

                        var item = this.items.splice(i, 1);
                        this.count--;

                        switch (item[0].model_type) {
                            case 'App\\Sale':
                                this.ordercount--;

                                break;
                            case 'App\\Task':
                                this.taskcount--;

                                break;
                            case 'App\\SatutoryTask':
                                this.taskcount--;

                                break;
                            case 'App\\Http\\Controllers\\Task':
                                this.taskcount--;

                                break;
                            case 'App\\Instruction':
                                this.taskcount--;

                                break;
                            case 'App\\DeveloperTask':
                                this.taskcount--;

                                break;
                            case 'MasterControl':
                                this.taskcount--;

                                break;
                            case 'User':
                                this.taskcount--;

                                break;
                            case 'App\\Order':
                                this.ordercount--;

                                break;
                            case 'App\\Leads':
                                this.leadcount--;

                                break;
                            case 'order':
                                this.ordercount--;

                                break;
                            case 'leads':
                                this.leadcount--;

                                break;
                        }
                    }
                }

                this.notificationCount();
            }

            // front function

        }, {
            key: 'front',
            value: function front() {
                // returns the Front element of
                // the queue without removing it.
                if (this.isEmpty()) return false;
                // return "No elements in Queue";
                return this.items[0];
            }

            // isEmpty function

        }, {
            key: 'isEmpty',
            value: function isEmpty() {
                // return true if the queue is empty.
                return this.items.length === 0;
            }
        }, {
            key: 'getQueue',
            value: function getQueue() {
                return this.items;
            }
        }, {
            key: 'notificationCount',
            value: function notificationCount() {
                // if ($('#notification_count').length === 0) {
                //     $('.notifications-container').prepend('<div id="notification_count"></div>');
                // }
                //
                // $('#notification_count').html(this.items.length);
            }
        }, {
            key: 'showNotification',
            value: function showNotification() {
                for (var i = 0; i < this.items.length; i++) {

                    if (this.count === notificationShowCount) break;

                    if (!this.items[i]['isShown']) {
                        if (this.items[i].model_type == 'App\\Sale' || this.items[i].model_type == 'App\\Order' || this.items[i].model_type == 'order') {
                            if (this.ordercount !== 5) {
                                this.items[i]['isShown'] = true;
                                toast(this.items[i]);
                                this.ordercount++;
                            }
                        }

                        if (this.items[i].model_type == 'App\\Task' || this.items[i].model_type == 'App\\SatutoryTask' || this.items[i].model_type == 'User' || this.items[i].model_type == 'App\\Http\\Controllers\\Task' || this.items[i].model_type == 'App\\Instruction' || this.items[i].model_type == 'App\\DeveloperTask' || this.items[i].model_type == 'MasterControl') {

                            if (this.taskcount !== 5) {
                                this.items[i]['isShown'] = true;
                                toast(this.items[i]);
                                this.taskcount++;
                            }
                        }

                        if (this.items[i].model_type == 'App\\Leads' || this.items[i].model_type == 'leads') {
                            if (this.leadcount !== 5) {
                                this.items[i]['isShown'] = true;
                                toast(this.items[i]);
                                this.leadcount++;
                            }
                        }

                        this.count++;
                    }
                }
            }
        }, {
            key: 'postPoneNotification',
            value: function postPoneNotification(notificationId) {
                if (this.isEmpty()) return false;

                for (var i = 0; i < this.items.length; i++) {

                    if (this.items[i].id === notificationId) {

                        var item = this.items.splice(i, 1);
                        this.count--;

                        switch (item[0].model_type) {
                            case 'App\\Sale':
                                this.ordercount--;

                                break;
                            case 'App\\Task':
                                this.taskcount--;

                                break;
                            case 'App\\SatutoryTask':
                                this.taskcount--;

                                break;
                            case 'App\\Http\\Controllers\\Task':
                                this.taskcount--;

                                break;
                            case 'App\\Instruction':
                                this.taskcount--;

                                break;
                            case 'App\\DeveloperTask':
                                this.taskcount--;

                                break;
                            case 'MasterControl':
                                this.taskcount--;

                                break;
                            case 'User':
                                this.taskcount--;

                                break;
                            case 'App\\Order':
                                this.ordercount--;

                                break;
                            case 'App\\Leads':
                                this.leadcount--;

                                break;
                            case 'order':
                                this.ordercount--;

                                break;
                            case 'leads':
                                this.leadcount--;

                                break;
                        }

                        this.enqueue(item[0]);
                        break;
                    }
                }
                this.notificationCount();
                this.showNotification();
            }
        }]);

        return Queue;
    }();

    $(document).on('click touchstart', '.notification', function (e) {
        if ($(e.target).hasClass('notification-link') != true) {
            $('.stack-container').not($(this).parent()).addClass('stacked');
            $(this).parent().toggleClass('stacked');
        }
    });

    var notificationQueue = new Queue();

    function getNotificaitons() {

        jQuery.ajax({
            type: 'GET',
            url: '{{ Route('pushNotifications') }}',
            dataType: 'json',
            success: function success(data) {

                data.forEach(function (notification) {
                    notificationQueue.enqueue(notification);
                });

                notificationQueue.showNotification();
                notificationQueue.notificationCount();
            },
            complete: function complete(data) {
                // return setTimeout(getNotificaitons, interval);
            } // Schedule the next
        });
    }

    //Instantly get notifications on page load.
    getNotificaitons();

    function toast(notification) {
        var link = void 0,
            message = void 0,
            img_position = void 0,
            message_without_img = void 0,
            notification_html = void 0,
            close_button = void 0;

        if (notification.type === 'button' && is_admin == false) {
            close_button = '';
        } else {
            close_button = '<button type="button" class="notification-close" role="button" data-id="' + notification.id + '">x</button>';
        }

        switch (notification.model_type) {
            case 'App\\Sale':

                link = '/sales/' + notification.model_id + '/edit';
                message = '<h4>ID : ' + notification.model_id + ' New Sale</h4><a class="notification-link" href="' + link + '" style="padding-bottom: 10px;">' + (notification.message.length > 30 ? notification.message.substring(0, 30 - 3) + '...' : notification.message) + ' - ' + moment(notification.created_at).format('H:m') + '</a>';

                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#orders-notification').append(notification_html);
                $('#orders-notification').css({'display': 'block'});

                break;

            case 'App\\Task':
                link = '/task#task_' + notification.model_id;
                message = '<h4>' + (notification.subject.length > 30 ? notification.subject.substring(0, 30 - 3) + '...' : notification.subject) + '</h4>\n                            <span>By :- ' + allUsers[notification.user_id] + '</span><br>\n                            <a class="notification-link" href="' + link + '">' + (notification.message.length > 30 ? notification.message.substring(0, 30 - 3) + '...' : notification.message) + '</a>' + getStatusButtons(notification);

                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#tasks-notification').append(notification_html);
                $('#tasks-notification').css({'display': 'block'});

                break;

            case 'App\\SatutoryTask':
                link = '/task#task_' + notification.model_id;
                message = '<h4>' + (notification.subject.length > 30 ? notification.subject.substring(0, 30 - 3) + '...' : notification.subject) + '</h4>\n                            <span>By :- ' + allUsers[notification.user_id] + '</span><br>\n                            <a class="notification-link" href="' + link + '">' + (notification.message.length > 30 ? notification.message.substring(0, 30 - 3) + '...' : notification.message) + '</a>' + getStatusButtons(notification);

                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#tasks-notification').append(notification_html);
                $('#tasks-notification').css({'display': 'block'});

                break;

            case 'App\\Http\\Controllers\\Task':
                link = '/task#task_' + notification.model_id;
                message = '<h4>' + (notification.subject.length > 30 ? notification.subject.substring(0, 30 - 3) + '...' : notification.subject) + '</h4>\n                            <span>By :- ' + allUsers[notification.user_id] + '</span><br>\n                            <a class="notification-link" href="' + link + '">' + (notification.message.length > 30 ? notification.message.substring(0, 30 - 3) + '...' : notification.message) + '</a>' + getStatusButtons(notification);

                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#tasks-notification').append(notification_html);
                $('#tasks-notification').css({'display': 'block'});

                break;

            case 'App\\Instruction':
                link = "{{ route('instruction.complete.alert') }}" + "?id=" + notification.model_id;
                // message = '<h4>Reminder</h4>\n                            <span>By :- ' + allUsers[notification.user_id] + '</span><br>\n                            <a class="notification-link" href="' + link + '">' + (notification.message.length > 30 ? notification.message.substring(0, 30 - 3) + '...' : notification.message) + '</a>' + getStatusButtons(notification);
                $('#instructionAlertUrl').attr('href', link);
                $('#instructionAlertModal .modal-body').html(notification.message);
                // jQuery.noConflict();
                $('#instructionAlertModal').modal('show');

                // notification_html = '<div class="notification">' + message + '</div>';
                // $('#tasks-notification').append(notification_html);
                // $('#tasks-notification').css({'display': 'block'});

                break;

            case 'App\\DeveloperTask':
                if (notification.message.indexOf('Remark') >= 0) {
                  link = "{{ route('development.verify.view') }}" + "?id=" + notification.model_id + '&user=' + notification.user_id;
                } else {
                  link = "{{ route('development.verify.view') }}" + "?id=" + notification.model_id + '&user=' + notification.user_id;
                }

                $('#developerAlertUrl').attr('href', link);
                $('#developerAlertModal .modal-body').html(notification.message);
                $('#developerAlertModal').modal('show');

                break;

            case 'MasterControl':
                link = "{{ route('mastercontrol.clear.alert') }}";

                $('#masterControlAlertUrl').attr('href', link);
                $('#masterControlAlertModal .modal-body').html(notification.message);
                $('#masterControlAlertModal').modal('show');

                break;

            case 'User':
                link = '/#task_' + notification.model_id;
                message = '<h4>' + (notification.subject.length > 30 ? notification.subject.substring(0, 30 - 3) + '...' : notification.subject) + '</h4><a class="notification-link" href="' + link + '" style="padding-bottom: 10px; display: block;">' + (notification.message.length > 30 ? notification.message.substring(0, 30 - 3) + '...' : notification.message) + ' - ' + moment(notification.created_at).format('H:m') + '</a>';

                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#tasks-notification').append(notification_html);
                $('#tasks-notification').css({'display': 'block'});

                break;

            case 'App\\Leads':

                link = '/leads/' + notification.model_id;
                message = '<h4>NL - ' + (notification.message.length > 25 ? notification.message.substring(0, 25 - 3) + '...' : notification.message) + '</h4><a class="notification-link" href="' + link + '">By ' + allUsers[notification.user_id] + ' - ' + moment(notification.created_at).format('H:m') + '</a>' + getStatusButtons(notification);
                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#leads-notification').append(notification_html);
                $('#leads-notification').css({'display': 'block'});
                break;

            case 'App\\Order':

                link = '/order/' + notification.model_id + '/edit';
                message = '<h4>NO - ' + (notification.message.length > 25 ? notification.message.substring(0, 25 - 3) + '...' : notification.message) + '</h4><a class="notification-link" href="' + link + '">By ' + allUsers[notification.user_id] + ' - ' + moment(notification.created_at).format('H:m') + '</a>' + getStatusButtons(notification);

                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#orders-notification').append(notification_html);
                $('#orders-notification').css({'display': 'block'});

                break;

            case 'order':
                img_position = notification.message.indexOf("<img");
                message_without_img = img_position != -1 ? notification.message.substring(0, img_position) : notification.message;
                link = '/order/' + notification.model_id;
                message = '<h4>NMO - ' + (notification.client_name.length > 20 ? notification.client_name.substring(0, 20 - 3) + '...' : notification.client_name) + '</h4><a class="notification-link" href="' + link + '" style="padding-bottom: 10px; display: block;">' + (message_without_img.length > 30 ? message_without_img.substring(0, 30 - 3) + '...' : message_without_img) + ' - ' + moment(notification.created_at).format('H:m') + '</a>';

                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#orders-notification').append(notification_html);
                $('#orders-notification').css({'display': 'block'});

                break;

            case 'leads':
                img_position = notification.message.indexOf("<img");
                message_without_img = img_position != -1 ? notification.message.substring(0, img_position) : notification.message;
                link = '/leads/' + notification.model_id;
                message = '<h4>NML - ' + (notification.client_name.length > 20 ? notification.client_name.substring(0, 20 - 3) + '...' : notification.client_name) + '</h4>\n                            <a class="notification-link" href="' + link + '" style="padding-bottom: 10px; display: block;">' + (notification.message.length > 30 ? notification.message.substring(0, 30 - 3) + '...' : notification.message) + ' - ' + moment(notification.created_at).format('H:m') + '</a>';

                notification_html = '<div class="notification">' + close_button + message + '</div>';
                $('#leads-notification').append(notification_html);
                $('#leads-notification').css({'display': 'block'});

                break;

            default:
                return;
        }
    }

    function markNotificationRead(id) {
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/pushNotificationMarkRead/' + id
        });
    }

    function nextNotification(id) {
        notificationQueue.dequeueWithId(parseInt(id));
        notificationQueue.showNotification();
    }

    function getStatusButtons(notificaiton) {

        if (notificaiton.type !== 'button') return '';

        return '<div class="row notification-row">\n                   <div data-id="' + notificaiton.id + '" class="btn-group btn-group-justified">\n                        <button value="1" class="n-status btn btn-notification text-success">Accept</button>\n                        <button value="2" class="n-status btn btn-notification">Postpone</button>\n                        <button value="3" class="n-status btn btn-notification text-danger">Decline</button>\n                    </div>\n                </div>';
    }

    $(document).on('click touchstart', '#notification_count', function () {
        $('.notifications-container').toggleClass('notifications-hide');
    });

    $(document).on('click touchstart', '.notification-close', function (e) {
        e.stopPropagation();
        var notification_id = $(this).data('id');

        markNotificationRead(notification_id);
        nextNotification(notification_id);

        $(this).parent().fadeOut(400);
    });


</script>
