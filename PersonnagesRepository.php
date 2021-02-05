<?php
class PersonnagesRepository
{
  private $_db;

  public function __construct($db)
  {
    $this->setDb($db);
  }
  
  public function add(Personnage $perso)
  {
    // Préparation de la requête d'insertion.
    $q = $this->_db->prepare('INSERT INTO personnages(nom) VALUES(:nom)');
    // Assignation des valeurs pour le nom du personnage.
    $q->bindValue(':nom', $perso->nom());
    // Exécution de la requête.
    $q->execute();
    // Hydratation du personnage passé en paramètre avec assignation de son identifiant et des dégâts initiaux (= 0).
    
    //print_r(date('Y-m-d H:i:s'));die;
    
    $perso->hydrate([
        'id' => $this->_db->lastInsertId(),
        'degats' => 0,
        'experience' => 0,
        'niveau' => 0,
        'strength' => 5,
        'nbCoup' => 0,
        'dateCoup' =>date('Y-m-d')
      ]);
  }
  
  public function count()
  {
    // Exécute une requête COUNT() et retourne le nombre de résultats retourné.
    return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
  }
  
  public function delete(Personnage $perso)
  {
    // Exécute une requête de type DELETE.
    $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id());
  }
  
  public function exists($info)
  {
    // Si le paramètre est un entier, c'est qu'on a fourni un identifiant.
      // On exécute alors une requête COUNT() avec une clause WHERE, et on retourne un boolean.
      if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
      {
        return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
      }
    // Sinon c'est qu'on a passé un nom.
    // Exécution d'une requête COUNT() avec une clause WHERE, et retourne un boolean.
    $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
    $q->execute([':nom' => $info]);
    
    return (bool) $q->fetchColumn();
  }
  
  public function get($info)
  {
    // Si le paramètre est un entier, on veut récupérer le personnage avec son identifiant.
      // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
      if (is_int($info))
      {
        $q = $this->_db->query('SELECT id, nom, degats, experience, niveau, strength, nbCoup, dateCoup FROM personnages WHERE id = '.$info);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);
        
        return new Personnage($donnees);
      }
    // Sinon, on veut récupérer le personnage avec son nom.
    // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
    else
    {
      $q = $this->_db->prepare('SELECT id, nom, degats, experience, niveau, strength, nbCoup, dateCoup  FROM personnages WHERE nom = :nom');
      $q->execute([':nom' => $info]);
    
      return new Personnage($q->fetch(PDO::FETCH_ASSOC));
    }
  }
  
  public function getList($nom)
  {
    // Retourne la liste des personnages dont le nom n'est pas $nom.
    // Le résultat sera un tableau d'instances de Personnage.
    $persos = [];
    
    $q = $this->_db->prepare('SELECT id, nom, degats, experience, niveau, strength, nbCoup, dateCoup FROM personnages WHERE nom <> :nom ORDER BY nom');
    $q->execute([':nom' => $nom]);
    
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      $persos[] = new Personnage($donnees);
    }
    
    return $persos;
  }
  
  public function update(Personnage $perso)
  {
    // Prépare une requête de type UPDATE.
     $q = $this->_db->prepare('UPDATE personnages SET degats = :degats, experience = :experience, niveau = :niveau, strength= :strength, nbCoup = :nbCoup, dateCoup = :dateCoup  WHERE id = :id' );
    // $q = $this->_db->prepare('UPDATE personnages SET degats = :degats WHERE id = :id' );
    // Assignation des valeurs à la requête.
    $q->bindValue(':dateCoup', $perso->dateCoup(), PDO::PARAM_STR);
    $q->bindValue(':nbCoup', $perso->nbCoup(), PDO::PARAM_INT);
    $q->bindValue(':strength', $perso->strength(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);
    // Exécution de la requête.
    $q->execute();
  }

  
  
  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }

}