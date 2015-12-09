<?php
namespace LightStrap\Controller;

use App\Controller\AppController;
use Cake\Utility\Inflector;

class AjaxController extends AppController
{
    /**
     * select2adminquery method
     *
     * @return void
     */
    public function select2adminquery($table, $colName, $contain = null)
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
        
        $results = [
            "results" => $q->toArray(),
            "pagination" => [
                "more" => $more
            ]
        ];
        
        echo json_encode($results);
    }
}
