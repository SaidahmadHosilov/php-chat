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
    for(const img of document.querySelectorAll('.select-group-chat > img')){
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
    for(const img of document.querySelectorAll('.select-group-chat > img')){
        img.style.filter = 'invert(0)';
    }
})
// set up mode

// select and send mail with ajax select-group-chat
// var selectGroupBtn = document.querySelectorAll(".select-chat-user");
var selectBtn = document.querySelectorAll(".select-chat-user");

$(document).ready(function(){
    $(".select-group-chat").on('click', function(event){
        var grId = this.getAttribute('data-grId');
        var userId = this.getAttribute('data-userId');
        $.ajax(
        {
            type: "GET",
            dataType: 'text',  // <-- what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            url: '/group/select',
            data: 'gr_id='+grId+'&user_id='+userId,
            success: function(info)
            {
                var info = JSON.parse(info);
                var outputGroup = '';
                var output = '';
                var query = window.location.search.substr(1)
                var queryObj = parseQuery(query);

                if( info[3] == info[0].admin ) {
                    outputGroup += '<img data-name="group" data-group-users="'+ info[1] +'" data-id="'+ info[0].id +'" src="/upload/'+ info[0].image +'">'+
                                    '<div class="per-info">'+
                                        '<div class="name">'+ info[0].name +'</div>'+
                                        '<div class="status">'+ info[1].length +' people |'+
                                        '<a class="delete-group-btn" href="/delete/group/'+ info[0].id +'" onclick="return confirm(\'Are you sure to delete the group?\')">Delete</a>'+
                                        '</div>'+
                                    '</div>';    
                } else {
                    outputGroup += '<img data-name="group" data-group-users="'+ info[1] +'" data-id="'+ info[0].id +'" src="/upload/'+ info[0].image +'">'+
                                    '<div class="per-info">'+
                                        '<div class="name">'+ info[0].name +'</div>'+
                                        '<div class="status">'+ info[1].length +' people |'+
                                        '</div>'+
                                    '</div>';
                }
                

                for(const i in info[2]){
                    if( info[2][i].user_id == info[3] ){
                        output += '<div class="chat-me">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text" data-id="'+info[2][i].user_id+'">'+
                                                '<a href="/delete/grChat" class="delete-sms-gr"><img src="/template/images/delete-sms.svg"></a>'+
                                                '<div class="images-chat">'+ info[2][i].image +'</div>'+
                                                '<p>' + info[2][i].sms + '</p>'+
                                                '<div class="chat-time">'+
                                                    '<span>' + (info[2][i].time) + '</span>'+
                                                '</div>'+
                                                '<div class="chat-arr"></div>'+
                                            '</div>'+
                                            '<img src="/upload/' + info[2][i].uimage + '" alt="">'+
                                        '</div>'+
                                    '</div>';
                    } else {
                        output += '<div class="chat-to">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text" data-id="'+info[2][i].user_id+'">'+
                                                '<div class="images-chat">'+ info[2][i].image +'</div>'+
                                                '<p>' + info[2][i].sms + '</p>'+
                                                '<div class="chat-time">'+
                                                    '<span>' + (info[2][i].time) + '</span>'+
                                                '</div>'+
                                                '<div class="chat-arr"></div>'+
                                            '</div>'+
                                            '<img src="/upload/' + info[2][i].uimage + '" alt="">'+
                                        '</div>'+
                                    '</div>';
                    }
                
                }

                document.querySelector('.header-chat').innerHTML = outputGroup;
                document.querySelector('.chat-content').innerHTML = output;

                for(const deleteBtn of document.querySelectorAll('.delete-sms-gr')){
                    deleteBtn.addEventListener('click', deleteGrChat);
                }

                if( queryObj.hasOwnProperty('dark_mode') ){
                    if(queryObj.dark_mode == 'on'){
                        darkMode.click();
                    }
                }
            }
        });
    })
})

function selectChat(e)
{
    e.preventDefault();
    var userToId = this.getAttribute("data-userToId");
    var userId = this.getAttribute("data-userId");
    // var params = "userId=" + userId + "&userToId=" + userToId;
    let query = document.location.search.substr(1);
    var queryObj = parseQuery(query);

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/select/chat'+"?userId=" + userId + "&userToId=" + userToId, true);
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
                                        '<div class="chat-text" data-id="'+userId+'">'+
                                            '<a href="/delete/chat" class="delete-sms"><img src="/template/images/delete-sms.svg"></a>'+
                                            '<div class="images-chat">'+ SMSinfo[1][i].images +'</div>'+
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
                                            '<div class="images-chat">'+ SMSinfo[1][i].images +'</div>'+
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
                    darkMode.click();
                }
            }
            for(const deleteBtn of document.querySelectorAll('.delete-sms')){
                deleteBtn.addEventListener('click', deleteChat);
            }
        } else {
            console.log("Page Not Found")
        }
    }
    xhr.send();
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
    var isGroup = document.querySelector(".header-chat img").getAttribute("data-name");
    var grUsers = document.querySelector(".header-chat img").getAttribute("data-group-users");
    var userId = sendBtn.getAttribute("data-userId");
    var images = document.querySelector(".for-pic").innerHTML;
    var messageVal = encodeURIComponent(document.getElementById('send-input').value);
    if( !(messageVal == '' && images == '') ){
        let query = document.location.search.substr(1);
        var queryObj = parseQuery(query);
        
        if(images.length == 0 || images == undefined ){
            if(isGroup != undefined){
                var params = "userId=" + userId + "&gr_id=" + userToId + "&text=" + messageVal+"&is_group="+1+"&gr_users="+grUsers;
            }else {
                var params = "userId=" + userId + "&userToId=" + userToId + "&text=" + messageVal+"&is_group="+0;
            }
            if(queryObj.hasOwnProperty('images')){
                let url = new URL(location.href);
                url.searchParams.delete('images');
                query = url.search.substr(1);
            }
        } else {
            if(isGroup != undefined){
                var params = "userId=" + userId + "&gr_id=" + userToId + "&images=" + images +"&text=" + messageVal+"&is_group="+1+"&gr_users="+grUsers;
            }else {
                var params = "userId=" + userId + "&userToId=" + userToId + "&images=" + images + "&text=" + messageVal+"&is_group="+0;
            }
            query = checkQuery(query, "images", query.match(/&images/g), query.match(/images/g), queryObj.images)
        }

        // check area
        query = checkQuery(query, "userId", query.match(/&userId/g), query.match(/userId/g), queryObj.userId)
        query = checkQuery(query, "userToId", query.match(/&userToId/g), query.match(/userToId/g), queryObj.userToId)
        query = checkQuery(query, "text", query.match(/&text/g), query.match(/text/g), queryObj.text)
        // check area
        
        let queryRequest = query.length == 0 ? '' + params : query + '&' + params; 

        window.history.replaceState({}, '', '/?' + queryRequest)

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/send/chat', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

        xhr.onload = function(){
            if(this.status == 200){
                if(this.responseText == 'error'){
                    document.getElementById('send-input').value = '';
                }

                document.querySelector('.for-pic').innerHTML = '';
                if(queryObj.hasOwnProperty('dark_mode') ){
                    if( queryObj.dark_mode == 'on' ){
                        darkMode.click();
                    }
                }
            } else {
                console.log("Page Not Found")
            }
        }
        xhr.send(queryRequest);
    }
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
	xhr.open('GET', '/user/userSearch?'+params, true);
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
	xhr.send();
}

// Debounce

// Delete SMS

for(const deleteBtn of document.querySelectorAll('.delete-sms-gr')){
    deleteBtn.addEventListener('click', deleteGrChat);
}

function deleteGrChat(e){
    e.preventDefault();
    var currentTime = this.parentElement.querySelector('.chat-time span').textContent
    var currentId = this.parentElement.getAttribute("data-id"); 
    var grId = document.querySelector('.header-chat img').getAttribute('data-id')
    var query = window.location.search.substr(1);
    var queryObj = parseQuery(query);
    $.ajax({
        type: "GET",
        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        url: '/delete/grChat',
        data: 'user_id='+currentId+"&time="+currentTime+"&gr_id="+grId,
        success: function(data)
        {
            var data = JSON.parse(data);
            var output = '';
            console.log(data);
            for(const i in data[0]){
                if( data[1] == data[0][i].user_id ){
                    output += '<div class="chat-me">'+
                                    '<div class="chat-cart">'+
                                        '<div class="chat-text" data-id="'+data[0][i].user_id+'">'+
                                            '<a href="/delete/grChat" class="delete-sms-gr"><img src="/template/images/delete-sms.svg"></a>'+
                                            '<div class="images-chat">'+ data[0][i].image +'</div>'+
                                            '<p>' + data[0][i].sms + '</p>'+
                                            '<div class="chat-time">'+
                                                '<span>' + (data[0][i].time) + '</span>'+
                                            '</div>'+
                                            '<div class="chat-arr"></div>'+
                                        '</div>'+
                                        '<img src="/upload/' + data[0][i].uimage + '" alt="">'+
                                    '</div>'+
                                '</div>';
                } else {
                    output += '<div class="chat-to">'+
                                    '<div class="chat-cart">'+
                                        '<div class="chat-text" data-id="'+data[0][i].user_id+'">'+
                                            '<div class="images-chat">'+ data[0][i].image +'</div>'+
                                            '<p>' + data[0][i].sms + '</p>'+
                                            '<div class="chat-time">'+
                                                '<span>' + (data[0][i].time) + '</span>'+
                                            '</div>'+
                                            '<div class="chat-arr"></div>'+
                                        '</div>'+
                                        '<img src="/upload/' + data[0][i].uimage + '" alt="">'+
                                    '</div>'+
                                '</div>';
                }
            
            }

            document.querySelector('.chat-content').innerHTML = output;
            
            for(const deleteBtn of document.querySelectorAll('.delete-sms-gr')){
                deleteBtn.addEventListener('click', deleteGrChat);
            }

            if(queryObj.hasOwnProperty('dark_mode') ){
                if( queryObj.dark_mode == 'on' ){
                    darkMode.click();
                }
            }
        }
    });
}

function deleteChat(e){
	e.preventDefault();
    var currentTime = this.parentElement.querySelector('.chat-time span').textContent
    var currentId = this.parentElement.getAttribute("data-id"); 
    var toId = document.querySelector('.header-chat img').getAttribute('data-id')
    var query = window.location.search.substr(1);
    var queryObj = parseQuery(query);

    var params = "sms_id=" + currentId + "&time=" + currentTime + "&toId=" + toId;

	var xhr = new XMLHttpRequest();
	xhr.open('GET', '/delete/chat?'+params, true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

	xhr.onload = function(){
		if(this.status == 200){
			var result = JSON.parse(this.responseText);
            var output = '';
            for(const i in result[1]){
                if( currentId == result[1][i].user_id ){
                    output += '<div class="chat-me">'+
                                    '<div class="chat-cart">'+
                                        '<div class="chat-text" data-id="'+currentId+'">'+
                                            '<a href="/delete/chat" class="delete-sms"><img src="/template/images/delete-sms.svg"></a>'+
                                            '<div class="images-chat">'+ result[1][i].images +'</div>'+
                                            '<p>' + result[1][i].text + '</p>'+
                                            '<div class="chat-time">'+
                                                '<span>' + result[1][i].time + '</span>'+
                                            '</div>'+
                                            '<div class="chat-arr"></div>'+
                                        '</div>'+
                                        '<img src="/upload/' + result[0].image + '" alt="">'+
                                    '</div>'+
                                '</div>';
                } else {
                    output += '<div class="chat-to">'+
                                    '<div class="chat-cart">'+
                                        '<div class="chat-text">'+
                                            '<div class="images-chat">'+ result[1][i].images +'</div>'+
                                            '<p>' + result[1][i].text + '</p>'+
                                            '<div class="chat-time">'+
                                                '<span>' + result[1][i].time + '</span>'+
                                            '</div>'+
                                            '<div class="chat-arr"></div>'+
                                        '</div>'+
                                        '<img src="/upload/'+result[2].image+'" alt="">'+
                                    '</div>'+
                                '</div>';
                }
            }
            document.querySelector('.chat-content').innerHTML = output;
            if(queryObj.hasOwnProperty('dark_mode') ){
                if( queryObj.dark_mode == 'on' ){
                    darkMode.click();
                }
            }
            for(const deleteBtn of document.querySelectorAll('.delete-sms')){
                deleteBtn.addEventListener('click', deleteChat);
            }
		} else {
			console.log("Page Not Found")
		}
	}
	xhr.send();
}
// Delete SMS


for(const deleteBtn of document.querySelectorAll('.delete-sms')){
    deleteBtn.addEventListener('click', deleteChat);
}

// Modal

let createModal = document.querySelector('.modal-group-container');
let openCreateModal = document.getElementById('group-modal');
let closeCreateModal = document.querySelector('.modal-header img');

openCreateModal.addEventListener('click', function(){
    createModal.classList.toggle('active-group-modal');
})

closeCreateModal.addEventListener('click', function(){
    createModal.classList.toggle('active-group-modal');
})

window.addEventListener('click', function(event){
    if(event.target.classList.contains('modal-main')){
        closeCreateModal.click()
    }
})
// Modal

// select member

$(document).ready(function(event){
    // var deleteBtnMember = document.querySelectorAll(".delete-gr-member");
    $("#grmembers").on('change',function(){
            var userId = this.value;
            if( userId != '' ){
            $.ajax({
                type: "GET",
                dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                url: '/group/add/member',
                data: 'user_id='+userId,
                success: function(data)
                {
                    var user = JSON.parse(data);
                    var output = '';
                    var isExist = 0;
                    $('.members-list .member').each(function(index){
                        if( this.getAttribute("data-id") == user.id ) isExist++;
                    })

                    if( !isExist ){
                        output +=   '<div data-id="'+user.id+'" class="member">'+
                                        '<img src="/upload/'+user.image+'">'+
                                        '<div>'+
                                            '<img class="delete-gr-member" src="/template/images/close.svg">'+
                                            '<p>'+user.name+'</p>'+
                                            '<span>'+user.phone+'</span>'+
                                        '</div>'+
                                    '</div>';
                        $('.members-list').append(output);

                        var deleteBtnMember = $(".delete-gr-member");

                        if( deleteBtnMember != undefined ) {
                            deleteBtnMember.each(function(index){
                                this.addEventListener('click', function(event){
                                    var prElem = this.parentElement.parentElement;
                                    prElem.remove();
                                })
                            })
                        }
                    }
                }
            });
        }
    })
    
})
// select member

// Create Group

$(document).ready(function(){
    $("#create-group-submit").on('click', function(event){
        // event.preventDefault();
        var file_image = $('#grpic').prop('files')[0];   
        var file_name = document.querySelector('#grname').value;   
        var form_data = new FormData();                  
        form_data.append('file', file_image);
        form_data.append('name', file_name);
        var users = [];

        for(const mem of document.querySelectorAll('.member')){
            users.push(mem.getAttribute('data-id'))
        }
        users.push(this.getAttribute('data-id'))
        form_data.append('users', users);

        if( users.length != 0 ){
            $.ajax(
            {
                type: "POST",
                dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                url: '/group/create',
                data: form_data,
                success: function(info)
                {
                    var result = JSON.parse(info);
                    if( result == 'error' ){
                        alert('Rasm 4 MB dan katta bo\'lmasligi kerak!');
                    }
                    if( result == 'success' ){
                        file_name.innerHTML = '';
                        closeCreateModal.click()
                    }
                }
            });
        }
    })
})

// Create Group
