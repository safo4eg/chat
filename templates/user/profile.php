{{content-class: "profile"}}

<div class="avatar">
    <?php if(!isset($_SESSION['view']['profile']['avatar'])) { ?>
        <img src="../media/uploads/default.png" alt="">
    <?php } else { ?>
        <img src="../media/uploads/<?= $_SESSION['view']['profile']['avatar'] ?>" alt="">
    <?php } ?>
</div>

<div class="right">
        <?php if(isset($_SESSION['view']['myself'])) { ?>
            {{title: "Профиль"}}
            <h3>Ваш профиль <?= $_SESSION['view']['profile']['login'] ?> </h3>
            <div class="settings">
                    <div class="item">
                        <a href="">Сменить пароль</a>
                    </div>

                    <div class="item">
                        <a href="">Сменить аватарку</a>
                    </div>
            </div>
        <?php } else { ?>
            {{title: "<?= $_SESSION['view']['profile']['login'] ?>"}}
            <h3><?= $_SESSION['view']['profile']['login'] ?></h3>
            <div class="settings">
                <div class="item">
                    <a href="">Написать</a>
                </div>

                <div class="item">
                    <a href="">Пожаловаться</a>
                </div>
            </div>
        <?php } ?>
</div>