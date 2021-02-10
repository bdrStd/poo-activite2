<?php
class Personnage
{
  private $_id;
  protected $_degats;
  protected $_nom;
  protected $_experience;
  protected $_niveau;
  protected $_strength;
  protected $_nbCoup;
  protected $_dateCoup;
  protected $_dateCo;

  const CEST_MOI = 1; // Constante renvoyée par la méthode `frapper` si on se frappe soi-même.
  const PERSONNAGE_TUE = 2; // Constante renvoyée par la méthode `frapper` si on a tué le personnage en le frappant.
  const PERSONNAGE_FRAPPE = 3; // Constante renvoyée par la méthode `frapper` si on a bien frappé le personnage.

  public function __construct(array $donnees)
  {
    $this->hydrate($donnees);
  }
  
  public function nomValide()
  {
    return !empty($this->_nom);
  }



  public function frapper(Personnage $perso)
  {
    // Avant tout : vérifier qu'on ne se frappe pas soi-même.
    // Si c'est le cas, on stoppe tout en renvoyant une valeur signifiant que le personnage ciblé est le personnage qui attaque.

    // On indique au personnage frappé qu'il doit recevoir des dégâts.
    if ($perso->id() == $this->_id)
    {
      return self::CEST_MOI;
    }
    
    // On indique au personnage qu'il doit recevoir des dégâts.
     // Puis on retourne la valeur renvoyée par la méthode : self::PERSONNAGE_TUE ou self::PERSONNAGE_FRAPPE
     if($this->_nbCoup < 3)
     {
      $this->gagneExperience();
      $this->donneCoup();
      //$this->recupererVie();
     return $perso->recevoirDegats($this->_strength);
 
     }
     if($this->_nbCoup >= 3)
     {  
     
       if($this->_dateCoup == date('Y-m-d'))
       {
         echo 'vous devez attendre demain pour rejouer';
         
       }
       else
       {
         $this->setNbCoup(0);
         
        
       }
     }
     

  }
  public function donneCoup()
  {
   
    $this->_nbCoup++;
    //$this->_dateCoup = date('Y-m-d');
    $this->setDateCoup(date('Y-m-d'));
    
  }

  public function gagneExperience()
  {
   
    $this->setExperience($this->_experience += 10);
    if($this->_experience == 100)
    {
      // $this->_niveau++;
      // $this->_experience = 0;
      $this->gagneNiveau();

    }
   
  }
  public function gagneStrength()
  {
      $this->setStrength($this->_strength += 5);

  }

  public function gagneNiveau()
  {
      $this->setNiveau($this->_niveau++);
      //$this->_experience = 0;
      $this->setExperience(0);
      $this->gagneStrength();
  }

  public function recupererVie()
  {
    if($this->_dateCo < date('Y-m-d') && ($this->_degats < 100))
    {
        if($this->_degats >= 10){
          $this->setDegats($this->_degats -= 10);
          $this->setDateCo($this->_dateCo = date('Y-m-d'));
        }
    }
  }
//On hydrate l'objet en passant par les setters; ucfirst met le premier caractere en Maj.
//method_exists veridie que la methode existe.
  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      
      if (method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }

  public function recevoirDegats($strength)
  {
    // On augmente de 5 les dégâts.
    //$this->_degats += $strength;
    //modification pour passer par les setters
    $this->setDegats($this->_degats += $strength);
    // Si on a 100 de dégâts ou plus, la méthode renverra une valeur signifiant que le personnage a été tué.
    if ($this->_degats >= 100)
    {
      return self::PERSONNAGE_TUE;
    }
    // Sinon, elle renverra une valeur signifiant que le personnage a bien été frappé.
    return self::PERSONNAGE_FRAPPE;
  }

   // GETTERS //

   public function dateCo()
   {
     return $this->_dateCo;
   }

   public function dateCoup()
   {
     return $this->_dateCoup;
   }

   public function nbCoup()
   {
     return $this->_nbCoup;
   }

   public function strength()
   {
     return $this->_strength;
   }

   public function niveau()
   {
     return $this->_niveau;
   }


   public function experience()
   {
     return $this->_experience;
   }
  

   public function degats()
   {
     return $this->_degats;
   }
   
   public function id()
   {
     return $this->_id;
   }
   
   public function nom()
   {
     return $this->_nom;
   }

   //SETTERS
   public function setDateCo($dateCo)
   {
    $dateCo =  $dateCo;
     
     if ($dateCo >= 0)
     {
       $this->_dateCo = $dateCo;
     }
   }

   public function setDateCoup($dateCoup)
   {
    $dateCoup =  $dateCoup;
     
     if ($dateCoup >= 0)
     {
       $this->_dateCoup = $dateCoup;
     }
   }

   public function setNbCoup($nbCoup)
   {
     $nbCoup = (int) $nbCoup;
     
     if ($nbCoup >= 0)
     {
       $this->_nbCoup = $nbCoup;
     }
   }

   public function setStrength($strength)
   {
     $strength = (int) $strength;
     
     if ($strength >= 0)
     {
       $this->_strength = $strength;
     }
   }

   public function setNiveau($niveau)
  {
    $niveau = (int) $niveau;
    
    if ($niveau >= 0)
    {
      $this->_niveau = $niveau;
    }
  }

   public function setDegats($degats)
  {
    $degats = (int) $degats;
    
    if ($degats >= 0 && $degats <= 100)
    {
      $this->_degats = $degats;
    }
  }
  
  public function setId($id)
  {
    $id = (int) $id;
    
    if ($id > 0)
    {
      $this->_id = $id;
    }
  }
  
  public function setNom($nom)
  {
    if (is_string($nom))
    {
      $this->_nom = $nom;
    }
  }
  public function setExperience($experience)
  {
    $experience = (int) $experience;
    if ($experience >= 0)
    {
      $this->_experience = $experience;
    }
  }
}