{{title: "Сообщения"}}
{{content-class: "dialogue"}}
{{script1: "../js/update.js"}}

<div class="top">
    <a  class="back" href="/messages">Назад</a>
    <span class="login"><?= $_SESSION['view']['companion_info']['login'] ?></span>
    <div class="right-block">
        <div class="actions">
            <a href="">Очистить</a>
        </div>
        <a href="" class="image-wrapper">
            <img src="/media/uploads/<?=
                $_SESSION['view']['companion_info']['avatar']? $_SESSION['view']['companion_info']['avatar']: "default.png"
            ?>" alt="">
        </a>
    </div>
</div>

<div class="center">
    <?php $lastMessageUserId = 0; ?>
    <?php foreach($_SESSION['view']['messages'] as $message) { ?>
        <?php if($lastMessageUserId !== $message['user_id']) { ?>
            <div class="message">
                <a class="image-wrapper" href="/profile/<?= $message['user_id'] ?>">
                    <?php if( $message['user_id'] !== $_SESSION['auth']['id']) { ?>
                        <img src="/media/uploads/<?=
                        $_SESSION['view']['companion_info']['avatar']? $_SESSION['view']['companion_info']['avatar']: "default.png"
                        ?>" alt="you">
                    <?php } else { ?>
                        <img src="/media/uploads/<?=
                        $_SESSION['view']['user_info']['avatar']? $_SESSION['view']['user_info']['avatar']: "default.png"
                        ?>" alt="companion">
                    <?php } ?>
                </a>

                <div class="right">
                    <?php if( $message['user_id'] !== $_SESSION['auth']['id']) { ?>
                        <span class="login"><?= $_SESSION['view']['companion_info']['login'] ?></span>
                    <?php } else { ?>
                        <span class="login"><?= $_SESSION['view']['user_info']['login'] ?></span>
                    <?php } ?>
                    <span><?= $message['message']?></span>
                </div>
            </div>
            <?php $lastMessageUserId = $message['user_id'] ?>
        <?php } else { ?>
            <div class="message empty">
                <div class="empty-avatar"></div>
                <div class="right">
                    <span class="text"><?= $message['message']?></span>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<div class="loading-wrapper">
    <span class="loading">Печатает...</span>
</div>

<div class="bot">
    <textarea placeholder="Новое сообщение"></textarea>
    <input id="companionId" type="hidden" value="<?= $_SESSION['view']['companion_info']['id'] ?>">
    <input id="lastUserId" type="hidden" value="<?= $lastMessageUserId ?>">
    <button>Send</button>
</div>