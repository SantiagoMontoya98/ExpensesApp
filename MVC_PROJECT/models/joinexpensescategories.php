<?php

class JoinExpensesCategories extends Model
{

  public function __construct()
  {
    parent::__construct();
  }

  private $expenseId, $title, $amount, $categoryId, $date, $userId, $nameCategory, $color;

  public function setExpenseId($expenseId)
  {
    $this->expenseId = $expenseId;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function setAmount($amount)
  {
    $this->amount = $amount;
  }

  public function setCategoryId($categoryId)
  {
    $this->categoryId = $categoryId;
  }

  public function setDate($date)
  {
    $this->date = $date;
  }

  public function setUserId($userId)
  {
    $this->userId = $userId;
  }

  public function setNameCategory($nameCategory)
  {
    $this->nameCategory = $nameCategory;
  }

  public function setColor($color)
  {
    $this->color = $color;
  }

  public function getExpenseId()
  {
    return $this->expenseId;
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
    return $this->categoryId;
  }

  public function getDate()
  {
    return $this->date;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function getNameCategory()
  {
    return $this->nameCategory;
  }

  public function getColor()
  {
    return $this->color;
  }

  public function getAll($userId)
  {

    try {

      $items = [];

      $query = $this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color FROM expenses INNER JOIN categories WHERE expenses.category_id = categories.id AND expenses.id_user = :userId ORDER BY date');
      $query->execute([
        'userId' => $userId
      ]);

      while ($p = $query->fetch(PDO::FETCH_ASSOC)) {

        $item = new JoinExpensesCategories();
        $item->from($p);

        array_push($items, $item);
      }

      return $items;
    } catch (PDOException $e) {
      error_log('JoinExpensesCategories::getAll => Error al consultar ' . $e);
      return false;
    }
  }

  public function from($array)
  {
    $this->expenseId    = $array['expense_id'];
    $this->title        = $array['title'];
    $this->categoryId   = $array['category_id'];
    $this->amount       = $array['amount'];
    $this->date         = $array['date'];
    $this->userId       = $array['id_user'];
    $this->nameCategory = $array['name'];
    $this->color        = $array['color'];
  }

  public function toArray()
  {
    $array = [];
    $array['id']          = $this->expenseId;
    $array['title']       = $this->title;
    $array['category_id'] = $this->categoryId;
    $array['amount']      = $this->amount;
    $array['date']        = $this->date;
    $array['id_user']     = $this->userId;
    $array['name']        = $this->nameCategory;
    $array['color']       = $this->color;

    return $array;
  }
}
