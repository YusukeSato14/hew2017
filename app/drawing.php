<?php

  require_once __DIR__ . '/fun.inc';
  require_logined_session();

  header ('Content-Type: text/html; charset=UTF-8');
  $userid = $_SESSION["userid"];
  $username = username_output("$userid");
?>

<!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <title>インターネット 画廊</title>
    <link rel="stylesheet" type="text/css" href="./css/drawing.css" />

  </head>
  <body>
      <!-- header -->
      <div id="header">
        <!-- logo -->
        <div id="logo">
          <h1>インターネット 画廊</h1>
        </div>
        <!-- logo -->
        <!-- profile -->
        <div id="profile">
          <!-- icon -->
          <div id="icon">
            <p>
              <?php
                $icon = icon_output($userid);
                print "<a href='./tl.php?personal={$userid}'><img src='{$icon}' alt='アイコン' /></a>";
              ?>
            </p>
          </div>
          <!-- icon -->
          <!-- profile_box -->
          <div id="profile_box">
            <h2><?php print $username ?></h2>
          </div>
          <!-- profile_box -->
          <!-- buttons -->
          <div id="buttons">
            <p>
              <a class="btn" style="width:100px;" href="./tl.php">TLへ</a>
              <a class="btn" href='./personal.php'>プロフィール編集</a>
              <?php if(empty($_GET["search"])): ?>
                <a class="btn" href='./tl.php?search=friends'>友だちを探す</a>
              <?php else :?>
                <a class="btn" style="width:100px;" href='./tl.php'>TLに戻る</a>
              <?php endif;?>
            <a class="btn" id="logout" href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a>
          </p><br />
          <?php if(isset($_GET["icon"])) :?>
            <p>アイコンを描いてください</p>
          <?php endif; ?>
          </p>
        </div>
        <!-- buttons -->
      </div>
      <!-- profile -->
    </div>
    <!-- header -->

    <!-- wrap -->
    <div id="wrap">
      <!-- item -->
      <div id="item">
        <!-- item1 -->
        <div id="item1">
            <label>線の太さ</label><input type="range" min="0" max="20" value="10" id="lineWidth"><span id="lineNum">10</span>
            <label>透 明 度</label><input type="range" min="0" max="100" value="100" id="alpha"><span id="alphaNum">100</span>
        </div>
        <!-- item1 -->
        <!-- item2 -->
        <div id="item2">
            <form action="./drawing_back.php" method="post" name="form" onsubmit="return false;">
            <input id="textbox" type="text" name="title" placeholder="タイトル(10文字以内)" />
            <textarea name="introduction" placeholder="ひとこと(7文字以内)"></textarea>
            <input type="hidden" name="userid" value="<?php print $_SESSION["userid"] ?>"/>
            <input type="hidden" name="username" value="<?php print $username ?>"/>
            <?php if(isset($_GET["icon"])) :?>
              <input type="hidden" name="icon" value="setting"/>
            <?php endif; ?>
            <input type="hidden" name="imagedata" value=""/>
          </form>
        </div>
        <!-- item2 -->
        <!-- item3 -->
        <div id="item3">
        <?php if(!isset($_GET["icon"])): ?>
          <p><button id="submitBtn" class="btn" style="width:100px;" onclick="post()" />搬入する</button>
        <?php else: ?>
          <p><button id="submitBtn" class="btn" style="width:100px;" onclick="icon()" />アイコン登録</button>
        <?php endif; ?>
        <button class="btn" style="margin-bottom:10px;width:100px;" id="undo">戻る</button>
        <button class="btn" style="width:100px;" id="clear">消去</button>
        <button class="btn" style="width:100px;" onclick="save()" />貯蔵する</button></p>
      </div>
      <!-- item3 -->
    </div>
    <!-- item -->

    <!-- contentLeft -->
    <div id="contentLeft">
      <ul>
        <li style="background-color:#000000"></li>
        <li style="background-color:#1b1b1b"></li>
        <li style="background-color:#313131"></li>
        <li style="background-color:#434343"></li>
        <li style="background-color:#535353"></li>
        <li style="background-color:#626262"></li>
        <li style="background-color:#707070"></li>
        <li style="background-color:#898989"></li>
        <li style="background-color:#959595"></li>
        <li style="background-color:#a0a0a0"></li>
        <li style="background-color:#b5b5b5"></li>
        <li style="background-color:#c9c9c9"></li>
        <li style="background-color:#dcdcdc"></li>
        <li style="background-color:#ffffff"></li>
        <li style="background-color:#ff0000"></li>
        <li style="background-color:#ffff00"></li>
        <li style="background-color:#00ff00"></li>
        <li style="background-color:#00ffff"></li>
        <li style="background-color:#0000ff"></li>
        <li style="background-color:#ff00ff"></li>
        <li style="background-color:#e60012"></li>
        <li style="background-color:#fff100"></li>
        <li style="background-color:#009944"></li>
        <li style="background-color:#00a0e9"></li>
        <li style="background-color:#1d2088"></li>
        <li style="background-color:#e4007f"></li>
        <li style="background-color:#f29b76"></li>
        <li style="background-color:#f6b37f"></li>
        <li style="background-color:#facd89"></li>
        <li style="background-color:#fff799"></li>
        <li style="background-color:#cce198"></li>
        <li style="background-color:#acd598"></li>
        <li style="background-color:#89c997"></li>
        <li style="background-color:#84ccc9"></li>
        <li style="background-color:#7ecef4"></li>
        <li style="background-color:#88abda"></li>
        <li style="background-color:#8c97cb"></li>
        <li style="background-color:#8f82bc"></li>
        <li style="background-color:#aa89bd"></li>
        <li style="background-color:#c490bf"></li>
        <li style="background-color:#f19ec2"></li>
        <li style="background-color:#f29c9f"></li>
        <li style="background-color:#ec6941"></li>
        <li style="background-color:#f19149"></li>
        <li style="background-color:#f8b551"></li>
        <li style="background-color:#fff45c"></li>
        <li style="background-color:#b3d465"></li>
        <li style="background-color:#80c269"></li>
        <li style="background-color:#32b16c"></li>
        <li style="background-color:#13b5b1"></li>
        <li style="background-color:#00b7ee"></li>
        <li style="background-color:#448aca"></li>
        <li style="background-color:#556fb5"></li>
        <li style="background-color:#5f52a0"></li>
        <li style="background-color:#8957a1"></li>
        <li style="background-color:#ae5da1"></li>
        <li style="background-color:#ea68a2"></li>
        <li style="background-color:#eb6877"></li>
        <li style="background-color:#e60012"></li>
        <li style="background-color:#eb6100"></li>
        <li style="background-color:#fff100"></li>
        <li style="background-color:#8fc31f"></li>
        <li style="background-color:#22ac38"></li>
        <li style="background-color:#009944"></li>
        <li style="background-color:#009e96"></li>
        <li style="background-color:#00a0e9"></li>
        <li style="background-color:#0068b7"></li>
        <li style="background-color:#00479d"></li>
        <li style="background-color:#1d2088"></li>
        <li style="background-color:#601986"></li>
        <li style="background-color:#920783"></li>
        <li style="background-color:#e4007f"></li>
        <li style="background-color:#e5004f"></li>
        <li style="background-color:#a40000"></li>
        <li style="background-color:#a84200"></li>
        <li style="background-color:#ac6a00"></li>
        <li style="background-color:#b7aa00"></li>
        <li style="background-color:#638c0b"></li>
        <li style="background-color:#097c25"></li>
        <li style="background-color:#007130"></li>
        <li style="background-color:#00736d"></li>
        <li style="background-color:#0075a9"></li>
        <li style="background-color:#004986"></li>
        <li style="background-color:#002e73"></li>
        <li style="background-color:#100964"></li>
        <li style="background-color:#440062"></li>
        <li style="background-color:#6a005f"></li>
        <li style="background-color:#a4005b"></li>
        <li style="background-color:#a40035"></li>
        <li style="background-color:#7d0000"></li>
        <li style="background-color:#7f2d00"></li>
        <li style="background-color:#834e00"></li>
        <li style="background-color:#8a8000"></li>
        <li style="background-color:#486a00"></li>
        <li style="background-color:#005e15"></li>
        <li style="background-color:#00561f"></li>
        <li style="background-color:#005752"></li>
        <li style="background-color:#005982"></li>
        <li style="background-color:#003567"></li>
        <li style="background-color:#001c58"></li>
        <li style="background-color:#03004c"></li>
        <li style="background-color:#31004a"></li>
        <li style="background-color:#500047"></li>
        <li style="background-color:#7e0043"></li>
        <li style="background-color:#7d0022"></li>
        <li style="background-color:#d1c0a5"></li>
        <li style="background-color:#a6937c"></li>
        <li style="background-color:#7e6b5a"></li>
        <li style="background-color:#59493f"></li>
        <li style="background-color:#362e2b"></li>
        <li style="background-color:#cfa972"></li>
        <li style="background-color:#b28850"></li>
        <li style="background-color:#996c33"></li>
        <li style="background-color:#81511c"></li>
        <li style="background-color:#6a3906"></li>
        <li style="background-color:#fdead6"></li>
        <li style="background-color:#f9d9b8"></li>
        <li style="background-color:#f5cea5"></li>
        <li style="background-color:#f1bf8b"></li>
        <li style="background-color:#eeb477"></li>
      </ul>
    </div>
    <!-- contentLeft -->

    <!-- contentRight -->
    <div id="contentRight">
      <canvas id="canvas" width="640px" height="480px">残念ながらHTML5に対応していません</canvas>
    </div>
    <!-- dontentRight -->
  </div>
  <!-- wrap -->
    <!-- footer -->
    <div id="footer">
      <p>© 2018 yusuke sato</p>
    </div>
    <!-- footer -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script>
  // 'use strict'

    // $('#submitBtn').click(function(e) {
    //   //alert('押されたけどページの更新はしないよ。');
    //   return e.preventDefault();                      //<=★コレよ。
    // });

    //canvasの読み込み設定
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");

    //背景パターンとして使用するImageオブジェクトを生成する
    var img = new Image();
    //生成したImageオブジェクトに画像ファイルのパスを代入する
    img.src = './imgs/background.png';

    img.onload = function() {
      ctx.beginPath();
      //背景画像とその繰り返し方法を指定する
      var pattern = ctx.createPattern(img, 'repeat');
      //上で指定した背景パターン内容を塗りつぶしスタイルに代入する
      ctx.fillStyle = pattern;
      ctx.rect(0,0,canvas.width,canvas.height);
      ctx.fill();
    }

    //マウスを操作する
    var mouse = {x:0,y:0,x1:0,y1:0,color:"black"};
    var draw = false;

    //マウスの座標を取得する
    canvas.addEventListener("mousemove",function(e) {
      var rect = e.target.getBoundingClientRect();
      ctx.lineWidth = document.getElementById("lineWidth").value;
    	ctx.globalAlpha = document.getElementById("alpha").value/100;

      mouseX = e.clientX - rect.left;
      mouseY = e.clientY - rect.top;


      //クリック状態なら描画をする
      if(draw === true) {
        ctx.beginPath();
        ctx.moveTo(mouseX1,mouseY1);
        ctx.lineTo(mouseX,mouseY);
        ctx.lineCap = "round";
        ctx.stroke();
        mouseX1 = mouseX;
        mouseY1 = mouseY;
      }
    });

      //クリックしたら描画をOKの状態にする
      canvas.addEventListener("mousedown",function(e) {
        draw = true;
        mouseX1 = mouseX;
        mouseY1 = mouseY;
        undoImage = ctx.getImageData(0, 0,canvas.width,canvas.height);
    });

    //クリックを離したら、描画を終了する
    canvas.addEventListener("mouseup", function(e){
      draw = false;
    });


    //線の太さの値を変える
    lineWidth.addEventListener("mousemove",function(){
      var lineNum = document.getElementById("lineWidth").value;
      document.getElementById("lineNum").innerHTML = lineNum;
    });

    //透明度の値を変える
    alpha.addEventListener("mousemove",function(){
      var alphaNum = document.getElementById("alpha").value;
      document.getElementById("alphaNum").innerHTML = alphaNum;
    });

    //色を選択
    $('li').click(function() {
      ctx.strokeStyle = $(this).css('background-color');
    });

    //消去ボタンを起動する
    $('#clear').click(function(e) {
      if(!confirm('本当に消去しますか？')) return;
      //e.preventDefault();
      ctx.beginPath();
      //背景画像とその繰り返し方法を指定する
      var pattern = ctx.createPattern(img, 'repeat');
      //上で指定した背景パターン内容を塗りつぶしスタイルに代入する
      ctx.fillStyle = pattern;
      ctx.rect(0,0,canvas.width,canvas.height);
      ctx.fill();
      //ctx.clearRect(0, 0, canvas.width, canvas.height);
    });

    //戻るボタンを配置
    $('#undo').click(function(e) {
      ctx.putImageData(undoImage,0,0);
    });


    //保存する
    function save(){
      var can = canvas.toDataURL("image/png");
      can = can.replace("image/png", "image/octet-stream");
      var filename = form.title.value;
      if(filename=='') {
        filename = 'no-title';
      }
      filename = filename+'.png';
      //confirm(filename);
      var a = document.createElement('a');
      a.download = filename;
      a.href = can;
      a.click();
    }

    //投稿する
    function post(){
      var image_data = canvas.toDataURL("image/png");
      image_data = image_data.replace(/^.*,/, '');
      var form = document.form;
      form.imagedata.value = image_data;
      form.submit();

    }

    //アイコン登録
    function icon(){
      var image_data = canvas.toDataURL("image/png");
      image_data = image_data.replace(/^.*,/, '');
      var form = document.form;
      form.imagedata.value = image_data;
      form.submit();
    }

    //スマホ用
    	var finger=new Array;
    	for(var i=0;i<10;i++){
    		finger[i]={
    			x:0,y:0,x1:0,y1:0,
    			color:"rgb("
    			+Math.floor(Math.random()*16)*15+","
    			+Math.floor(Math.random()*16)*15+","
    			+Math.floor(Math.random()*16)*15
    			+")"
    		};
    	}

    	//タッチした瞬間座標を取得
    	canvas.addEventListener("touchstart",function(e){
    		e.preventDefault();
    		var rect = e.target.getBoundingClientRect();
    		ctx.lineWidth = document.getElementById("lineWidth").value;
    		ctx.globalAlpha = document.getElementById("alpha").value/100;
    		undoImage = ctx.getImageData(0, 0,canvas.width,canvas.height);
    		for(var i=0;i<finger.length;i++){
    			finger[i].x1 = e.touches[i].clientX-rect.left;
    			finger[i].y1 = e.touches[i].clientY-rect.top;
    		}
    	});

    	//タッチして動き出したら描画
    	canvas.addEventListener("touchmove",function(e){
    		e.preventDefault();
    		var rect = e.target.getBoundingClientRect();
    		for(var i=0;i<finger.length;i++){
    			finger[i].x = e.touches[i].clientX-rect.left;
    			finger[i].y = e.touches[i].clientY-rect.top;
    			ctx.beginPath();
    			ctx.moveTo(finger[i].x1,finger[i].y1);
    			ctx.lineTo(finger[i].x,finger[i].y);
    			ctx.lineCap="round";
    			ctx.stroke();
    			finger[i].x1=finger[i].x;
    			finger[i].y1=finger[i].y;

    		}
    	});

    	//線の太さの値を変える
    lineWidth.addEventListener("touchmove",function(){
    var lineNum = document.getElementById("lineWidth").value;
    document.getElementById("lineNum").innerHTML = lineNum;
    });

    //透明度の値を変える
    alpha.addEventListener("touchmove",function(){
    var alphaNum = document.getElementById("alpha").value;
    document.getElementById("alphaNum").innerHTML = alphaNum;
    });



    // websocket による同期処理
  const ws = new WebSocket('ws://192.168.3.3:/8888');
  ws.onmessage = event => {
    const str = event.data;
    // 一番初めに接続した場合はmasterとなり、同期元となる
    if (str === 'master') {
      setInterval(() => {
        ws.send(rect.packData());
      }, rect.syncTime);
      return true;
    }
    // masterでなければ、データを受け取って描画を更新する
    const data = JSON.parse(str);
    rect.x = data.x;
    rect.y = data.y;
    rect.vx = data.vx;
    rect.vy = data.vy;
    rect.update();
  }
  </script>
  </body>
</html>
