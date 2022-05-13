<?php
/**
 * @var array $lotData
 * @var array $categories
 * @var array $lotBets
 * @var  int $userId
 * @var  int $minBet
 * @var $endDate
 * @var int $lotCreatorId
 */
?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="/all-lots.php?categoryId=<?= $category['id']?>"><?= $category['name']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">

        <h2><?= $lotData['title']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= $lotData['url_image']; ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lotData['name']; ?></span></p>
                <p class="lot-item__description"><?= $lotData['description']; ?></p>
            </div>

            <?php if ($_SESSION['name'] ?? false) : ?>
            <div class="lot-item__right ">

                <div class="lot-item__state">
                    <?php $time = getDtRange($lotData['end_date'], 'now') ?>
                    <div class="lot-item__timer timer <?php if ($time[0] < 1) :
                        ?>timer--finishing<?php
                                                      endif; ?> ">
                        <?= sprintf("%02d", $time[0]) . ':' . sprintf("%02d", $time[1]); ?>
                    </div>
                    <div class="lot-item__cost-state">

                        <?php $currentPrice = $lotData['max_price'] ?? $lotData['price'] ;?>

                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= priceFormatting($currentPrice);?></span>
                        </div>

                          <?php $minBet = $currentPrice + $lotData['step_bet'];?>

                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= priceFormatting($minBet); ?></span>
                        </div>
                    </div>

                    <?php  $lastBetUserId = $lotBets[0]['user_id'] ?? "" ;?>
                    <?php if (!hideBetForm($endDate, 'now', $userId, $lotCreatorId, $lastBetUserId)) :?>
                        <?php $classname = !empty($errors) ? "form__item--invalid" : "";?>
                    <form class="lot-item__form" action="" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item <?= $classname ;?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="price"
                                   placeholder="<?= createPricePlaceholder($minBet) ;?>">
                            <span class="form__error"><?= $errors['price'] ?? "" ;?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                    <?php endif; ?>
                </div>
                <div class="history">
                    <h3>История ставок (<span><?= count($lotBets);?></span>)</h3>
                    <?php foreach ($lotBets as $bet) : ?>
                    <table class="history__list">
                        <tr class="history__item">
                            <td class="history__name"><?= $bet['name'] ;?></td>
                            <td class="history__price"><?= $bet['price'] ;?></td>
                            <td class="history__time"><?= getPassedTimeBet($bet['date_creation'], 'now');?></td>
                        </tr>
                    </table>
                    <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    </section>
</main>
