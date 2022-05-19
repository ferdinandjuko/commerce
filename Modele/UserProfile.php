<?php

require_once('Modele.php');

/**
 * Created by PhpStorm.
 * User: Nicolas Sobczak & Vincent Reynaert
 * Date: 02/11/2016
 */
class UserProfile extends Modele
{
    // Constantes
    const PASSWORD_UPDATE_SUCCESS = 1;
    const PASSWORD_UPDATE_BAD_OLD_PASSWORD = 2;
    const PASSWORD_UPDATE_FORM_INVALID = 3;
    const PASSWORD_UPDATE_USER_ERROR = 4;


    //______________________________________________________________________________________
    /** Renvoie les informations sur un utillisateur
     *
     * @param int $id L'identifiant de l'utilisateur
     * @return array L'utilisateur
     * @throws Exception Si l'identifiant de l'utilisateur est inconnu
     */
    public function getUser($userID)
    {
        $sql = 'select userID, nom, prenom, chemin, niveau_accreditation, mail, mot_de_passe from user where userID=?';
        $user = $this->executerRequete($sql, array($userID));
        if ($user->rowCount() == 1)
            return $user->fetch();  // Accès à la première ligne de résultat
        else
            throw new Exception("Aucun utilisateur ne correspond à l'identifiant '$userID'");
    }


    /**
     * Fonction updateChemin = fonction qui met à jour le chemin de l'image utilisateur
     *
     * @param $newPath
     * @param $userID
     */
    public function updateChemin($newPath, $userID)
    {
        echo "updateChemin";
        $sql = "UPDATE user SET chemin = ? WHERE userID = ?";
        $this->executerRequete($sql, array($newPath, $userID));
    }


    /** Enregistre une image sur le serveur et change le chemin de l'image de l'utilisateur
     *
     * @param file $fichier L'image à télécharger
     * @param int $userID L'identifiant correspondant à l'utilisateur
     */
    public function uploadPicture($fichier, $userID)
    {
        $target_dir = "Images/Profil/";
        $target_file = $target_dir . basename($_FILES["fichier"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        echo ' $FILES : ' . $_FILES["fichier"]["name"] . '||';

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fichier"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["fichier"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            // replace filename by userID
            $file_titre = $target_dir . $userID . '.' . $imageFileType;
            if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $file_titre)) {
                // on met a jour la bdd
                echo " file titre : " . $file_titre;
                $this->updateChemin($file_titre, $userID);
                echo "The file " . basename($_FILES["fichier"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }


    /**
     * Fonction updatePassword = fonction qui met à jour le mot de passe de l'utilisateur
     *
     * @param $newPassword
     * @param $userID
     * @return Code ID de l'erreur / succès
     */
    public function updatePassword($newPassword, $userID)
    {
        $sql = "UPDATE `user` SET `mot_de_passe` = ? WHERE `userID` = ?;";
        $this->executerRequete($sql, array($newPassword, $userID));
        return UserProfile::PASSWORD_UPDATE_SUCCESS;
    }

}