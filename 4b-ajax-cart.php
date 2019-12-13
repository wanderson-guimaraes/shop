<?php
// CARRINHO VAI SER GUARDADO NA SESSÃO
// $_SESSION['cart'][PRODUCT ID] = QUANTIDADE
require __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "2a-config.php";
switch ($_POST['req']) {
  /* [REQUISIÇÃO INVÁLIDA] */
  default:
    echo "REQUISIÇÃO INVÁLIDA";
    break;

  /* [ADICIONA ITEM AO CARRINHO] */
  case "add":
    if (is_numeric($_SESSION['cart'][$_POST['product_id']])) {
      $_SESSION['cart'][$_POST['product_id']] ++;
    } else {
      $_SESSION['cart'][$_POST['product_id']] = 1;
    }
    echo "Item adicionado ao carrinho,obrigado!";
    break;

  /* [Conta o numero total de itens] */
  case "count":
    $total = 0;
    if (count($_SESSION['cart'])>0) {
      foreach ($_SESSION['cart'] as $id => $qty) {
        $total += $qty;
      }
    }
    echo $total;
    break;

  /* [mostra carrinho] */
  case "show":
    //  pega produtos
    require PATH_LIB . "2b-lib-db.php";
    require PATH_LIB . "4c-lib-cart.php";
    $cartLib = new Cart();
    $products = $cartLib->details();

    //conteudo html do carrinho
    $sub = 0;
    $total = 0; ?>
    <h1>MEU CARRINHO</h1>
    <table id="cart-table">
      <tr>
        <th>Remover</th>
        <th>Qtd</th>
        <th>Item</th>
        <th>Preço</th>
      </tr>
      <?php
      if (count($_SESSION['cart'])>0) {
      foreach ($_SESSION['cart'] as $id => $qty) {
        $sub = $qty * $products[$id]['product_price'];
        $total += $sub; ?>
      <tr>
        <td>
          <input class="cart-remove" type="button" value="X" onclick="cart.remove(<?= $id ?>);"/>
        </td>
        <td><input id='qty_<?= $id ?>' onchange='cart.change(<?= $id ?>);' type='number' value='<?= $qty ?>'/></td>
        <td><?= $products[$id]['product_name'] ?></td>
        <td><?= sprintf("$%0.2f", $sub) ?></td>
      </tr>
      <?php }} else { ?>
      <tr><td colspan="3">Carrinho está vazio</td></tr>
      <?php } ?>
      <tr>
        <td colspan="2"></td>
        <td><strong> Total Geral</strong></td>
        <td><strong><?= sprintf("$%0.2f", $total) ?></strong></td>
      </tr>
    </table>
    <?php if (count($_SESSION['cart']) > 0) { ?>
    <form id="cart-checkout" onsubmit="return cart.checkout();">
      <label>Nome</label>
      <input type="text" id="co_name" required value="John Doe"/>
      <label>Email</label>
      <input type="email" id="co_email" required value="john@doe.com"/>
      <input type="submit" value="Checkout"/>
    </form>
    <?php }
    break;

  /* [Mudar quantidade] */
  case "change":
    if ($_POST['qty'] == 0) {
      unset($_SESSION['cart'][$_POST['product_id']]);
    } else {
      $_SESSION['cart'][$_POST['product_id']] = $_POST['qty'];
    }
    echo "Quantidade Atualizada";
    break;

  /* [CHECKOUT] */
   
  // DAQUI VOCÊ PODE IMPLEMENTAR DIVERSOS TIPOS DE CHECKOUT

  case "checkout":
    require PATH_LIB . "2b-lib-db.php";
    require PATH_LIB . "4c-lib-cart.php";
    $cartLib = new Cart();
    if ($cartLib->checkout($_POST['name'], $_POST['email'])) {
      $_SESSION['cart'] = [];
      echo "OK";
    } else {
      echo $cartLib->error;
    }
    break;

  /* [CHECKOUT ALTERNATIVO] */
  // This version sends an email to the customer on successful checkout
  case "checkout-email":
    require PATH_LIB . "2b-lib-db.php";
    require PATH_LIB . "4c-lib-cart.php";
    $cartLib = new Cart();
    if ($cartLib->checkout($_POST['name'], $_POST['email'])) {
      $_SESSION['cart'] = [];
      // @TODO
      // Format this email message as you see fit
      $order = $cartLib->get($cartLib->orderID);
      $to = $_POST['email'];
      $subject = "Pedido Recebido";
      $message = "";
      foreach ($order['items'] as $pid=>$p) {
        $message .= $p['product_name'] . " - " . $p['quantity'] . "<br>";
      }
      $headers = implode("\r\n", [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        'From: john@doe.com'
      ]);
      echo @mail($to, $subject, $message, $headers) ? "OK" : "Erro ao enviar email!" ;
    } else {
      echo $cartLib->error;
    }
    break;
}
?>