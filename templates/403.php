<?php
/**
 * @var array $categories
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
    <section class="lot-item container">
        <h2>403 Доступ запрещен</h2>
    </section>
</main>
