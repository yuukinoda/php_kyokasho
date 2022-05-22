<?php
require('dbconnect.php');
session_start();


if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();
} else {
    //
    header('Location: login.php');
    exit();
}

//投稿を記録する

if (!empty($_POST)) {
    if ($_POST['message'] != '') {
        $message = $db->prepare('INSERT INTO posts SET member_id=?,message=?,created=NOW()');
        $message->execute(array(
            $member['id'],
            $_POST['message']
        ));// ここのDB設計はreply_idをヌル許可にしておく,readme参照ください

        header('Location:index.php');
        exit();
    }
}

$posts = $db->query('SELECT m.name,m.picture,p.* FROM members m,posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

?>


<div id="lead">
    <p>メールアドレスとパスワードを記入してログインしてください</p>
    <p>入会手続きがまだの方はこちらからどうぞ</p>

    <p>&raquo;<a href="join/">入会手続きをする</a></p>
</div>

<form action="" method="post">
    <dl>
        <dt>メッセージをどうぞ</dt>
        <dd>
            <textarea name="message" cols="50" rows="5"></textarea>
        </dd>
    </dl>


    <div>
        <input type="submit" value="投稿する"/>
    </div>
    </dl>
</form>

<?php
foreach ($posts as $post):
?>

<div class="msg">
    <img src="member_picture/<?php echo htmlspecialchars($post['picture'],ENT_QUOTES); ?> " width="48" height="48" alt="<?php echo htmlspecialchars($post['name'],ENT_QUOTES);?>" />
    <p><?php echo htmlspecialchars($post['message'],ENT_QUOTES); ?> <span class="name">(<?php echo htmlspecialchars($post['name'],ENT_QUOTES); ?> )</span></p>
    <p class="day"><?php echo htmlspecialchars($post['created'],ENT_QUOTES); ?> </p>
</div>
<?php
endforeach;
?>