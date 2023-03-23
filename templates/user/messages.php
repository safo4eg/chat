{{title: "Сообщения"}}
{{content-class: "messages"}}
{{script1: "../js/update.js"}}

<?php foreach($_SESSION['view']['dialogues'] as $dialogue) { ?>
    <a href="/dialogue/<?=$dialogue['members_id']?>" class="dialog">
        <div class="image-wrapper">
            <img src="media/uploads/<?= $dialogue['avatar']? $dialogue['avatar']: 'default.png'  ?>" alt="">
        </div>

        <div class="info">
            <div class="top">
                <div class="name"><?=$dialogue['login']?></div>
                <div class="online">
                    <?php if(time() - $dialogue['u_timestamp'] < 60) { ?>
                        <span style="color: cornflowerblue;">online</span>
                    <?php } else { ?>
                        <span style="color: red;">offline</span>
                    <?php } ?>
                </div>

                <span class="message">
                    <?= $dialogue['message_user_id'] === $_SESSION['auth']['id']? "Вы: {$dialogue['last_message']}": $dialogue['last_message']?>
                </span>
            </div>
        </div>
    </a>
<?php } ?>
