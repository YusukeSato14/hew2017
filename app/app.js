'use strict'

const http = require('http');
const fs   = require('fs');
const ws   = require('websocket.io');


// http サーバで初期画面を取得できるようにする
const server = http.createServer(function (req, res) {
  const view = fs.readFileSync('./drawing.php');
  res.end(view);
});

let master = null;

// websocket サーバを構築する
const wserver = ws.listen(8888);

wserver.on('connection', socket => {
  // masterが存在しなければ、コネクション成立したものをmasterにする
  if (master === null) {
    master = socket;
    socket.send('master');
  }

  socket.on('message', data => {
    if (socket != master) return false;// master以外が情報を送信する権限はない
    wserver.clients.forEach(client => {
      if (client != master) client.send(data);// master以外の運動状態を送信
    });
  });

  // 描画情報がクライアントから渡されたら、接続中の他ユーザーへ
  // broadcastで描画情報を送ります。
  // ちなみに、最近のsocket.IOでは、イベント名(以下だとdraw)は
  // 自由にネーミング出来るようになったようです。便利！！
  socket.on("draw", function (data) {
    console.log(data);
    socket.broadcast.emit("draw", data);
  });

  // 色変更情報がクライアントからきたら、
  // 他ユーザーへ変更後の色を通知します。
  socket.on("color", function (color) {
    console.log(color);
    socket.broadcast.emit("color", color);
  });

  // 線の太さの変更情報がクライアントからきたら、
  // 他ユーザーへ変更後の線の太さを通知します。
  socket.on("lineWidth", function (width) {
    console.log(width);
    socket.broadcast.emit("lineWidth", width);
  });

  socket.on('close', () => {
    if (socket === master) {
      master = null;
    }
  })
});

server.listen(8888);
