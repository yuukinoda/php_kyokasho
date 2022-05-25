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
        $message = $db->prepare('INSERT INTO posts SET member_id=?,message=?, reply_post_id = ?,created=NOW()');
        $message->execute(array(
            $member['id'],
            $_POST['message'],
            $_POST['reply_post_id']
        ));// ここのDB設計はreply_idをヌル許可にしておく,readme参照ください

        header('Location:index.php');
        exit();
    }
}

$posts = $db->query('SELECT m.name,m.picture,p.* FROM members m,posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

//返信の場合
if(isset($_REQUEST['res'])){
    $response = $db->prepare('SELECT m.name, m.picture,p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id =? ORDER BY p.created DESC');
    $response->execute(array($_REQUEST['res']));

    $table = $response->fetch();
    $message = '@'.$table['name'].' '.$table['message'];

}

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
            <textarea name="message" cols="50" rows="5"><?php
                echo htmlspecialchars($message,ENT_QUOTES);
                ?></textarea>
            <input type="hidden" name="reply_post_id" value="<?php echo htmlspecialchars($_REQUEST['res'],ENT_QUOTES);?>" />

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
    <p><?php echo htmlspecialchars($post['message'],ENT_QUOTES); ?> <span class="name">(<?php echo htmlspecialchars($post['name'],ENT_QUOTES); ?> )</span>
    [<a href="index.php?res=<?php echo htmlspecialchars($post['id'],ENT_QUOTES); ?>" > </a>]

    </p>
    <p class="day"><?php echo htmlspecialchars($post['created'],ENT_QUOTES); ?> </p>
</div>
<?php
endforeach;
?>