<?php
class Wizard extends Personnage
{
    public function frapper(Personnage $perso)
    {
        if ($this->degats >= 0 && $this->degats <= 25)
        {
          $this->_atout = 4;
        }
        elseif ($this->degats > 25 && $this->degats <= 50)
        {
          $this->_atout = 3;
        }
        elseif ($this->degats > 50 && $this->degats <= 75)
        {
          $this->_atout = 2;
        }
        elseif ($this->degats > 75 && $this->degats <= 90)
        {
          $this->_atout = 1;
        }
        else
        {
          $this->_atout = 0;
        }
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
       return $perso->recevoirDegats($this->_strength + $this->_atout);
   
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
}
?>