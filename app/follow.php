<?php
  $con = mysqli_connect(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"));
  mysqli_set_charset($con, "utf8");
  mysqli_select_db($con, "drawing");
  if(isset($_POST["follow"])) {
    $sql = "INSERT INTO follow VALUES(?, ?)";//慣習的にsql文では大文字、小文字の使い分けを行う。見やすさなどのためにも。
    $stmt = mysqli_prepare($con, $sql);
    $userid = $_POST["userid"];
    $follow = $_POST["follow"];
    mysqli_stmt_bind_param($stmt,'ss', $userid, $follow);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    echo "フォローしたよ！";
  }else if(isset($_POST["unfollow"])) {
    $sql = "DELETE FROM follow WHERE user_id = ? AND follow = ?";
    $stmt = mysqli_prepare($con, $sql);
    $userid = $_POST["userid"];
    $unfollow = $_POST["unfollow"];
    mysqli_stmt_bind_param($stmt,'ss', $userid, $unfollow);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    echo "アンフォローしたよ";
  }
?>
