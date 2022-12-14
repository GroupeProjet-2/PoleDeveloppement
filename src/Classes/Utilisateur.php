<?php

    include("../BD/connexion_bd.php");

    require("../utils.php");

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
     * Losque l'on crée un utilisateur est crée avec un login qui est un mail ou n'a que le login en argument, on
     * suppose que l'utilisateur existe dans la base de données. On récupère alors les informations de l'utilisateur
     */
    class Utilisateur {
        private string $login;
        private mixed $firstName;
        private mixed $lastName;
        private mixed $mail;
        private mixed $password;
        private mixed $role_id;
        private mixed $td;
        private mixed $tp;

        public function __construct(
            $login,
            $firstName = null,
            $lastName = null,
            $mail = null,
            $password = null,
            $role_id = null,
            $td = null,
            $tp = null
        ) {
            $this->login = isMail($login) ? "" : $login;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->mail = isMail($login) ? $login : $mail;
            $this->password = $password;
            $this->role_id = $role_id;
            $this->td = $td;
            $this->tp = $tp;

            if (isMail($login) or $firstName == null) {
                $this->recupererDepuisBD();
            }

        }

        // GETTERS
        public function getLogin(): string {
            if ($this->login == null) {
                $this->recupererDepuisBD();
            }
            return $this->login;
        }

        public function getFirstName(): string {
            if ($this->firstName == null) {
                $this->recupererDepuisBD();
            }
            return $this->firstName;
        }

        public function getLastName(): string {
            if ($this->lastName == null) {
                $this->recupererDepuisBD();
            }
            return $this->lastName;
        }

        public function getMail(): string {
            if ($this->mail == null) {
                $this->recupererDepuisBD();
            }
            return $this->mail;
        }

        public function getPassword(): string {
            if ($this->password == null) {
                $this->recupererDepuisBD();
            }
            return $this->password;
        }

        public function getRoleId(): int {
            if ($this->role_id == null) {
                $this->recupererDepuisBD();
            }
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

            $sql = "SELECT * FROM Utilisateur WHERE USER_LOGIN = :login or USER_EMAIL = :mail";

            $stmt = $conn_bd->prepare($sql);

            $stmt->bindParam(':login', $this->login);
            $stmt->bindParam(':mail', $this->mail);
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

            if ($role_id == 1) {
                $sql = "INSERT INTO Utilisateur (
                    USER_LOGIN,
                    USER_FIRST_NAME,
                    USER_LAST_NAME,
                    USER_EMAIL,
                    USER_PASSWORD,
                    USER_ROLE_ID,
                    USER_TD,
                    USER_TP
                ) VALUES (
                    :login,
                    :firstName,
                    :lastName,
                    :mail,
                    :password,
                    :role_id,
                    :user_td,
                    :user_tp
                )";
            } else {
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
            }

            $stmt = $conn_bd->prepare($sql);


            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':mail', $mail);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role_id', $role_id);

            if ($role_id == 1) {
                $user_td = $this->td ?? 0;
                $user_tp = $this->tp ?? 0;

                $stmt->bindParam(':user_td', $user_td);
                $stmt->bindParam(':user_tp', $user_tp);
            }

            $stmt->execute();

            $error = $stmt->errorInfo();
            if ($error[0] !== '00000') {
                echo "Erreur lors de l'insertion de l'utilisateur";
                echo $error[2];
                return false;
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

            $sql = "SELECT * FROM UTILISATEUR WHERE USER_LOGIN = :login OR USER_EMAIL = :mail";

            $stmt = $conn_bd->prepare($sql);
            $stmt->bindParam(':login', $this->login);
            $stmt->bindParam(':mail', $this->mail);
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
            $this->td = $result['USER_TD'];
            $this->tp = $result['USER_TP'];
        }


        /**
         * estEtudiant
         *
         * @access public
         * @return bool - true si l'utilisateur est un étudiant, false sinon
         */
        public function estEtudiant(): bool {
            return $this->role_id == 1;
        }

        /**
         * estProfesseur
         *
         * @access public
         * @return bool - true si l'utilisateur est un professeur, false sinon
         */
        public function estProfesseur(): bool {
            return $this->role_id == 2;
        }

        /**
         * estAdministrateur
         *
         * @access public
         * @return bool - true si l'utilisateur est un administrateur, false sinon
         */
        public function estAdministrateur(): bool {
            return $this->role_id == 3;
        }
    } // Fin classe Utilisateur

     $tom_montbord = new Utilisateur(
        "tmontbord",
        "Tom",
        "Montbord",
        "tmontbord@iutbayonne.univ-pau.fr",
        "Pikatchu197",
        1,
        1,
        2
    );

    $mathis_heriveau = new Utilisateur(
        "mheriveau",
        "Mathis",
        "Hériveau",
        "mheriveau@iutbayonne.univ-pau.fr",
        "Pikatchu197",
        1,
        1,
        2
    );


    $mme_bruyere = new Utilisateur(
        "bruyere",
        "Marie",
        "Bruyère",
        "bruyere@iutbayonne.univ-pau.fr",
        "JaimeLesMathsVrmBcp",
        2,
    );

    #$tom_montbord->insererDansBd();
    #$mathis_heriveau->insererDansBd();
    #$mme_bruyere->insererDansBd();
