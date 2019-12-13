<?php
class Products extends DB {
  function get () {
  // get () : pega todos os produtos

    return $this->fetch(
      "SELECT * FROM `products`", null, 
      "product_id"
    );
  }

  function add ($name, $img, $desc, $price) {
  // add () : adiciona novo produto

    return $this->exec(
      "INSERT INTO `products` (`product_name`, `product_image`, `product_description`, `product_price`) VALUES (?, ?, ?, ?)",
      [$name, $img, $desc, $price]
    );
  }

  function edit ($id, $name, $img, $desc, $price) {
  // edit () : atualiza

    return $this->exec(
      "UPDATE `products` SET `product_name`=?, `product_image`=?, `product_description`=?, `product_price`=? WHERE `product_id`=?",
      [$name, $img, $desc, $price, $id]
    );
  }

  function del ($id) {
  // del () : deleta produto

    return $this->exec(
      "DELETE FROM `products` WHERE `product_id`=?",
      [$id]
    );
  }
}
?>