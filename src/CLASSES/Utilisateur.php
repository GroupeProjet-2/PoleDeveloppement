<?php

    include("../BD/connexion_bd.php");


    /**
     * Utilisateur est une classe représentant un utilisateur.
     *  - Elle contient les informations de l'utilisateur.
     *  - Elle permet de
     *    - vérifier si l'utilisateur existe dans la base de données.
     *    - d'insérer un utilisateur dans la base de données.
     *    - de récupérer les informations d'un utilisateur depuis la base de données.
     *    - de mettre à jour les informations d'un utilisateur dans la base de données.
     *    - de supprimer un utilisateur de la base de données.
     *
     */
    class Utilisateur {
        private string $login;
        private string $firstName;
        private string $lastName;
        private string $mail;
        private string $password;
        private int $role_id;

        public function __construct(
            $login,
            $firstName = null,
            $lastName = null,
            $mail = null,
            $password = null,
            $role_id = null
        ) {
            $this->login = $login;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->mail = $mail;
            $this->password = $password;
            $this->role_id = $role_id;
        }

        // GETTERS
        public function getLogin(): string {
            return $this->login;
        }

        public function getFirstName(): string {
            return $this->firstName;
        }

        public function getLastName(): string {
            return $this->lastName;
        }

        public function getMail(): string {
            return $this->mail;
        }

        public function getPassword(): string {
            return $this->password;
        }

        public function getRoleId(): int {
            return $this->role_id;
        }

        // SETTERS
        public function setLogin(string $login): void {
            $ancienLogin = $this->login;
            $this->login = $login;
            $this->modifierDansBD("login", $ancienLogin, $login);
        }

        public function setFirstName(string $firstName): void {
            $this->firstName = $firstName;
            $this->modifierDansBD("firstName");
        }

        public function setLastName(string $lastName): void {
            $this->lastName = $lastName;
            $this->modifierDansBD("lastName");
        }

        public function setMail(string $mail): void {
            $this->mail = $mail;
            $this->modifierDansBD("mail");
        }

        public function setPassword(string $password): void {
            $this->password = $password;
            $this->modifierDansBD("password");
        }

        public function setRoleId(int $role_id): void {
            $this->role_id = $role_id;
            $this->modifierDansBD("role_id");
        }

        public function __toString(): string {
            return "Login: " . $this->login;
        }

        // METHODES
        /**
         * estDansBD
         * @access public
         * @return bool - true si l'utilisateur est dans la BD, false sinon
         */
        public function estDansBD(): bool {
            global $conn_bd;

            $sql = "SELECT * FROM Utilisateur WHERE USER_LOGIN = :login";

            $stmt = $conn_bd->prepare($sql);
            $stmt->bindParam(':login', $this->login);
            $stmt->execute();

            $result = $stmt->fetch();

            return (bool)$result;
        }


        /**
         * insererDansBD
         *
         * @access public
         * @return bool - true si l'insertion s'est bien passée, false sinon
         */
        public function insererDansBd(): bool {

            if ($this->estDansBD()) {
                echo "L'utilisateur est déjà dans la BD, attention !";
                return false;
            }

            global $conn_bd;

            $login = $this->login;
            $firstName = $this->firstName;
            $lastName = $this->lastName;
            $mail = $this->mail;
            $password = $this->password;
            $role_id = $this->role_id;

            $password = hash('sha256', $password);

            $sql = "INSERT INTO Utilisateur (
                USER_LOGIN,
                USER_FIRST_NAME,
                USER_LAST_NAME,
                USER_EMAIL,
                USER_PASSWORD,
                USER_ROLE_ID
            ) VALUES (
                :login,
                :firstName,
                :lastName,
                :mail,
                :password,
                :role_id
            )";

            $stmt = $conn_bd->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':mail', $mail);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role_id', $role_id);

            $stmt->execute();

            $error = $stmt->errorInfo();
            if ($error[0] !== '00000') {
                echo "Erreur lors de l'insertion de l'utilisateur";
                echo $error[2];
                return false;
            }

            if ($role_id == 1) {
                // C'est un étudiant
                $sql = "INSERT INTO ETUDIANT (ETUDIANT_LOGIN, TD, TP) VALUES (
                    :login,
                    :td,
                    :tp
                )";

                $stmt = $conn_bd->prepare($sql);
                $stmt->bindParam(':login', $login);
                $stmt->bindParam(':td', $td);
                $stmt->bindParam(':tp', $tp);

                $stmt->execute();

                $error = $stmt->errorInfo();
                if ($error[0] !== '00000') {
                    return false;
                }
            }

            return true;
        }


        /**
         * supprimerDeBD
         *
         * @access public
         * @return bool - true si la suppression s'est bien passée, false sinon
         */
        public function supprimerDeBD(): bool {
            global $conn_bd;

            $sql = "DELETE FROM UTILISATEUR WHERE USER_LOGIN = :login";

            $stmt = $conn_bd->prepare($sql);
            $stmt->bindParam(':login', $this->login);
            $stmt->execute();

            $error = $stmt->errorInfo();

            return $error[0] === '00000';
        }


        /**
         * modifierDansBD
         *
         * @param string $champModifie - le champ à modifier
         * @param string|null $ancienLogin - la nouvelle valeur du champ
         *
         * @access private
         * @return void - true si la modification s'est bien passée, false sinon
         */
        private function modifierDansBD(string $champModifie, string $ancienLogin = null): void {
            global $conn_bd;

            switch ($champModifie) {
                case 'login':
                    $sql = "UPDATE UTILISATEUR SET USER_LOGIN = :login WHERE USER_LOGIN = :oldLogin";
                    $stmt = $conn_bd->prepare($sql);
                    $stmt->bindParam(':login', $this->login);
                    $stmt->bindParam(':oldLogin', $ancienLogin);
                    break;
                case 'firstName':
                    $sql = "UPDATE UTILISATEUR SET USER_FIRST_NAME = :firstName WHERE USER_LOGIN = :login";
                    $stmt = $conn_bd->prepare($sql);
                    $stmt->bindParam(':firstName', $this->firstName);
                    $stmt->bindParam(':login', $this->login);
                    break;
                case 'lastName':
                    $sql = "UPDATE UTILISATEUR SET USER_LAST_NAME = :lastName WHERE USER_LOGIN = :login";
                    $stmt = $conn_bd->prepare($sql);
                    $stmt->bindParam(':lastName', $this->lastName);
                    $stmt->bindParam(':login', $this->login);
                    break;
                case 'mail':
                    $sql = "UPDATE UTILISATEUR SET USER_EMAIL = :mail WHERE USER_LOGIN = :login";
                    $stmt = $conn_bd->prepare($sql);
                    $stmt->bindParam(':mail', $this->mail);
                    break;
                case 'password':
                    $sql = "UPDATE UTILISATEUR SET USER_PASSWORD = :password WHERE USER_LOGIN = :login";
                    $stmt = $conn_bd->prepare($sql);
                    $stmt->bindParam(':password', $this->password);
                    break;
                case 'role_id':
                    $sql = "UPDATE UTILISATEUR SET USER_ROLE_ID = :role_id WHERE USER_LOGIN = :login";
                    $stmt = $conn_bd->prepare($sql);
                    $stmt->bindParam(':role_id', $this->role_id);
                    break;
                default:
                    return;
            }

            $stmt->execute();

            $error = $stmt->errorInfo();

        }

        /**
         * recupererDepuisBD
         *
         * récupère les informations de l'utilisateur depuis la BD
         *
         * @access private
         * @return void
         */
        private function recupererDepuisBD(): void {
            global $conn_bd;

            $sql = "SELECT * FROM UTILISATEUR WHERE USER_LOGIN = :login";

            $stmt = $conn_bd->prepare($sql);
            $stmt->bindParam(':login', $this->login);
            $stmt->execute();

            $error = $stmt->errorInfo();

            if ($error[0] !== '00000') {
                echo "Erreur lors de la récupération de l'utilisateur";
                echo $error[2];
                return;
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->login = $result['USER_LOGIN'];
            $this->firstName = $result['USER_FIRST_NAME'];
            $this->lastName = $result['USER_LAST_NAME'];
            $this->mail = $result['USER_EMAIL'];
            $this->password = $result['USER_PASSWORD'];
            $this->role_id = $result['USER_ROLE_ID'];
        }
    } // Fin classe Utilisateur

     $utilisateur = new Utilisateur(
        "tplanche001",
        "Tom",
        "Planche",
        "tplanche001@icloud.com",
        "12345",
        1
    );


    // Modification du login de l'utilisateur
    $utilisateur->setLogin("tplanche002");
    $utilisateur->setMail("tesst@it.com");
    $utilisateur->setFirstName("Toto");
    $utilisateur->setLastName("Titi");

