<nav>
    <?php if(!isset($_SESSION['auth'])) {?>
        <a class="link" href="/login">Вход</a>
        <a class="link" href="/register">Регистрация</a>
    <?php } else { ?>
        <?php if($_SESSION['auth']['status'] == 1) { ?>
            <a class="link" href="/profile/<?= $_SESSION['auth']['id'] ?>">Профиль</a>
            <a class="link" href="/search">Пользователи</a>
            <a class="link" href="/messages">Сообщения</a>
            <a class="link" href="/logout">Выход</a>
        <?php } elseif($_SESSION['auth']['status'] == 2) { ?>
            Статус admin
        <?php } ?>
    <?php } ?>
</nav>
