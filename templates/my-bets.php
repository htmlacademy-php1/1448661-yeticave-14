<?php
/**
 * @var $categories
 * @var  $userAllBets
 * @var $link
 */
?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="/all-lots.php?categoryId=<?= $category['id'] ?>">
                        <?= htmlspecialchars($category['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($userAllBets as $bet) : ?>
            <tr class="rates__item
                <?php if ($bet['winner_id'] === $bet['user_id']) :
                    ?>rates__item--win
                <?php endif;?> ">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= htmlspecialchars($bet['image']); ?>"
                             width="54" height="40" alt="<?= $bet['cat_name']; ?>">
                    </div>
                    <?php if ($bet['winner_id'] === $bet['user_id']) : ?>
                        <div>
                            <h3 class="rates__title">
                                <a href="<?= 'lot.php?id=' . $bet['id']; ?>">
                                    <?= htmlspecialchars($bet['title']); ?>
                                </a>
                            </h3>
                            <?php $contact = getLotCreatorContacts($link, $bet['id']) ;?>
                            <p><?= htmlspecialchars($contact['contacts']); ?></p>
                        </div>
                    <?php else : ?>
                        <h3 class="rates__title">
                            <a href="<?= 'lot.php?id=' . $bet['id']; ?>">
                                <?= htmlspecialchars($bet['title']); ?>
                            </a>
                        </h3>
                    <?php endif;?>

                </td>
                <td class="rates__category">
                    <?= htmlspecialchars($bet['cat_name']); ?>
                </td>

                <?php $time = getDtRange(htmlspecialchars($bet['end_date']), 'now') ?>
                <td class="rates__timer ">
                    <?php if ($bet['winner_id'] === $bet['user_id']) : ?>
                    <div class="timer timer--win">
                        Ставка выиграла
                    </div>
                    <?php elseif ($bet['winner_id'] === null && $time[0] > 1) : ?>
                      <div class="timer">
                        <?= sprintf("%02d", $time[0]) . ':' . sprintf("%02d", $time[1]); ?>
                        </div>
                    <?php elseif ($bet['winner_id'] === null && $time[0] < 1) : ?>
                        <div class="timer timer--finishing">
                            <?= sprintf("%02d", $time[0]) . ':' . sprintf("%02d", $time[1]); ?>
                        </div>

                    <?php else : ?>
                    <div class="timer timer--end">
                        Торги окончены
                    </div>
                    <?php endif;?>
                </td>
                <td class="rates__price">
                    <?= htmlspecialchars($bet['price']); ?>
                </td>
                <td class="rates__time">
                    <?= getPassedTimeBet(htmlspecialchars($bet['date_creation']), 'now'); ?>
                </td>
            <?php endforeach; ?>
            </tr>
        </table>

    </section>
</main>
