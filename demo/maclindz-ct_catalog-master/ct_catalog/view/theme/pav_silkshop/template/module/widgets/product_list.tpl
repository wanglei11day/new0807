<?php
	$themeConfig = (array)$this->config->get('themecontrol');
	$listingConfig = array(
		'category_pzoom'        => 1,
		'quickview'             => 0,
		'show_swap_image'       => 0,
		'product_layout'		=> 'default',
		'enable_paneltool'	    => 0
	);
	$listingConfig = array_merge($listingConfig, $themeConfig );
	$categoryPzoom = $listingConfig['category_pzoom'];
	$quickview     = $listingConfig['quickview'];
	$swapimg       = $listingConfig['show_swap_image'];
	$categoryPzoom = isset($themeConfig['category_pzoom']) ? $themeConfig['category_pzoom']:0; 

	$span = 12/$cols;

	$productLayout = DIR_TEMPLATE.$this->config->get('config_template').'/template/common/product/'.$listingConfig['product_layout'].'.tpl';	
	if( !is_file($productLayout) ){
		$listingConfig['product_layout'] = 'default';
	}
	$productLayout = DIR_TEMPLATE.$this->config->get('config_template').'/template/common/product/'.$listingConfig['product_layout'].'.tpl';
	$button_cart = $this->language->get('button_cart');
	$id = rand(1,9)+rand();

	$columns_count  = 1;
?>
<div class="panel bg-carousel panel-default">
	<?php if( $show_title ) { ?>
	<div class="widget-heading panel-heading block-border"><h3 class="panel-title"><?php echo $heading_title?></h3></div>
	<?php } ?>
	
	<?php if ( isset($imagefile) && $imagefile )  { ?>
	 <div class="image-item effect-adv pull-left hidden-xs hidden-sm">
		<img class="img-responsive" alt=" " src="<?php echo $thumbnailurl; ?>"/>						
	 </div>
	<?php } ?>
		
	<div class="list box-products owl-carousel-play" id="product_list<?php echo $id;?>" data-ride="owlcarousel">
			<?php if( count($products) > $itemsperpage ) { ?>
			<div class="carousel-controls hidden-xs hidden-sm">
				<a class="carousel-control left" href="#product_list<?php echo $id;?>"   data-slide="prev"><i class="fa fa-arrow-left"></i></a>
				<a class="carousel-control right" href="#product_list<?php echo $id;?>"  data-slide="next"><i class="fa fa-arrow-right"></i></a>
			</div>
		    <?php } ?>
			<div class="owl-carousel product-grid"  data-show="<?php echo ($columns_count); ?>" data-pagination="false" data-navigation="true">

				<?php $pages = array_chunk( $products, $itemsperpage); ?>
					<?php foreach ($pages as  $k => $tproducts ) {   ?>
					
						<?php foreach( $tproducts as $i => $product ) {  $i=$i+1;?>
							<div class="item <?php if($k==0) {?>active<?php } ?> products-block">
								<?php require( $productLayout );  ?>

							</div>
						<?php } //endforeach; ?>
					
			    <?php } ?>
			</div>	
	</div>	
	<div class="clearfix"></div>
</div>
<script type="text/javascript">
	$('.owl-carousel').owlCarousel({
  items: 2,
  loop: true,
  margin: 10,
  lazyLoad: true,
  merge: true, 
  video: true,
  responsive:{	
    480:{
      items:2
    },

    678:{
      items:3
    },

    960:{
      items:<?php echo $itemsperpage;?>
    }
  }
});
</script>