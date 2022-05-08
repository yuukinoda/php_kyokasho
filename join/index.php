<?php

ini_set("display_errors", "Off");
session_start();

if(!empty($_POST)) {
    //エラー項目の確認
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }

    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }

    if (strlen($_POST['password'] < 4)) {
        $error['password'] = 'length';
    }

    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }

    $fileName = $_FILES['image']['name'];
    if(!empty($fileName)){
        $ext = substr($fileName,-3);
        if($ext != 'jpg' && $ext!='gif'){
            $error['image']='type';
        }
    }



    if (empty($error)) {
        //画像をアップロードする
        $image = date('YmdHis').$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/'.$image);

        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('Location:check.php');
        exit();
    }
}

?>

<p>次のフォームに必要事項をご記入ください。</p>

<form action="" method="post" enctype="multipart/form-data">
    <dl>
        <dt>ニックネーム<span class="required">必須</span></dt>
        <dd><input type="text" name="name" size="35" maxlength="255"
                   value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>"/></dd>
        <?php if ($error['name'] == 'blank'): ?>
            <p class="error">*ニックネームを入力してください</p>
        <?php endif; ?>
        <dt>メールアドレス<span class="required">必須</span></dt>
        <dd><input type="text" name="email" size="35" maxlength="255"/
            value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>">
        </dd>
        <?php if ($error['email'] == 'blank'): ?>
            <p class="error">*メールアドレスを入力してください</p>
        <?php endif; ?>
        <dt>パスワード<span class="required">必須</dt>
        <dd><input type="password" name="password" size="10" maxlength="20"
                   　value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>"/></dd>
        <?php if ($error['password'] == 'blank'): ?>
            <p class="error">*パスワードを入力してください</p>
        <?php endif; ?>
        <?php if ($error['password'] == 'length'): ?>
            <p class="error">*パスワードは4文字以上で入力してください</p>
        <?php endif; ?>
        <dt>写真など</dt>
        <dd><input type="file" name="image" size="35"/></dd>
        <?php if ($error['image'] == 'type'): ?>
            <p class="error">*写真などは「.gif」または「.jpg]の画像を指定してください</p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p class="error">*恐れ入りますが、画像を改めて指定してください</p>
        <?php endif; ?>
        <div><input type="submit" value="入力内容を確認する"/></div>
    </dl>
</form>