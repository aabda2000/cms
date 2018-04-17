<?php
// src/Controller/ArticlesController.php

namespace App\Controller;
use Cake\ORM\Query;

class ArticlesController extends AppController
{
    public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }


    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // Hardcoding the user_id is temporary, and will be removed later
            // when we build authentication out.
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Votre article a été sauvegardé.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'ajouter votre article.'));
        }
        // Récupère une liste des tags.
        $tags = $this->Articles->Tags->find('list');

        // Passe les tags au context de la view
        $this->set('tags', $tags);
        $this->set('article', $article);
    }   


   public function edit($slug)
   {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
          $this->Articles->patchEntity($article, $this->request->getData());
        if ($this->Articles->save($article)) {
            $this->Flash->success(__('Votre article a été mis à jour.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('Impossible de mettre à jour l\'article.'));
      }
      // Récupère une liste des tags.
      $tags = $this->Articles->Tags->find('list');
      // Passe les tags au context de la view
     $this->set('tags', $tags);
     $this->set('article', $article);
     
   }

   public function tags()
   {
     // La clé 'pass' est fournie par CakePHP et contient tous les
     // segments d'URL passés dans la requête
     $tags = $this->request->getParam('pass');

     // Utilisation de ArticlesTable pour trouver les articles taggés
     $articles = $this->Articles->find('tagged', [
        'tags' => $tags
     ]);

     // Passage des variable dans le contexte de la view du template
     $this->set([
        'articles' => $articles,
        'tags' => $tags
     ]);
   }
}
