## How to use:
1. Create new OAuth 2.0 Client ID in the GMB Reviews project in AU’s Google Developer Console
2. Add your URIs (origins and redirects -- this’ll be the url for the page that the reviews will render on)
3. Download JSON
4. Rename downloaded file to client_secret.json
5. FTP into server
6. Upload it to the _wpeprivate directory (or another secure, unaccessible directory; will need to adjust the paths in gmb-reviews.php though)
7. Follow this guide to get your access tokens -- https://osandadeshan.medium.com/getting-google-oauth-access-token-using-google-apis-18b2ba11a11a
8. Make a file called token.json containing the json from the Postman request
9. Upload that file to the _wpeprivate directory
10. Get MyBusiness.php from https://github.com/bronhy/google-my-business-php-client/blob/master/MyBusiness.php  (only need to get once, can be reused)
11. Get /src from https://github.com/googleapis/google-api-php-client
12. Create a “php” folder within your themes’ directory.
13. Upload the /src directory from step 10 to your new “php” folder
14. Upload the My.Business.php file from step 9 to the /src directory
15. Upload composer files from https://github.com/AUFrasier/gmb-reviews-template to the php directory
16. SSH into server -- see https://wpengine.com/support/ssh-gateway/
17. cd into the php directory and run “php composer.phar install” to install all of the php dependencies defined in composer.json
18. In the gmb-reviews.php file change the $name variable (typically on line 34) to the name shown in AU’s Google My Business group (if needed make a new group).
19. Make sure to set the $location variable as needed depending on the data being returned.  (ex. We may need to get the 2nd item in the array instead of the first).
20. Make any other design changes need in the loop (typically on line 76)

