<?php

class CategoriesModel extends Model implements IModel
{

  public function __construct()
  {
    parent::__construct();
  }

  private $id, $name, $color;

  public function setName($name)
  {
    $this->name = $name;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function setColor($color)
  {
    $this->color = $color;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getColor()
  {
    return $this->color;
  }

  public function save()
  {
    try {

      $query = $this->prepare('INSERT INTO categories (name, color) VALUES (:name, :color)');
      $query->execute([
        'name' => $this->name,
        'color' => $this->color
      ]);

      if ($query->rowCount()) return true;

      return false;
    } catch (PDOException $e) {
      error_log('CategoriesModel::save => Error al guardar categorie ' . $e);
      return false;
    }
  }
  public function getAll()
  {
    try {

      $items = [];

      $query = $this->query('SELECT * FROM categories');

      while ($p = $query->fetch(PDO::FETCH_ASSOC)) {
        $item = new CategoriesModel();
        $item->from($p);

        array_push($items, $item);
      }

      return $items;
    } catch (PDOException $e) {
      error_log('CategoriesModel::getAll => Error al consultar category ' . $e);
      return [];
    }
  }
  public function get($id)
  {
    try {

      $query = $this->prepare('SELECT * FROM categories WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);

      $category = $query->fetch(PDO::FETCH_ASSOC);
      $this->from($category);

      return $this;
    } catch (PDOException $e) {
      error_log('CategoriesModel::get => Error al consultar el category ' . $e);
      return [];
    }
  }
  public function update()
  {
    try {

      $query = $this->prepare('UPDATE categories SET name = :name, color = :color WHERE id = :id');
      $query->execute([
        'name' => $this->name,
        'color' => $this->color,
        'id' => $this->id
      ]);

      if ($query->rowCount()) return true;

      return false;
    } catch (PDOException $e) {
      error_log('CategoriesModel::update => Error al actualizar category ' . $e);
      return false;
    }
  }
  public function delete($id)
  {
    try {

      $query = $this->prepare('DELETE FROM categories WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);

      return true;
    } catch (PDOException $e) {
      error_log('CategoriesModel::delete => Error al eliminar category ' . $e);
      return false;
    }
  }

  public function from($array)
  {
    $this->id = $array['id'];
    $this->name = $array['name'];
    $this->color = $array['color'];
  }

  public function exists($name)
  {

    try {

      $query = $this->prepare('SELECT name FROM categories WHERE name = :name');
      $query->execute([
        'name' => $name
      ]);

      if ($query->rowCount()) return true;

      return false;
    } catch (PDOException $e) {
      error_log('CategoriesModel::exists => Error al validar el category ' . $e);
      return [];
    }
  }
}
