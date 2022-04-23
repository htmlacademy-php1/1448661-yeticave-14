<?php

/**
 * @var array $categories
 * @var array $errors
 */

?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?= $category['name']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php $classname = !empty($errors) ? "form--invalid" : "" ?>
    <form class="form container <?= $classname; ?> " action="" method="post" autocomplete="off"> <!-- form
    --invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <?php $classname = isset($errors['email']) ? "form__item--invalid" : "" ?>
        <div class="form__item <?= $classname; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= getPostVal('email'); ?>">
            <span class="form__error"><?= $errors['email'] ?? ""; ?></span>
        </div>
        <?php $classname = isset($errors['password']) ? "form__item--invalid" : "" ?>
        <div class="form__item <?= $classname; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <span
                class="form__error"><?= $errors['password'] ?? ""; ?></span>
        </div>
        <?php $classname = isset($errors['name']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= getPostVal('name') ?>">
            <span class="form__error"><?= $errors['name'] ?? ""; ?></span>
        </div>
        <?php $classname = isset($errors['contacts']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $classname; ?>">
            <label for="contacts">Контактные данные <sup>*</sup></label>
            <textarea id="contacts" name="contacts"
                      placeholder="Напишите как с вами связаться"><?= getPostVal('contacts') ?></textarea>
            <span
                class="form__error"><?= $errors['contacts'] ?? ""; ?></span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="/login.php">Уже есть аккаунт</a>
    </form>
</main>
