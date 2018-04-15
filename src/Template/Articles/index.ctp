<!-- Fichier : src/Template/Articles/index.ctp -->

<h1>Articles</h1>
<p><?= $this->Html->link("Ajouter un article", ['action' => 'add']) ?></p>
<table>
    <tr>
        <th>Titre</th>
        <th>Créé le</th>
        <th>Action</th>
    </tr>

    <!-- C'est ici que nous bouclons sur notre objet Query $articles pour afficher les informations de chaque article -->

<?php foreach ($articles as $article): ?>
    <tr>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Html->link('Modifier', ['action' => 'edit', $article->slug]) ?>
        </td>
    </tr>
<?php endforeach; ?>

</table>
