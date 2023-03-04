<div style="display: flex;  flex-wrap: wrap; justify-content: space-between">
    <?php foreach ($annonces as $annonce) : ?>
        <div class="card" style="width: 20rem;  margin-bottom: 20px;">
            <div class="card-body">
                <h5 class="card-title"><a href="/annonces/lire/<?= $annonce->id ?>"><?= $annonce->titre ?></a></h5>
                <p class="card-text"><?= $annonce->descriptions ?></p>
                <a href="/annonces/lire/<?= $annonce->id ?>" class="btn btn-primary">Lire l'artcile
                </a>
            </div>
        </div>
    <?php endforeach ?>
</div>