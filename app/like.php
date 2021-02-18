<?php
session_start();
require_once('fun.inc');

  $con = mysqli_connect("localhost", "root", ""); //mysql -u root -h localhost
  mysqli_set_charset($con, "utf8");
  mysqli_select_db($con, "drawing");
  $sql = "SELECT * FROM likes WHERE img_id = ? AND liker = ?";//慣習的にsql文では大文字、小文字の使い分けを行う。見やすさなどのためにも。
  $stmt = mysqli_prepare($con, $sql);
  $imgid = $_POST["imgid"];
  $userid = $_SESSION["userid"];
  mysqli_stmt_bind_param($stmt,'is', $imgid ,$userid);//VARCHAR(200)って200文字？
  mysqli_stmt_execute($stmt);//いいねフラグの更新
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_array($result);

  if(!isset($row["like_flg"])) {
    $sql = "INSERT INTO likes VALUES(?, ?, 1)";//慣習的にsql文では大文字、小文字の使い分けを行う。見やすさなどのためにも。
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt,'is', $imgid ,$userid);//VARCHAR(200)って200文字？
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $likes = "いいね！".like($imgid);
    echo $likes;
  }else if($row["like_flg"] == 0) {
    $sql = "UPDATE likes SET like_flg = 1 WHERE img_id = ? AND liker = ?";//慣習的にsql文では大文字、小文字の使い分けを行う。見やすさなどのためにも。
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt,'is', $imgid ,$userid);//VARCHAR(200)って200文字？
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $likes = "いいね！".like($imgid);
    echo $likes;
  }else {
    $sql = "UPDATE likes SET like_flg = 0 WHERE img_id = ? AND liker = ?";//慣習的にsql文では大文字、小文字の使い分けを行う。見やすさなどのためにも。
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt,'is', $imgid ,$userid);//VARCHAR(200)って200文字？
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $likes = "いいね！".like($imgid);
    echo $likes;
  }
  mysqli_stmt_close($stmt); //閉じるタイミングについて
//  header('Location: ./tl.php');
//  exit;
?>
