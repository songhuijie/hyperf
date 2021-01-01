<!DOCTYPE html>
<html>
<head>
    <title>Java后端WebSocket的Tomcat实现</title>
　　 <meta content='width=device-width, initial-scale=, maximum-scale=, user-scalable=no' name='viewport' />    　　　　　　
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 </head>
<body>
    Welcome<br/>
    <div id="char" style="width:80%;height: 400px;border:#000000 1px solid " >

    </div>
    <hr/>

    <select id="select">

    </select>
     <input type="text" id="text" name="content"/>
     <button onclick="sendTo()">发送消息</button>
    <input type="hidden" name="fid" id="fid" value="0">
     <hr/>
     <button onclick="closeWebSocket()">关闭WebSocket连接</button>
     <hr/>
     <div id="message"></div>
 </body>

<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.1.0/jquery.js"></script>
 <script type="text/javascript">
         var websocket = null;
         var username = '';
         //判断当前浏览器是否支持WebSocket
         if ('WebSocket' in window) {
             {{--websocket = new WebSocket("ws://127.0.0.1:9503/ws?id={{$id}}");--}}
             websocket = new WebSocket("ws://websocket.hyperf.com/ws?id={{$id}}");
         }
         else {
             alert('当前浏览器 Not support websocket')
         }

         //连接发生错误的回调方法
         websocket.onerror = function () {
             setMessageInnerHTML("WebSocket连接发生错误");
         };

         //连接成功建立的回调方法
         websocket.onopen = function () {
             setMessageInnerHTML("WebSocket连接成功");
             heartCheck.start();
         }
         window.setInterval(function () { //每隔5秒钟发送一次心跳，避免websocket连接因超时而自动断开
             var ping = {"type": "ping"};
             websocket.send(JSON.stringify(ping));
         }, 3000);

         //接收到消息的回调方法
         websocket.onmessage = function (event) {

             var json = event.data;
             var data = JSON.parse(json);
             switch (data.type) {
                 case 'users':
                     var html = '<option value="">未选择</option>';
                     for(v in data.data){
                         html += '<option value="'+ v +'">'+ data.data[v] +'</option>';
                     }
                     $('#select').html(html);
                     break;
                 case 'notice':
                     setMessageInnerHTML(event.data);
                     break;
                 case 'to_all':
                     var html = "<div style='text-align: left;'>"+data.from+" :<span>"+data.message+"<span></div>";
                     $('#char').append(html);

                     break;
                 case 'to_someone':

                     var html = "<div style='text-align: left;'>"+data.from+" :<span>"+data.message+"<span></div>";
                     $('#char').append(html);

                     break;
                 case 'connect':

                     username = data.account;

                     break;
                 default:
                     break;
             }


         }

         //连接关闭的回调方法
         websocket.onclose = function () {
             setMessageInnerHTML("WebSocket连接关闭");
         }

         //监听窗口关闭事件，当窗口关闭时，主动去关闭websocket连接，防止连接还没断开就关闭窗口，server端会抛异常。
         window.onbeforeunload = function () {
             closeWebSocket();
         }
         //心跳检测
         var heartCheck = {
             timeout: 3000,
             timeoutObj: null,
             serverTimeoutObj: null,
             start: function(){
                 console.log('start');
                 var self = this;
                 this.timeoutObj && clearTimeout(this.timeoutObj);
                 this.serverTimeoutObj && clearTimeout(this.serverTimeoutObj);

                 // this.timeoutObj = setTimeout(function () { //每隔5秒钟发送一次心跳，避免websocket连接因超时而自动断开
                 //     var ping = {"type": "ping"};
                 //     websocket.send(JSON.stringify(ping));
                 // }, this.timeout);

                 this.timeoutObj = setTimeout(function(){
                     //这里发送一个心跳，后端收到后，返回一个心跳消息，
                     websocket.send(JSON.stringify({"type": "ping"}));
                     self.serverTimeoutObj = setTimeout(function() {
                         console.log(websocket);

                         // createWebSocket();
                     }, self.timeout);

                 }, this.timeout)
             }
         }

         //将消息显示在网页上
         function setMessageInnerHTML(innerHTML) {
             document.getElementById('message').innerHTML += innerHTML + '<br/>';
         }

         //关闭WebSocket连接
         function closeWebSocket() {
             websocket.close();
         }

         //发送消息
         function send() {
             var message = document.getElementById('text').value;
             websocket.send(JSON.stringify({"type": 'send',"message": message}));

         }
         //对某个人发送消息
         function sendTo() {



             var message = document.getElementById('text').value;
             var fid = $('#fid').val();

             var html = "<div style='text-align: right;'>"+ username +" :<span>"+message+"<span></div>";
             $('#char').append(html);

             var send_message = '';
             if(fid == 0){
                 send_message = JSON.stringify({"type": 'send',"message": message});
             }else{
                 send_message = JSON.stringify({"type": 'sendTo','to':fid,"message": message});
             }
             websocket.send(send_message);
         }

         $('#select').change(function () {
             var checkValue =  $("select option:selected").val();
             $('#fid').val(checkValue);
         })
     </script>
 </html>
