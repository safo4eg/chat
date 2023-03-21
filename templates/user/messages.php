{{title: "Сообщения"}}
{{content-class: "messages"}}
{{script1: "../js/update.js"}}

<?php foreach($_SESSION['view']['dialogues'] as $dialogue) { ?>
    <a href="/dialogue/" class="dialog">
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

                <span class="message">message</span>
            </div>
        </div>
    </a>
<?php } ?>
