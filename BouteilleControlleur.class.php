<?php
/**
 * Class BouteilleControleur
 * Controleur de la ressource Bouteille
 * 
 * @author Equipe de 4
 * @version 1.1
 * @update 2019-11-11
 * @license MIT
 */

  
class BouteilleControlleur 
{
	private $retour = array('data'=>array());

	/**
	 * Méthode qui gère les action en GET
     * @access public
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function getAction(Requete $requete)
	{
        // cellier
        if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))
        {
            $id_cellier = (int)$requete->url_elements[0];                
            // bouteille
            if(isset($requete->url_elements[1])) 
            {   
                $id_bouteille = (int)$requete->url_elements[1];             
                if(is_numeric($requete->url_elements[1])) 
                {
                    if(isset($requete->url_elements[2])) 
                    {
                        switch($requete->url_elements[2]) 
                        {
                            case 'quantite':
                                $this->retour["data"] = $requete->url_elements;
                                $this->ajouterQuantiteBouteille($id_bouteille);
                                break;
                            default:
                                $this->retour['erreur'] = $this->erreur(400);
                                unset($this->retour['data']);
                                break;
                        }
                    }
                    else
                    {
                        $this->retour["data"] = $this->getBouteilleDansUnCellier($id_bouteille, $id_cellier);
                    }
                } 
                else
                {
                    $this->retour['erreur'] = $this->erreur(400);
                }
            } 
            else 
            {
                $this->retour["data"] = $this->getBouteillesDansCellier($id_cellier);
            }
        } 
        else if (isset($requete->url_elements[0]))
        {
            switch($requete->url_elements[0]) 
            {
                case 'bouteilles': 
                    $this->retour["data"] = $this->getBouteillesInserer(); //SAQ
                    break;   
                default:
                    $this->retour['erreur'] = $this->erreur(400);
                    unset($this->retour['data']);
                    break;
            }
        }
        else 
        {
            $this->retour["data"] = $this->getBouteilles();
        }
        return $this->retour;	
	}
	

	/**
	 * Méthode qui gère les action en POST
     * @access public
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function postAction(Requete $requete)	// Modification
	{
        if(!$this->valideAuthentification())
		{
			$this->retour['erreur'] = $this->erreur(401);
		}
		else {
            if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// l'id du cellier 
            {
                if(isset($requete->url_elements[1]) && is_numeric($requete->url_elements[1]))	// l'id de la bouteille 
                {
                    $this->retour["data"] = $this->modifBouteille($requete->parametres);
                }
                else
                {
                    $this->retour["data"] = $this->modifCellier($requete->parametres);
                }
            }
            else
            {
                $this->retour['erreur'] = $this->erreur(400);
                unset($this->retour['data']);
            }
        }
        return $this->retour;
	}


	/**
	 * Méthode qui gère les action en PUT
     * @access public
	 * @param Requete $requete
	 * @return Mixed Données retournées
	 */
	public function putAction(Requete $requete)		// Ajout
	{
        if(!$this->valideAuthentification())
		{
			$this->retour['erreur'] = $this->erreur(401);
		}
		else {
            if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// l'id du cellier
            {
                if(isset($requete->url_elements[1]) && is_numeric($requete->url_elements[1]))	// l'id de la bouteille 
                {
                    $id_bouteille = (int)$requete->url_elements[1];
                    
                    if(isset($requete->url_elements[2])) 
                    {
                        switch($requete->url_elements[2]) 
                        {
                            case 'quantite':
                                $this->retour["data"] = $this->ajouterQuantiteBouteille($id_bouteille);
                                break;
                            default:
                                $this->retour['erreur'] = $this->erreur(400);
                                unset($this->retour['data']);
                                break;
                        }
                    } 
                    else
                    {
                        $this->retour['erreur'] = $this->erreur(400);
                        unset($this->retour['data']);
                    }
                } 
                else 
                {
                    $this->retour["data"] = $this->ajouterUneBouteille($requete->parametres);
                }
            }
            else 
            {
                $this->retour["data"] = $this->ajouterUnCellier($requete->parametres);
            }
        }
		return $this->retour;
	}

	
	/**
	 * Méthode qui gère les action en DELETE
     * @access public
	 * @param Requete $oReq
	 * @return Mixed Données retournées
	 */
	public function deleteAction(Requete $requete)
	{
        if(!$this->valideAuthentification())
		{
			$this->retour['erreur'] = $this->erreur(401);
		}
		else {
            if(isset($requete->url_elements[0]) && is_numeric($requete->url_elements[0]))	// l'id du cellier
            {
                $id_cellier = (int)$requete->url_elements[0];
                if(isset($requete->url_elements[1]) && is_numeric($requete->url_elements[1]))	// l'id de la bouteille 
                {
                    $id_bouteille = (int)$requete->url_elements[1];
                    
                    if(isset($requete->url_elements[2])) 
                    {
                        switch($requete->url_elements[2]) 
                        {
                            case 'quantite':
                                $this->retour["data"] = $this->boireQuantiteBouteille($id_bouteille);
                                break;
                            default:
                                $this->retour['erreur'] = $this->erreur(400);
                                unset($this->retour['data']);
                                break;
                        }
                    } 
                    else
                    {
                        $this->retour['erreur'] = $this->erreur(400);
                        unset($this->retour['data']);
                    }
                } 
                else 
                {
                    $this->retour["data"] = $this->effacerUnCellier($id_cellier);
                }
            }
        }
        return $this->retour;
	}

    /**
	 * Méthode qui augmente de 1 le nombre de bouteilles avec $id au cellier
     * @access public
	 * @param int $id de la bouteille
	 * @return Array Tableau des bouteilles retournée
	 */
	public function ajouterQuantiteBouteille($id)
    {
		$oBouteille = new Bouteille;
		$oBouteille->modifierQuantiteBouteilleCellier($id, 1);

		return $this->getBouteilles();
	}


	/**
	 * Méthode qui réduit de 1 le nombre de bouteilles avec $id au cellier 
     * @access public
	 * @param int $id de la bouteille
	 * @return Array Tableau des bouteilles retournée
	 */
	public function boireQuantiteBouteille($id)
    {
		$oBouteille = new Bouteille;
		$oBouteille->modifierQuantiteBouteilleCellier($id, -1);
		
		return $this->getBouteilles();
	}

	
	/**
	 * Modifie les informations de la bouteille
	 * @access private
	 * @param Array Les informations de la bouteille
	 * @return int $id Identifiant de la bouteille dans le cellier à modifier
	 */	
	private function modifBouteille($data)
	{
		$res = Array();
		$oBouteille = new Bouteille();
		
		$res = $oBouteille->modifBouteille($data);
		return $res; 
	}

    
    /**
	 * Modifie les informations du cellier
	 * @access private
	 * @param Array Les informations de la bouteille
	 * @return int $id Identifiant de la bouteille dans le cellier à modifier
	 */	
	private function modifCellier($data)
	{
		$res = Array();
		$oBouteille = new Cellier();
		
		$res = $oBouteille->modifCellier($data);
		return $res; 
	}
	
    /**
	 * Ajouter un cellier
	 * @access private
	 * @param Array Les informations du cellier
	 * @return int $id_cellier Identifiant du nouveau cellier
	 */	
	private function ajouterUnCellier($data)
	{
		$res = Array();
		$oCellier = new Cellier();
		$res = $oCellier->ajouterCellier($data);
		return $res; 
	}


    /**
	 * Ajouter une bouteille au cellier
	 * @access private
	 * @param Array Les informations de la bouteille
	 * @return int $id_bouteille Identifiant de la nouvelle bouteille
	 */	
	private function ajouterUneBouteille($data)
	{
		$res = Array();
		$oBouteille = new Bouteille();
		$res = $oBouteille->ajouterBouteilleCellier($data);
		return $res; 
	}

	
    /**
	 * Retourne les informations des bouteilles dans tout les celliers	 
     * @access private
	 * @return Array Tableau de toutes les bouteilles au cellier
	 */	
	private function getBouteilles()
	{
		$res = Array();
		$oVino = new Bouteille();
		$res = $oVino->getListeBouteilleCellier();
		
		return $res; 
	}

    
    /**
	 * Retourne liste des bouteilles importées de la SAQ disponibles pour ajouter au cellier. 
	 * @return Array Les informations sur toutes les bouteilles
	 * @access private
	 */	
	private function getBouteillesInserer()
	{
		$res = Array();
		$oBouteille = new Bouteille();
		$res = $oBouteille->getBouteillesInserer();
		
		return $res; 
	}

    
    /**
	 * Méthode qui retourne les informations de la bouteille avec id_bouteille au cellier avec id_cellier
     * @access public
	 * @param int $id_cellier du cellier
     * @param int $id_bouteille du cellier
	 * @return Array Tableau d'information sur la bouteille retournée
	 */
    private function getBouteilleDansUnCellier($id_bouteille, $id_cellier) 
    {
        $res = Array();
		$oCellier = new Cellier();
		$res = $oCellier->getBouteilleDansCellier($id_bouteille, $id_cellier);
		
		return $res; 
    }

    /**
	 * Méthode qui retourne les informations des bouteilles au cellier avec id_cellier
     * @access public
	 * @param int $id_cellier du cellier
	 * @return Array Tableau des bouteilles retournée
	 */
    private function getBouteillesDansCellier($id_cellier) 
    {
        $res = Array();
		$oCellier = new Cellier();
		$res = $oCellier->getBouteillesCellier($id_cellier);
		
		return $res; 
    }


    /**
	 * Effacer le cellier $id_cellier
	 * @param int $id_cellier Identifiant du cellier
	 * @return boolean Succès ou échec
	 * @access private
	 */	
	private function effacerUnCellier($id_cellier)
	{
		$res = Array();
		$oCellier = new Cellier();
		
		$res = $oCellier->effacerCellier($id_cellier);
		return $res; 
	}

    /**
	 * Valide les données d'authentification du service web
	 * @return Boolean Si l'authentification est valide ou non
	 * @access private
	 */	
	private function valideAuthentification()
    {
      	$access = false;
		$headers = apache_request_headers();
        //$usager = getUsager();
		
		if(isset($headers['Authorization']) || isset($headers['authorization']))	//Fetch avec Chrome envoie authorization et non Authorization ! 
		{
			if(isset($_SERVER['PHP_AUTH_PW']) && isset($_SERVER['PHP_AUTH_USER']))
			{
				if($_SERVER['PHP_AUTH_PW'] == '$usager' && $_SERVER['PHP_AUTH_USER'] == '$mot_passe')
				{
					$access = true;
				}
			}
		}
      	return $access;
    }

    /**
	 * Afficher des erreurs
	 * @access private
	 * @param String Le code d'erreur
	 * @return Array Les message d'erreurs
	 */	
	private function erreur($code, $data="")
	{
		//header('HTTP/1.1 400 Bad Request');
		http_response_code($code);

		return array("message"=>"Erreur de requete", "code"=>$code);
	}

}