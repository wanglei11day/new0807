<?php
class ModelAccountOrder extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}


			$status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE order_status_id = '" . (int)$order_query->row['order_status_id'] . "' AND  language_id = '" . (int)$this->config->get('config_language_id')."'");

			if ($status_query->num_rows) {
				$status = $status_query->row['name'];
			} else {
				$status = '';
			}
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_telephone'       => $order_query->row['shipping_telephone'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'status'         		  => $status,
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip']
			);
		} else {
			return false;
		}
	}

	public function getOrders($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getOrderProduct($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT op.*,p.image FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON op.product_id = p.product_id  WHERE op.order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}


    public function getTshirtOrderProducts($order_id) {
        $query = $this->db->query("SELECT op.*,p.image,p.pdf FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON op.product_id = p.product_id INNER JOIN " . DB_PREFIX . "tshirtdesign_order t ON t.order_product_id = op.order_product_id  WHERE op.order_id = '" . (int)$order_id . "'");
        return $query->rows;
    }


	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}


	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added");

		return $query->rows;
	}

	public function getTotalOrders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row['total'];
	}

	public function getTotalOrderProductsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderVouchersByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function updateSign($order_id,$sign){
        $this->db->query("UPDATE " . DB_PREFIX . "order SET cmb_sign = '" . $sign . "' WHERE order_id = '" . (int)$order_id . "'");
    }

    public function update_tshirt_order($order_product_id,$data){

        $this->db->query("UPDATE " . DB_PREFIX . "tshirtdesign_order SET options = '" . $this->db->escape($data). "' WHERE order_product_id = '" . (int)$order_product_id . "'");
    }




    public function getOneOrder() {
        $sql = "SELECT o.* FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON op.order_id = o.order_id INNER JOIN " . DB_PREFIX . "tshirtdesign_order t ON t.order_product_id = op.order_product_id WHERE  downloaded = '0' AND failed_count < 3  GROUP BY o.order_id ORDER BY o.order_id DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->row;
    }
    
    public function getTshirtOrders($start_order_id,$end_order_id,$status_id='') {
        if(!$status_id){
            $sql = "SELECT o.*,p.pdf,p.model FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON op.order_id = o.order_id INNER JOIN " . DB_PREFIX . "tshirtdesign_order t ON t.order_product_id = op.order_product_id LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = op.product_id WHERE  o.order_id >= '$start_order_id' AND o.order_id <= '$end_order_id'   GROUP BY o.order_id ORDER BY o.order_id DESC";
        }else{
            $sql = "SELECT o.*,p.pdf,p.model FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON op.order_id = o.order_id INNER JOIN " . DB_PREFIX . "tshirtdesign_order t ON t.order_product_id = op.order_product_id LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = op.product_id WHERE  o.order_id >= '$start_order_id' AND o.order_id <= '$end_order_id'   AND o.order_status_id = ' $status_id ' GROUP BY o.order_id ORDER BY o.order_id DESC";
        }
        $query = $this->db->query($sql);
        return $query->rows;
    }


    public function getKeditorOrderDesigns($start_order_id,$end_order_id,$status_id='') {
        if($status_id){
            $sql = "SELECT cd.*,o.order_id as order_id,p.pdf,p.model FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON op.order_id = o.order_id  LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = op.product_id INNER JOIN " . DB_PREFIX . "customer_design cd ON cd.customer_creation_id = op.customer_creation_id WHERE  o.order_id >= '$start_order_id' AND o.order_id <= '$end_order_id'    ORDER BY o.order_id DESC";
        }else{
            $sql = "SELECT cd.*,o.order_id as order_id,p.pdf,p.model FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON op.order_id = o.order_id  LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = op.product_id INNER JOIN " . DB_PREFIX . "customer_design cd ON cd.customer_creation_id = op.customer_creation_id WHERE  o.order_id >= '$start_order_id' AND o.order_id <= '$end_order_id'   AND o.order_status_id = ' $status_id '  ORDER BY o.order_id DESC";
        }
        $query = $this->db->query($sql);
        return $query->rows;
    }


    public function getKeditorOrderDesign($order_id,$order_product_id) {
        $sql = "SELECT cd.*,o.order_id as order_id,p.pdf,p.model FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON op.order_id = o.order_id  LEFT JOIN " . DB_PREFIX . "product p ON p.product_id = op.product_id INNER JOIN " . DB_PREFIX . "customer_design cd ON cd.customer_creation_id = op.customer_creation_id WHERE  o.order_id = '$order_id' AND op.order_product_id = '$order_product_id' ORDER BY o.order_id DESC";
        $query = $this->db->query($sql);
        return $query->row;
    }

    

    public function updateDownloadStatus($order_id,$status) {
        if($status){
            $this->db->query("UPDATE " . DB_PREFIX . "order SET downloaded = 1 WHERE order_id = '" . (int)$order_id . "'");
        }else{
            $this->db->query("UPDATE " . DB_PREFIX . "order SET failed_count =  failed_count+1 WHERE order_id = '" . (int)$order_id . "'");
        }

    }



}