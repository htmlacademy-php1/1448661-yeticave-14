<?php
/**
 * @var  $categories
 * @var  $lots
 * @var $pages
 * @var $currentPage
 * @var $pageCount
 * @var $categoryId
 * @var $link
 */

?>

<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) :?>
            <li class="nav__item <?php if ($categoryId === $category['id']) :
                ?>nav__item--current <?php
                                 endif;?>">
                <a href="/all-lots.php?categoryId=<?= $category['id']?>">
                    <?= htmlspecialchars($category['name']); ?>
                </a>
            </li>
            <?php endforeach ;?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <?php foreach ($categories as $category) :?>
                <?php if ($categoryId === $category['id']) : ?>
                <h2>Все лоты в категории <span><?= '«' . htmlspecialchars($category['name']) . '»';?></span></h2>
                <?php endif;?>
            <?php endforeach ;?>

            <ul class="lots__list">

                <?php foreach ($lots as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src='<?= htmlspecialchars($lot['image']); ?>' width="350" height="260" alt="">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($lot['name']); ?></span>
                            <h3 class="lot__title">
                                <a class="text-link" href="lot.php?id=<?= $lot['id']; ?> ">
                                    <?= htmlspecialchars($lot['title']); ?>
                                </a>
                            </h3>
                            <div class="lot__state">

                                <div class="lot__rate">
                                        <?php $lotBets = getLotBets($link, $lot['id']) ;?>
                                    <span class="lot__amount">
                                        <?= count($lotBets) === 0 ? 'Стартовая цена' :
                                            count($lotBets) . ' ' . getNounPluralForm(
                                                count($lotBets),
                                                'ставка',
                                                'ставки',
                                                'ставок'
                                            ) ;?>
                                    </span>
                                    <span
                                        class="lot__cost"><?= priceFormatting(htmlspecialchars($lot['price']));?>
                                    </span>
                                </div>
                                <?php $time = getDtRange(htmlspecialchars($lot['end_date']), 'now') ?>
                                <div
                                    class="lot__timer timer <?php if ($time[0] < 1) :
                                        ?>timer--finishing<?php
                                                            endif; ?> ">
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

                <li class="pagination-item pagination-item-prev">
                    <a<?php if ($currentPage !== 1) :
                        ?> href="<?= buildPaginationLink('all-lots.php', $currentPage - 1, $_GET);?>" <?php
                      endif; ?>>
                        Назад
                    </a></li>

                <?php foreach ($pages as $page) : ?>
                    <li class="pagination-item
                        <?php if ($page === $currentPage) :
                            ?> pagination-item-active <?php
                        endif; ?>">
                        <a href="<?= buildPaginationLink('all-lots.php', $page, $_GET);?>"><?= $page; ?></a>
                    </li>
                <?php endforeach; ?>


                <li class="pagination-item pagination-item-next">
                    <a<?php if ($currentPage < $pageCount) :
                        ?> href="<?= buildPaginationLink('all-lots.php', $currentPage + 1, $_GET);?>"<?php
                      endif; ?>>
                        Вперед
                    </a></li>


            </ul>
        <?php endif; ?>
    </div>
</main>
