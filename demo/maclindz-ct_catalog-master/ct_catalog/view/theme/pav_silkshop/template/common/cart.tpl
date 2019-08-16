<?php $objlang = $this->registry->get('language');  ?>

<div id="cart" class="minibasket">
    <div data-toggle="dropdown" data-loading-text="<?php echo $text_loading; ?>" class="heading media dropdown-toggle">
        <h5 class="heading-cart"><?php echo 'Shopping Cart';?></h5>
        <span id="cart-total"></span>
    </div>
  <ul class="dropdown-menu pull-right">
    <?php if ($products || $vouchers) { ?>
    <li>
      <table class="table table-striped">
        <?php foreach ($products as $product) { ?>
        <tr>
            <td class="text-center"><?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
            <td class="text-left"><a href="<?php echo $product['href']; ?>">
			<?php if($product['diydesign_edituri']){ ?>
					 <span style="margin-right:10px"><a target="_blank" href="<?php echo $product['diydesign_edituri']; ?>">
					  <?php echo $product['diydesign_name']; ?></span>
					</a>
				<?php  } ?>
				(<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>) 
			
            <?php if ($product['option']) { ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br/>
              -  <small><span class="design-item-bold"> <?php echo $option['name']; ?></span>: <?php echo $option['value']; ?></small>
            <?php } ?>
            <?php } ?>
            <?php if ($product['recurring']) { ?>
            <br/>
            - <small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
              <?php } ?></td>
            <td class="text-right">x <?php echo $product['quantity']; ?></td>
            <td class="text-right"><?php echo $product['total']; ?></td>
          <td class="text-center"><button type="button" onclick="cart.remove('<?php echo $product['cart_id']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-primary btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="text-center"></td>
          <td class="text-left"><?php echo $voucher['description']; ?></td>
          <td class="text-right">x&nbsp;1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-center text-danger"><button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <?php } ?>
      </table>
    </li>
    <li>
      <div>
        <table class="table table-bordered">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
            <td class="text-right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
          <p class="pull-left"><a class="btn btn-primary" href="<?php echo $cart; ?>"><strong> <?php echo $text_cart; ?></strong></a></p>
          <p class="pull-right"><a class="btn btn-primary" href="<?php echo $checkout; ?>"><strong><?php echo $text_checkout; ?></strong></a></p>
      </div>
    </li>
    <?php } else { ?>
    <li>
      <p class="text-center"><?php echo $text_empty; ?></p>
    </li>
    <?php } ?>
  </ul>
</div>
