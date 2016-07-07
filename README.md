# Article read time module - Drupal 8

Module provides the time it will take and individual to read an article or a blog post.

Calculation is fairly simple: 
Since average words per minute = 225wmp
article read time for words = 225/number of words in the post/article
article read time for an image = number of images * 12
overall artical read time = article read time for words + article read time for an image
