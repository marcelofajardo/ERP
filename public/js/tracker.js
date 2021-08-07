var url = window.location.href;
window.collectedData = [
    {
        type: 'key',
        data: ''
    },
    {
        type: 'mouse',
        data: []
    }
];

$(document).ready(function() {
    $.ajax({
        url: "/track",
        type: 'post',
        data: {
            url: url,
            type: 'LOADED',
            data: "User entered to ERP.",
            _token: token
        }
    });
});

$(document).keypress(function(event) {
    var x = event.charCode || event.keyCode;  // Get the Unicode value
    var y = String.fromCharCode(x);
    collectedData[0].data += y;

    if (collectedData[0].data.length > 10) {
        let data_ = collectedData[0].data;
        let type_ = collectedData[0].type;
        collectedData[0].data = "";
        $.ajax({
            url: "/track",
            type: 'post',
            data: {
                url: url,
                type: type_,
                data: data_,
                _token: token
            }
        });
    }
});

window.onbeforeunload = function() {
    $.ajax({
        url: "/track",
        type: 'post',
        data: {
            url: url,
            type: 'GONE_OUT',
            data: "LEFT THE PAGE",
            _token: token
        }
    });
};

window.onblur = function() {
    $.ajax({
        url: "/track",
        type: 'post',
        data: {
            url: url,
            type: 'TAB_CHANGED',
            data: "User went to another tab..",
            _token: token
        }
    });
};

$(document).click(function(event) {
    event.stopPropagation();
    let tag = event.target.tagName.toLowerCase();
    let value = "";
    if (tag == 'a') {
        value = $(event.target).attr('href');
    }
    if (tag == 'select') {
        value = $(event.target).val();
    }
    if (tag == 'img') {
        value = $(event.target).attr('src');
    }

    if (tag == 'div') {
        value = $(event.target).html();
    }

    $.ajax({
        url: "/track",
        type: 'post',
        data: {
            url: url,
            type: 'click_'+tag,
            data: value,
            _token: token
        }
    });


});