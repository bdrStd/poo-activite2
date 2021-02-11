<?php
class War extends Personnage
{
    public function recevoirDegats($strength)
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
      
      //$this->degats += 5 - $this->atout;
      $this->setDegats($this->_degats = $this->_degats + $strength - $this->_atout);
      
      // Si on a 100 de dégâts ou plus, on supprime le personnage de la BDD.
      if ($this->degats >= 100)
      {
        return self::PERSONNAGE_TUE;
      }
      
      // Sinon, on se contente de mettre à jour les dégâts du personnage.
      return self::PERSONNAGE_FRAPPE;
    }
}
?>