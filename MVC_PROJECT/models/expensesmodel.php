<?php

class ExpensesModel extends Model implements IModel
{

  public function __construct()
  {
    parent::__construct();
  }

  private $id, $title, $amount, $categoryid, $date, $userid;

  public function setId($id)
  {
    $this->id = $id;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function setAmount($amount)
  {
    $this->amount = $amount;
  }

  public function setCategoryId($categoryid)
  {
    $this->categoryid = $categoryid;
  }

  public function setDate($date)
  {
    $this->date = $date;
  }

  public function setUserId($userid)
  {
    $this->userid = $userid;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getAmount()
  {
    return $this->amount;
  }

  public function getCategoryId()
  {
    return $this->categoryid;
  }

  public function getDate()
  {
    return $this->date;
  }

  public function getUserId()
  {
    return $this->userid;
  }

  public function save()
  {
    try {

      $query = $this->prepare('INSERT INTO expenses (title, amount, category_id, date, id_user) VALUES (:title, :amount, :category_id, :d, :userid)');
      $query->execute([
        'title' => $this->title,
        'amount' => $this->amount,
        'category_id' => $this->categoryid,
        'd' => $this->date,
        'userid' => $this->userid
      ]);

      if ($query->rowCount()) return true;

      return false;
    } catch (PDOException $e) {
      error_log('ExpensesModel::save => Error al guardar expense ' . $e);
      return false;
    }
  }
  public function getAll()
  {
    try {

      $items = [];

      $query = $this->query('SELECT * FROM expenses');

      while ($p = $query->fetch(PDO::FETCH_ASSOC)) {
        $item = new ExpensesModel();
        $item->from($p);

        array_push($items, $item);
      }

      return $items;
    } catch (PDOException $e) {
      error_log('ExpensesModel::getAll => Error al consultar expenses ' . $e);
      return [];
    }
  }
  public function get($id)
  {
    try {

      $query = $this->prepare('SELECT * FROM expenses WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);

      $expense = $query->fetch(PDO::FETCH_ASSOC);
      $this->from($expense);

      return $this;
    } catch (PDOException $e) {
      error_log('ExpensesModel::get => Error al consultar el expense ' . $e);
      return [];
    }
  }
  public function update()
  {
    try {

      $query = $this->prepare('UPDATE expenses SET title = :title, amount = :amount, category_id = :category_id, date = :d, id_user = :userid WHERE id = :id');
      $query->execute([
        'title' => $this->title,
        'amount' => $this->amount,
        'category_id' => $this->categoryid,
        'date' => $this->date,
        'userid' => $this->userid,
        'id' => $this->id
      ]);

      if ($query->rowCount()) return true;

      return false;
    } catch (PDOException $e) {
      error_log('ExpensesModel::update => Error al actualizar expense ' . $e);
      return false;
    }
  }
  public function delete($id)
  {
    try {

      $query = $this->prepare('DELETE FROM expenses WHERE id = :id');
      $query->execute([
        'id' => $id
      ]);

      return true;
    } catch (PDOException $e) {
      error_log('ExpensesModel::delete => Error al eliminar expense ' . $e);
      return false;
    }
  }

  public function from($array)
  {
    $this->id = $array['id'];
    $this->title = $array['title'];
    $this->amount = $array['amount'];
    $this->categoryid = $array['category_id'];
    $this->date = $array['date'];
    $this->userid = $array['id_user'];
  }

  public function getAllByUserId($userid)
  {
    try {

      $items = [];

      $query = $this->prepare('SELECT * FROM expenses WHERE id_user = :id');
      $query->execute([
        'id' => $userid
      ]);

      while ($p = $query->fetch(PDO::FETCH_ASSOC)) {
        $item = new ExpensesModel();
        $item->from($p);

        array_push($items, $item);
      }

      return $items;
    } catch (PDOException $e) {
      error_log('ExpensesModel::getAllByUserId => Error al consultar expenses by userid ' . $e);
      return [];
    }
  }

  public function getByUserIdAndLimit($userid, $limit)
  {
    try {

      $items = [];

      $query = $this->prepare('SELECT * FROM expenses WHERE id_user = :id ORDER BY expenses.date DESC LIMIT 0, :n');
      $query->execute([
        'id' => $userid,
        'n' => $limit
      ]);

      while ($p = $query->fetch(PDO::FETCH_ASSOC)) {
        $item = new ExpensesModel();
        $item->from($p);

        array_push($items, $item);
      }

      return $items;
    } catch (PDOException $e) {
      error_log('ExpensesModel::getByUserIdAndLimit => Error al consultar expenses ' . $e);
      return [];
    }
  }

  public function getTotalAmountThisMonth($userid)
  {
    try {

      $year = date('Y');
      $month = date('m');

      $query = $this->prepare('SELECT SUM(amount) AS total FROM expenses WHERE YEAR(date) = :y AND MONTH(date) = :m AND id_user = :id');
      $query->execute([
        'id' => $userid,
        'y' => $year,
        'm' => $month
      ]);

      $total = $query->fetch(PDO::FETCH_ASSOC)['total'];

      if (!$total) $total = 0;

      return $total;
    } catch (PDOException $e) {
      error_log('ExpensesModel::getTotalAmountThisMonth => Error al consultar amount this month ' . $e);
      return [];
    }
  }

  public function getMaxExpensesThisMonth($userid)
  {
    try {

      $year = date('Y');
      $month = date('m');

      $query = $this->prepare('SELECT MAX(amount) AS total FROM expenses WHERE YEAR(date) = :year AND MONTH(date) = :month AND id_user = :id');
      $query->execute([
        'id' => $userid,
        'year' => $year,
        'month' => $month
      ]);

      $total = $query->fetch(PDO::FETCH_ASSOC)['total'];

      if (!$total) $total = 0;

      return $total;
    } catch (PDOException $e) {
      error_log('ExpensesModel::getTotalAmountThisMonth => Error al consultar amount this month ' . $e);
      return [];
    }
  }

  public function getTotalByCategoryThisMonth($categoryid, $userid)
  {
    try {

      $total = 0;
      $year = date('Y');
      $month = date('m');

      $query = $this->prepare('SELECT SUM(amount) AS total FROM expenses WHERE category_id = :category AND YEAR(date) = :year AND MONTH(date) = :month AND id_user = :id');
      $query->execute([
        'id' => $userid,
        'year' => $year,
        'month' => $month,
        'category' => $categoryid
      ]);

      $total = $query->fetch(PDO::FETCH_ASSOC)['total'];

      if (!$total) $total = 0;

      return $total;
    } catch (PDOException $e) {
      error_log('ExpensesModel::getTotalAmountThisMonth => Error al consultar amount this month ' . $e);
      return [];
    }
  }

  public function getTotalByMonthAndCategory($date, $categoryid, $userid)
  {
    try {

      $total = 0;
      $year = substr($date, 0, 4);
      $month = substr($date, 5, 7);

      $query = $this->prepare('SELECT SUM(amount) AS total FROM expenses WHERE category_id = :category AND YEAR(date) = :year AND MONTH(date) = :month AND id_user = :id');
      $query->execute([
        'id' => $userid,
        'year' => $year,
        'month' => $month,
        'category' => $categoryid
      ]);

      if ($query->rowCount())
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
      else $total = 0;

      return $total;
    } catch (PDOException $e) {
      error_log('ExpensesModel::getTotalAmountThisMonth => Error al consultar amount this month ' . $e);
      return [];
    }
  }

  public function getNumberOfExpensesByCategoryThisMonth($categoryid, $userid)
  {
    try {

      $total = 0;
      $year = date('Y');
      $month = date('m');

      $query = $this->prepare('SELECT COUNT(amount) AS total FROM expenses WHERE category_id = :category AND YEAR(date) = :year AND MONTH(date) = :month AND id_user = :id');
      $query->execute([
        'id' => $userid,
        'year' => $year,
        'month' => $month,
        'category' => $categoryid
      ]);

      $total = $query->fetch(PDO::FETCH_ASSOC)['total'];

      if (!$total) $total = 0;

      return $total;
    } catch (PDOException $e) {
      error_log('ExpensesModel::getNumberOfExpensesByCategoryThisMonth => Error al consultar amount this month ' . $e);
      return [];
    }
  }
}
