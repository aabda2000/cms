<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;
use  Cake\Utility\Text;
use Cake\ORM\Table;
use Cake\ORM\Query;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags'); 
    }

   public function beforeSave($event, $entity, $options)
   {
      if ($entity->isNew() && !$entity->slug) {
         $sluggedTitle = Text::slug($entity->title);
         // On ne garde que le nombre de caractère correspondant à la longueur
         // maximum définie dans notre schéma
         $entity->slug = substr($sluggedTitle, 0, 191);
       }
   }
   // L'argument $query est une instance du Query builder.
   // Le tableau $options va contenir l'option 'tags' que nous avons passé
   // à find('tagged') dans notre action de controller.
   public function findTagged(Query $query, array $options)
   {
    $columns = [
        'Articles.id', 'Articles.user_id', 'Articles.title',
        'Articles.body', 'Articles.published', 'Articles.created',
        'Articles.slug',
    ];

    $query = $query
        ->select($columns)
        ->distinct($columns);

    if (empty($options['tags'])) {
        // si aucun tag n'est fourni, trouvons les articles qui n'ont pas de tags
        $query->leftJoinWith('Tags')
            ->where(['Tags.title IS' => null]);
    } else {
        // Trouvons les articles qui ont au moins un des tags fourni
        $query->innerJoinWith('Tags')
            ->where(['Tags.title IN' => $options['tags']]);
    }

    return $query->group(['Articles.id']);
  }


}
