$(document).ready(function () {
    $('.form-auth-login').submit(function () {
        $.post(this.baseURI + 'json/' + 'block' + this.action.substr(this.baseURI.length - 1), $(this).serialize(), function (data) {
            console.log(data);
            $('#top-user').html($('<div/>').html(data.content).text());
            appendScripts(data);
        }, 'json');
    });
})
