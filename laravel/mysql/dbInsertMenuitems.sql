USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @menuitem_code = "0000000000";
SET @menu_id = 1;
SET @menuitem_name = "Family Fried Chicken (Whole)";
SET @menuitem_desc = "The original and classic fried chicken that made Max's world famous and a Filipino institution. Golden fried to perfection with a unique blend of secret spices. Truly Sarap to the Bones";
SET @menuitem_price = 548.90;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443579955_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 2
# ==========
SET @menuitem_code = "0000000001";
SET @menu_id = 1;
SET @menuitem_name = "Family Chicken Basket";
SET @menuitem_desc = "Eight pieces of Max's Fried Chicken™ (legs and thighs) cooked to an unmatched perfection to satisfy your craving. This is the original and classic fried chicken that made Max's world famous and a Filipino institution. Golden fried to perfection with a unique blend of secret spices.";
SET @menuitem_price = 517.0;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443579901_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 3
# ==========
SET @menuitem_code = "0000000002";
SET @menu_id = 1;
SET @menuitem_name = "Regular Fried Chicken (Whole)";
SET @menuitem_desc = "The original classic fried chicken that made Max's popular. It is cooked to golden perfection, processed in a very unique way which gives it the unforgettable Sarap to the Bones quality, spiced just right and totally MSG-free.";
SET @menuitem_price = 449.90;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443580053_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 4
# ==========
SET @menuitem_code = "0000000003";
SET @menu_id = 1;
SET @menuitem_name = "Spring Chicken (Whole)";
SET @menuitem_desc = "The distinctive characteristic of Max's Spring Chicken™' is the tenderness of its meat, since the chicken is harvested at a young age. Otherwise, it is the same original and classic fried chicken that made Max's world famous and a Filipino institution. Golden fried to perfection with a unique blend of secret spices. Truly sarap to the bones!";
SET @menuitem_price = 374.00;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443580103_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 5
# ==========
SET @menuitem_code = "0000000004";
SET @menu_id = 1;
SET @menuitem_name = "Family Fried Chicken (Half)";
SET @menuitem_desc = "The original classic fried chicken that made Max's popular. It is cooked to golden perfection, processed in a very unique way which gives it the unforgettable Sarap to the bones™ quality, spiced just right and totally MSG free.";
SET @menuitem_price = 279.40;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443579933_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 6
# ==========
SET @menuitem_code = "0000000005";
SET @menu_id = 2;
SET @menuitem_name = "Per Table Menu A (Full Set)";
SET @menuitem_desc = "A complete set menu, from soup to dessert, good for ten people, just right for the whole family and friends. Includes the following dishes: Max's Fried Chicken™, Soup of the Day, Pancit Canton, Chopsuey, Lumpiang Shanghai, Plain Rice, Caramel Bar™ Serves 10 people.";
SET @menuitem_price = 3298.90;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1470803878_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 7
# ==========
SET @menuitem_code = "0000000006";
SET @menu_id = 2;
SET @menuitem_name = "Per Table Menu B (Full Set)";
SET @menuitem_desc = "A complete set menu, from soup to dessert, good for ten people, just right for the whole family and friends. Includes the following dishes: Max's Fried Chicken™, Soup of the Day, Oriental Beef with Mushroom, Sweet and Sour Fish Fillet, Pancit Canton, Plain Rice, Roasted Nuts, Frozen Fruit Salad Serves 10 people ";
SET @menuitem_price = 4332.90;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443590257_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 8
# ==========
SET @menuitem_code = "0000000007";
SET @menu_id = 2;
SET @menuitem_name = "Per Table Menu C (Full Set)";
SET @menuitem_desc = "A complete set menu, from soup to dessert, good for ten people, just right for the whole family. Includes the following dishes: Max's Fried Chicken™ Sinigang na Hipon, Boneless Bangus, Kare-Kare, Lechon Kawali, Plain Rice, Roasted Nuts, Buko Pandan Serves 10 people.";
SET @menuitem_price = 5135.90;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443590428_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 9
# ==========
SET @menuitem_code = "0000000008";
SET @menu_id = 2;
SET @menuitem_name = "Per Table Menu A (Half)";
SET @menuitem_desc = "A complete set menu, from soup to dessert, good for five people, just right for the whole family and friends. Includes the following dishes: Max's Fried Chicken™, Soup of the Day, Pancit Canton, Chopsuey, Lumpiang Shanghai, Plain Rice, Caramel Bar™ Serves 5 people.";
SET @menuitem_price = 1648.90;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443590208_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 10
# ==========
SET @menuitem_code = "0000000009";
SET @menu_id = 2;
SET @menuitem_name = "Per Table Menu B (Half)";
SET @menuitem_desc = "A complete set menu, from soup to dessert, just right for the whole family and friends. Includes the following dishes: Max's Fried Chicken™ Soup of the Day, Oriental Beef with Mushroom, Sweet and Sour Fish Fillet, Pancit Canton, Plain Rice, Roasted Nuts, Frozen Fruit Salad Serves 5 people.";
SET @menuitem_price = 2167.00;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443590414_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 11
# ==========
SET @menuitem_code = "0000000010";
SET @menu_id = 3;
SET @menuitem_name = "Holiday Delivery Duo";
SET @menuitem_desc = "1 Cater Tray Max's Fried Chicken & 1 Cater Tray Pancit Canton Large.";
SET @menuitem_price = 2198.90;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1480922327_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 12
# ==========
SET @menuitem_code = "0000000011";
SET @menu_id = 4;
SET @menuitem_name = "Carrot Cake";
SET @menuitem_desc = "Classic moist carrot cake with chopped walnuts, sweet vanilla cream cheese frosting and garnished with orange-green-white chocolate tiles.";
SET @menuitem_price = 715.00;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1481091332_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 13
# ==========
SET @menuitem_code = "0000000012";
SET @menu_id = 4;
SET @menuitem_name = "Red Velvet";
SET @menuitem_desc = "Classic red velvet butter cake with sweet vanilla cream cheese frosting and decorated with whipped cream rosettes and white chocolate curls on top.";
SET @menuitem_price = 654.50;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1481091486_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 14
# ==========
SET @menuitem_code = "0000000013";
SET @menu_id = 4;
SET @menuitem_name = "Chocolate Ganache";
SET @menuitem_desc = "A deeply chocolatey and moist chocolate butter cake with rich truffle icing and dark chocolate glaze, garnished with dark and white chocolate cut-outs.";
SET @menuitem_price = 654.50;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1481091691_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 15
# ==========
SET @menuitem_code = "0000000014";
SET @menu_id = 4;
SET @menuitem_name = "Chocolate Cream Fudge";
SET @menuitem_desc = "Sinful with every bite, this luscious chocolate cake is an indulgence hard to resist. Enriched by a creamy custard filling, coated with flavorful chocolate icing, and topped with chocolate logs, this is a real treat!";
SET @menuitem_price = 654.50;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443577265_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 16
# ==========
SET @menuitem_code = "0000000015";
SET @menu_id = 4;
SET @menuitem_name = "Black Forest";
SET @menuitem_desc = "The heavenly goodness of Max's Corner Bakery's Chocolate Moist Cake infused with rich strawberry filling and topped generously with whipped cream and cherries. Garnished with chocolate shavings to perfection, this best-seller is a delicious indulgence.";
SET @menuitem_price = 654.50;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443576942_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 17
# ==========
SET @menuitem_code = "0000000016";
SET @menu_id = 5;
SET @menuitem_name = "Chicken Dinner Meal";
SET @menuitem_desc = "A half order of Max's Regular Fried Chicken complemented with Soup of the Day and Plain rice. Comes with softdrink and Caramel Bar™.";
SET @menuitem_price = 332.20;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443576281_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 18
# ==========
SET @menuitem_code = "0000000017";
SET @menu_id = 5;
SET @menuitem_name = "Spring Chicken Meal";
SET @menuitem_desc = "One-half of Max's Spring Chicken™ served with Soup of the Day, Steamed rice and softdrink capped off with a Caramel Bar™ for dessert.";
SET @menuitem_price = 299.20;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443576361_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 19
# ==========
SET @menuitem_code = "0000000018";
SET @menu_id = 5;
SET @menuitem_name = "Chopseuy Meal";
SET @menuitem_desc = "A serving of fresh mixed vegetables, pork and shrimp sauteed in thick sauce. This comes with a serving of steamed rice and a quarter of Max's Fried Chicken™. An excellent meal for vegetable lovers.";
SET @menuitem_price = 282.70;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443576309_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 20
# ==========
SET @menuitem_code = "0000000019";
SET @menu_id = 5;
SET @menuitem_name = "Platter Meal";
SET @menuitem_desc = "A quarter of Max's mouthwatering Fried Chicken, a serving of steamed rice and serving of Pancit Canton. Served with a drink and Max's very own Caramel Bar™ as dessert.";
SET @menuitem_price = 282.70;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443576346_thumb.jpg";
SET @menuitem_featured = 1;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);
	
# ==========
# RECORD 21
# ==========
SET @menuitem_code = "0000000020";
SET @menu_id = 5;
SET @menuitem_name = "Fiesta Plate Meal";
SET @menuitem_desc = "Max's quarter fried chicken that comes with (choices) Fresh or Fried lumpiang ubod and Steamed rice, a piece of Caramel Bar™ and a refreshing drink.";
SET @menuitem_price = 275.00;
SET @menuitem_image = "http://delivery.maxschicken.com/product/1443576328_thumb.jpg";
SET @menuitem_featured = 0;

INSERT INTO menuitems(
	menuitem_code, 
	menu_id, 
	menuitem_name, 
	menuitem_desc, 
	menuitem_price, 
	menuitem_image, 
	menuitem_featured
	)
VALUES(
	@menuitem_code, 
	@menu_id, 
	@menuitem_name, 
	@menuitem_desc, 
	@menuitem_price, 
	@menuitem_image, 
	@menuitem_featured
	);