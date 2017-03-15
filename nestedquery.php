<?php
//Get number of results from PRODUCTS_ORDERED
                    if ($selectproductsordered = $db -> prepare("SELECT po.ordered_id, po.product_id, po.ordered_quantity, p.product_name, p.product_photo, p.product_sku FROM products_ordered AS po INNER JOIN products AS p ON po.product_id=p.product_id WHERE transaction_id=?")) {
                        $selectproductsordered -> bind_param('s', $transaction_id);
                        $selectproductsordered -> execute();
                        $selectproductsordered -> bind_result($ordered_id, $product_id, $ordered_quantity, $product_name, $product_photo, $product_sku);
                        $selectproductsordered -> store_result();
                        if ($selectproductsordered -> num_rows == 0)
                        {

                        }
                        else {
                            $products_ordered = [];

                            while($selectproductsordered -> fetch()) {
                                $products_ordered[ $ordered_id ] = [
                                    'product_id' => $product_id,
                                    'ordered_quantity' => $ordered_quantity,
                                    'product_name' => $product_name,
                                    'meta' => []
                                ];
                            }
                            $ordered_ids = implode(',',array_keys($products_ordered));
                            if(count($products_ordered)) {
                                $result = $db->query("SELECT *, vd.data_name FROM products_ordered_meta INNER JOIN variation_data AS vd ON products_ordered_meta.product_id=p.product_id WHERE ordered_id IN ($ordered_ids)");
                                if ($result) {
                                    while ($pom = $result->fetch_array()) {
                                        $products_ordered[$pom['ordered_id']]['meta'][] = $pom;
                                    }
                                    $result->close();
                                }
                            }


                            echo '<pre>';
                            var_dump($products_ordered); exit;
                            foreach($products_ordered as $order_id => $order) {
                                //$order['product_id']
                                //$order['ordered_quantity']

                                if(count($order['meta'])) {
                                    foreach($order['meta'] as $product_meta) {
                                        //you got all table data inside product_meta variable
                                    }
                                }
                            }

                        }
                        $selectproductsordered -> close();
                    }
?>					
