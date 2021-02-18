<?php
  function post($userid, $username) {
    if(isset($_POST["imagedata"]) && isset($_POST["title"])) {
      $imagedata = $_POST["imagedata"];
      $title = $_POST["title"];
      $introduction = $_POST["introduction"];
      //タイトルの空白を防ぎたい
      if(empty($_POST["title"])) {
        $title = "no-title";
      }
      $imagedata= base64_decode($imagedata);
      $image = imagecreatefromstring($imagedata);

      $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
      mysqli_set_charset($con, "utf8");
      mysqli_select_db($con, "drawing");
      //挿入
      $sql = "INSERT INTO imgs (title, user_id, introduction) VALUES(?, ?, ?)";//ins_dtはNOW()というmysqlの関数を使ってもいい。
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt,'sss', $title, $userid, $introduction);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      $sql = "UPDATE imgs SET path = ? WHERE img_id = ?";
      $stmt = mysqli_prepare($con, $sql);

      $imgid = mysqli_insert_id($con);
      $idPath = "./imgs/".$imgid.".png";
      mysqli_stmt_bind_param($stmt,'si', $idPath, $imgid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      imagepng($image, $idPath);
      imagedestroy($image);
      mysqli_stmt_close($stmt);
    }
  }

  //アイコンの登録
  function icon($userid) {
    if(isset($_POST["imagedata"])) {
      $imagedata = $_POST["imagedata"];;
      //タイトルの空白を防ぎたい
      $imagedata= base64_decode($imagedata);
      $image = imagecreatefromstring($imagedata);

      $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
      mysqli_set_charset($con, "utf8");
      mysqli_select_db($con, "drawing");
      $iconpath = "./icon/".$userid.".png";

      //挿入
      $sql = "UPDATE member set icon =? WHERE user_id = ?";//ins_dtはNOW()というmysqlの関数を使ってもいい。
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt,'ss', $iconpath, $userid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      imagepng($image, $iconpath);
      imagedestroy($image);
      mysqli_stmt_close($stmt);
    }
  }


  //タイムラインの表示
  function tl($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT member.user_id, img_id, title, user_name, path, introduction from imgs INNER JOIN member ON imgs.user_id = member.user_id inner join follow on imgs.user_id = follow.follow where follow.user_id  = ? ORDER BY img_id DESC;";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
    mysqli_stmt_close($stmt);
  }

  //新しい友だちを探す
  function search_tl($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT * FROM imgs INNER JOIN member ON imgs.user_id = member.user_id WHERE imgs.user_id IN (SELECT user_id FROM member WHERE user_id NOT IN (SELECT follow FROM follow WHERE user_id = ?)) ORDER BY img_id DESC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
    mysqli_stmt_close($stmt);
  }

  //個人の絵のtl
  function personal_tl($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT * FROM imgs INNER JOIN member on imgs.user_id = member.user_id WHERE member.user_id = ? ORDER BY img_id DESC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }

  function disp_follow($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT * FROM follow WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }

  function cnt_follow($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT count(follow) FROM follow WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_array($result)) {
      return $row["count(follow)"];
    }
  }

  //いいね数表示
  function like($imgid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT SUM(like_flg) as sum FROM likes WHERE img_id = $imgid";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    return $row["sum"];
    mysqli_stmt_close($stmt);
  }




  //メンバーの新規登録
  function register() {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //挿入
    $sql = "INSERT INTO member(user_id, user_name, password) VALUES(? , ? , ?)";
    $stmt = mysqli_prepare($con, $sql);
    $userid = $_POST["userid"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    mysqli_stmt_bind_param($stmt,'sss',$userid, $username, $password);
    $exe = mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    //TLに表示するため、自分をフォローする。
    $sql = "INSERT INTO follow VALUES(? , ?)";
    $stmt = mysqli_prepare($con, $sql);
    $userid = $_POST["userid"];
    mysqli_stmt_bind_param($stmt,'ss',$userid, $userid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if(!$exe) {
      print mysqli_error($con);
      return false;
    }else {
      return true;
    }
  }


  /*
   * ログイン状態によってリダイレクト
   * 初回時または失敗時にはヘッダを送信してexitする
   */

  function require_unlogined_session () {
      // セッション開始
      @session_start();
      // ログインしていれば
      if (isset($_SESSION["username"])) {
          header('Location: ./tl.php');
          exit;
      }
  }

  function require_logined_session() {
      // セッション開始
      @session_start();
      // ログインしていなければlogin.phpに遷移
      if (!isset($_SESSION["username"])) {
          header('Location: ./login.php');
          exit;
      }
  }

  // CSRFトークンの生成
  function generate_token() {
      // セッションIDからハッシュを生成
      return hash ( 'sha256', session_id() );
  }

  // CSRFトークン
  function validate_token ($token) {
      return $token === generate_token();
  }

  // htmlspecialchars
  function h ($var) {
      if (is_array($var)){
          return array_map(h, $var);
      } else {
          return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
      }
  }


  //プロフィールデータのサーバ送信
  function profile_input($username, $profile, $userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //挿入
    if(empty($username)) {
      $sql = "UPDATE member SET profile = ? WHERE user_id = ?";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt,'ss', $profile, $userid);//VARCHAR(200)って200文字？
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }else if(empty($profile)) {
      $sql = "UPDATE member SET user_name = ? WHERE user_id = ?";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt,'ss', $username, $userid);//VARCHAR(200)って200文字？
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }else {
      $sql = "UPDATE member SET user_name = ? WHERE user_id = ?";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt,'ss', $username, $userid);//VARCHAR(200)って200文字？
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $sql = "UPDATE member SET profile = ? WHERE user_id = ?";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt,'ss', $profile, $userid);//VARCHAR(200)って200文字？
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    }
    mysqli_stmt_close($stmt);
  }

  //アイコン取得
  function icon_output($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT icon FROM member WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    return $row["icon"];
  }

  //ユーザ名の取得
  function username_output($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT user_name FROM member WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_array($result)) {
      return $row["user_name"];
    }
    mysqli_stmt_close($stmt);

  }

  //自己紹介の取得
  function profile_output($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT profile FROM member WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_array($result)) {
      return $row["profile"];
    }else {
      $row["profile"] = "";
      return $row["profile"];
    }
    mysqli_stmt_close($stmt);
  }

  //フォローしてる人の表示
  function follows($userid) {
    $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
    mysqli_set_charset($con, "utf8");
    mysqli_select_db($con, "drawing");
    //選択
    $sql = "SELECT user_id, user_name, icon FROM member WHERE user_id IN (SELECT follow  FROM follow WHERE user_id = ?) AND user_id != ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $userid, $userid);
    mysqli_stmt_execute($stmt);
    return $result = mysqli_stmt_get_result($stmt);
  }
 ?>
