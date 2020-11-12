<?php 
	class MatieresController extends AppController {
		public $uses = array('Matiere', 'Note');

		public function index() {
			$this->layout = 'gestion';
			//debug($this->Matiere->find('all'));
			if($this->request->is('post')) {
				if(strtolower($this->request->data['Matiere']['libelle_mat'])=='conduite')
					$this->request->data['Matiere']['code_mat'] = 'MAT997';
					//debug(strtolower($this->request->data['Matiere']['libelle_mat']));die();
				$this->Matiere->create($this->request->data);
				if($this->Matiere->validates())	{
					if(empty($this->Matiere->findByCode_mat([$this->request->data['Matiere']['code_mat']])))
					 {
					 	if($this->Matiere->save())
							$this->set("ajout_msg", "<label style=\"color:#28a745\">Succès!</label>");	
					 	else {
					 		$this->set("ajout_msg", "<label style=\"color: #dc3545\">Echec!</label>");
					 		header('Refresh: 2;');
					 	}
					 }
					 else $this->set("ajout_msg", "<label style=\"color: #17a2b8\">Ce code est déjà utilisé.</label>");
				}
			}

			$this->paginate = ['limit' => 20, 'order' =>'Matiere.code_mat ASC'];
			$this->set('liste_matiere', $this->paginate('Matiere'));
			$this->set('nombre_matiere', sizeof($this->Matiere->getListMatiere()));
		}
		/*public function supprimer() {
			$this->autoRender = false;
			$this->request->onlyAllow('ajax');
			return json_encode($this->Matiere->deleteAll(['code_mat'=>$this->request->data['code_mat']], false));
			}*/

		public function inserer() {

			$this->autoRender = false;
			$this->request->onlyAllow('ajax');

		    $codes = $this->Matiere->find('all', array(
		    	'fields' => array('code_mat'),
		        'conditions' => array(
		        	'code_mat >' => $this->request->data['code_mat'],
		        	"NOT" => array("code_mat" => array("MAT997", "MAT998", "MAT999"))
		        ),
		        'order' => array('code_mat' => 'DESC')//En ordre décroissante, la mise à jour se fait de haut en bas
		    ));
		   
		    foreach ($codes as $code) {

		    	$new_code = substr($code['Matiere']['code_mat'], 3) + 1;
		    	while (strlen($new_code) < 3)
		    		$new_code = '0'.$new_code;
		    	$new_code = 'MAT'.$new_code;
		    	//Matière
		    	$this->Matiere->updateAll(
				    array('code_mat' => "'$new_code'"),
				    array('code_mat' => $code['Matiere']['code_mat'])
				);
				
				$this->Note->updateAll(
				    array('code_mat' => "'$new_code'"),
				    array('code_mat' => $code['Matiere']['code_mat'])
				);
		    }

	    	$new_code = substr($this->request->data['code_mat'], 3) + 1;

	    	while (strlen($new_code) < 3)
	    		$new_code = '0'.$new_code;

	    	$new_code = 'MAT'.$new_code;

			$this->Matiere->create([
				'code_mat'=> $new_code,
				'libelle_mat'=>$this->request->data['libelle_mat'],
			 ]);

			if($this->Matiere->validates()) {
				$this->Matiere->save();
				return json_encode('Insertion avec succès! Code : '.$new_code);
			}
			return json_encode('Echec de validation de la désignation ...');
		}

		public function mettre_jour() {
				$this->autoRender = false;
				$this->request->onlyAllow('ajax');
				$this->Matiere->create([
					'code_mat'=> $this->request->data['code_mat'],
					'libelle_mat'=>$this->request->data['libelle_mat'],
				 ]);
				if($this->Matiere->validates()) {
					return ($this->Matiere->updateAll(
						['libelle_mat' => "'".htmlspecialchars($this->request->data['libelle_mat'])."'"],
						['code_mat' => $this->request->data['code_mat']]
					));
				}
				else return ('Saisir des caractères alphanumériques:[1-50]');
			}
	}
;?>