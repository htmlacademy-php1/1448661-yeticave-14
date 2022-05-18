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
                    <a href="/all-lots.php?categoryId=<?= $category['id']?>">
                        <?= htmlspecialchars($category['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <?php $className = !empty($errors) ? 'form--invalid' : ""; ?>
    <form class="form container <?= $className; ?>" action="" method="post">
        <h2>Вход</h2>
        <?php $className = isset($errors['email']) ? "form__item--invalid" : ""; ?>
        <div class="form__item <?= $className; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= getPostVal('email'); ?>">
            <span class="form__error"><?= $errors['email'] ?? ""; ?></span>
        </div>
        <?php $className = isset($errors['password']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--last <?= $className; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <span class="form__error"><?= $errors['password'] ?? ""; ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
