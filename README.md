# M2Commerce Commerce: Magento 2 Search Autocomplete

Autocomplete search suggestions are common nowadays. Search Autocomplete was designed to make searching simple and intuitive. It delivers quick and precise results, thus helping customers get more effective queries.
Autocomplete functionality provides the intuitive search experience and instantly displays search suggestions based on the first-entered characters. This functionality helps customers move from the search box to the checkout as quickly as possible.

## Features

- Instant search results in a customizable AJAX pop-up 
- Suggested searches
- Smart search queries caching 
- Adjustable search delay period 
- The ability to specify the number of displayed search results

## Configuration

There are several configuration options for this extension, which can be found at **STORES > Configuration > Commerce Enterprise > Search Auto Complete**.

### ScreenShots
![ss-1](Screenshots/ss_1.png)

## Installation
### Magento® Marketplace

This extension will also be available on the Magento® Marketplace when approved.

1. Go to Magento® 2 root folder
2. Require/Download this extension:

   Enter following commands to install extension.

   ```
   composer require m2commerce/search-autocomplete
   ```

   Wait while composer is updated.

   #### OR

   You can also download code from this repo under Magento® 2 following directory:

    ```
    app/code/M2Commerce/SearchAutocomplete
    ```    

3. Enter following commands to enable the module:

   ```
   php bin/magento module:enable M2Commerce_SearchAutocomplete
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento cache:clean
   php bin/magento cache:flush
   ```

4. If Magento® is running in production mode, deploy static content:

   ```
   php bin/magento setup:static-content:deploy
   ```
