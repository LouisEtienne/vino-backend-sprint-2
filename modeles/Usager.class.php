<?php
/**
 * Class User
 * Cette classe possède les fonctions de gestion des utilisateurs dans le user et des bouteilles dans le catalogue complet.
 * 
 * @author Equipe de 4
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */
class Usager extends Modele {
    
	/**
	 * Retourne la liste des usager
	 * @access public
	 * @return Array
	 */
	public function getListerUsager() 
	{
		$res = Array();
		if($mrResultat = $this->_db->query("select * from vino__usager"))
		{
			while($usager = $mrResultat->fetch_assoc())
			{
				$res[] = $usager;
			}
		}
		return $res;
	}


	    /**
	 * Cette méthode annonce une liste des bouteilles importées de la SAQ disponibles pour ajouter au cellier.
	 * @access public
	 * @return Array $data Tableau des données représentants la liste des bouteilles.
	 */
	public function getListeUser()
	{
		$rows = Array();
        $requete ='SELECT * FROM vino__usager;'; 

		if(($res = $this->_db->query($requete)) ==	 true)
		{
			if($res->num_rows)
			{
				while($row = $res->fetch_assoc())
				{
					$rows[] = $row;
				}
			}
		}
		else 
		{
			throw new Exception("Erreur de requête sur la base de donnée", 1);
			//$this->_db->error;
		}
		return $rows;
	}

    /**
	 * Ajoute un usager
	 * @param Array $data Les données d'usager 
	 * @access public
	 * @return int id d'usager
	 */
	public function ajouterUsager($data) 
	{
		$usager='';
		$res = Array();
		
		$query = "INSERT INTO vino__usager (`courriel`,`mot_passe`) 
		VALUES ('". $data['courriel']. "','".$data['mot_passe']."')";
		// $query = "INSERT INTO vino__usager (`nom`, `courriel`, `phone`, `adresse`, `mot_passe`, `connecte`) 
		// VALUES ('".$nom. "','". $courriel. "','". $phone. "','".$adresse."','".$mot_passe."','true')";
		$this->_db->query($query);
	
		$id =  ($this->_db->insert_id ? $this->_db->insert_id : 0);
		
		if($mrResultat = $this->_db->query("select * from vino__usager where courriel =".$data['courriel']." AND mot_passe=".$data['mot_passe'].";"))
		{
			$usager = $mrResultat->fetch_assoc();
		}
	
		$res[]= $usager;
		return $res;
	}
	
	/**
	 * Effacer un  usager
	 * @access public
	 * @param Array $id Identifiant d'usager'  
	 * @return Boolean
	 */
	public function effacerUsager($id) 
	{
		$resQuery = false;
		if(isset($id))
		{
			$id = $this->_db->real_escape_string($id);
			$query = "DELETE from vino__usager where id = ". $id;
			$resQuery = $this->_db->query($query);	
		}
		return $resQuery;
	}
	
	/**
	 * Récupère  un usager
	 * @access public
	 * @param int $id Identifiant d'usager
	 * @return Array
	 */
	public function getUsager($params) 
	{
		// $res = Array();
		if($mrResultat = $this->_db->query("select * from vino__usager where courriel =". $params['courriel']." AND mot_passe = ".$params['mot_passe']))
		{
			$usager = $mrResultat->fetch_assoc();
		}
		return $usager;
	}

    /**
	 * Récupère  un usager
	 * @access public
	 * @param int Identifiant d'usager
	 * @return Array
	 */
	public function getLoginUser($courriel, $mot_passe)
    {
        // $usager = '';
        // if($mrResultat = $this->_db->query("select `courriel`,`mot_passe` from vino__usager where courriel=". $courriel." AND mot_passe=". $mot_passe.";"))
		// {
		// 	$usager = $mrResultat->fetch_assoc();
        //     if(count($usager)>0)
        //     {
        //         $usager['connecte'] = 'true';
        //         // est ce que nous avons une variable de session en utilisant webservice?
        //         // return $usager;
        //     }
		// }
        // else
        // {
        //     $usager['connecte'] = 'Aucun utilisateur correspondant';
        //     // return $usager;
        // }

        $usager['connecte'] = 'false';
        return $usager;
    }
	
	/**
	 * Modifier un usager
	 * @access public
	 * @param int $id Identifiant d'usager
	 * @param Array $param Paramètres et valeur à modifier 
	 * @return int id d'usager ou 0 en cas d'échec
	 */
	public function modifierUsager($id, $param)	
	{
		$aSet = Array();
		$resQuery = false;
		foreach ($param as $cle => $valeur) {
			$aSet[] = ($cle . "= '".$valeur. "'");
		}
		if(count($aSet) > 0)
		{
			$query = "Update vino__usager SET ";
			$query .= join(", ", $aSet);
			$query .= ("WHERE id = ". $id); 
			$resQuery = $this->_db->query($query);
			
		}
		return ($resQuery ? $id : 0);
	}

}