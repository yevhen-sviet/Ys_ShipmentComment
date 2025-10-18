Ys_ShipmentComment — Magento 2 Extension

Adds shipment comments to checkout and orders.

Overview:
Ys_ShipmentComment extends Magento 2 checkout and order flow by allowing customers to leave a shipment/delivery comment during checkout.
The comment is stored on the quote and transferred to the order when placed.
Comment is visible in:
- Admin Order View
- Customer Account Order View
- Optionally included in order confirmation emails (need to add {{var shipment_comment}} to email template)
- The module supports both Luma and headless setups (GraphQL mutation included)


Features:
- Adds “Shipment Comment” textarea under the shipping step
- Admin can enable/disable feature globally
- Show only for selected shipping methods
- Comment can be optional or required depending on admin toggle
- Real-time validation + error message (no alert popups)
- Comment is displayed on order view pages
- Separate CSS configuration for checkout and customer account
- Supports GraphQL: setShipmentCommentOnCart mutation + shipment_comment query field
- Additional server-side validation


Installation:
unzip Ys_ShipmentComment.zip -d app/code
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
bin/magento setup:static-content:deploy -f


Configuration:
Stores → Configuration → Sales → Shipping Settings → Shipment Comment


Setting	Description:
Enable Shipment Comment	Turns the feature on/off globally.
Require Shipment Comment	When set to Yes, the comment field becomes required for selected methods. When No, it’s optional.
Selected Methods	Choose which shipping methods show the comment field (e.g., flatrate_flatrate). If a method is not selected, the field won’t appear at all.
Checkout CSS	Custom CSS applied to checkout page only.
Customer Account CSS	Custom CSS applied to the customer order view page.


GraphQL Support:
The module exposes a read and write API for headless stores.

Query example
{
  cart(cart_id: "abcdef123456") {
    shipment_comment
  }
}

Mutation example
mutation {
  setShipmentCommentOnCart(
    input: { cart_id: "abcdef123456", shipment_comment: "Please deliver before 5 PM." }
  ) {
    cart {
      shipment_comment
    }
  }
}

Resolvers handle both read (CartShipmentComment, OrderShipmentComment) and write (SetShipmentCommentOnCart).


Tested on Magento Open Source v2.4.8-p2:
![Alt text](/../tests/screens/1.png?raw=true)
![Alt text](/../tests/screens/2.png?raw=true)
![Alt text](/../tests/screens/3.png?raw=true)
![Alt text](/../tests/screens/4.png?raw=true)
![Alt text](/../tests/screens/5.png?raw=true)