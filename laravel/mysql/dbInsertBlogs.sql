USE ziplogic;

# ==========
# RECORD 1
# ==========
SET @blog_title = "Fuel your Pokemon Go craze with Boulangerie22 Pokemon Cakes";
SET @blog_author = "Clarisse";
SET @blog_content = "With their penchant for creating super cute character cakes, I knew it was only a matter of time before the Asian bakery chains got in on the Pokemon Go craze. And with the introduction of the Boulangerie22 Pokemon cakes, that time has finally arrived. My first encounter with Boulangerie22 was fairly recent– late last year …";
SET @blog_image = "http://thetummytrain.com/wp-content/uploads/2017/03/Boulangerie22-Pokemon-Cakes-1.jpg";
SET @blog_url = "http://thetummytrain.com/2017/03/10/boulangerie22-pokemon-cakes-review/";

INSERT INTO blogs(
	blog_title, 
	blog_author, 
	blog_content, 
	blog_image, 
	blog_url
	)
VALUES(
	@blog_title, 
	@blog_author, 
	@blog_content, 
	@blog_image, 
	@blog_url
	);
	
# ==========
# RECORD 2
# ==========
SET @blog_title = "Sapporo Travel Diary 2017: A brief visit to the Sapporo Central Wholesale Market";
SET @blog_author = "Clarisse";
SET @blog_content = "Despite having visited Japan twice before, would you believe this is only my first time paying a visit to a Japanese market? I guess third time really is the charm! Because I wanted to acknowledge this momentous occasion, I decided to write about it briefly as part of my travel diaries. This is the last …";
SET @blog_image = "http://thetummytrain.com/wp-content/uploads/2017/03/Sapporo-Wholesale-Market-Crabs.jpg";
SET @blog_url = "http://thetummytrain.com/2017/03/08/sapporo-travel-diary-2017-sapporo-central-wholesale-market/";

INSERT INTO blogs(
	blog_title, 
	blog_author, 
	blog_content, 
	blog_image, 
	blog_url
	)
VALUES(
	@blog_title, 
	@blog_author, 
	@blog_content, 
	@blog_image, 
	@blog_url
	);