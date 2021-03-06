<?php

namespace Laetitia_Bernardi\site\Model;
require_once('models/Manager.php');
use \DateTime;
use \PDO;

class UserManager extends Manager
{
    
    private $_id_user, $_pseudo, $_pass, $_email, $_registration_date, $_id_group;


    public function getIdUser()
    {
        return $this->_id_user;
    }

 
    public function getIdGroup()
    {
        return $this->_id_group;
    }

    public function getPseudo()
    {
        return $this->_pseudo;
    }

    public function getPass()
    {
        return $this->_pass;
    }

 
    public function getEmail()
    {
        return $this->_email;
    }


    public function getRegistrationDate()
    {
        return $this->_registration_date;
    }


    /********************************************* GETTERS/ SETTERS *************************************************/

 
    public function setIdUser($id_user)
    {
        $id_user = (int) $id_user;
        if($id_user > 0){
            $this->_id_user = $id_user;
        }

    }


    public function setIdGroup($id_group)
    {
        $id_group = (int) $id_group;
        if($id_group > 0)
        {
            $this->_id_group = $id_group;
        }
    }


    public function setPseudo($pseudo)
    {
        if(is_string($pseudo)) {
            $this->_pseudo = $pseudo;
        }
    }


    public function setPass($pass)
    {
        if(is_string($pass)) {
            $this->_pass = $pass;
        }
    }


    public function setEmail($email)
    {
        if(is_string($email)) {
            $this->_email = $email;
        }
    }


    public function setRegistrationDate(DateTime $registration_date)
    {
        $this->_registration_date = $registration_date;
    }

//recuperation des données de l'utilisateur
    public function getUser($pseudo)
    {
        $this->setPseudo($pseudo);
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users WHERE pseudo = ? ');
        $req->execute(array($this->getPseudo()));
        $user = $req->fetch();
        $pseudoexist = $req->rowcount();

        return $user;
    }

    public function getUserByMail($email)
    {
        $this->setEmail($email);
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users WHERE email = ? ');
        $req->execute(array($this->getEmail()));
        $user = $req->fetch();
        $emailexist = $req->rowcount();

        return $user;
    }

    public function getUserById($id_user)
    {
        $this->setIdUser($id_user);

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, id_group, pseudo, pass, email, DATE_FORMAT(registration_date, \'%d/%m/%Y\') AS registration_date FROM users WHERE id = ?');
        $req->execute(array($this->getIdUser()));
        $user = $req->fetch();

        return $user;
    }


    public function getAllUsers()
    {
        $db = $this->dbConnect();
        $users = $db->query('SELECT id, id_group, pseudo, pass, email, DATE_FORMAT(registration_date, \'%d/%m/%Y\') AS registration_date FROM users ORDER BY id_group');

        return $users;
    }

    public function countUsers()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT COUNT(*) AS total_users FROM users');
        $req->execute();
        $usersTotal = $req->fetch();
        return $usersTotal;
    }

    public function createUser($id_group, $pseudo, $password_hache, $email)
    {
        $this->setIdGroup($id_group);
        $this->setPseudo($pseudo);
        $this->setPass($password_hache);
        $this->setEmail($email);

        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO users(id_group, pseudo, pass, email, registration_date) VALUES(?, ?, ?, ?, NOW())');
        $registerUser = $req->execute(array(
            $this->getIdGroup(),
            $this->getPseudo(),
            $this->getPass(),
            $this->getEmail()
            ));

        return $registerUser;
    }

    public function deleteUser($id_user)
    {
        $this->setIdUser($id_user);

        $db = $this->dbConnect();
        $user = $db->prepare('DELETE FROM users WHERE id= ?');
        $deleteUser = $user->execute(array($this->getIdUser()));

        return $deleteUser;
    }
}