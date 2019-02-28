<?php

require_once("Planet.php");

interface PlanetStorage {

	/* Insère une nouvelle planete dans la base. Renvoie l'identifiant
	 * de la nouvelle planete. */
	public function create(Planet $p);

	/* Renvoie la planete d'identifiant $id, ou null
	 * si l'identifiant ne correspond à aucune planete. */
	public function read($id);

	/* Renvoie un tableau associatif id => Color
	 * contenant toutes les planetes de la base. */
	public function readAll();

	/* Met à jour une planete dans la base. Renvoie
	 * true si la modification a été effectuée, false
	 * si l'identifiant ne correspond à aucune planete. */
	public function update($id, Planet $p);

	/* Supprime une planete. Renvoie
	 * true si la suppression a été effectuée, false
	 * si l'identifiant ne correspond à aucune planete. */
	public function delete($id);

	/* Vide la base. */
	public function deleteAll();

}

?>
