<?php

require_once("lib/ObjectFileDB.php");
require_once("lib/init.php");
require_once("model/Planet.php");
require_once("model/PlanetStorage.php");




/*
 * Gère le stockage de Planets dans un fichier.
 * Plus simple que l'utilisation d'une base de données,
 * car notre application est très simple.
 */

class PlanetStorageFile implements PlanetStorage {

	/* le ObjectFileDB dans lequel l'instance est enregistrée */
	private $db;

	/* Construit une nouvelle instance, qui utilise le fichier donné
	 * en paramètre. */
	public function __construct($file) {
		$this->db = new ObjectFileDB($file);
	}

	/* Insère une nouvelle Planet dans la base. Renvoie l'identifiant
	 * de la nouvelle Planet. */
	public function create(Planet $p) {
        return $this->db->insert($p);
	}

	/* Renvoie la Planet d'identifiant $id, ou null
	 * si l'identifiant ne correspond à aucune Planet. */
	public function read($id) {
		if ($this->db->exists($id)) {
			return $this->db->fetch($id);
        } else {
			return null;
        }
	}

	/* Renvoie un tableau associatif id => Planet
	 * contenant toutes les Planets de la base. */
	public function readAll() {
		return $this->db->fetchAll();
	}

	/* Met à jour une Planet dans la base. Renvoie
	 * true si la modification a été effectuée, false
	 * si l'identifiant ne correspond à aucune Planet. */
	public function update($id, Planet $p) {
		if ($this->db->exists($id)) {
            $this->db->update($id, $p);
			return true;
		}
		return false;
	}

	/* Supprime une Planet. Renvoie
	 * true si la suppression a été effectuée, false
	 * si l'identifiant ne correspond à aucune Planet. */
	public function delete($id) {
		if ($this->db->exists($id)) {
			$this->db->delete($id);
			return true;
		}
		return false;
	}

	/* Vide la base. */
	public function deleteAll() {
        $this->db->deleteAll();
	}
}

?>
