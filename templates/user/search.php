{{title: "Пользователи"}}
{{script: "../js/search.js"}}

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
                        <?php if(time() - $user['timestamp'] < 300) { ?>
                            <span style="color: cornflowerblue;">online</span>
                        <?php } else { ?>
                            <span style="color: red;">offline</span>
                        <?php } ?>
                    </div>
                    <div class="bot">
                        <a href="">Написать</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div>Пусто</div>
    <?php } ?>
</div>