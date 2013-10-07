== Installation ==

 * Unzip the files and upload the folder into your plugins folder (wp-content/plugins/) overwriting old versions if they exist
 * Activate the plugin in your WordPress admin area.
 * Open the settings page for WooCommerce and click the "Payment Gateways" tab
 * Click on the sub tab for "Paypal_express"
 * Configure your PayPal express settings.  See below how to.
 

== Where to find your PayPal Express Credentials ==

To setup your PayPal Express payment gateway you will need to enter your API Username, API Password, and Signature.

First, sign up for PayPal Express

1.  Login to your PayPal account
2.  Click on the "Merchant Services" tab
3.  In the left hand menu click on "Express Checkout"
4.  Click on the Sign Up button
5.  You will be taken to the "My Business Setup" page
6.  Verify that in the "Set up my payment solution" box it says "You've selected: Express Checkout"
7.  Press the "Start Setup" button inside the "Set up my payment solution" box
8.  On the "Option 1" tab under "Helpful info:" , press the "Request API info" link at the bottom of the page.
9.  On the "Request API Credentials" page leave "Request API signature" radio box selected and press "Agree and Submit"
10. You will see the "View or Remove API Signature" page.  On this page you will find your API Username, API Password, and Signature. 
11. Copy these values to your WooCommerce PayPal Express settings page and press "Save Changes"


== Notes on Tax, Shipping and Coupons ==

The PayPal Express checkout is designed to limit the amount of data entry the customer needs to do. The shipping address is added from the customers PayPal account and isn't known to WooCommerce until after the customer returns from authorizing the transaction at the PayPal site.  Because of this tax and shipping calculations are not done until the Review Order page.

PayPal Express does not include the ability to add coupons in the request sent to PayPal.  To get around this limitation any coupons or discounts that are applied to the cart are sent as one of two fields:
- The Cart Discounts value is the sum of all discounts applied before taxes
- The Order Discount value is the sum of all discounts applied after taxes
Because of this aggregation the individual coupon names are not displayed on the PayPal site. 