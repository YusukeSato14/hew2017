<?php
  require_once __DIR__ . '/fun.inc';
  require_logined_session();

  header ('Content-Type: text/html; charset=UTF-8');


?>
<!DOCTYPE html>
<html>
  <head>
    <title>プロフィール編集ページ</title>
    <link rel="stylesheet" type="text/css" href="./css/personal.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
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
            print "<a href='./tl.php?personal={$userid}'><img src='{$icon}' alt='アイコン' /></a>";
          ?>
          </p>
        </div>
        <!-- icon -->
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
        <!-- buttons -->
        <div id="buttons">
          <p>
            <a class="btn" href='./drawing.php'>お絵描き画面へ</a>
            <a class="btn" style="width:100px;" href='./tl.php'>TLへ</a>
            <?php if(empty($_GET["search"])): ?>
              <a class="btn" href='./tl.php?search=friends'>友だちを探す</a>
            <?php else :?>
              <a class="btn" href='./tl.php'>TLに戻る</a>
            <?php endif;?>
            <a  class="btn" href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a>
          </p>
        </div>
        <!-- buttons -->
      </div>
      <!-- profile -->
    </div>
    <!-- header -->

    <!-- wrap -->
    <div id="wrap">
      <!-- follows -->
      <div id="follows">
        <?php
          $follows = follows($userid);
          while($row =mysqli_fetch_array($follows)) {
            print "<div class='items'><p>".$row["user_name"]."</p><a href='./tl.php?personal=".$row["user_id"]."'><img class='icons' src='".$row["icon"]."' alt='".$row["user_name"]."'/></a>";
            print "<input type='button' class='unfollowBtn".$row["user_id"]." btn unfollow' value='アンフォロー'/></div>";
            //アンフォロー機能
//            if(isset($_GET["search"])) {
              print "<script type='text/javascript'>";
              print "$(function() {";
              print " $('.unfollowBtn".$row["user_id"]."').on('click',function() {";
              print "   $.ajax({";
              print "     type: 'POST',";
              print "     url: './follow.php',";
              print "     data: {";
              print "       'unfollow':'".$row["user_id"]."',";
              print "       'userid':'".$userid."'";
              print "      }";
              print "   })";
              print "   .done( function(data) {";
              print "     $('.unfollowBtn".$row["user_id"]."').val(data);";
              print "     console.log(data);";
              print "   })";
              print "   .fail( function(data) {";
              print "     $('.unfollowBtn".$row["user_id"]."').val(data);";
              print "     console.log(data);";
              print "    })";
              print "  });";
              print "});";
              print "</script>";
            }
  //        }
        ?>
      </div>
      <!-- follow -->
      <!-- setting -->
      <div id="setting">
        <form method="post" action="./tl.php">
          <ul>
            <li><label id="username">ユーザ名：</label></li>
            <li><input id="textbox" type="text" name="username" placeholder="10文字以内"/></li>
            <li><label id="profile">自己紹介文：</label></li>
            <li><textarea name="profile" placeholder="15文字以内"></textarea></li>
          </ul>
          <p id="profbtns">
            <input class="btn settingBtn" type="submit" value="更新">
            <input class="btn settingBtn" type="button" onclick="location.href='./drawing.php?icon=setting'" value="アイコンを新しく描く">
          </p>
        </form>
      </div>
      <!-- setting -->
    </div>
    <!-- wrap -->
    <div id="footer">
      <p>© 2018 yusuke sato</p>
    </div>
  </body>
</html>
