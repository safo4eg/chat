{{title: "Пользователи"}}
{{script1: "../js/search.js"}}
{{script2: "../js/update.js"}}
{{script3: "../js/modal/message-search.js"}}

<div class="search">
    <input type="text" placeholder="Найти пользователя">
</div>

<div class="user-wrapper">
    <?php if(isset($_SESSION['view']['users'])) { ?>
        <?php foreach($_SESSION['view']['users'] as $user) { ?>
            <div class="user">
                <div class="avatar">
                    <?php if(isset($user['avatar'])) { ?>
                        <img src="../media/uploads/<?= $user['avatar'] ?>" alt="">
                    <?php } else { ?>
                        <img src="../media/uploads/default.png" alt="">
                    <?php } ?>
                </div>

                <div class="info">
                    <div class="top">
                        <a href="/profile/<?= $user['id'] ?>"><h3><?= $user['login'] ?></h3></a>
                        <?php if(time() - $user['timestamp'] < 60) { ?>
                            <span style="color: cornflowerblue;">online</span>
                        <?php } else { ?>
                            <span style="color: red;">offline</span>
                        <?php } ?>
                    </div>
                    <div class="bot">
                        <a href="" id="openModalMessage">Написать</a>

                        <div class="modal" id="modalMessage">
                            <div class="window">
                                <div class="top">
                                    <h3>Написать <?= $user['login'] ?></h3>
                                </div>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <textarea name="message"></textarea>
                                <div class="bot">
                                    <button class="send-message">Отправить</button>
                                    <button id="close-message">Отмена</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div>Пусто</div>
    <?php } ?>
</div>
