<?php
/**
 * Created by PhpStorm.
 * User: Quentin Levert
 * Date: 18/11/2016
 * Time: 14:56
 */
require_once('Modele/ChiffreAffaire.php');
require_once('Modele/UserLogin.php');
require_once('Vue/Vue.php');

class ControleurChiffreAffaire implements Controleur
{

    /**
     * @var ChiffreAffaire
     *
     */
    private $ChiffreAffaire;


    //______________________________________________________________________________________
    /**
     * ControleurChiffreAffaire constructor.
     */
    public function __construct()
    {
        $this->ChiffreAffaire = new ChiffreAffaire();
    }

    /**
     * Getter de $ChiffreAffaire
     *
     * @return ChiffreAffaire
     */
    public function getChiffreAffaire()
    {
        return $this->ChiffreAffaire;
    }

    /**
     * Setter de $ChiffreAffaire
     *
     * @param $newChiffreAffaire
     */
    public function setChiffreAffaire($newChiffreAffaire)
    {
        $this->ChiffreAffaire = $newChiffreAffaire;
    }


    //______________________________________________________________________________________
    /**
     *  Fonction qui affiche la vue
     */
    public function getHTML()
    {
        $vue = new Vue("ChiffreAffaire");
        $vue->generer(array("CAFinal" => $this->ChiffreAffaire->getChiffreAffaire()));

    }

}