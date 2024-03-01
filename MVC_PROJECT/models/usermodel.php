<?php

class UserModel extends Model implements IModel
{

  private $id;
  private $username;
  private $password;
  private $photo;
  private $budget;
  private $name;

  function __construct()
  {
    parent::__construct();
    $this->id = '';
    $this->username = '';
    $this->password = '';
    $this->photo = '';
    $this->budget = 0.0;
    $this->role = '';
    $this->name = '';
    error_log("UserModel::construct-> inicio de UserModel");
  }

  private function hashedPassword($password)
  {

    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function setUserName($username)
  {
    $this->username = $username;
  }

  public function setPassword($password, $hash = true)
  {
    if ($hash)
      $this->password = $this->hashedPassword($password);
    else
      $this->password = $password;
  }

  public function setPhoto($photo)
  {
    $this->photo = $photo;
  }

  public function setBudget($budget)
  {
    $this->budget = $budget;
  }

  public function setRole($role)
  {
    $this->role = $role;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getUserName()
  {
    return $this->username;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getPhoto()
  {
    return $this->photo;
  }

  public function getBudget()
  {
    return $this->budget;
  }

  public function getRole()
  {
    return $this->role;
  }

  public function getName()
  {
    return $this->name;
  }

  public function exists($username)
  {

    try {

      $query = $this->prepare('SELECT username FROM users WHERE username = :username');
      $query->execute([
        'usernmae' => $username
      ]);

      if ($query->rowCount() === 1)
        return true;
      else
        return false;
    } catch (PDOException $e) {
      error_log('USERMODEL::exists -> PDOException ' . $e);
      return false;
    }
  }

  public function comparePassword($password, $id)
  {

    try {

      $user = $this->get($id);

      return password_verify($password, $user->getPassword());
    } catch (PDOException $e) {
      error_log('USERMODEL::comparePassword -> PDOException ' . $e);
      return false;
    }
  }

  public function save()
  {

    try {

      $query = $this->prepare('INSERT INTO users(username, password, budget, photo, name, role) VALUES (:username, :password, :budget, :photo, :name, :role)');
      $query->execute([
        'username' => $this->username,
        'password' => $this->password,
        'budget' => $this->budget,
        'photo' => $this->photo,
        'role' => $this->role,
        'name' => $this->name
      ]);

      return true;
    } catch (PDOException $e) {
      error_log('USERMODEL::save -> PDOException ' . $e);
      return false;
    }
  }

  public function getAll()
  {

    $items = [];

    try {

      $query = $this->query('SELECT * FROM users');

      while ($p = $query->fetch(PDO::FETCH_ASSOC)) {

        $this->setId($p['id']);
        $this->setUserName($p['username']);
        $this->setPassword($p['password'], false);
        $this->setBudget($p['budget']);
        $this->setPhoto($p['photo']);
        $this->setRole($p['role']);
        $this->setName($p['name']);

        array_push($items, $this);
      }

      return $items;
    } catch (PDOException $e) {
      error_log('USERMODEL::getAll -> PDOException ' . $e);
    }
  }

  public function get($id)
  {

    try {

      $query = $this->prepare('SELECT * FROM users WHERE id = :id');
      $query->execute(['id' => $id]);

      $user = $query->fetch(PDO::FETCH_ASSOC);

      error_log('UserModel::get => Usuario devuelto de la DB ' . $user['name']);

      $this->setId($user['id']);
      $this->setUserName($user['username']);
      $this->setPassword($user['password'], false);
      $this->setPhoto($user['photo']);
      $this->setBudget($user['budget']);
      $this->setRole($user['role']);
      $this->setName($user['name']);

      return $this;
    } catch (PDOException $e) {
      error_log('USERMODEL::getAll -> PDOException ' . $e);
    }
  }
  public function update()
  {

    try {

      $query = $this->prepare('UPDATE users SET username = :username, password = :password, budget = :budget, photo = :photo, role = :role, name = :name WHERE id = :id');
      $query->execute([
        'id' => $this->id,
        'username' => $this->username,
        'password' => $this->password,
        'budget' => $this->budget,
        'photo' => $this->photo,
        'role' => $this->role,
        'name' => $this->name
      ]);

      return true;
    } catch (PDOException $e) {
      error_log('USERMODEL::update -> PDOException ' . $e);
      return false;
    }
  }
  public function delete($id)
  {

    try {

      $query = $this->prepare('DELETE FROM users WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);

      return true;
    } catch (PDOException $e) {
      error_log('USERMODEL::update -> PDOException ' . $e);
      return false;
    }
  }

  public function from($array)
  {
    $this->id = $array['id'];
    $this->username = $array['username'];
    $this->password = $array['password'];
    $this->photo = $array['photo'];
    $this->budget = $array['budget'];
    $this->role = $array['role'];
    $this->name = $array['name'];
  }
}
