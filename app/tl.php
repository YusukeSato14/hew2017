<?php
  require_once __DIR__ . '/fun.inc';
  require_logined_session();

  header ('Content-Type: text/html; charset=UTF-8');

  if(isset($_POST["profile"])) {
    $username = $_POST["username"];
    $profile = $_POST["profile"];
    $userid = $_SESSION["userid"];
    profile_input($username, $profile, $userid);
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>インターネット 画廊</title>
    <link rel="stylesheet" type="text/css" href="./css/tl.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="js/jquery.mousewheel.min.js"></script>
    <script src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript">
    $(function() {
        //コンテンツの横サイズ
        // var cont = $('#scroll_id');
        // var contW = $('.items').outerWidth(true) * $('div',cont ).length;
        // cont.css('width', contW);
        //スクロールスピード
        var speed = 70;
        //マウスホイールで横移動
        $('ul').mousewheel(function(event, mov) {
            //ie firefox
            $(this).scrollLeft($(this).scrollLeft() - mov * speed);
            //webkit
            $('body').scrollLeft($('body').scrollLeft() - mov * speed);
            return false;   //縦スクロール不可
        });
    });
    // $(function(){
    //   $(".caption").hover(function(){
    //     $(this).animate({
    //       "height" : "200px",
    //     }, 30).fadeTo(60, 0.8).css({
    //       "color" : "blue"
    //     });
    //   }).mouseout(function(){
    //     $(this).animate({
    //       "height" : "75px",
    //     }, 60).fadeTo(20, 1).css({"color" : "black"});
    //   });
    // });
    // $(".caption").hover(
    //   function () {
    //     $(this).append($("<span> ***</span>"));
    //   },
    //   function () {
    //     $(this).find("span:last").remove();
    //   }
    // );
    </script>
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
            $userid = $_SESSION["userid"];
            $icon = icon_output($userid);
            print "<a href='./tl.php?personal=".$userid."'><img src='{$icon}' alt='アイコン' /></a>";
          ?>
          </p>
        </div>
        <!-- icon -->
        <!-- buttons -->
        <div id="buttons">
          <p>
            <a class="btn" href='./drawing.php'>お絵描き画面へ</a>
            <?php if(empty($_GET["search"])): ?>
            <a class="btn" href='./personal.php'>プロフィール編集</a>
              <a class="btn" href='./tl.php?search=friends'>友だちを探す</a>
            <?php else :?>
              <a class="btn" href='./tl.php' style="width:100px">TLへ</a>
              <a class="btn" href='./personal.php'>プロフィール編集</a>
            <?php endif;?>
            <a  class="btn" href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a>
          </p>
        </div>
        <!-- buttons -->
        <!-- profile_box -->
        <div id="profile_box">
          <h2>
            <?php
              $username = username_output($userid);
              print $username;
            ?>
          </h2>
          <p>
          <?php
            $profile = profile_output($userid);
            print $profile;
          ?>
          </p>
        </div>
        <!-- profile_box -->
      </div>
      <!-- profile -->
      </div>
      <!-- header -->
      <!-- wrap -->
      <div id="wrap">
      <!-- tl -->
      <div id="tl">
        <!-- ul -->
        <ul class="scroll_lst">
          <?php
            require_once('fun.inc');
            if(isset($_GET["search"]) && $_GET["search"] == "friends") {
                $result = search_tl($userid);
            }else if(isset($_GET["personal"])){
              $personal = $_GET["personal"];
              $result = personal_tl($personal);
            }else {
              $result = tl($userid);
            }
            $cnt = 0;
            while($row = mysqli_fetch_array($result)) {
              if(!is_null($row["path"])) {
                $cnt ++;
                if(!empty($row["introduction"])) {
                  $caption = "<div class='caption' onclick='location.href=\"./tl.php?personal=".$row["user_id"]."\"'>".$row["img_id"] . "." . $row["title"] . "<br /> 作者：" . $row["user_name"] ."<br />  <p class='intro'>一言：". $row["introduction"]."</p></div>";
                }else {
                  $caption = "<div class='caption' onclick='location.href=\"./tl.php?personal=".$row["user_id"]."\"'>".$row["img_id"] . "." . $row["title"] . "<br /> 作者：" . $row["user_name"]."</div>";
                }
                $likes = like($row["img_id"]);

                //画像の挿入
                print "<li class='items'>". $caption." <img class='gallery' src='".$row["path"]."' alt='".$row["title"]."'><br/>";

                print "<div id='snsbtn'>";
                //いいねボタン
                print "<input type='button' class='btn' id='likeBtn".$row["img_id"]."' value='いいね！".$likes ."'>";
                if(isset($_GET["search"]) || isset($_GET["personal"])) {
                  $result_follow = disp_follow($userid);
                  $cnt_follow = cnt_follow($userid);
                  $cnt_follow_check = 0;
                  while($row_follow = mysqli_fetch_array($result_follow)) {
                    // print $row_follow["follow"];
                    if(isset($_GET["personal"]) && $row_follow["follow"] == $_GET["personal"]) {
                      break;
                    }else if($cnt_follow_check == $cnt_follow-1 || isset($_GET["search"]) && $_GET["search"] == "friends") {
                      //フォローボタン
                      print "   <input type='button' class='followBtn".$row["user_id"]." btn followBtn' value='フォロー！'/></div></li>";
                      break;
                    }
                    $cnt_follow_check++;
                  }
                }else {
                  print "</div></li>";
                }
                //いいね機能
                print "<script type='text/javascript'>";
                print "$(function() {";
                print " $('#likeBtn".$row["img_id"]."').on('click',function() {";
                print "   $.ajax({";
                print "     type: 'POST',";
                print "     url: './like.php',";
                print "     data: {";
                print "       'imgid':".$row["img_id"];
                print "      }";
                print "   })";
                print "   .done( function(data) {";
                print "     $('#likeBtn".$row["img_id"]."').val(data);";
                print "     console.log(data);";
                print "   })";
                print "   .fail( function(data) {";
                print "     $('#likeBtn".$row["img_id"]."').val(data);";
                print "     console.log(data);";
                print "    })";
                print "  });";
                print "});";
                print "</script>";

                //フォロー機能
                if(isset($_GET["search"]) || isset($_GET["personal"])) {
                  print "<script type='text/javascript'>";
                  print "$(function() {";
                  print " $('.followBtn".$row["user_id"]."').on('click',function() {";
                  print "   $.ajax({";
                  print "     type: 'POST',";
                  print "     url: './follow.php',";
                  print "     data: {";
                  print "       'follow':'".$row["user_id"]."',";
                  print "       'userid':'".$userid."'";
                  print "      }";
                  print "   })";
                  print "   .done( function(data) {";
                  print "     $('.followBtn".$row["user_id"]."').val(data);";
                  print "     console.log(data);";
                  print "   })";
                  print "   .fail( function(data) {";
                  print "     $('.followBtn".$row["user_id"]."').val(data);";
                  print "     console.log(data);";
                  print "    })";
                  print "  });";
                  print "});";
                  print "</script>";
                }

//                print "<for"m action='./like.php' method='post'><input id='likeBtn' type='submit' onclick='()' name='like' value='いいね。".$likes ."'><input type='hidden' id=".$row["img_id"]." name='imgid' value=".$row["img_id"]."></form></li>";
                //<!--*****index.php-->
                //print "<button class='letsVote' data-id='".$row["img_id"]."' data-numhtml='countNum1'><span class='countNum1'>".getVoteCount($row["img_id"])."</span>いいね！</button></div>";
                //<!--/////index.php-->
              }
            }
            if($cnt==0) {
              print "データはありません。";
            }
    //          while($row) {
      //          print "<p><li class='item'>". $row["id"].".".$row["title"]."</p> <img src='".$row["path"]."' alt='".$row["title"]."'></li><br />";
        //      }
          ?>
          <!--
          <form action='./like.php' method='post'>
            <input id='likeBtn' type='submit' onclick='()' name='like' value='いいね。".$likes ."'>
            <input type='hidden' name='imgid' value=".$row["img_id"].">
          </form>
           <button id="btn" class="load-btn">Load More..</button> -->
        </ul>
        <!-- ul -->
      </div>
      <!-- tl -->
    </div>
    <!-- wrap -->
    <div id="footer">
      <p>© 2018 yusuke sato</p>
    </div>
  </body>
</html>
