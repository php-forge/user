<?php

declare(strict_types=1);

use Forge\Html\Tag\Tag;

?>

<!DOCTYPE html>
<html lang="en">
<?= $this->beginPage() ?>
    <?= Tag::begin('html', ['class' => 'h-100', 'lang' => 'en']) ?>
        <?= $this->render('_head') ?>

        <?php $this->beginBody() ?>
            <body class="d-flex w-100 h-100 mx-auto flex-column">

                <header class="mb-auto">
                    <?= $this->render('_menu') ?>
                    <?= $this->render('_flash') ?>
                </header>

                <main class="container position-relative pb-5">
                    <?= $content ?>
                </main>

                <footer class='mt-auto bg-dark py-4'>
                    <?= $this->render('_footer') ?>
                </footer>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
            </body>
        <?php $this->endBody() ?>

    <?= Tag::end('html') ?>
<?= $this->endPage();
