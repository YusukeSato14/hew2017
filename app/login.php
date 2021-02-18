<?php

  require_once('./fun.inc');
  if(isset($_POST["register"])) {
    $reg = register();
    //drawing.php?icon=Settingへ飛ばすフラグ
    $flg = 1;
  }else {
    $reg = true;
    $flg = 0;
  }
  require_unlogined_session();

  foreach (['userid','password','token','submit'] as $key) {
    $$key = (string)filter_input(INPUT_POST, $key);
  }

  // エラーを格納する配列を初期化
  $errors = [];

  // POSTのときのみ実行
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ( $userid === "" || $password === "" ) {
      $errors[] = 'ユーザ名またはパスワードが入力されていません。';
    } else {

      $userid = h($userid);
      $password = h($password);

      $dbtype  = 'mysql';
      $host    = 'localhost';
      $db      = 'drawing';
      $charset = 'utf8';

      $dsn = "$dbtype:host=$host; dbname=$db;charset=$charset;";
      $db = new PDO ( $dsn, 'root', '' );
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = 'SELECT * FROM member WHERE user_id = ?';
      $prepare = $db->prepare($sql);
      $prepare->bindValue(1, $userid, PDO::PARAM_STR);
      $prepare->execute();

      $result = $prepare->fetch(PDO::FETCH_ASSOC);

      if (!$reg) {
        $errors[] = "すでにそのIDのユーザがいます！残念！";
      }else if (validate_token(filter_input(INPUT_POST, 'token')) && password_verify( $password, password_hash($result["password"], PASSWORD_DEFAULT ))) {
        // 認証が成功
        // セッションIDの追跡を防ぐ
        session_regenerate_id(true);
        //ユーザ名をセット
        $_SESSION['username'] = $result['user_name'];
        //ユーザIDもセット
        $_SESSION['userid'] = $userid;
        // ログイン後に/に遷移
        if($flg == 1) {
          header ('Location: ./drawing.php?icon=setting');
          exit;
        }else {
          header ('Location: ./drawing.php');
          exit;
        }
      }else {
        // 認証が失敗
        $errors[] = "ユーザ名またはパスワードが違います！";
      }
    }
  }
  header ('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html>
  <head>
    <title>ログインページ</title>
    <script type="text/javascript">
      function ChangeTab(tabname) {
        // タブメニュー実装
        document.getElementById('tab1').style.display = 'none';
        document.getElementById('tab2').style.display = 'none';
        // タブメニュー実装
        document.getElementById(tabname).style.display = 'block';
      }
    </script>

    <style type="text/css">
      /* ▼ タブメニュー全体の設定 */
      div.tabbox { margin: 15px 0 0 0; padding: 0px; width:600px; height:200px;}
      /* ▼ タブ部分のマージンとパディング領域を設定 */
      p.tabs { margin: 0px; padding: 0px; }
      p.tabs a {
         /* ▼ リンクをタブのように見せる */
         display: block; width: 100px; float: left;
         margin: 0px 3px 0px 0px; padding: 3px;
         text-align: center;
         font-size:12px;
      }
      /* ▼ タブごとの配色設定 */
      p.tabs a.tab1 {text-decoration:none; background-color:#fafafa;border-right:1px solid #dcdcdc;border-left:1px solid #dcdcdc;border-top: 3px solid rgba(170, 0, 0, 0.85);  color: #666; font-weight:600;}
      p.tabs a.tab2 {text-decoration:none; background-color:#fafafa;border-right:1px solid #dcdcdc;border-left:1px solid #dcdcdc;border-top: 3px solid rgba(0, 170, 0, 0.85);  color: #666; font-weight:600;}
      p.tabs a.tab3 {text-decoration:none; background-color:#fafafa;border-right:1px solid #dcdcdc;border-left:1px solid #dcdcdc;border-top: 3px solid rgba(0, 0, 170, 0.85);  color: #666; font-weight:600;}
      p.tabs a:hover {opacity:0.7;filter:alpha(opacity=70);}
      /* ▼ タブ中身のボックス */
      div.tab {
         /* ▼ ボックス共通の装飾 */
         height: 270px;  clear: left;
      }
      /* ▼ 各ボックスの配色設定 */
      div#tab1 { border: 2px solid #DDD; background-color: #fbfbfb; }
      div#tab2 { border: 2px solid #DDD; background-color: #fbfbfb; }
      div#tab3 { border: 2px solid #DDD; background-color: #fbfbfb; }
      div.tab p { margin: 0.5em; }
    </style>
  </head>
  <body>
    <!-- タブメニュー実装　HTML文 -->

    <div class="tabbox">
      <p class="tabs">
        <a href="#tab1" class="tab1" onclick="ChangeTab('tab1'); return false;">ログイン</a>
        <a href="#tab2" class="tab2" onclick="ChangeTab('tab2'); return false;">新規登録</a>
      </p>
        <div id="tab1" class="tab">
          <h1>ログインしてください</h1>
          <?php if ($errors): ?>
            <ul>
              <?php foreach ($errors as $err): ?>
                <li><?=h($err)?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <form method="post" action="">
            <p>ユーザID: <input type="text" name="userid" value="<?php echo $userid = isset($_POST['userid']) ? $_POST['userid']: ''; ?>"></p>
            <p>パスワード: <input type="password" name="password" value=""></p>
            <!-- トークン -->
            <input type="hidden" name="token" value="<?=h(generate_token())?>">    <!--<input type="hidden" name="token" value="<?php //echo password_hash('1111', PASSWORD_DEFAULT, array('cost', 10)) ?>">-->
            <p><input type="submit" name="submit" value="ログイン"></p>
          </form>
        </div>

        <div id="tab2" class="tab">
          <h1>ようこそ！アカウントを作る！</h1>
          <form method="post" action="./login.php">
            <p>ユーザ名: <input type="text" name="username" placeholder="10文字以内" /></p>
            <p>ユーザID: <input type="text" name="userid" /></p>
            <p>パスワード: <input type="password" name="password" /></p>
            <!-- トークン -->
            <input type="hidden" name="token" value="<?=h(generate_token())?>">    <!--<input type="hidden" name="token" value="<?php echo password_hash('1111', PASSWORD_DEFAULT, array('cost', 10)) ?>">-->
            <p><input type="submit" name="register" value="登録"></p>
          </form>
        </div>
    </div><!-- tabbox -->

    <!-- ページを開いた際の最初に表示されるタブの選択 -->
    <script type="text/javascript">
       ChangeTab('tab1');
    </script>
    <!-- タブメニュー実装　HTML文 -->
  </body>
</html>
