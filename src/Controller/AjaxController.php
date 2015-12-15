<?php
namespace LightStrap\Controller;

use App\Controller\AppController;
use Cake\Core\Plugin;
use Cake\Filesystem\File;
use Cake\Utility\Inflector;

class AjaxController extends AppController
{
    /**
     * select2AdminQuery method
     *
     * @return void
     */
    public function select2AdminQuery($table, $colName, $contain = null, $imageSrcCol = null)
    {
        if (!$this->request->is('ajax')) {
            return;
        }

        $pageLimit = 20;
        $this->autoRender = false;
        $this->viewBuilder()->layout('ajax');
        $table = Inflector::camelize($table);
        $this->loadModel($table);
        $curPage = $this->request->query['page'];
        if (!isset($this->request->query['q'])) {
            $searchQuery = '';
        } else {
            $searchQuery = $this->request->query['q'];
        }
        
        if (!is_null($contain) && $contain != 'undefined') {
            $contain = Inflector::camelize($contain);
            $this->loadModel($contain);
            
    
            $q = $this->{$table}->{$contain}->find('all')
                ->where(["$contain.$colName LIKE" => $searchQuery . '%'])
                ->order(["$contain.$colName"]);
            $count = $q->count();
            $q = $q->limit($pageLimit)
                    ->page($curPage)
                    ->all();
        } else { //maybe delete this
            $q = $this->{$table}->find('all')
                ->where(["$table.$colName LIKE" => $searchQuery . '%'])
                ->order(["$table.$colName"]);
            $count = $q->count();
            $q = $q->limit($pageLimit)
                    ->page($curPage)
                    ->all();
        }
        
        if ($count > $curPage * $pageLimit) {
            $more = true;
        } else {
            $more = false;
        }
        
        //if image not exists
        if (!is_null($imageSrcCol) && $imageSrcCol != 'undefined') {
            $pluginPath = Plugin::path('LightStrap');
            foreach ($q as $item) {
                $imageFile = new File(WWW_ROOT . 'files' . DS . $item->{$imageSrcCol});
                if (!$imageFile->exists()) {
                    $item->path = '../light_strap/img/no_image.png';
                }
            }
        }
        $results = [
            "results" => $q->toArray(),
            "pagination" => [
                "more" => $more
            ]
        ];
        
        echo json_encode($results);
    }
    
    /**
     * select2GetImage method
     *
     * @return void
     */
    public function select2GetImage($table, $id, $imageSrcCol = null)
    {
        if (!$this->request->is('ajax')) {
            return;
        }
        $this->autoRender = false;
        $this->viewBuilder()->layout('ajax');
        $this->loadModel($table);
        
        $q = $this->{$table}->find('all')
            ->where(["$table.id" => $id]);
        
        //if image not exists
        if (!is_null($imageSrcCol) && $imageSrcCol != 'undefined') {
            $pluginPath = Plugin::path('LightStrap');
            foreach ($q as $item) {
                $imageFile = new File(WWW_ROOT . 'files' . DS . $item->{$imageSrcCol});
                if (!$imageFile->exists()) {
                    $item->path = '../light_strap/img/no_image.png';
                }
            }
        }
        
        echo json_encode($q->toArray());
    }
}
