var chatContent = document.querySelector(".chat-content");
if(chatContent)
    chatContent.scrollTo(0,chatContent.scrollHeight);
// scroll

var hamMenu = document.querySelector('.hamburger-menu');
var closeMenu = document.querySelector('.close-menu');
var listPeople = document.querySelector('.people-list');
var chatContent = document.querySelector('.chat');

hamMenu.addEventListener('click', function(event){
    listPeople.classList.toggle('close-list');
    chatContent.classList.toggle('active-chat');
    if(!chatContent.classList.contains('active-chat')){
        hamMenu.style.zIndex = -99
        closeMenu.style.zIndex = 99
    }
})

closeMenu.addEventListener('click', function(event){
    listPeople.classList.toggle('close-list');
    chatContent.classList.toggle('active-chat');
    if(chatContent.classList.contains('active-chat')){
        hamMenu.style.zIndex = 99
        closeMenu.style.zIndex = -99
    }
})

// set up mode
var lightMode = document.querySelector('.light-mode');
var darkMode = document.querySelector('.dark-mode');
lightMode.style.display = 'none';

darkMode.addEventListener('click', function(event){
    lightMode.style.display = 'block';
    darkMode.style.display = 'none';
    document.body.style.background = '#464444';
    closeMenu.style.filter = 'invert(1)';
    hamMenu.style.filter = 'invert(1)';
    document.querySelector('.people-list').style.background = '#102934';
    for(const a of document.querySelectorAll('.people a')){ a.style.color = '#fff' }
    document.querySelector('.chat').style.background = '#1c1c1c';
    document.querySelector('.chat').style.color = '#fff';
    for(const text of document.querySelectorAll('.chat-me .chat-text'))
    { text.style.background = '#38385c' }
    for(const text of document.querySelectorAll('.chat-me .chat-arr'))
    { text.style.background = '#38385c' }
    for(const text of document.querySelectorAll('.chat-to .chat-text'))
    { text.style.background = '#202c44' }
    for(const text of document.querySelectorAll('.chat-to .chat-arr'))
    { text.style.background = '#202c44' }
    for(const img of document.querySelectorAll('.control-panel img')){
        img.style.filter = 'invert(1)';
    }
})

lightMode.addEventListener('click', function(event){
    lightMode.style.display = 'none';
    darkMode.style.display = 'block';
    document.body.style.background = '#eee';
    closeMenu.style.filter = 'invert(0)';
    hamMenu.style.filter = 'invert(0)';
    document.querySelector('.people-list').style.background = '#fff';
    for(const a of document.querySelectorAll('.people a')){ a.style.color = '#000' }
    document.querySelector('.chat').style.background = '#fff';
    document.querySelector('.chat').style.color = '#000';
    for(const text of document.querySelectorAll('.chat-me .chat-text'))
    { text.style.background = '#eee' }
    for(const text of document.querySelectorAll('.chat-me .chat-arr'))
    { text.style.background = '#eee' }
    for(const text of document.querySelectorAll('.chat-to .chat-text'))
    { text.style.background = '#e9effb' }
    for(const text of document.querySelectorAll('.chat-to .chat-arr'))
    { text.style.background = '#e9effb' }
    for(const img of document.querySelectorAll('.control-panel img')){
        img.style.filter = 'invert(0)';
    }
})
// set up mode

// select and send mail with ajax
var selectBtn = document.querySelectorAll(".select-chat-user");
for(const btn of selectBtn ){
    btn.addEventListener('click', selectChat);
    function selectChat(e)
    {
        e.preventDefault();
        var userToId = btn.getAttribute("data-userToId");
        var userId = btn.getAttribute("data-userId");
        var params = "userId=" + userId + "&userToId=" + userToId;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/select/chat', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

        xhr.onload = function(){
            if(this.status == 200){
                var SMSinfo = JSON.parse(this.responseText); 
                var output = '';
                var outputUser = '';
                console.log(SMSinfo);

                outputUser +=   '<img data-id="'+ SMSinfo[0].id +'" src="/upload/'+ SMSinfo[0].image +'">'+
                                '<div class="per-info">'+
                                    '<div class="name">'+ SMSinfo[0].name +'</div>'+
                                    '<div class="status">'+ SMSinfo[0].phone +'</div>'+
                                '</div>'


                for(const i in SMSinfo[1]){
                    if( userId == SMSinfo[1][i].user_id ){
                        output += '<div class="chat-me">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text">'+
                                                '<p>' + SMSinfo[1][i].text + '</p>'+
                                                '<div class="chat-time">'+
                                                    '<span>' + SMSinfo[1][i].time + '</span>'+
                                                '</div>'+
                                                '<div class="chat-arr"></div>'+
                                            '</div>'+
                                            '<img src="/upload/' + SMSinfo[2].image + '" alt="">'+
                                        '</div>'+
                                    '</div>';
                    } else {
                        output += '<div class="chat-to">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text">'+
                                                '<p>' + SMSinfo[1][i].text + '</p>'+
                                                '<div class="chat-time">'+
                                                    '<span>' + SMSinfo[1][i].time + '</span>'+
                                                '</div>'+
                                                '<div class="chat-arr"></div>'+
                                            '</div>'+
                                            '<img src="/upload/'+SMSinfo[0].image+'" alt="">'+
                                        '</div>'+
                                    '</div>';
                    }
                
                }
                document.querySelector('.header-chat').innerHTML = outputUser;
                document.querySelector('.chat-content').innerHTML = output;
                if(chatContent)
                    chatContent.scrollTo(0,chatContent.scrollHeight);
            } else {
                console.log("Page Not Found")
            }
        }
        xhr.send(params);
    } 
}

var sendBtn = document.getElementById("send-message-form");
sendBtn.addEventListener('click', sendChat);
function sendChat(e)
{
    e.preventDefault();
    var userToId = document.querySelector(".header-chat img").getAttribute("data-id");
    var userId = sendBtn.getAttribute("data-userId");
    var messageVal = document.getElementById('send-input').value;
    var params = "userId=" + userId + "&userToId=" + userToId + "&text=" + messageVal;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/send/chat', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

    xhr.onload = function(){
        if(this.status == 200){
            var SMSinfo = JSON.parse(this.responseText); 
            var output = '';
            var outputUser = '';
            console.log(SMSinfo);

            for(const i in SMSinfo[1]){
                if( userId == SMSinfo[1][i].user_id ){
                    output += '<div class="chat-me">'+
                                    '<div class="chat-cart">'+
                                        '<div class="chat-text">'+
                                            '<p>' + SMSinfo[1][i].text + '</p>'+
                                            '<div class="chat-time">'+
                                                '<span>' + SMSinfo[1][i].time + '</span>'+
                                            '</div>'+
                                            '<div class="chat-arr"></div>'+
                                        '</div>'+
                                        '<img src="/upload/no-image.png" alt="">'+
                                    '</div>'+
                                '</div>';
                } else {
                    output += '<div class="chat-to">'+
                                    '<div class="chat-cart">'+
                                        '<div class="chat-text">'+
                                            '<p>' + SMSinfo[1][i].text + '</p>'+
                                            '<div class="chat-time">'+
                                                '<span>' + SMSinfo[1][i].time + '</span>'+
                                            '</div>'+
                                            '<div class="chat-arr"></div>'+
                                        '</div>'+
                                        '<img src="/upload/no-image.png" alt="">'+
                                    '</div>'+
                                '</div>';
                }
            
            }
            
            document.getElementById('send-input').value = '';
            document.querySelector('.header-chat').innerHTML = outputUser;
            document.querySelector('.chat-content').innerHTML = output;
            if(chatContent)
                chatContent.scrollTo(0,chatContent.scrollHeight);
        } else {
            console.log("Page Not Found")
        }
    }
    xhr.send(params);
} 
