<?php
/**
 * @var array $lots
 * @var array $categories
 */
?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= $category['name']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <?php foreach ($lots

        as $lot): ?>
        <h2><?= $lot['title']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= $lot['url_image']; ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['name']; ?></span></p>
                <p class="lot-item__description"><?= $lot['description']; ?></p>
            </div>
            <?php endforeach; ?>
            <?php if ($_SESSION['name'] ?? false)  : ?>
            <div class="lot-item__right ">
                <?php foreach ($lots

                               as $lot): ?>
                <div class="lot-item__state">
                    <?php $time = getDtRange($lot['end_date'], 'now') ?>
                    <div class="lot-item__timer timer <?php if ($time[0] < 1): ?>timer--finishing<?php endif; ?> ">
                        <?= sprintf("%02d", $time[0]) . ':' . sprintf("%02d", $time[1]); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= checkPriceValue($lot['price']); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= checkPriceValue($lot['min_price']); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000">
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <div class="history">
                    <h3>История ставок (<span>10</span>)</h3>
                    <table class="history__list">
                        <tr class="history__item">
                            <td class="history__name">Иван</td>
                            <td class="history__price">10 999 р</td>
                            <td class="history__time">5 минут назад</td>
                        </tr>

                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
