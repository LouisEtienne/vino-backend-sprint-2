<?php
/**
 * Class Cellier
 * Cette classe possède les fonctions de gestion des bouteilles dans le cellier et des bouteilles dans le catalogue complet.
 * 
 * @author Equipe de 4
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */

 class Cellier extends Modele {
    
    /**
	 * Cette méthode annonce une liste des bouteilles importées de la SAQ disponibles pour ajouter au cellier.
	 * @access public
	 * @return Array $data Tableau des données représentants la liste des bouteilles.
	 */
	public function getBouteillesCellier($id)
	{
		$rows = Array();
		$requete ='SELECT 
                    cb.id_cellier,
                    cb.id_bouteille, 
                    cb.id_achats, 
                    cb.quantite,
                    cb.prix, 
                    cb.millesime, 
                    cb.garde_jusqua,
                    c.nom, 
                    c.adresse as cellier_adresse,
                    c.id_usager,
                    b.id as bouteille_id_bouteille,
                    b.nom, 
                    b.image, 
                    b.code_saq,
                    b.description,
                    b.prix_saq,
                    b.url_saq,
                    b.url_img,
                    b.format, 
                    b.id_type, 
                    b.id_pays,
                    t.type,
                    u.id as usager_id_usager,
                    u.nom,
                    u.courriel,
                    u.phone,
                    u.adresse as usager_adresse
                    from vino__cellier_bouteille cb
                    INNER JOIN vino__cellier c ON cb.id_cellier = c.id
                    INNER JOIN vino__bouteille b ON cb.id_bouteille = b.id
                    INNER JOIN vino__type t ON b.id_type = t.id
                    INNER JOIN vino__usager u ON c.id_usager = u.id
                    WHERE id_cellier = '. $id .'
                    '; 
		if(($res = $this->_db->query($requete)) ==	 true)
		{
			if($res->num_rows)
			{
				while($row = $res->fetch_assoc())
				{
					$row['nom'] = trim(utf8_encode($row['nom']));
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
	 * Cette méthode annonce une bouteille avec id_bouteille au cellier avec id_cellier.
	 * @access public
	 * @return Array $data Tableau des données représentants la bouteille.
	 */
	public function getBouteilleDansCellier($id_bouteille, $id_cellier)
	{
		$rows = Array();
		$requete ='SELECT 
                    cb.id_cellier,
                    cb.id_bouteille, 
                    cb.id_achats, 
                    cb.quantite,
                    cb.prix, 
                    cb.millesime, 
                    cb.garde_jusqua,
                    c.nom, 
                    c.adresse as cellier_adresse,
                    c.id_usager,
                    b.id as bouteille_id_bouteille,
                    b.nom, 
                    b.image, 
                    b.code_saq,
                    b.description,
                    b.prix_saq,
                    b.url_saq,
                    b.url_img,
                    b.format, 
                    b.id_type, 
                    b.id_pays,
                    t.type,
                    u.id as usager_id_usager,
                    u.nom,
                    u.courriel,
                    u.phone,
                    u.adresse as usager_adresse
                    from vino__cellier_bouteille cb
                    INNER JOIN vino__cellier c ON cb.id_cellier = c.id
                    INNER JOIN vino__bouteille b ON cb.id_bouteille = b.id
                    INNER JOIN vino__type t ON b.id_type = t.id
                    INNER JOIN vino__usager u ON c.id_usager = u.id
                    WHERE cb.id_cellier = '. $id_cellier .'
                    AND cb.id_bouteille = '. $id_bouteille .'
                    '; 
		if(($res = $this->_db->query($requete)) ==	 true)
		{
			if($res->num_rows)
			{
				while($row = $res->fetch_assoc())
				{
					$row['nom'] = trim(utf8_encode($row['nom']));
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
     * @Crossorigin
	 * Cette méthode ajoute une ou des bouteilles au cellier
	 * @access public
	 * @param Array $data Tableau des données représentants la bouteille
	 * @return int Renvoie l'id de la bouteille ajoutée
	 */
	public function ajouterCellier($data)
	{
        if (is_array($data) || is_object($data)) 
        {    
            if(extract($data) > 0)
            {
                $requete = "INSERT INTO vino__cellier(`nom`, `adresse`) VALUES ('".$nom. "','". $adresse. "')";

                $this->_db->query($requete);
            }
            return ($this->_db->insert_id ? $this->_db->insert_id : $requete);
        } else {
            echo "Une erreur s'est produite.";
        }
	}

 
    /**
     * @Crossorigin
	 * Cette méthode modifie la bouteille
	 * @access public
	 * @param Array $param Paramètres et valeur à modifier 
	 * @return int id de la bouteille ou 0 en cas d'échec
	 */
	public function modifCellier($param)	
	{
		$aSet = Array();
		$resQuery = false;
        $id = $param['id'];
        if (is_array($param) || is_object($param)) 
        {
            foreach ($param as $cle => $valeur) 
            {
                $aSet[] = ($cle . "= '".$valeur. "'");
            }
            if(count($aSet) > 0)
            {
                $query = "Update vino__cellier SET ";
                $query .= join(", ", $aSet);
                $query .= (" WHERE id = ". $id); 
                $resQuery = $this->_db->query($query);
            }
            return ($resQuery ? $id : 0);
        } 
        else 
        {
            echo "Une erreur s'est produite.";
        }
	}


	/**
	 * Effacer un cellier
	 * @access public
	 * @param Array $id Identifiant du cellier  
	 * @return Boolean
	 */
	public function effacerCellier($id)
    {
		$resQuery = false;
		if(isset($id))
		{
			$id = $this->_db->real_escape_string($id);

            $query = "SET foreign_key_checks = 0";
            $resQuery = $this->_db->query($query);

            $query = "DELETE from vino__achats where cellier_bouteille_id_cellier = ". $id;
			$resQuery = $this->_db->query($query);

            $query = "DELETE from vino__notes where cellier_bouteille_id_cellier = ". $id;
			$resQuery = $this->_db->query($query);

			$query = "DELETE from vino__cellier_bouteille where id_cellier = ". $id;
			$resQuery = $this->_db->query($query);

            $query = "DELETE from vino__cellier where id = ". $id;
			$resQuery = $this->_db->query($query);
            
            $query = "SET foreign_key_checks = 1";
            $resQuery = $this->_db->query($query);
		}
		return $resQuery;
	}
}




?>