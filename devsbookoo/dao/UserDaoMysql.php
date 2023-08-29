<?php
require_once 'models/User.php';

class UserDaoMysql implements UserDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    private function generateUser($array) {
        $u = new User();
        $u->id = $array['id'] ?? 0;
        $u->email = $array['email'] ?? '';
        $u->password = $array['password'] ?? '';
        $u->name = $array['name'] ?? '';
        $u->birthdate = $array['birthdate'] ?? '';
        $u->city = $array['city'] ?? '';
        $u->work = $array['work'] ?? '';
        $u->avatar = $array['avatar'] ?? '';
        $u->cover = $array['cover'] ?? '';
        $u->token = $array['token'] ?? '';

        return $u;
    }

    public function findByToken($token) {
        if(!empty($token)) {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
            $sql->bindvalue(':token', $token);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
                return $user;
            }
        }
        
        return false;
    }

    public function findByEmail($email) {
        if(!empty($email)) {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $sql->bindvalue(':email', $email);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
                return $user;
            }
        }
        
        return false;
    }

    public function update(User $u) {
        $sql = $this->pdo->prepare("UPDATE users SET
        email = :email,
        password = :password,
        name = :name,
        birthdate = :birthdate,
        city = :city, 
        work = :work, 
        avatar = :avatar,
        cover = :cover,
        token = :token
        WHERE id = :id");

        $sql->bindvalue(':email', $u->email);
        $sql->bindvalue(':password', $u->password);
        $sql->bindvalue(':name', $u->name);
        $sql->bindvalue(':birthdate', $u->birthdate);
        $sql->bindvalue(':city', $u->city);
        $sql->bindvalue(':work', $u->work);
        $sql->bindvalue(':avatar', $u->avatar);
        $sql->bindvalue(':cover', $u->cover);
        $sql->bindvalue(':token', $u->token);
        $sql->bindvalue(':id', $u->id);
        $sql->execute();

        return true;
    }

    public function insert(User $u) {
        $sql = $this->pdo->prepare("INSERT INTO users (
            email, password, name, birthdate, token
        ) VALUES (
            :email, :password, :name, :birthdate, :token
        )");

        $sql->bindValue(':email', $u->email);
        $sql->bindValue(':password', $u->password);
        $sql->bindValue(':name', $u->name);
        $sql->bindValue(':birthdate', $u->birthdate);
        $sql->bindValue(':token', $u->token);
        $sql->execute();

        return true;
    }

   }   