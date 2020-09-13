/**
 * Created by aravind on 4/28/17.
 */


$(function () {
    window.initialMessageDisplayed = false;
    $(document).mouseenter(function(){
        if(!window.initialMessageDisplayed){
            var obj = JSON.parse($("#dom-target").text());
            var event = obj.result.action;
            var answerdiv = jQuery('<div/>', {
                html: obj.result.fulfillment.speech.linkify()+'&nbsp;',
                'class': "rounded-div-bot",
                tabindex:1
            });
            $("#chat-text").append(answerdiv);
            $("#message").focus();
            window.initialMessageDisplayed = true;
        }
    });


    var guid = ($("#sessionId").text()).trim();


    $('form').on('submit', function (e) {
        var query = $("#message").val();
        try{
            sendGAEvent('iFrame', guid, 'userSays: '+query);
        }
        catch(e){
            console.log(e);
        }

        showUserText();
        e.preventDefault();


        $.ajax({
            type: 'post',
            url: 'process.php',
            data: {submit:true, message:query, sessionid: guid},
            success: function (response) {
                var obj = JSON.parse(response);
                var event = obj.result.action;
                var answerdiv = jQuery('<div/>', {
                    html: obj.result.fulfillment.speech.linkify()+'&nbsp;',
                    'class': "rounded-div-bot",
                    tabindex:1
                });
                $("#chat-text").append(answerdiv);
                if(event){
                    var stylingDiv = jQuery('<div/>', {
                        html: $("#template").html(),
                        tabindex:1
                    });
                    if(event === 'show.customizer'){
                        $(answerdiv).append(stylingDiv);
                    }
                }
                $(answerdiv).focus();
                $("#message").focus();
                try{
                    sendGAEvent('iFrame',guid,'botSays: '+obj.result.fulfillment.speech)
                }
                catch(e){
                    console.log(e);
                }
            }
        });

    });

});

function sendGAEvent(category, action, label){
    //var d = new Date();
    //var t = d.getTime();
    //ga('send','event',category,action,label,t);
}

function showUserText(){
    var div = jQuery('<div/>', {
        text: $("#message").val(),
        'class': "rounded-div",
        tabindex:1
    });
    $("#chat-text" ).append(div);
    $("#message").val('');
}

if(!String.linkify) {
    String.prototype.linkify = function() {

        // http://, https://, ftp://
        var urlPattern = /\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/gim;

        // www. sans http:// or https://
        var pseudoUrlPattern = /(^|[^\/])(www\.[\S]+(\b|$))/gim;

        // Email addresses
        var emailAddressPattern = /[\w.]+@[a-zA-Z_-]+?(?:\.[a-zA-Z]{2,6})+/gim;

        return this
            .replace(urlPattern,
                '<a class="answerLink" style="color:#0000EE" target="_blank" href="$&">$&</a>')
            .replace(pseudoUrlPattern,
                '$1<a class="answerLink" style="color:#0000EE" target="_blank" href="http://$2">$2</a>')
            .replace(emailAddressPattern,
                '<a class="answerLink" style="color:#0000EE" href="mailto:$&">$&</a>');
    };
}