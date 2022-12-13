<?php

    include("../BD/connexion_bd.php");


    // Utilisateur class
    class Utilisateur {
        private string $login;
        private string $firstName;
        private string $lastName;
        private string $mail;
        private string $password;
        private int $role_id;
        private mixed $td; // NULL or INT
        private mixed $tp; // NULL or INT

        public function __construct(
            $login,
            $firstName,
            $lastName,
            $mail,
            $password,
            $role_id,
            $td = null,
            $tp = null
        ) {
            $this->login = $login;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->mail = $mail;
            $this->password = $password;
            $this->role_id = $role_id;

            if ($td) {
                $this->td = $td;
            }

            if ($tp) {
                $this->tp = $tp;
            }
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

        public function getTd(): ?int {
            return $this->td ? $this->td : null;
        }

        public function getTp(): ?int {
            return $this->tp ? $this->tp : null;
        }

        // SETTERS
        public function setLogin(string $login): void {
            $this->login = $login;
        }

        public function setFirstName(string $firstName): void {
            $this->firstName = $firstName;
        }

        public function setLastName(string $lastName): void {
            $this->lastName = $lastName;
        }

        public function setMail(string $mail): void {
            $this->mail = $mail;
        }

        public function setPassword(string $password): void {
            $this->password = $password;
        }

        public function setRoleId(int $role_id): void {
            $this->role_id = $role_id;
        }

        public function setTd(?int $td): void {
            $this->td = $td;
        }

        public function setTp(?int $tp): void {
            $this->tp = $tp;
        }

        public function __toString(): string {
            return "Login: " . $this->login;
        }

        // METHODES
        /**
         * estDansBD
         *
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
         * @return bool - true si l'insertion s'est bien passée, false sinon
         */
        public function insererDansBd(): bool {

            if ($this->estDansBD()) {
                return false;
            }

            global $conn_bd;

            $login = $this->login;
            $firstName = $this->firstName;
            $lastName = $this->lastName;
            $mail = $this->mail;
            $password = $this->password;
            $role_id = $this->role_id;
            $td = $this->td;
            $tp = $this->tp;

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
                    echo "Erreur lors de l'insertion de l'étudiant";
                    echo $error[2];
                    return false;
                }
            }

            return true;
        }
    }

    // Fin classe Utilisateur


     $utilisateur = new Utilisateur(
        "tplanche001",
        "Tom",
        "Planche",
        "tplanche001@icloud.com",
        "12345",
        1,
        1,
        2
    );


    $utilisateur->insererDansBd();

