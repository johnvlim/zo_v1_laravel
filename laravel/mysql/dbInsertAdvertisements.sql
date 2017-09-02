USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @company_name = "Max's";
SET @advertisement_title = "Gilas Favorite Takeout Treat";
SET @advertisement_content = "1 Whole Regular Fried Chicken with Max's Banana Ketchup and limited edition Gilas Pilipinas Eco Bag";
SET @advertisement_price = 299;
SET @advertisement_image = "http://2.bp.blogspot.com/-UZZk8Pdl9xw/U72-1D-a2TI/AAAAAAABCfQ/vY6b8OYtXtM/s1600/Gilas+TakeOut.jpg";
SET @advertisement_url = "http://sweetnbonappetit.blogspot.com/2014_07_01_archive.html";

INSERT INTO advertisements(
	company_name, 
	advertisement_title, 
	advertisement_content, 
	advertisement_price, 
	advertisement_image, 
	advertisement_url
	)
VALUES(
	@company_name, 
	@advertisement_title, 
	@advertisement_content, 
	@advertisement_price, 
	@advertisement_image, 
	@advertisement_url
	);
	
# ==========
# RECORD 2
# ==========
SET @company_name = "Max's";
SET @advertisement_title = "Fish Fillet in Black Bean Sauce";
SET @advertisement_content = "Featuring Ruby's Favorite Fish Fillet in Black Bean Sauce";
SET @advertisement_price = 799;
SET @advertisement_image = "http://3.bp.blogspot.com/-rUGXqOmW7yY/Uvynn3TaDAI/AAAAAAAA1no/-uq8B3riIf0/s1600/Maxs+4Sharing+Meal.jpg";
SET @advertisement_url = "http://www.wazzuppilipinas.com/2014/02/4sharing-sumptuous-seafood-spread-from.html";

INSERT INTO advertisements(
	company_name, 
	advertisement_title, 
	advertisement_content, 
	advertisement_price, 
	advertisement_image, 
	advertisement_url
	)
VALUES(
	@company_name, 
	@advertisement_title, 
	@advertisement_content, 
	@advertisement_price, 
	@advertisement_image, 
	@advertisement_url
	);