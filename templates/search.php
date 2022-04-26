<?php

/**
 * @var array $categories
 * @var array $searchResult
 * @var string $search
 * @var array $pages
 * @var $currentPage
 * @var $pageCount
 */

?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="../pages/all-lots.html"><?= $category['name']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= $search; ?></span>»</h2>
            <ul class="lots__list">
                <?php foreach ($searchResult as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src='<?= htmlspecialchars($lot['url_image']); ?>' width="350" height="260" alt="">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($lot['name']); ?></span>
                            <h3 class="lot__title"><a class="text-link"
                                                      href="lot.php?id=<?= $lot['id']; ?> "><?= htmlspecialchars($lot['title']); ?></a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span
                                        class="lot__cost"><?= priceFormatting(htmlspecialchars($lot['price'])); ?></span>
                                </div>
                                <?php $time = getDtRange($lot['end_date'], 'now') ?>
                                <div
                                    class="lot__timer timer <?php if ($time[0] < 1): ?>timer--finishing<?php endif; ?> ">
                                    <?= sprintf("%02d", $time[0]) . ':' . sprintf("%02d", $time[1]); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php if ($pageCount > 1) : ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev"><a href="/search.php?page=1">Назад</a></li>
                <?php foreach ($pages as $page) : ?>
                    <li class="pagination-item <?php if ($page === $currentPage): ?>pagination__item--active<?php endif; ?>">
                        <a href="/search.php?page=<?= $page; ?>"><?= $page; ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next"><a
                        href="/search.php?page=<?= $pageCount; ?>">Вперед</a></li>

            </ul>
        <?php endif; ?>
    </div>
</main>
