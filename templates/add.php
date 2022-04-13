<?php

/**
 * @var array $categories
 * @var array $errors

 */

?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) :?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= $category['name']; ?></a>
            </li>
         <?php endforeach; ?>
        </ul>
    </nav>

    <?php $classname = !empty($errors) ? "form--invalid" : ""?>
    <form class="form form--add-lot container <?= $classname ;?>" action=""
          method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $classname = isset($errors['title']) ? "form__item--invalid" : ""?>
            <div class="form__item <?= $classname ;?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="title" value="<?=getPostVal('title'); ?>" placeholder="Введите наименование лота">
                <span class="form__error"><?= isset($errors['title']) ? $errors['title'] : ''?></span>
            </div>
            <?php $classname = isset($errors['category_id']) ? "form__item--invalid" : ""?>
            <div class="form__item <?= $classname ;?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category_id">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $category) :?>
                    <option value="<?=$category['id']; ?>"
                      <?php if($category['id'] == getPostVal('category_id')): ?>selected<?php endif;?>><?= $category['name'];
                      ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?= isset($errors['category_id']) ? $errors['category_id'] : ''?></span>

            </div>
        </div>
        <?php $classname = isset($errors['description']) ? "form__item--invalid" : ""?>
        <div class="form__item form__item--wide <?= $classname ;?> ">
            <label for="message">Описание <sup>*</sup></label>
            <textarea
                id="message" name="description"  placeholder="Напишите описание лота"><?= getPostVal('description'); ?></textarea>
            <span class="form__error"><?= isset($errors['description']) ? $errors['description'] : ''?></span>
        </div>
        <?php $classname = isset($errors['file']) ? "form__item--invalid" : ""?>
        <div class="form__item form__item--file <?= $classname ;?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="image" value="">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <span class="form__error"><?= isset($errors['file']) ? $errors['file'] : ''?></span>
        </div>

        <div class="form__container-three">
            <?php $classname = isset($errors['price']) ? "form__item--invalid" : ""?>
            <div class="form__item form__item--small <?= $classname ;?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="price" value="<?=getPostVal('price'); ?>" placeholder="0">
                <span class="form__error"><?= isset($errors['price']) ? $errors['price'] : ''?></span>
            </div>
            <?php $classname = isset($errors['step_bet']) ? "form__item--invalid" : ""?>
            <div class="form__item form__item--small <?= $classname ;?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="step_bet" value="<?=getPostVal('step_bet'); ?>" placeholder="0">
                <span class="form__error"><?= isset($errors['step_bet']) ? $errors['step_bet'] : ''?></span>
            </div>
            <?php $classname = isset($errors['end_date']) ? "form__item--invalid" : ""?>
            <div class="form__item <?= $classname ;?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="end_date" value="<?=getPostVal('end_date'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <span class="form__error"><?= isset($errors['end_date']) ? $errors['end_date'] : ''?></span>
            </div>
        </div>

        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>

