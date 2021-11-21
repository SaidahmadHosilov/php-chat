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

function parseQuery(queryString) {
    var query = {};
    var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split('=');
        query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
    }
    return query;
}

darkMode.addEventListener('click', darkModeFunc)
function darkModeFunc(){
    let query = document.location.search.substr(1);
    
    window.history.replaceState({}, '', '/?' + 'dark_mode=on')

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
}

lightMode.addEventListener('click', function(event){
    window.history.replaceState({}, '', '/?' + 'dark_mode=off')

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
function selectChat(e)
{
    e.preventDefault();
    var userToId = this.getAttribute("data-userToId");
    var userId = this.getAttribute("data-userId");
    var params = "userId=" + userId + "&userToId=" + userToId;
    let query = document.location.search.substr(1);
    var queryObj = parseQuery(query);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/select/chat', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

    xhr.onload = function(){
        if(this.status == 200){                
            var SMSinfo = JSON.parse(this.responseText); 
            var output = '';
            var outputUser = '';

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
            if(queryObj.hasOwnProperty('dark_mode') ){
                if( queryObj.dark_mode == 'on' ){
                    for(const text of document.querySelectorAll('.chat-me .chat-text'))
                    { text.style.background = '#38385c' }
                    for(const text of document.querySelectorAll('.chat-me .chat-arr'))
                    { text.style.background = '#38385c' }
                    for(const text of document.querySelectorAll('.chat-to .chat-text'))
                    { text.style.background = '#202c44' }
                    for(const text of document.querySelectorAll('.chat-to .chat-arr'))
                    { text.style.background = '#202c44' }
                } else {
                    for(const text of document.querySelectorAll('.chat-me .chat-text'))
                    { text.style.background = '#eee' }
                    for(const text of document.querySelectorAll('.chat-me .chat-arr'))
                    { text.style.background = '#eee' }
                    for(const text of document.querySelectorAll('.chat-to .chat-text'))
                    { text.style.background = '#e9effb' }
                    for(const text of document.querySelectorAll('.chat-to .chat-arr'))
                    { text.style.background = '#e9effb' }
                }
            }
            chatContent.scrollTo(0,chatContent.scrollHeight);
        } else {
            console.log("Page Not Found")
        }
    }
    xhr.send(params);
} 
for(const btn of selectBtn ){
    btn.addEventListener('click', selectChat);
}

var sendBtn = document.getElementById("send-message-form");

// Check Function
function checkQuery( query, path, path1, path2, arg){
    if(path1 != null) query = query.replace("&"+path+"="+arg, "");
    if(path2 != null) query = query.replace(path+"="+arg+"&", "");
    if(path2 != null) query = query.replace(path+"="+arg, "");
    return query
}
// !Check Function

sendBtn.addEventListener('click', sendChat);
function sendChat(e)
{
    e.preventDefault();
    var userToId = document.querySelector(".header-chat img").getAttribute("data-id");
    var userId = sendBtn.getAttribute("data-userId");
    var messageVal = document.getElementById('send-input').value;
    var params = "userId=" + userId + "&userToId=" + userToId + "&text=" + messageVal;
    let query = document.location.search.substr(1);
    var queryObj = parseQuery(query);

    // check area
    query = checkQuery(query, "userId", query.match(/&userId/g), query.match(/userId/g), queryObj.userId)
    query = checkQuery(query, "userToId", query.match(/&userToId/g), query.match(/userToId/g), queryObj.userToId)
    query = checkQuery(query, "text", query.match(/&text/g), query.match(/text/g), queryObj.text)
    // check area
    
    let queryRequest = query.length == 0 ? '' + params : query + '&' + params; 

    window.history.replaceState({}, '', '/?' + queryRequest)

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/send/chat?'+queryRequest, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

    xhr.onload = function(){
        if(this.status == 200){
            if(this.responseText == 'error'){
                document.getElementById('send-input').value = '';
            }

            if(queryObj.hasOwnProperty('dark_mode') ){
                if( queryObj.dark_mode == 'on' ){
                    for(const text of document.querySelectorAll('.chat-me .chat-text'))
                    { text.style.background = '#38385c' }
                    for(const text of document.querySelectorAll('.chat-me .chat-arr'))
                    { text.style.background = '#38385c' }
                    for(const text of document.querySelectorAll('.chat-to .chat-text'))
                    { text.style.background = '#202c44' }
                    for(const text of document.querySelectorAll('.chat-to .chat-arr'))
                    { text.style.background = '#202c44' }
                } else {
                    for(const text of document.querySelectorAll('.chat-me .chat-text'))
                    { text.style.background = '#eee' }
                    for(const text of document.querySelectorAll('.chat-me .chat-arr'))
                    { text.style.background = '#eee' }
                    for(const text of document.querySelectorAll('.chat-to .chat-text'))
                    { text.style.background = '#e9effb' }
                    for(const text of document.querySelectorAll('.chat-to .chat-arr'))
                    { text.style.background = '#e9effb' }
                }
            }
            chatContent.scrollTo(0,chatContent.scrollHeight);
        } else {
            console.log("Page Not Found")
        }
    }
    xhr.send();
} 


// Debounce

const debounce = (fn, ms) => {
	let timeout;
	return function() {
		const fnCall = () => { fn.apply(this, arguments) }
		clearTimeout(timeout);
		timeout = setTimeout(fnCall, ms)
	}
}
onChange = debounce(onChange, 500);

document.getElementById('search-input').addEventListener('keyup', onChange);

function onChange(e){
	e.preventDefault();
    let query = window.location.search.substr(1);
    let queryObj = parseQuery(query);
	let searchVal = e.target.value;
	var searchResult = document.querySelector('#people-list-box ul');
	var params = "name=" + searchVal;

	var xhr = new XMLHttpRequest();
	xhr.open('POST', '/user/userSearch', true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

	xhr.onload = function(){
		if(this.status == 200){
			var result = JSON.parse(this.responseText); 
            var output = '';

            if( result[0] == 'No Result' ){
                output += '<p style="color: #fff; text-align:center; margin-top:5px;">Ma\'lumot topilmadi :(</p>';
            } else {
                for(const i in result[0]){
                    output +=   '<li>'+
                                    '<a class="select-chat-user" '+
                                    'data-userToId="'+ result[0][i].id +'" data-userId="'+ result[1] +'">'+
                                        '<div class="person-box">'+
                                            '<img src="/upload/' + result[0][i].image + '" alt="">'+
                                            '<div class="per-info">'+
                                                '<div class="name">' + result[0][i].name + '</div>'+
                                                '<div class="status"> <div class="online-class"></div> online</div>'+
                                            '</div>'+
                                        '</div>'+
                                   '</a>'+
                                '</li>';
                }
            }
            searchResult.innerHTML = output;
            var selectBtn = document.querySelectorAll(".select-chat-user");
            for(const btn of selectBtn ){
                btn.addEventListener('click', selectChat);
            }
            if(queryObj.hasOwnProperty('dark_mode') ){
                if( queryObj.dark_mode == 'on' ){
                    for(const text of document.querySelectorAll('.select-chat-user'))
                        { text.style.color = 'rgb(255, 255, 255)' }
                }
            }
		} else {
			console.log("Page Not Found")
		}
	}
	xhr.send(params);
}

// Debounce